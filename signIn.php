<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="base.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="./node_modules/axios/dist/axios.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
    <script src="https://apis.google.com/js/api.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>


    <div class="pop-up-bg hide   ">


    </div>

    <section class="ninth-section">



        <div class="container">
            <div class="forms-container">
                <div class="signin-signup">
                    <form action="#" class="sign-in-form">
                        <h2 class="title">Sign in</h2>
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Email" id="logInEmailInput" />
                        </div>
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Password" id="logInPassInput" />
                        </div>
                        <button type="submit" value="Login" class="btn solid" onclick="logInClick(event)"> Sign In</button>
                        <p class="social-text">Or Sign in with social platforms</p>
                        <div class="social-media">
                            <a href="#" onclick="fetchSuccessMsg()" class="social-icon">
                                <i class="fab fa-facebook-f"></i>
                            </a>

                            <a href="#" class="social-icon" onclick="googleSignIn()">
                                <i class="fab fa-google"></i>
                            </a>

                        </div>
                    </form>
                    <form action="#" class="sign-up-form">
                        <h2 class="title">Sign up</h2>
                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="First Name" id="signUpFN" />
                        </div>

                        <div class="input-field">
                            <i class="fas fa-user"></i>
                            <input type="text" placeholder="Last Name" id="signUpLN" />
                        </div>
                        <div class="input-field">
                            <i class="fas fa-envelope"></i>
                            <input type="email" placeholder="Email" id="signUpEmail" />
                        </div>
                        <div class="input-field">
                            <i class="fas fa-lock"></i>
                            <input type="password" placeholder="Password" id="signUpPass" />
                        </div>
                        <button type="submit" class="btn" onclick="signUpClick(event)"> Sign Up</button>
                        <p class="social-text">Or Sign up with social platforms</p>
                        <div class="social-media">
                            <a href="#" class="social-icon">
                                <i class="fab fa-facebook-f"></i>
                            </a>

                            <a href="#" class="social-icon" onclick="googleSignUp()">
                                <i class="fab fa-google"></i>
                            </a>

                        </div>
                    </form>
                </div>
            </div>

            <div class="panels-container">
                <div class="panel left-panel">

                    <i id="backIcon" onclick="backClick()" class="fas fa-arrow-left"></i>

                    <div class="content">
                        <h3>New here?</h3>
                        <p>
                            Join us today and discover everything we have to offer!
                        </p>
                        <button class="btn transparent" id="sign-up-btn">
                            Sign up
                        </button>
                    </div>
                    <!-- <img src="resources/log.svg" class="image" alt="" /> -->
                    <img src="resources/loginpict.png" class="image" alt="">




                </div>
                <div class="panel right-panel">
                    <div class="content">



                    <h3>One of us?</h3>
<p>
    Welcome back! Ready to dive in and explore more?
