<?php include '../db/session.php'; ?>




<?php


// Check if admin session exists
if (isset($_SESSION['admin'])) {
} else {
    echo ("Session userInfo key does not exist. Redirecting to login.");
    // Redirect to login page
    header("Location: adminSI.php");
    exit;
}



?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>




    <script>
        // Function to set light or dark mode
        const setMode = (isDark) => {
            const root = document.documentElement;

            if (isDark) {
                root.style.setProperty("--bg-color", "#222325");
                root.style.setProperty("--container-color", "#161719");
                root.style.setProperty("--text-color", "#FFFFFF");
                root.style.setProperty("--td-color", "#D1D0D0");
                root.style.setProperty("--hover-color", "#37383a");
                root.style.setProperty("--title-color", "#ffffff");
                root.style.setProperty("--chart-border-color", "#161719");

                localStorage.setItem("darkMode", "enabled");
            } else {
                root.style.setProperty("--bg-color", "#F3F3F7");
                root.style.setProperty("--container-color", "#ffffff");
                root.style.setProperty("--text-color", "#58555E");
                root.style.setProperty("--td-color", "dimgray");
                root.style.setProperty("--hover-color", "#f0f8ff");
                root.style.setProperty("--title-color", "#19181B");
                root.style.setProperty("--chart-border-color", "white");

                localStorage.setItem("darkMode", "disabled");
            }

            // Introduce a slight delay to allow the browser to process style changes
            setTimeout(() => {
                // Force reflow
                root.style.display = "none";
                root.offsetHeight; // Trigger reflow
                root.style.display = "";

                rootStyles = getComputedStyle(root);
                borderColor = rootStyles.getPropertyValue("--chart-border-color").trim();
                console.log("Updated Border Color:", borderColor); // For debugging
            }, 0);
        };

        // Get the root element
        var rootStyles;

        // Retrieve CSS variable values
        var borderColor;

        // Check localStorage for saved mode
        const isDarkMode = localStorage.getItem("darkMode") === "enabled";
        setMode(isDarkMode);
    </script>




    <link rel="stylesheet" href="./admin.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous">
    <script src="../node_modules/axios/dist/axios.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery (necessary for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">






</head>



<body>



    <div class="admin-main">

        <div class="pop-up-bg hide   ">


        </div>


        <div class="header">

            <!-- <div class="search-bar" style="opacity: 0;">

                <input type="text" name="" placeholder="Search">



            </div> -->


            <div class="header__toggle">
                <i class="bx bx-menu nav__icon" id="header-toggle" onclick="toggleMenu()"></i>
            </div>


            <div class="profile">

                <img src="../resources/admin.png">

                <span>Admin</span>

                <i><svg width="13" height="7" viewBox="0 0 13 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.17585 6.38027C6.79349 6.73088 6.20651 6.73088 5.82415 6.38027L1.03312 1.98704C0.360988 1.37072 0.797034 0.25 1.70896 0.25L11.291 0.25C12.203 0.25 12.639 1.37072 11.9669 1.98704L7.17585 6.38027Z" fill="#4D4D4D" />
                    </svg>
                </i>

            </div>

        </div>


        <div class="nav" id="navbar">
            <nav class="nav__container">
                <div>

                    <a href="#" class="nav__link nav__logo">
                        <!-- <i class='bx bxs-disc nav__icon'></i> -->
                        <img class="nav__icon" src="../resources/logo2png.png" alt="">
                        <span class="nav__name">Novel Nexus</span>
                    </a>

                    <!-- <div class="logo-container">

                    <img src="../resources/logo2png.png" alt="">
                    Novel
                    </div> -->


                    <div class="nav__list">
                        <div class="nav__items">
                            <h3 class="nav__subtitle">Dashboard</h3>

                            <a href="admin.php?page=dashboard" class="nav__link navActive">
                                <i class='bx bxs-dashboard nav__icon'></i>
                                <span class="nav__name">Dashboard</span>
                            </a>


                            <a href="admin.php?page=books" class="nav__link">
                                <i class='bx bx-book-alt nav__icon'></i>
                                <span class="nav__name">Books</span>
                            </a>


                            <a href="admin.php?page=users" class="nav__link">
                                <i class='bx bx-user nav__icon'></i>
                                <span class="nav__name">Users</span>
                            </a>

                            <a href="admin.php?page=loans" class="nav__link">
                                <i class='bx bx-book-reader  nav__icon'></i>
                                <span class="nav__name">Loans</span>
                            </a>


                            <a href="admin.php?page=return" class="nav__link">
                                <i class='bx bx-book  nav__icon'></i>
                                <span class="nav__name">Book Return</span>
                            </a>


                            <i class=''></i>



                        </div>


                    </div>
                </div>



                <div class="nav__list">
                    <div class="nav__items">


                        <span onclick="darkModeToggle()" class="nav__link nav__logout">
                            <i class='bx bx-moon  nav__icon'></i>
                            <span class="nav__name">Mode Toggle</span>
                        </span>

                        <a href="../db/php/dashboard/adminLogout.php" class="nav__link ">
                            <i class='bx bx-log-out nav__icon'></i>
                            <span class="nav__name">Log Out</span>
                        </a>



                    </div>
                </div>
            </nav>
        </div>




        <div class="content">
            <?php
            // Set the default page to 'home'
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

            // Sanitize the page variable to prevent directory traversal
            $allowed_pages = [
                'dashboard',
                'users',
                'books',
                'book-prev',
                'loans',
                'return'
            ];


            if (in_array($page, $allowed_pages)) {
                include "pages/$page.php";
            } else {
                echo "<p>Page not found.</p>";
            }
            ?>
        </div>
    </div>


</body>


<script src="./admin.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>


<script>
    // Get the current URL's `page` query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page') || 'dashboard'; // Default to 'dashboard'

    // Get all nav links
    const navLinks = document.querySelectorAll('.nav__link');

    navLinks.forEach(link => {
        // Check if the link's href matches the current page
        const linkPage = new URL(link.href).searchParams.get('page');
        if (linkPage === currentPage) {
            link.classList.add('navActive');
        } else {
            link.classList.remove('navActive');
        }
    });

    /*==================== SHOW NAVBAR ====================*/

    function toggleMenu() {
    const toggleBtn = document.getElementById('header-toggle');
    const nav = document.getElementById('navbar');

    nav.classList.toggle('show-menu');
    toggleBtn.classList.toggle('bx-x');
}


</script>








</html>