<html>
  <head>
    <title>Example</title>
  </head>
  <body>
    <!-- Load the Facebook JavaScript SDK -->
    <div id="fb-root"></div>
    <script src="//connect.facebook.net/en_US/all.js"></script>
    
    <script type="text/javascript">
      
      // Initialize the Facebook JavaScript SDK
      FB.init({
	    appId: '284206068404926',
        xfbml: true,
        status: true,
        cookie: true,
      });
      
      // Check if the current user is logged in and has authorized the app
      FB.getLoginStatus(checkLoginStatus);
      
      // Login in the current user via Facebook and ask for email permission
      function authUser() {
        FB.login(checkLoginStatus, {scope:'email'});
      }
      
      // Check the result of the user status and display login button if necessary
      function checkLoginStatus(response) {
        if(response && response.status == 'connected') {
          alert('User is authorized' + response.email);
          
          // Hide the login button
          document.getElementById('loginButton').style.display = 'none';
          
          // Now Personalize the User Experience
          console.log('Access Token: ' + response.authResponse.accessToken);
        } else {
          alert('User is not authorized');
          
          // Display the login button
          document.getElementById('loginButton').style.display = 'block';
        }
      }
    </script>
    
    <input id="loginButton" type="button" value="Login!" onclick="authUser();" />
  </body>
</html>