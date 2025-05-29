<?php
session_start();
require_once 'includes/db.php';

// Get all public playlists
$stmt = $db->prepare("SELECT * FROM playlists WHERE is_public = 1");
$stmt->execute();
$playlists = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $db->prepare("SELECT * FROM videos WHERE playlist_id = ?");
$data = [];

foreach ($playlists as $pl) {
    $stmt2->execute([$pl['id']]);
    $videos = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $data[] = [
        'playlist' => [
            'id' => $pl['id'],
            'title' => $pl['title'],
            'videos' => array_map(function($v) {
                return [
                    'youtube_id' => $v['youtube_id'],
                    'title' => $v['title']
                ];
            }, $videos)
        ]
    ];
}

// Output YAML format
header('Content-Type: text/plain');
foreach ($data as $item) {
    echo "- playlist:\n";
    echo "    id: " . $item['playlist']['id'] . "\n";
    echo "    title: \"" . addslashes($item['playlist']['title']) . "\"\n";
    echo "    videos:\n";
    foreach ($item['playlist']['videos'] as $v) {
        echo "      - youtube_id: \"" . addslashes($v['youtube_id']) . "\"\n";
        echo "        title: \"" . addslashes($v['title']) . "\"\n";
    }
}
exit;
