<?php
require_once '../vendor/autoload.php';
require_once '../db_config.php';

// init configuration 
$clientID = '71002881248-l6021c7r8m367v2ste3rghg4kskva1i0.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/bb/Fullstack/google_oauth.php';
  
// create Client Request to access Google API 
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
 
// authenticate code from Google OAuth Flow 
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);
  
  // get profile info 
  $google_oauth = new Google\Service\Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

  //Connect to database
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }   
  $sql = "INSERT INTO Cust (Email, CustName) VALUES ('$email', '$name')";
  if (mysqli_query($conn, $sql)) {
    echo "ข้อมูลถูกบันทึกลงในฐานข้อมูลเรียบร้อยแล้ว";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  mysqli_close($conn);
  header('Location: ../index.php');

} else {
  echo "<a href='".$client->createAuthUrl()."'>Google Login</a>";
}
?>