</p>

                        <button class="btn transparent" id="sign-in-btn">
                            Sign in
                        </button>
                    </div>
                    <!-- <img src="./resources/register.svg" class="image" alt="" /> -->
                </div>
            </div>
        </div>



    </section>




    <script>
        const popUpBg = document.querySelector('.pop-up-bg');
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".container");
        const backButton = document.querySelector(".fas.fa-arrow-left");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
            backButton.classList.add("sign-up-mode");

        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
            backButton.classList.remove("sign-up-mode");
        });

        function backClick() {
            window.location.href = "./base.php";
        }


        // Initialize the client
        window.onload = function() {
            google.accounts.id.initialize({
                client_id: "714210173346-cobkd1up9q5m0t1dt7i23vrvf2ng42or.apps.googleusercontent.com", // Replace with your actual Client ID
                callback: handleCredentialResponse, // Single callback function for both sign-up and sign-in
            });
        };

        // Function to trigger Google Sign-Up
        function googleSignUp() {
            google.accounts.id.prompt(); // Shows the Google login prompt for sign-up
        }

        // Function to trigger Google Sign-In
        function googleSignIn() {
            google.accounts.id.prompt(); // Shows the Google login prompt for sign-in
        }

        // Example function to check if the user is new
        function isUserNew(email, userData) {
            // Send a request to the backend to check if the user exists
            axios.post('./db/php/checkUser.php', {
                    email
                })
                .then(response => {
                    if (response.data.userExists) {
                        // User exists, perform sign-in
                        userData.google_signin = true;
                        axios.post('./db/php/googleLogin.php', userData)
                            .then(response => {
                                if (response.data.success) {
                                    console.log("User signed in successfully.");

                                    window.location.href = "./";

                                    // Optionally, redirect or perform other actions here
                                } else {
                                    console.error("Error signing in:", response.data.error);
                                    fetchErrorMsg("Error signing in");
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                fetchErrorMsg("Error signing in");
                            });
                    } else {

                        axios.post('./db/php/googleSignup.php', userData)
                            .then(response => {
                                if (response.data.success) {
                                    console.log("User registered successfully.");
                                    window.location.href = "./?page=pricing";
                                } else {
                                    console.error("Error registering user:", response.data.error);
                                    fetchErrorMsg("Error Registering User");
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                fetchErrorMsg("Error Registering User");
                            });
                    }
                })
                .catch(error => {
                    console.error("Error checking user existence:", error);
                    fetchErrorMsg("Error checking user existence");
                });
        }


        // Callback to handle the Google OAuth response (for both sign-up and sign-in)
        function handleCredentialResponse(response) {
            try {
                const user = jwt_decode(response.credential); // Decode the Google JWT response
                console.log("Google User Info:", user);

                let userData = {
                    email: user.email,
                    picture: user.picture, // Optional: Include the user's profile picture URL
                    first_name: user.given_name,
                    last_name: user.family_name,
                    google_signup: true // Set to true if the user signed up with Google

                };

                // Check if the user is new and handle accordingly
                isUserNew(user.email, userData);
            } catch (error) {
                console.error("Error decoding JWT or handling response:", error);
                fetchErrorMsg("Error processing Google sign-in/sign-up");
            }
        }





        function signUpClick(event) {
            event.preventDefault();

            var signUpFN = document.getElementById('signUpFN').value;
            var signUpLN = document.getElementById('signUpLN').value;
            var signUpEmail = document.getElementById('signUpEmail').value;
            var signUpPass = document.getElementById('signUpPass').value;




            axios.post('./db/php/signup.php', {
                    first_name: signUpFN,
                    last_name: signUpLN,
                    email: signUpEmail,
                    password: signUpPass
                })
                .then(response => {
                    if (response.data.success) {
                        // Display success message
                        // document.getElementById('result').textContent = "Signed Up Successfully";
                        // document.getElementById('result').style.color = '#28a745';
                        console.log("User registered successfully.");
                        window.location.href = "./?page=pricing";
                    } else {
                        // Display error message

                        console
                        // document.getElementById('result').textContent = response.data.error;
                        console.error("Error registering user:", response.data.error);
                        fetchErrorMsg("Error Registering User");
                    }
                })
                .catch(error => {
                    // Display error message
                    fetchErrorMsg("Error Registering User");
                    console.error("Error:", error);
                });
        }


        function logInClick(event) {
            // Assume authToken is the authentication token you want to store
            const authToken = "SignedIn";

            event.preventDefault();
            var logInEmailInput = document.getElementById('logInEmailInput').value;
            var logInPassInput = document.getElementById('logInPassInput').value;


            axios.post('./db/php/login.php', {
                    email: logInEmailInput,
                    password: logInPassInput,
                })
                .then(response => {
                    if (response.data.success) {
                        // User authenticated successfully
                        console.log("User authenticated successfully.");

                        window.location.href = './';
                    } else {
                        // Invalid email or password
                        console.error("Invalid email or password:", response.data.error);
                        fetchErrorMsg("Invalid email or password");
                    }
                })
                .catch(error => {
                    // Handle any errors that occurred during the request
                    console.error("Error:", error);
                    fetchErrorMsg("Invalid email or password");
                });
        }






        function fetchSuccessMsg(message) {
            console.log('clickckc');

            popUpBg.classList.remove('hide');

            // Fetch the PHP content
            fetch('./popup/successMessage.php') // Replace with the correct PHP file path
                .then(response => response.text())
                .then(data => {
                    popUpBg.innerHTML = data;

                    const SuccessPopUp = document.querySelector('.pop-up-success');
                    const successLabel = SuccessPopUp.querySelector('label'); // Select the label inside the success popup

                    // Use the passed message parameter to set the label text
                    successLabel.textContent = message;

                    SuccessPopUp.classList.remove('show'); // Ensure 'show' class is not there initially

                    setTimeout(() => {
                        // Now apply the 'show' class and trigger the transition
                        SuccessPopUp.classList.add('show');
                    }, 10); // Small delay to allow the browser to register the initial styles
                })
                .catch(error => console.error('Error loading PHP content:', error));
        }


        function fetchErrorMsg(message) {
            console.log('clickckc');

            popUpBg.classList.remove('hide');

            // Fetch the PHP content
            fetch('./popup/errorMessage.php') // Replace with the correct PHP file path
                .then(response => response.text())
                .then(data => {
                    popUpBg.innerHTML = data;

                    const ErrorPopUp = document.querySelector('.pop-up-error');
                    const errorLabel = ErrorPopUp.querySelector('label'); // Select the label inside the success popup

                    // Use the passed message parameter to set the label text
                    errorLabel.textContent = message;

                    ErrorPopUp.classList.remove('show'); // Ensure 'show' class is not there initially

                    setTimeout(() => {
                        // Now apply the 'show' class and trigger the transition
                        ErrorPopUp.classList.add('show');
                    }, 10); // Small delay to allow the browser to register the initial styles
                })
                .catch(error => console.error('Error loading PHP content:', error));
        }



        function closePopup() {



            popUpBg.classList.add('hide');


            // Remove the fetched content from the popup div
            popUpBg.innerHTML = '   ';


        }
    </script>

</body>

</html>