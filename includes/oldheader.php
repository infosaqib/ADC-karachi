<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Simple Website</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
</head>

<body>

    <header class="bg-[#111b21] text-white py-2 uppercase font-semibold tracking-wide text-lg z-20 relative">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="w-[70px] md:w-[85px]">
                <img src="https://armydogcenter.net.pk/images/newlogo.png" alt="Logo" class="w-full h-auto object-contain" />
            </div>
            <button id="navbar-dropdown" data-collapse-toggle="navbar-dropdown" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                aria-controls="navbar-dropdown" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <nav class="hidden z-20 bg-[#111b21] absolute md:static top-[76px] right-0 p-4  md:block overflow-y-auto md:overflow-y-hidden h-[80vh] md:h-auto"
                id="menu">
             <ul class="flex flex-col md:flex-row gap-4 text-base md:text-xl ">   <li><a href="https://armydogcenter.net.pk/" class="px-3 hover:text-gray-200 transition">Home</a></li>
                <li><a href="https://about.armydogcenter.net.pk/" class="px-3 hover:text-gray-200 transition">About</a></li>
                <li><a href="https://blog.armydogcenter.net.pk/" class="px-3 hover:text-gray-200 transition">Blog</a></li>
       <li class="group relative">
    <a href="https://services.armydogcenter.net.pk/" id="dropdownNavbarLink" class="flex items-center justify-between w-full py-2 px-3 text-lg text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-teal-500 md:p-0 md:w-auto dark:text-white md:dark:hover:text-teal-500 dark:focus:text-white dark:border-gray-700 dark:hover:bg-gray-700 md:dark:hover:bg-transparent">
        Services
        <svg class="hidden md:block w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"></path>
        </svg>
    </a>
<!-- Dropdown menu -->
<div id="dropdownNavbar" class="flex md:hidden md:group-hover:block md:absolute top-6 z-10 font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-400" aria-labelledby="dropdownNavbarLink">
        <li>
            <a href="https://services.armydogcenter.net.pk/malir-cannt-karachi.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center MALIR CANTT KARACHI | 03003406220
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/karachi.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center KARACHI | 03332874135
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/hub-chowki.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center HUB CHOWKI | 03003406220
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/hyderabad.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center HYDERABAD | 03332874135
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/hyderabad-cantt.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center HYDERABAD CANTT | 03003406220
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/thatta.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center THATTA | 03332874135
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/badin.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center BADIN | 03003406220
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/badin-cantt.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center BADIN CANTT | 03332874135
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/mirpurkhass.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center MIRPURKHASS | 03003406220
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/umerkote.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center UMERKOTE | 03332874135
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/chohar-cantt.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center CHOHAR CANTT | 03003406220
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/islamkote.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center ISLAMKOTE | 03332874135
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/mithi.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center MITHI | 03003406220
            </a>
        </li>
        <li>
            <a href="https://services.armydogcenter.net.pk/sujawal.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                Army Dog Center SUJAWAL | 03332874135
            </a>
        </li>
    </ul>
</div>

</li>
                <li><a href="https://contact.armydogcenter.net.pk/" class="px-3 hover:text-gray-200 transition">Contact</a></li>
                <!--<li><a href="tel:+923332874135"-->
                <!--    class="px-3 py-2 mx-4 rounded-[100px] bg-cyan-500 hover:bg-cyan-600 text-white text-sm md:text-base tracking-wide font-semibold">-->
                <!--    Call Us: 03332874135-->
                <!--</a></li>-->
                </ul>
            </nav>
        </div>
    </header>

    <!-- OVERLAY -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-10" onclick="hideMenu()"></div>

    <!-- Call Us Button -->
    <a class="fixed z-[3] bottom-20 size-12 p-[7px] left-4 bg-green-400 rounded-lg flex justify-center items-center"
        href="tel:+923332874135">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class=" size-12 bi bi-telephone-fill"
        viewBox="0 0 16 16">
        <path fill-rule="evenodd"
            d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
    </svg>
    </a>

    <!-- Whatsapp Button -->
    <a class="fixed z-[3] bottom-4 left-4 size-12" href="https://wa.me/+923332874135">
        <svg class="wow flash bg-[#1dcf1d] hover:bg-[#09b709] rounded-xl" data-wow-delay="5s" data-wow-duration="2s"
            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32"
            style="visibility: visible; animation-duration: 2s; animation-delay: 5s; animation-name: flash;">
            <path
                d=" M19.11 17.205c-.372 0-1.088 1.39-1.518 1.39a.63.63 0 0 1-.315-.1c-.802-.402-1.504-.817-2.163-1.447-.545-.516-1.146-1.29-1.46-1.963a.426.426 0 0 1-.073-.215c0-.33.99-.945.99-1.49 0-.143-.73-2.09-.832-2.335-.143-.372-.214-.487-.6-.487-.187 0-.36-.043-.53-.043-.302 0-.53.115-.746.315-.688.645-1.032 1.318-1.06 2.264v.114c-.015.99.472 1.977 1.017 2.78 1.23 1.82 2.506 3.41 4.554 4.34.616.287 2.035.888 2.722.888.817 0 2.15-.515 2.478-1.318.13-.33.244-.73.244-1.088 0-.058 0-.144-.03-.215-.1-.172-2.434-1.39-2.678-1.39zm-2.908 7.593c-1.747 0-3.48-.53-4.942-1.49L7.793 24.41l1.132-3.337a8.955 8.955 0 0 1-1.72-5.272c0-4.955 4.04-8.995 8.997-8.995S25.2 10.845 25.2 15.8c0 4.958-4.04 8.998-8.998 8.998zm0-19.798c-5.96 0-10.8 4.842-10.8 10.8 0 1.964.53 3.898 1.546 5.574L5 27.176l5.974-1.92a10.807 10.807 0 0 0 16.03-9.455c0-5.958-4.842-10.8-10.802-10.8z"
                fill-rule="evenodd" fill="#fff"></path>
        </svg>
    </a>

    <script>
        // Mobile menu toggle
        const toggleButton = document.querySelector("[data-collapse-toggle]");
        const menu = document.getElementById("menu");
        const overlay = document.getElementById("overlay");

        toggleButton.addEventListener("click", () => {
            menu.classList.toggle("hidden");
            overlay.classList.toggle("hidden");
            document.body.classList.toggle('overflow-y-hidden');
        });

        function hideMenu() {
            menu.classList.add("hidden");
            overlay.classList.add("hidden");
            document.body.classList.remove('overflow-y-hidden');
        }
    </script>
</body>

</html>
