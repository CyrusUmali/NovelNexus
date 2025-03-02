<div class="users-section">

 <h2>Settings</h2>
    <div class="settings-nav">
        <?php
        // Set active page for highlighting
        $activePage = isset($_GET['page']) ? $_GET['page'] : 'settings';
        ?>

        <a href="?page=settings" class="row <?php echo $activePage == 'settings' ? 'active' : ''; ?>">
            <div class="left">
                <!-- <i class='bx bx-user'></i> -->
                <span class="underline-hover-animation">User Info</span>
            </div>
            <!-- <i class='bx bx-chevron-right'></i> -->
        </a>

        <?php if ($userPasswordNotNull): ?>
            <a href="?page=password" class="row <?php echo $activePage == 'password' ? 'active' : ''; ?>">
                <div class="left">
                    <!-- <i class='bx bx-lock-open-alt'></i> -->
                    <span class="underline-hover-animation">Password & Security</span>
                </div>
                <!-- <i class='bx bx-chevron-right'></i> -->
            </a>
        <?php endif; ?>

        <a href="?page=notifications" class="row <?php echo $activePage == 'notifications' ? 'active' : ''; ?>">
            <div class="left">
                <!-- <i class='bx bx-bell'></i> -->
                <span class="underline-hover-animation">Notifications</span>
            </div>
            <!-- <i class='bx bx-chevron-right'></i> -->
        </a>

        <a href="?page=subscription" class="row <?php echo $activePage == 'subscription' ? 'active' : ''; ?>">
            <div class="left">
                <!-- <i class='bx bx-card'></i> -->
                <span class="underline-hover-animation">Subscription</span>
            </div>
            <!-- <i class='bx bx-chevron-right'></i> -->
        </a>
    </div>


    <div class="user-profile" id="userProfilePage">

        <div class="head">

            <div class="left">

                <div class="left-1">

                    <img src="" alt="" id="userPhoto">

                </div>

                <div class="left-2">

                    <b>Upload a New Photo</b> <br>
                    <label for="">Profile-pic.jpg</label>

                </div>



            </div>

            <button type="button" onclick="updateUserPhoto()" id="updateUserButton">Update</button>

        </div>

        <form action="" class="user-profile-form">
            <b onclick="uploadImageToCloudinary()">Change User Information Here</b>

            <div class="row">
                <div>
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" >
                </div>

                <div>
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name"  >
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="phone-number">Phone Number</label>
                    <input type="text" id="phone-number" >
                </div>

                <div>
                    <label for="email">Email</label>
                    <input type="text" id="email"  >
                </div>
            </div>

            <button onclick="updateUserInfo()" type="button">Update Information</button>
        </form>



    </div>

</div>