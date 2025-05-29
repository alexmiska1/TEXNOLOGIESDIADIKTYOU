<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('962879946280-7vb2d3mr1lu4ds652semkb1ctp44ji6i.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:8080/google-callback.php');
$client->addScope('email');
$client->addScope('profile');

if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
} else {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    $_SESSION['user_id'] = $userInfo->id;
    $_SESSION['user_email'] = $userInfo->email;
    $_SESSION['user_name'] = $userInfo->name;


    header('Location: dashboard.php');
    exit();
}
?>