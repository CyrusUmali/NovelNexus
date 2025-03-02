<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Login</title>
</head>
<body>

    <button id="fb-login-btn">Login with Facebook</button>

    <!-- Facebook SDK -->
    <script>
      // Load the Facebook SDK
      window.fbAsyncInit = function() {
          FB.init({
              appId      : 'YOUR_APP_ID',  // Replace with your Facebook App ID
              cookie     : true,
              xfbml      : true,
              version    : 'v12.0'         // You can use a newer version if needed
          });

          // Check the login status when the page loads
          FB.getLoginStatus(function(response) {
              if (response.status === 'connected') {
                  console.log('User logged in');
                  // You can handle a successful login here
              }
          });
      };

      // Load the SDK asynchronously
      (function(d, s, id){
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "https://connect.facebook.net/en_US/sdk.js";
          fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>

    <script>
        // Function to handle the login
        document.getElementById('fb-login-btn').onclick = function() {
            FB.login(function(response) {
                if (response.authResponse) {
                    console.log('Login successful', response.authResponse);
                    // Handle login success here, e.g., send data to your server
                    // You can fetch the user info using FB.api()
                    FB.api('/me', { fields: 'id,name,email' }, function(response) {
                        console.log('Good to go! User:', response);
                        // You can now display user info or use it to create a session
                    });
                } else {
                    console.log('User cancelled login or did not fully authorize');
                }
            }, { scope: 'email' }); // Request email permission
        };
    </script>

</body>
</html>
