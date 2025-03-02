<?php
include './db/conn.php';


session_start();



// Check if the keys exist in session
if (isset($_SESSION['userInfo'][0])) {
    // Debugging output

    // Get user information
    $userInfo = $_SESSION['userInfo'][0];

    // Ensure that 'id' key exists in the user information
    if (isset($userInfo['id'])) {
        // Retrieve specific fields from user info
        $customerId = $userInfo['id'];
        $userPhoto = $userInfo['photo'];

        // Fetch updated user information from the database
        $selectQuery = "SELECT * FROM users WHERE id=?";
        $selectStatement = $conn->prepare($selectQuery);
        $selectStatement->bind_param("i", $customerId);
        $selectStatement->execute();
        $result = $selectStatement->get_result();
        $updatedUserInfo = $result->fetch_assoc();

        // Update session with new user information
        $_SESSION['userInfo'][0] = $updatedUserInfo;

        // Encode user information as JSON
        $userInfoJson = json_encode($updatedUserInfo);

        // Set a cookie with the encoded user information
        setcookie('userInfo', $userInfoJson, time() + (86400 * 30), '/'); // Cookie will expire in 30 days
        // Check if the password is not null and set a flag
        $userPasswordNotNull = !is_null($updatedUserInfo['password']);
    } else {
        echo ("Error: 'id' key is missing in userInfo array.");
    }
} else {
    echo ("Session userInfo key does not exist. Redirecting to login.");
    // Redirect to login page
    header("Location: signIn.php");
    exit;
}







?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="referrer" content="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="..." crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="./admin/admin.css">
    <link rel="stylesheet" href="./base.css">
    <script src="./node_modules/axios/dist/axios.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>

</head>


<script>
    // document.addEventListener("DOMContentLoaded", () => {
        // const toggleButton = document.getElementById("dark-mode-toggle");



        // Check localStorage for saved mode
   


    // });


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
            root.style.setProperty("--bg-color2", "#222325");


            localStorage.setItem("darkMode", "enabled");
        } else {
            root.style.setProperty("--bg-color", "#f9f9f9");
            root.style.setProperty("--container-color", "#ffffff");
            root.style.setProperty("--text-color", "#58555E");
            root.style.setProperty("--td-color", "dimgray");
            root.style.setProperty("--hover-color", "#f0f8ff");
            root.style.setProperty("--title-color", "#19181B");
            root.style.setProperty("--chart-border-color", "white");
            root.style.setProperty("--bg-color2", "#F3F3F7");

            

            localStorage.setItem("darkMode", "disabled");
        }
        rootStyles = getComputedStyle(document.documentElement);
        borderColor = rootStyles.getPropertyValue('--chart-border-color').trim();
    };


    // Get the root element
    var rootStyles;

    // Retrieve CSS variable values
    var borderColor;

    

    function darkModeToggle() {


        const isCurrentlyDark = localStorage.getItem("darkMode") === "enabled";
        setMode(!isCurrentlyDark);

    }


    const isDarkMode = localStorage.getItem("darkMode") === "enabled";
    setMode(isDarkMode);

</script>

<body>






    <div class="main">


        <div class="pop-up-bg hide">





        </div>

        <div class="header">

            <!-- <div class="search-bar" style="opacity: 0;">

                <input type="text" name="" placeholder="Search">



            </div> -->

            <div class="header__toggle" class="nav__link" >
                <i class='bx bx-menu nav__icon' id="header-toggle" onclick="toggleMenu()"></i>
            </div>


            <div class="right">

                <div>

                    <div class="notifIconDiv" onclick="showNotif()">
                        <i class='bx bx-bell nav__icon'></i>
                        <span class="notifCount">5</span>
                    </div>



                    <div id="popUpNotif" class="popup-notifications hide">

                        <div class="head">

                            <div>
                                <i class='bx bx-bell'></i>
                                <span>Recent Notifications</span>
                            </div>


                            <i class='bx bx-x' onclick="showNotif()"></i>
                        </div>



                        <div id="notifContent" class="notifContent">
                            <!-- Notifications will be injected here -->
                        </div>
                    </div>





                </div>


                <div class="profile">

                    <img src="<?php echo htmlspecialchars($userPhoto); ?>" alt="User Profile">
                    <span>
                        <?php
                        if (isset($_SESSION['userInfo'][0])) {
                            $userInfo = $_SESSION['userInfo'][0];
                            echo $userInfo['fname'];
                        }
                        ?>
                    </span>
                    <i><svg width="13" height="7" viewBox="0 0 13 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.17585 6.38027C6.79349 6.73088 6.20651 6.73088 5.82415 6.38027L1.03312 1.98704C0.360988 1.37072 0.797034 0.25 1.70896 0.25L11.291 0.25C12.203 0.25 12.639 1.37072 11.9669 1.98704L7.17585 6.38027Z" fill="#4D4D4D" />
                        </svg></i>
                </div>

            </div>



        </div>

        <div class="nav" id="navbar" >
            <nav class="nav__container">
                <div>


                    <!-- <img src="./resources/logo.png" alt=""> -->


                    <a href="#" class="nav__link nav__logo">
                        <!-- <i class='bx bxs-disc nav__icon'></i> -->
                        <img class="nav__icon" src="./resources/logo2png.png" alt="">
                        <span class="nav__name">Novel Nexus</span>
                    </a>


                    <!-- <a href="#" class="nav__link nav__logo">
                        <i class='bx bxs-disc nav__icon' style="color:mediumaquamarine"></i>
                        <span class="nav__logo-name">Library</span>
                    </a> -->

                    <div class="nav__list">
                        <div class="nav__items">
                            <h3 class="nav__subtitle">Profile</h3>

                            <a href="index.php?page=home-page" class="nav__link navActive">
                                <i class='bx bx-home nav__icon'></i>
                                <span class="nav__name">Home</span>
                            </a>


                            <a href="index.php?page=search-book" class="nav__link">
                                <i class='bx bx-search nav__icon'></i>
                                <span class="nav__name">Search</span>
                            </a>


                            <a href="index.php?page=my-shelf" class="nav__link">
                                <i class='bx bx-cabinet nav__icon'></i>
                                <span class="nav__name">My Shelf</span>
                            </a>

                            <a href="index.php?page=settings" class="nav__link">
                                <i class='bx bx-cog nav__icon'></i>
                                <span class="nav__name">Settings</span>
                            </a>




                        </div>

                    </div>
                </div>


                

                <div class="nav__list">
                    <div class="nav__items">


                        <span onclick="darkModeToggle()" class="nav__link nav__logout">
                            <i class='bx bx-moon  nav__icon'></i>
                            <span class="nav__name">Mode Toggle</span>
                        </span>

                        <a href="./db/php/user/logout.php" class="nav__link ">
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
            $page = isset($_GET['page']) ? $_GET['page'] : 'home-page';

            // Sanitize the page variable to prevent directory traversal
            $allowed_pages = [
                'home-page',
                'search-book',
                'my-shelf',
                'book-prev',
                'about',
                'support',
                'tos',
                'settings',
                'notifications',
                'password',
                'subscription',
                'userInfo',
                'pricing'
            ];


            if (in_array($page, $allowed_pages)) {
                include "pages/$page.php";
            } else {
                echo "<p>Page not found.</p>";
            }
            ?>
        </div>
    </div>


    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="./pages/user.js"></script>
    <script src="./js/bookPrev.js"></script>
    <script src="./js/payment.js"></script>
    <script src="./js/userProfile.js"></script>
    <script src="./js/userNotif.js"></script>


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
    toggleBtn.classList.toggle('bx-x');}
    </script>





</body>

</html>