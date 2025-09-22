<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

include('../../config/config.php');
require_once(__DIR__ . '/../static-generator.php');

$edit_id = $_GET['edit_id'] ?? null;
$edit_title = '';
$edit_description = '';
$isEditing = !empty($edit_id);
$buttonLabel = $isEditing ? 'Update' : 'Post';
$msg = '';
$msgType = '';

if ($isEditing) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM blog WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_post = $stmt->fetch();
        if ($edit_post) {
            $edit_title = $edit_post['title'];
            $edit_description = $edit_post['description'];
        }
    } catch (PDOException $e) {
        $msg = "Failed to load post.";
        $msgType = "error";
    }
}

if (isset($_POST['submit'])) {
    try {
        $title = $_POST['title'];
        $description = $_POST['content'];
        $image = '';

        $description = preg_replace_callback(
            '/<img[^>]+src=["\'](?:uploads\/)([^"\']+)["\']/',
            fn($matches) => '<img src="../admin/uploads/' . $matches[1] . '"',
            $description
        );

        if (preg_match('/<img[^>]+src=["\']\.\.\/admin\/uploads\/([^"\']+)["\']/', $description, $matches)) {
            $image = $matches[1];
        }

        $created_at = date('Y-m-d H:i:s');
        $generator = new StaticBlogGenerator($pdo);

        if (!empty($_POST['edit_id'])) {
            $editId = $_POST['edit_id'];
            $stmt = $pdo->prepare("UPDATE blog SET title = :title, description = :description, image = :image WHERE id = :id");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':image' => $image,
                ':id' => $editId
            ]);

            $post = [
                'id' => $editId,
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'created_at' => $created_at
            ];
            $generator->generateStaticPage($post);

            $msg = "Post updated successfully.";
            $msgType = "success";
        } else {
            $stmt = $pdo->prepare("INSERT INTO blog (title, description, image, created_at) VALUES (:title, :description, :image, :created_at)");
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':image' => $image,
                ':created_at' => $created_at
            ]);
            $postId = $pdo->lastInsertId();
            $post = [
                'id' => $postId,
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'created_at' => $created_at
            ];
            $generator->generateStaticPage($post);

            $msg = "Post published successfully.";
            $msgType = "success";
        }
    } catch (PDOException $e) {
        $msg = "Failed to publish post.";
        $msgType = "error";
    } catch (Exception $e) {
        $msg = "Static page generation failed.";
        $msgType = "error";
    }
}

if (isset($_GET['delete_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT image FROM blog WHERE id = ?");
        $stmt->execute([$_GET['delete_id']]);
        $post = $stmt->fetch();

        $stmt = $pdo->prepare("DELETE FROM blog WHERE id = ?");
        $stmt->execute([$_GET['delete_id']]);

        if ($post && $post['image']) {
            $imagePath = 'uploads/' . $post['image'];
            if (file_exists($imagePath)) unlink($imagePath);
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        $msg = "Failed to delete post.";
        $msgType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rich Text Editor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="stylesheets/style.css">
</head>
<body>
<div class="container my-6">
    <a href="logout.php" class="flex items-center justify-center my-4">
        <button class="btn btn-primary">Logout</button>
    </a>

    <h3 class="text-center text-3xl my-2">Rich Text Editor</h3>
    <p class="intro-text text-center my-2">Create or update blog posts using the editor below.</p>

    <div class="box">
        <form method="post">
            <input type="hidden" name="edit_id" value="<?= $isEditing ? htmlspecialchars($edit_id) : '' ?>">

            <input type="text" name="title" class="title-input" placeholder="Enter blog title" value="<?= htmlspecialchars($edit_title) ?>" required>

            <div class="form-group">
                <textarea id="content" name="content" class="form-control"><?= htmlspecialchars($edit_description) ?></textarea>
            </div>

            <div class="form-group flex items-center justify-center">
                <input type="submit" name="submit" value="<?= $buttonLabel ?>" id="submitBtn" class="btn btn-primary px-24">
            </div>
        </form>

        <?php if (!empty($msg)): ?>
            <div class="my-4 px-4 py-2 rounded text-white <?= $msgType === 'success' ? 'bg-green-500' : 'bg-red-500' ?>">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>
    </div>

    <hr class="my-12">

    <section class="flex flex-row-reverse flex-wrap items-center justify-center gap-4 px-2 py-8">
        <?php
        try {
            $stmt = $pdo->query("SELECT id, title, description, image, url FROM blog ORDER BY created_at DESC");
            while ($blog = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $title = htmlspecialchars($blog['title']);
                $description = strip_tags($blog['description']);
                $image = htmlspecialchars($blog['image']);
                $readMoreUrl = !empty($blog['url']) ? $blog['url'] : "blogpost.php?id=" . $blog['id'];
                ?>
                <article class="relative overflow-hidden w-[300px] rounded-lg border border-gray-500 bg-white shadow-sm">
                    <button onclick="if(confirm('Are you sure you want to delete this post?')) window.location.href='?delete_id=<?= $blog['id'] ?>'" 
                            class="absolute top-2 right-2 z-10 bg-white hover:bg-gray-100 text-black text-3xl border border-black font-bold py-1 px-4 rounded">
                        ×
                    </button>

                    <a href="?edit_id=<?= $blog['id'] ?>" 
                       class="absolute top-2 left-2 z-10 bg-yellow-300 hover:bg-yellow-400 text-black font-bold py-2 px-4 rounded text-base border border-black">
                        ✎ Edit
                    </a>

                    <a href="<?= $readMoreUrl ?>">
                        <?php if ($image): ?>
                            <img alt="<?= substr($title, 0, 50) . '...' ?>" 
                                 src="uploads/<?= $image ?>" 
                                 class="h-52 w-full object-fill" />
                        <?php endif; ?>
                        <div class="p-4 sm:p-6">
                            <h3 class="text-xl font-medium text-gray-900"><?= substr($title, 0, 50) . '...' ?></h3>
                            <p class="mt-2 text-xl text-gray-500"><?= substr($description, 0, 150) . '...' ?></p>
                            <span class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Read more →
                            </span>
                        </div>
                    </a>
                </article>
                <?php
            }
        } catch (PDOException $e) {
            echo "<div class='text-red-500'>Error loading posts.</div>";
        }
        ?>
    </section>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    let editor;

    ClassicEditor
        .create(document.querySelector('#content'), {
            ckfinder: {
                uploadUrl: 'fileupload.php'
            }
        })
        .then(newEditor => {
            editor = newEditor;
            const msg = document.querySelector('.bg-green-500');
            if (msg) {
                editor.setData('');
                document.querySelector('input[name=title]').value = '';
                document.querySelector('#submitBtn').value = 'Post';
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        })
        .catch(error => console.error(error));
</script>

<script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
