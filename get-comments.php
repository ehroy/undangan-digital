<?php
// get-comments.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Ambil parameter GET
$post_id  = isset($_GET['post_id']) ? (int)$_GET['post_id'] : null;
$offset   = isset($_GET['comments']) ? (int)$_GET['comments'] : 0;
$limit    = isset($_GET['get']) ? (int)$_GET['get'] : 10;
$order    = strtoupper($_GET['order'] ?? 'DESC');

// Validasi
if (!$post_id) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'post_id wajib diisi'
    ]);
    exit;
}

$file = __DIR__ . '/comments.json';

if (!file_exists($file)) {
    echo json_encode([
        'status' => 'success',
        'total' => 12,
        'data' => []
    ]);
    exit;
}

// Ambil semua data
$comments = json_decode(file_get_contents($file), true) ?? [];

// Filter berdasarkan post_id
$filtered = array_values(array_filter($comments, function ($c) use ($post_id) {
    return isset($c['post_id']) && $c['post_id'] === $post_id;
}));

// Sorting
usort($filtered, function ($a, $b) use ($order) {
    if ($order === 'ASC') {
        return $a['timestamp'] <=> $b['timestamp'];
    }
    return $b['timestamp'] <=> $a['timestamp'];
});

// Total komentar
$total = 12;

// Pagination
$data = array_slice($filtered, $offset, $limit);

// Response
echo json_encode([
    'status' => 'success',
    'post_id' => $post_id,
    'total' => $total,
    'offset' => $offset,
    'limit' => $limit,
    'order' => $order,
    'data' => $data
], JSON_PRETTY_PRINT);
