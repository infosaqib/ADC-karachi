<!-- Blog Posts Preview -->
<section class="container mx-auto px-4 py-12">
  <h2 class="text-3xl md:text-5xl inter-font font-light mb-8 text-center">
    Recent Blog Posts
  </h2>
  <div class="flex flex-wrap items-center justify-center gap-6">
    <?php
    global $pdo;
    include 'config/config.php';
    try {
      $stmt = $pdo->query("SELECT id, title, description, image, url, created_at FROM blog ORDER BY created_at DESC LIMIT 3");
      while ($blog = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Format the date
        $date = new DateTime($blog['created_at']);
        $formatted_date = $date->format('j F, Y');

        // Strip and limit title and description
        $title = strip_tags($blog['title']);
        $title = (strlen($title) > 50) ? substr($title, 0, 50) . '...' : $title;

        $description = strip_tags($blog['description']);
        $description = (strlen($description) > 90) ? substr($description, 0, 90) . '...' : $description;

        // Image logic
        $image = $blog['image'];
        $isExternal = preg_match('/^https?:\/\//', $image);
        $imgSrc = $isExternal ? $image : "blog/admin/uploads/" . $image;

        $readMoreUrl = !empty($blog['url']) ? $blog['url'] : "blogpost.php?id=" . $blog['id'];
        ?>
        <div
          class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 w-[350px] flex flex-col overflow-hidden">
          <a href="https://blog.armydogcenter.net.pk<?= $readMoreUrl ?>" class="flex flex-col h-full">
            <div class="h-[260px] w-full overflow-hidden">
              <?php if (!empty($blog['image'])): ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($title) ?>"
                  class="w-full h-full object-cover" />
              <?php else: ?>
                <div class="flex items-center justify-center bg-gray-200 h-full w-full">
                  <span class="text-gray-500">No Image</span>
                </div>
              <?php endif; ?>
            </div>
            <div class="px-6 py-4 border-t h-[180px]">
              <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($title) ?></h3>
                <p class="text-sm text-gray-600"><?= htmlspecialchars($description) ?></p>
              </div>
              <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center gap-2">
                  <svg fill="none" height="18" width="18" viewBox="0 0 24 24" stroke="#9ca3af"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-width="1.5"
                      d="M16 2.42v4.17c0 .23.22.42.5.42s.5-.19.5-.42V2.42c0-.23-.22-.42-.5-.42s-.5.19-.5.42zM7 2.42v4.17c0 .23.22.42.5.42s.5-.19.5-.42V2.42c0-.23-.22-.42-.5-.42s-.5.19-.5.42zM3 6.47v14.06c0 1.36 1.1 2.47 2.46 2.47h13.08c1.36 0 2.46-1.11 2.46-2.47V6.47c0-1.36-1.1-2.47-2.46-2.47H5.46C4.1 4 3 5.11 3 6.47z" />
                  </svg>
                  <span><?= $formatted_date ?></span>
                </div>
                <span class="text-xs font-medium uppercase text-teal-600">Blog</span>
              </div>
            </div>
          </a>
        </div>

        <?php
      }
    } catch (PDOException $e) {
      echo "<div class='text-red-500'>Error loading blog posts: " . $e->getMessage() . "</div>";
    }
    ?>
  </div>
</section>