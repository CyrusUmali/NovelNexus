<?php
include './db/conn.php';
?> 
 
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <link rel="stylesheet" href="base.css"> 
     <!-- <link rel="stylesheet" href="style.css"> -->
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
     <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>

 
 </head>

 <body>


     <div class="container">

         <nav class="nav  ">

             <div class="left">

                 <!-- Library --> -
                  <img src="./resources/logo.png" alt="">

             </div>

             <i class='bx bx-menu' onclick="expandNav()"></i>


             <ul class="mid  ">

                 <li><a href="base.php?page=land-page" class="underline-hover-animation">Home</a></li>
                 <li><a href="base.php?page=about" class="underline-hover-animation">About</a></li>
                 <!-- <li><a href="base.php?page=faq" class="underline-hover-animation">Faq </a></li> -->
                 <li><a href="base.php?page=contactus" class="underline-hover-animation">Contact Us</a></li>
                 <!-- <li><a href="base.php?page=books-page" class="underline-hover-animation">Books</a></li> -->

             </ul>

             <div class="right">

                 <button class="login" onclick="logInClick()">Login</button>
                 <button class="signup" onclick="logInClick()">Sign Up</button>
             </div>

         </nav>





         <div>
             <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'land-page';

                $allowed_pages = [
                    // 'faq',
                    'land-page',
                    'about',
                    'contactus',
                    'books-page'
                ];


                if (in_array($page, $allowed_pages)) {
                    include "pages/$page.php";
                } else {
                    echo "<p>Page not found.</p>";
                }
                ?>






         </div>





     </div>




     <script>
         const logInBtn = document.querySelector('.login');

         function logInClick() {


             window.location.href = "./signIn.php";

         }

         function expandNav() {
             const nav = document.querySelector('.nav');
             nav.classList.toggle('expand');

             const navLinks = document.querySelector('.nav .mid');
             navLinks.classList.toggle('show');
         }
     </script>



 




     <script>
         let lastScrollTop = 0;
         const hideClass = 'hide';
         const scrolledClass = 'nav-scrolled';
         const content = document.querySelector('.container .nav');

         window.addEventListener('scroll', function() {
             let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

             // Add 'hide' class when scrolling down
             if (currentScroll > lastScrollTop) {
                 content.classList.add(hideClass);
             } else {
                 content.classList.remove(hideClass);
             }

             // Add 'nav-scrolled' class when not at the top of the page
             if (currentScroll > 0) {
                 content.classList.add(scrolledClass);
             } else {
                 content.classList.remove(scrolledClass);
             }

             lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
         });
     </script>




 </body>

 </html>