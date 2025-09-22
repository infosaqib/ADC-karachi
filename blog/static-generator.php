<?php
class StaticBlogGenerator {
    private $pdo;
    private $template;
    private $baseDir;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->baseDir = dirname(__FILE__);
        $templatePath = $this->baseDir . '/template.php';
        
        if (!file_exists($templatePath)) {
            throw new Exception("Template file not found at: " . $templatePath);
        }
        
        $this->template = file_get_contents($templatePath);
    }
    
    public function generateSlug($title) {
        // Convert to lowercase and replace spaces with hyphens
        $slug = strtolower(trim($title));
        // Remove special characters
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        // Remove multiple consecutive hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        // Remove leading and trailing hyphens
        $slug = trim($slug, '-');
        return $slug;
    }
    
    public function generateStaticPage($post) {
        // Generate slug from title
        $slug = $this->generateSlug($post['title']);
        
        // Create the static file path using absolute path
        $pagesDir = $this->baseDir . '/pages';
        
        // Create pages directory if it doesn't exist
        if (!file_exists($pagesDir)) {
            if (!mkdir($pagesDir, 0755, true)) {
                throw new Exception("Failed to create pages directory at: " . $pagesDir);
            }
        }
        
        $filePath = $pagesDir . "/{$slug}.php";
        
        // Create URL for the post
        $url = "/pages/{$slug}.php";
        $post['url'] = $url;
        
        // Replace placeholders in template with actual content
        $content = $this->template;
        
        // Create a PHP array representation of the post data
        $postDataPhp = var_export($post, true);
        
        // Insert the post data into the template
        $content = str_replace('/* POST_DATA_PLACEHOLDER */', $postDataPhp, $content);
        
        // Generate related posts section
        $relatedPosts = $this->getRelatedPosts($post['id']);
        $relatedPostsHtml = $this->generateRelatedPostsHtml($relatedPosts);
        $content = str_replace('<?php /* RELATED_POSTS_PLACEHOLDER */ ?>', $relatedPostsHtml, $content);
        
        // Save the static file
        if (file_put_contents($filePath, $content) === false) {
            throw new Exception("Failed to write static page to: " . $filePath);
        }
        
        // Update database with the URL
        $this->updatePostUrl($post['id'], $url);
        
        return $slug;
    }
    
    private function getRelatedPosts($postId) {
        $stmt = $this->pdo->prepare("
            SELECT id, title, description, image, created_at 
            FROM blog 
            WHERE id != ? 
            ORDER BY RAND() 
            LIMIT 4
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function generateRelatedPostsHtml($relatedPosts) {
        $html = '';
        foreach ($relatedPosts as $related) {
            $relatedSlug = $this->generateSlug($related['title']);
            $html .= '<article class="max-w-xs border border-gray-300">
                <a href="/pages/' . $relatedSlug . '.php">
                    <img src="/admin/uploads/' . htmlspecialchars($related['image']) . '"
                        class="mb-5 rounded-lg w-full h-64" alt="' . htmlspecialchars($related['title']) . '">
                </a>
                <div class="p-4">
                    <h2 class="mb-2 text-xl font-bold leading-tight text-gray-900">
                        <a href="/pages/' . $relatedSlug . '.php">' . htmlspecialchars($related['title']) . '</a>
                    </h2>
                    <p class="mb-4 text-gray-500">' . 
                        (strlen(strip_tags($related['description'])) > 100 
                            ? substr(strip_tags($related['description']), 0, 100) . '...' 
                            : strip_tags($related['description'])) . 
                    '</p>
                    <p class="text-sm text-gray-500">
                        ' . date('M d, Y', strtotime($related['created_at'])) . '
                    </p>
                </div>
            </article>';
        }
        return $html;
    }
    
    private function updatePostUrl($postId, $url) {
        try {
            $stmt = $this->pdo->prepare("UPDATE blog SET url = ? WHERE id = ?");
            $stmt->execute([$url, $postId]);
        } catch (PDOException $e) {
            throw new Exception("Failed to update post URL in database: " . $e->getMessage());
        }
    }
    
    public function generateAllPages() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM blog ORDER BY created_at DESC");
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $generatedCount = 0;
            foreach ($posts as $post) {
                $this->generateStaticPage($post);
                $generatedCount++;
            }
            
            return $generatedCount;
        } catch (PDOException $e) {
            throw new Exception("Failed to fetch posts for regeneration: " . $e->getMessage());
        }
    }
    
    public function deleteStaticPage($postId) {
        try {
            // Get the post URL
            $stmt = $this->pdo->prepare("SELECT url FROM blog WHERE id = ?");
            $stmt->execute([$postId]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($post && $post['url']) {
                $filePath = $this->baseDir . $post['url'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Failed to delete static page: " . $e->getMessage());
        }
    }
    
     public function createPost($title, $description, $image) {
        try {
            $created_at = date('Y-m-d H:i:s');
            
            $stmt = $this->pdo->prepare("
                INSERT INTO blog (title, description, image, created_at) 
                VALUES (:title, :description, :image, :created_at)
            ");

            if($stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':image' => $image,
                ':created_at' => $created_at
            ])) {
                // Update sitemap
                if(updateSitemap($this->pdo)) {
                    $msg = "Post created and sitemap updated successfully!";
                    
                    // Generate static page for the new post
                    $postId = $this->pdo->lastInsertId();
                    $post = [
                        'id' => $postId,
                        'title' => $title,
                        'description' => $description,
                        'image' => $image,
                        'created_at' => $created_at
                    ];
                    $this->generateStaticPage($post);
                } else {
                    $msg = "Post created but sitemap update failed.";
                }
            } else {
                $msg = "Error creating post.";
            }
            
            return ['success' => ($msg === "Post created and sitemap updated successfully!"), 'message' => $msg];
        } catch (PDOException $e) {
            throw new Exception("Failed to create post: " . $e->getMessage());
        }
    }
    
    public function updateStaticPage($postId) {
        try {
            // Get the full post data
            $stmt = $this->pdo->prepare("SELECT * FROM blog WHERE id = ?");
            $stmt->execute([$postId]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($post) {
                // Delete the old static file if it exists
                $this->deleteStaticPage($postId);
                
                // Generate new static file
                $this->generateStaticPage($post);
            }
        } catch (Exception $e) {
            throw new Exception("Failed to update static page: " . $e->getMessage());
        }
    }
}