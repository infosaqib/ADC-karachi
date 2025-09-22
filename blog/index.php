
<!-- SEO Meta Tags -->
<head>
      <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Optimization -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4828179335177828"
     crossorigin="anonymous"></script>
    <title>Blog | 03003406220, 03332874135 </title>
     <meta name="description" content="        چوری، ڈکیتی، اور دیگر ہنگامی حالات میں مدد کے لیے تیار۔ ہمارے تربیت یافتہ کتے ثبوت اور سراغ تلاش کرنے میں مدد کرتے ہیں۔ 24/7 دستیاب، کہیں بھی اور جب بھی آپ کو ضرورت ہو، آپ کے اطمینان کے لیے ہماری سرشار ٹیم ہمیشہ موجود ہے۔  ">
        <meta name="robots" content="index, follow">
        
        
        <!-- Google Site Verification -->
        <meta name="google-site-verification" content="" />

    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="Blog | 03003406220, 03332874135">
    <meta property="og:description" content="  چوری، ڈکیتی، اور دیگر ہنگامی حالات میں مدد کے لیے تیار۔ ہمارے تربیت یافتہ کتے ثبوت اور سراغ تلاش کرنے میں مدد کرتے ہیں۔ 24/7 دستیاب، کہیں بھی اور جب بھی آپ کو ضرورت ہو، آپ کے اطمینان کے لیے ہماری سرشار ٹیم ہمیشہ موجود ہے۔    ">
    <meta property="og:url" content="https://armydogcenter.net.pk/">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://armydogcenter.net.pk/images/card-4.jpeg">
    <meta property="og:site_name" content="Blog | 03003406220, 03332874135">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Blog | 03003406220, 03332874135">
    <meta name="twitter:description" content="  چوری، ڈکیتی، اور دیگر ہنگامی حالات میں مدد کے لیے تیار۔ ہمارے تربیت یافتہ کتے ثبوت اور سراغ تلاش کرنے میں مدد کرتے ہیں۔ 24/7 دستیاب، کہیں بھی اور جب بھی آپ کو ضرورت ہو، آپ کے اطمینان کے لیے ہماری سرشار ٹیم ہمیشہ موجود ہے۔    ">
    <meta name="twitter:image" content="https://armydogcenter.net.pk/images/card-4.jpeg">

    <!-- Favicon -->
    <link rel="icon" href="https://armydogcenter.net.pk/images/newlogo.png" type="image/webp">
    <link rel="apple-touch-icon" href="https://armydogcenter.net.pk/images/newlogo.png">
    <link rel="canonical" href="https://armydogcenter.net.pk/">
        
 
  <!-- Schema Markup -->
    <script type="application/ld+json">
    {
        "@context":"https://schema.org",
        "@graph":[
            {
                "@type": "WebSite",
                "name": "Army Dog Center Karachi",
                "url": "https://armydogcenter.net.pk/",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "https://armydogcenter.net.pk/",
                    "query-input": "required name=armydogcenter"
                }
            },
            {
                "@type":"WebPage",
                "@id":"https://blog.armydogcenter.net.pk/#webpage",
                "url":"https://blog.armydogcenter.net.pk/",
                "name":"Blog | Army Dog Center Karachi",
                "description":"Insights into military dog training, operational strategies, and the crucial roles of trained canines in security and defense operations.",
                "about":{"@id":"https://armydogcenter.net.pk/#organization"},
                "inLanguage":"en-US"
            },
          
        ]
    }
</script>
  </head>
    <?php include '../includes/header.php'; ?>

<!-- Blog Header -->
<div class="flex flex-col items-center justify-center text-center gap-4 p-4">
  <h2 class="my-4 text-teal-500 font-bold text-2xl sm:text-5xl tracking-wider uppercase">Our Blog</h2>
  <p class="mb-4 leading-relaxed text-gray-600 text-sm w-[75%] text-center">
    Stay updated with our latest news, training tips, and success stories.
  </p>
</div>

<!-- Blog Section -->
<section class="p-6 flex flex-wrap items-end justify-center gap-8 overflow-x-hidden">

<?php
include '../config/config.php';

$limit = 6; // Number of posts per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

