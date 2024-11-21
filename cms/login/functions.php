<?php 
//pagina voor secure functies

//functie voor het (secure) starten van een sessie
function sec_session_start() {
        $session_name = 'sec_session_id'; // Set a custom session name
        $secure = false; // Set to true if using https.
        $httponly = true; // This stops javascript being able to access the session id. 
 
        ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
        $cookieParams = session_get_cookie_params(); // Gets current cookies params.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Sets the session name to the one set above.
        session_start(); // Start the php session
        session_regenerate_id(true); // regenerated the session, delete the old one.     
}

function disable_login_checks() {
   if(isset($_SESSION['showAUTH'])) { unset($_SESSION['showAUTH']); }
   if(isset($_SESSION['authenticated'])) { unset($_SESSION['authenticated']); }
}

/*Secure Login Function:  */
function login($email, $password, $mysqli) {
   $user_id = '';
   $username = '';
   $db_password = '';
   $salt = '';
   
   // Using prepared Statements means that SQL injection is not possible. 
   if ($stmt = $mysqli->prepare("SELECT id, username, password, salt FROM siteworkcms_gebruikers WHERE email = ? LIMIT 1")) { 
      $stmt->bind_param('s', $email); // Bind "$email" to parameter.
      $stmt->execute(); // Execute the prepared query.
      $stmt->store_result();
      $stmt->bind_result($user_id, $username, $db_password, $salt); // get variables from result.
      $stmt->fetch();
      $password = hash('sha512', $password.$salt); // hash the password with the unique salt.
 
      if($stmt->num_rows == 1) { // If the user exists
         // We check if the account is locked from too many login attempts
         if(checkbrute($user_id, $mysqli) == true) { 
            // Account is locked
            // Send an email to user saying their account is locked
            return false;
         } else {
            if($db_password == $password) { // Check if the password in the database matches the password the user submitted. 
               // Password is correct!
               $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
               $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.

               $user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
               $_SESSION['id'] = $user_id; 
               $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
               $_SESSION['username'] = $username;
               $_SESSION['login_string'] = hash('sha512', $password.$ip_address.$user_browser);
               $_SESSION['showAUTH'] = 0;
               $_SESSION['authenticated'] = 0;

                  // Login successful.
               $now = time();
               $ip_address = $_SERVER['REMOTE_ADDR'];
               $mysqli->query("INSERT INTO sitework_login_log (user_id, time, ip) VALUES ('$user_id', '$now', '$ip_address')");
                  
               return true;    
            } else {
               // Password is not correct
               // We record this attempt in the database
               $now = time();
               $ip_address = $_SERVER['REMOTE_ADDR'];
               $mysqli->query("INSERT INTO sitework_login_fouten (user_id, time, ip) VALUES ('$user_id', '$now', '$ip_address')");
               return false;
            }
         }
      } else {
         // No user exists. 
         return false;
      }
   }
}
/*Einde Secure Login Function:  */

// verificatie_check
// =================
function verificatie_check($user_id, $mysqli) {
   $verificatie = "";
   $secret_key = "";

   $stmt = $mysqli->prepare("SELECT twee_stap_verificatie, verificatie_secretkey FROM siteworkcms_gebruikers WHERE id = ? LIMIT 1");
   $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
   $stmt->execute(); // Execute the prepared query.
   $stmt->store_result();
   $stmt->bind_result($verificatie, $secret_key); // get variables from result.
   $stmt->fetch();

   $Auth_gegevens = [];

   $Auth_gegevens['verificatie_actief'] = $verificatie;
   $Auth_gegevens['verificatie_secretkey'] = $secret_key;

   return $Auth_gegevens;
}

/*
Brute Force Function.
Brute force attacks are when a hacker will try 1000s of different passwords on an account, 
either randomly generated passwords or from a dictionary. 
In ourscript if a user account has a failed login more than 5 times their account is locked.
*/

function checkbrute($user_id, $mysqli) {
   // Get timestamp of current time
   $now = time();
   // All login fouten are counted from the past 2 hours. 
   $valid_attempts = $now - (2 * 60 * 60); 
 
   if ($stmt = $mysqli->prepare("SELECT time FROM login_fouten WHERE user_id = ? AND time > '$valid_attempts'")) { 
      $stmt->bind_param('i', $user_id); 
      // Execute the prepared query.
      $stmt->execute();
      $stmt->store_result();
      // If there has been more than 5 failed logins
      if($stmt->num_rows > 5) {
         return true;
      } else {
         return false;
      }
   }
}


//checken of men is ingelogd
/* Check logged in status.
We do this by checking the the "user_id" and the "login_string" SESSION variables. 
The "login_string" SESSION variable has the users IP address and Browser Info hashed together with the password. 
We use the IP address and Browser Info because it is very unlikely that the user will change their IP address or browser mid-session. 
Doing this helps prevent session hijacking.
*/

//chatCPT v2 login_check

function login_check($mysqli) {
   // Start the session if not already started
   if (session_status() === PHP_SESSION_NONE) {
      session_start();
   }
   $password = "";
   // Check if all session variables are set
   if(isset($_SESSION['id'], $_SESSION['username'], $_SESSION['login_string'])) {
     $user_id = $_SESSION['id'];
     $login_string = $_SESSION['login_string'];
     $username = $_SESSION['username'];
     $ip_address = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user. 
     $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
 
      if ($stmt = $mysqli->prepare("SELECT password FROM siteworkcms_gebruikers WHERE id = ? LIMIT 1")) { 
         $stmt->bind_param('i', $user_id); // Bind "$user_id" to parameter.
         $stmt->execute(); // Execute the prepared query.
         $stmt->store_result();
 
         if($stmt->num_rows == 1) { // If the user exists
            $stmt->bind_result($password); // get variables from result.
            $stmt->fetch();
            $login_check = hash('sha512', $password . $ip_address . $user_browser);

            if (hash_equals($login_check, $login_string)) {      
               $session_duration = 3600; // 1 hour in seconds
               
               if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] <= $session_duration)) {
                  $_SESSION['last_activity'] = time(); // Update last activity time
                  
                  // Logged in
                  return true;
               } else {
                  // Session timed out
                  return false;
               }

               // Logged In!!!!
               return true;
            } else {
               // Log error for debugging (optional)
               error_log('Login check failed: Security issue with session variables.');
               // Not logged in (invalid session or security issue)
               return false;
            }
         } else {
            // Log error for debugging (optional)
            error_log('Login check failed: User not found.');
            // Not logged in (user not found)
            return false;
         }
      } else {
         // Log error for debugging (optional)
         error_log('Login check failed: Database query failed.');
         // Not logged in (database query failed)
         return false;
      }
   } else {
       // Log error for debugging (optional)
       error_log('Login check failed: Session variables not set.');
       // Not logged in (session variables not set)
       return false;
   }
}
//einde inlogcheck

function login_check_v2(){
   if (!isset($_SESSION['loggedin'])) {
      header('Location: ./index.php');
      exit;
   }
}

function get_user() {
   return $_SESSION;
}


?>