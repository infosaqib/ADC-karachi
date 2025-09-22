<?php
function updateSitemap($pdo) {
    try {
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Log the start of sitemap generation
        error_log("Starting sitemap generation...");
        
        // Create new XML document
        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->formatOutput = true;
        
        error_log("XML document created successfully");
        
        // Create urlset element
        $urlset = $xml->createElement("urlset");
        $urlset->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
        $xml->appendChild($urlset);
        
        // Add homepage
        $homeUrl = $xml->createElement("url");
        $loc = $xml->createElement("loc", "https://" . $_SERVER['HTTP_HOST']);
        $lastmod = $xml->createElement("lastmod", date('Y-m-d'));
        $priority = $xml->createElement("priority", "1.0");
        $homeUrl->appendChild($loc);
        $homeUrl->appendChild($lastmod);
        $homeUrl->appendChild($priority);
        $urlset->appendChild($homeUrl);
        
        error_log("Homepage added to sitemap");
        
        // Get all blog posts
        $stmt = $pdo->query("SELECT url, created_at FROM blog ORDER BY created_at DESC");
        if (!$stmt) {
            error_log("Failed to query blog posts: " . print_r($pdo->errorInfo(), true));
            return false;
        }
        
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Found " . count($posts) . " blog posts");
        
        // Add each blog post to sitemap
        foreach ($posts as $post) {
            if (empty($post['url'])) {
                error_log("Skipping post with empty URL");
                continue;
            }
            
            $url = $xml->createElement("url");
            
            // Create full URL by combining host and post URL
            $fullUrl = "https://" . $_SERVER['HTTP_HOST'] . $post['url'];
            error_log("Processing URL: " . $fullUrl);
            
            $loc = $xml->createElement("loc", $fullUrl);
            $lastmod = $xml->createElement("lastmod", date('Y-m-d', strtotime($post['created_at'])));
            $priority = $xml->createElement("priority", "0.8");
            
            $url->appendChild($loc);
            $url->appendChild($lastmod);
            $url->appendChild($priority);
            
            $urlset->appendChild($url);
        }
        
        // Get the save path and ensure directory exists
        $sitemapPath = dirname(__FILE__) . '/sitemap.xml';
        $directory = dirname($sitemapPath);
        
        // Check directory permissions
        if (!is_writable($directory)) {
            error_log("Directory is not writable: " . $directory);
            return false;
        }
        
        error_log("Attempting to save sitemap to: " . $sitemapPath);
        
        // Save sitemap file
        if ($xml->save($sitemapPath)) {
            error_log("Sitemap saved successfully");
            // Ping search engines
            pingSearchEngines();
            return true;
        } else {
            error_log("Failed to save sitemap");
            return false;
        }
        
    } catch (Exception $e) {
        error_log("Sitemap generation failed with exception: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return false;
    }
}

// Update the createPost method in StaticBlogGenerator class
public function createPost($title, $description, $image) {
    try {
        error_log("Starting post creation...");
        
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
            error_log("Post inserted successfully");
            
            // Update sitemap
            if(updateSitemap($this->pdo)) {
                error_log("Sitemap updated successfully");
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
                error_log("Static page generated for post ID: " . $postId);
            } else {
                error_log("Sitemap update failed");
                $msg = "Post created but sitemap update failed.";
            }
        } else {
            error_log("Post creation failed: " . print_r($stmt->errorInfo(), true));
            $msg = "Error creating post.";
        }
        
        return ['success' => ($msg === "Post created and sitemap updated successfully!"), 'message' => $msg];
    } catch (PDOException $e) {
        error_log("Post creation failed with exception: " . $e->getMessage());
        throw new Exception("Failed to create post: " . $e->getMessage());
    }
}