<?php
// comment.php

// Izinkan CORS (supaya bisa dipanggil dari frontend)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Pastikan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Ambil data dari form-urlencoded
$guest_name     = $_POST['guest_name']     ?? null;
$message        = $_POST['message']        ?? null;
$will_attend    = $_POST['will_attend']    ?? null;
$invitation_id  = $_POST['invitation_id']  ?? null;
$comment_press  = $_POST['comment_press']  ?? null;

// Validasi sederhana
if (!$guest_name || !$message || !$invitation_id) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

// Contoh: simpan ke file (tanpa database dulu)
$data = [
    'guest_name'    => $guest_name,
    'message'       => $message,
    'will_attend'   => filter_var($will_attend, FILTER_VALIDATE_BOOLEAN),
    'invitation_id' => $invitation_id,
    'comment_press' => filter_var($comment_press, FILTER_VALIDATE_BOOLEAN),
    'ip'            => $_SERVER['REMOTE_ADDR'],
    'created_at'    => date('Y-m-d H:i:s')
];

$file = __DIR__ . '/comments.json';

// Baca data lama
$comments = [];
if (file_exists($file)) {
    $comments = json_decode(file_get_contents($file), true) ?? [];
}

// Tambahkan data baru
$comments[] = $data;

// Simpan ulang
file_put_contents($file, json_encode($comments, JSON_PRETTY_PRINT));

// Response sukses
return  json_encode([
    'status' => 'success',
    'message' => 'Komentar berhasil diterima',
    'data' => $data
]);
