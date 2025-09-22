<?php
// template.php
// Post data will be injected here by the generator
$post = /* POST_DATA_PLACEHOLDER */
?>
<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="language" content="en">
    <base href="https://blog.armydogcenter.net.pk/" />

    <!-- SEO Optimization -->
    <title><?= htmlspecialchars($post['title']) ?> | Army Dog Center Karachi</title>
    <meta name="description" content="03003006220 | 03453406220 | <?= htmlspecialchars($post['title']) ?> - The Army Dog Center is a premier facility dedicated to the training, care, and rehabilitation of military working dogs. Our team consists of experienced trainers, veterinarians, and animal behaviorists who understand the unique needs of these courageous animals.">
    <meta name="keywords" content="army dog center, military working dogs, dog training, <?= htmlspecialchars($post['title']) ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $post['url']; ?>">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="https://blog.armydogcenter.net.pk/sitemap.xml" />
    <meta name="DC.title" content="<?= htmlspecialchars($post['title']) ?>">
    <meta name="DC.subject" content="Military Working Dogs">
    <meta name="DC.creator" content="Army Dog Center Karachi">

    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="<?= htmlspecialchars($post['title']) ?> | Army Dog Center Karachi">
    <meta property="og:description" content="03003006220 | 03453406220 | <?= htmlspecialchars($post['title']) ?> - The Army Dog Center is a premier facility dedicated to the training, care, and rehabilitation of military working dogs.">
    <meta property="og:url" content="<?= $post['url']; ?>">
    <meta property="og:type" content="article">
  <meta property="og:image" content="https://armydogcenter.net.pk/images/card-4.jpeg">
    <meta property="og:site_name" content="Army Dog Center Karachi">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($post['title']) ?> | Army Dog Center Karachi">
    <meta name="twitter:description" content="03003006220 | 03453406220 | <?= htmlspecialchars($post['title']) ?> - The Army Dog Center is a premier facility dedicated to military dog training and care.">
    <meta name="twitter:image" content="https://armydogcenter.net.pk/images/logo.png">

    <!-- Favicon -->
    <link rel="icon" href="https://armydogcenter.net.pk/images/logo.png" type="image/webp">
    <link rel="apple-touch-icon" href="https://armydogcenter.net.pk/images/logo.png">

    <!-- JSON-LD Schema Markup -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Article",
            "headline": "<?= htmlspecialchars($post['title']) ?>",
            "datePublished": "<?= $post['created_at'] ?>",
            "image": "https://armydogcenter.net.pk/images/card-4.jpeg",
            "author": {
                "@type": "Organization",
                "name": "Army Dog Center Karachi"
            },
            "publisher": {
                "@type": "Organization",
                "name": "Army Dog Center Karachi",
                "logo": {
                    "@type": "ImageObject",
                    "url": "https://armydogcenter.net.pk/images/logo.png"
                }
            },
            "url": "<?= $post['url']; ?>"
        }
    </script>
      <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
      <?php
require '../../includes/header.php';
?>
    <main class="pt-8 pb-16 lg:pt-16 lg:pb-24 bg-white antialiased">
        <div class="flex justify-between px-4 mx-auto max-w-screen-xl">
            <article class="mx-auto w-full max-w-2xl format format-sm sm:format-base lg:format-lg format-blue">
                <header class="mb-4 lg:mb-6 not-format">
                    <address class="flex items-center mb-6 not-italic">
                        <div class="inline-flex items-center mr-3 text-sm text-gray-900">
                            <img class="mr-4 w-16 h-16 rounded-full"
                                src="https://armydogcenter.net.pk/images/logo.png" alt="Army Dog Center">
                            <div>
                                <a rel="author" class="text-xl font-bold text-gray-900">
                                    Army Dog Center</a>
                               
                                <p class="text-base text-gray-500 ">
                                    <time pubdate datetime="<?= $post ? $post['created_at'] : ''; ?>">
                                        <?= $post ? date('M d, Y', strtotime($post['created_at'])) : ''; ?>
                                    </time>
                                </p>
                            </div>
                        </div>
                    </address>
                    <h1 class="text-3xl font-bold leading-tight text-gray-900">
                        <?= htmlspecialchars($post['title']) ?>
                    </h1>
                </header>
                <div class="text-gray-700">
                    <?= $post['description'] ?>
                </div>
            </article>
        </div>
    </main>

    <aside aria-label="Related articles" class="py-8 lg:py-24 bg-gray-50">
        <div class="px-4 mx-auto max-w-screen-xl">
            <h2 class="mb-8 text-2xl font-bold text-gray-900">Related articles</h2>
            <div class="grid gap-4" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                 <?php /* RELATED_POSTS_PLACEHOLDER */ ?>
            </div>
        </div>
    </aside>
      <?php
require '../../includes/footer.php';
?>
</body>
</html>