try {
    $stmt = $pdo->prepare("SELECT id, title, description, image, url, created_at FROM blog ORDER BY created_at DESC LIMIT :start, :limit");
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    while ($blog = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $date = new DateTime($blog['created_at']);
        $formatted_date = $date->format('j F, Y');

        // Truncate description if it's too long
        $description = strip_tags($blog['description']);
        $description = strlen($description) > 90 ? substr($description, 0, 90) . '...' : $description;

        // Truncate title if it's too long
        $title = strip_tags($blog['title']);
        $title = strlen($title) > 50 ? substr($title, 0, 50) . '...' : $title;

        $readMoreUrl = !empty($blog['url']) ? $blog['url'] : "blogpost.php?id=" . $blog['id'];
        ?>
        <div class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300 w-[320px] sm:w-[400px] md:w-[350px] lg:w-[400px]">
            <a href="<?= $readMoreUrl ?>">
                <div class="flex justify-center items-center h-[195px] overflow-hidden rounded-t-lg">
                    <?php if ($blog['image']): ?>
                        <img class="object-cover h-full w-full" src="admin/uploads/<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($title) ?>">
                    <?php else: ?>
                        <div class="flex items-center justify-center bg-gray-200 h-full w-full rounded-t-lg">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="px-6 py-4 border-t h-[190px]">
                    <h2 class="my-2 text-xl font-semibold text-gray-800"><?= htmlspecialchars($title) ?></h2>
                    <hr>
                    <p class="my-2 text-sm font-semibold text-gray-500 flex items-center gap-2">
                        BLOG
                        <svg fill="none" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#fff" stroke="#9ca3af" d="M16 2.42v4.17c0 .23.22.42.5.42s.5-.19.5-.42V2.42c0-.23-.22-.42-.5-.42s-.5.19-.5.42zM7 2.42v4.17c0 .23.22.42.5.42s.5-.19.5-.42V2.42c0-.23-.22-.42-.5-.42s-.5.19-.5.42zM3 6.47v14.06c0 1.36 1.1 2.47 2.46 2.47h13.08c1.36 0 2.46-1.11 2.46-2.47V6.47c0-1.36-1.1-2.47-2.46-2.47H5.46C4.1 4 3 5.11 3 6.47zm5 10.53c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm4 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm4 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-8-4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm4 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm4 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm4.05-3H3.95v-3.53c0-.84.68-1.52 1.52-1.52h13.06c.84 0 1.52.68 1.52 1.52z"/>
                        </svg>
                        <span><?= $formatted_date ?></span>
                    </p>
                    <p class="my-2 text-sm text-gray-500"><?= $description ?></p>
                </div>
            </a>
        </div>
        <?php
    }

    // Pagination
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM blog");
    $totalPosts = $stmtTotal->fetchColumn();
    $totalPages = ceil($totalPosts / $limit);
    ?>
</section>


<!-- Pagination -->
<div class="w-full flex justify-center my-10">
  <div class="inline-flex -space-x-px text-sm">
    <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>" class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 rounded-l-lg">Previous</a>
    <?php endif; ?>
      
    <?php
    // Calculate the range of pages to show
    $startPage = max(1, $page - 1);
    $endPage = min($totalPages, $page + 1);
    
    // Adjust if we're at the beginning or end
    if ($page == 1) {
        $endPage = min($totalPages, 3);
    } elseif ($page == $totalPages) {
        $startPage = max(1, $totalPages - 2);
    }
    
    // Show first page if not in range
    if ($startPage > 1): ?>
        <a href="?page=1" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100">1</a>
        <?php if ($startPage > 2): ?>
            <span class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300">...</span>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
      <a href="?page=<?= $i ?>" class="px-3 py-2 leading-tight border <?= $page === $i ? 'bg-cyan-600 text-white border-cyan-600' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100' ?>">
        <?= $i ?>
      </a>
    <?php endfor; ?>
    
    <!-- Show last page if not in range -->
    <?php if ($endPage < $totalPages): ?>
        <?php if ($endPage < $totalPages - 1): ?>
            <span class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300">...</span>
        <?php endif; ?>
        <a href="?page=<?= $totalPages ?>" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100"><?= $totalPages ?></a>
    <?php endif; ?>

    <?php if ($page < $totalPages): ?>
      <a href="?page=<?= $page + 1 ?>" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 rounded-r-lg">Next</a>
    <?php endif; ?>
  </div>
</div>

<?php
} catch (PDOException $e) {
    echo "<div class='text-red-500'>Error loading blog posts: " . $e->getMessage() . "</div>";
}
?>

        <?php include '../includes/footer.php'; ?>
