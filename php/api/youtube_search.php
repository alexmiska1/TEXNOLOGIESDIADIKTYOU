<?php
header('Content-Type: application/json');

if (!isset($_GET['q']) || empty(trim($_GET['q']))) {
    echo json_encode(['error' => 'Missing search query']);
    exit;
}

$query = urlencode(trim($_GET['q']));
$apiKey = '';AIzaSyDdfhZNQVWUqT90vYLUf_xySfIgAgkU0EI // Βάλε εδώ το δικό σου API key
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('962879946280-7vb2d3mr1lu4ds652semkb1ctp44ji6i.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/google-callback.php'); // change to your redirect
$client->addScope("email");
$client->addScope("profile");

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit;


$url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=10&q={$query}&key={$apiKey}";

$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(['error' => 'Failed to fetch from YouTube API']);
    exit;
}

echo $response;
