<?php
require_once "../vendor/autoload.php";
include "../db_config.php";
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$google_oauth_client_id = $_ENV['GOOGLE_CLIENT_ID'];
$google_oauth_client_secret = $_ENV['GOOGLE_SECRET_ID'];
$google_oauth_redirect_uri = $_ENV['GOOGLE_REDIRECT'];
$google_oauth_version = 'v3';

use Firebase\JWT\JWT;

$google_oauth_version = 'v3';

if (isset($_GET['code']) && !empty($_GET['code'])) {
    $params = [
        'code' => $_GET['code'],
        'client_id' => $google_oauth_client_id,
        'client_secret' => $google_oauth_client_secret,
        'redirect_uri' => $google_oauth_redirect_uri,
        'grant_type' => 'authorization_code'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);
    if (isset($response['access_token']) && !empty($response['access_token'])) {
        // Execute cURL request to retrieve the user info associated with the Google account
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($response, true);
        // Make sure the profile data exists
        if (isset($profile['email'])) {
            $CustNo = uniqid('cust_');
            $Email = mysqli_real_escape_string($conn, $profile['email']);
            $query = "SELECT * FROM Cust WHERE Email = '$Email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                $CustName = mysqli_real_escape_string($conn, $profile['name']);
                $Username = mysqli_real_escape_string($conn, $profile['name']);

                $Role = "google_user";
                $query = "INSERT INTO Cust (CustNo, Role, Email, Username, CustName) VALUES ('$CustNo', '$Role', '$Email', '$Username', '$CustName')";
                if (mysqli_query($conn, $query)) {
                    echo "เพิ่มข้อมูลลูกค้าใหม่เข้าสู่ระบบสำเร็จ";
                } else {
                    echo "มีข้อผิดพลาดในการเพิ่มข้อมูลลูกค้าใหม่: " . mysqli_error($conn);
                }
            }
            $row = mysqli_fetch_assoc($result);

            mysqli_close($conn);

            $google_name_parts = [];
            $google_name_parts[] = isset($profile['given_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['given_name']) : '';
            $google_name_parts[] = isset($profile['family_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['family_name']) : '';

            // Generate JWT token
            $payload = [
                'iat' => time(),
                'exp' => strtotime('+1 hour'),
                'data' => [
                    'UserId' => $row['CustNo'],
                    'Role' => $row['Role'],
                    'Email' => $profile['email'],
                    'Username' => $profile['name'],
                    'CustName' => $row['CustName'],
                ]
            ];
            $secret_key = $_ENV['SECRETKEY'];
            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            // Set JWT token in cookie
            setcookie("token", $jwt, time() + 3600, "/", "", true, true);

            header('Location: ../index.php');
            exit;
        } else {
            exit('Could not retrieve profile information! Please try again later!');
        }
    } else {
        exit('Invalid access token! Please try again later!');
    }
} else {
    // Define params and redirect to Google Authentication page
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}
?>
