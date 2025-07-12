<?php
header('Content-Type: application/json');
require 'db.php';
session_start(); // 🔥 ضيفها هنا

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طلب غير مسموح']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'يرجى إدخال البريد وكلمة المرور']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id']; // 🔥 خزّن الـ ID في السيشن
    echo json_encode(['success' => true, 'message' => 'تم تسجيل الدخول بنجاح 🎉']);
} else {
    echo json_encode(['success' => false, 'message' => 'البريد أو كلمة المرور غير صحيحة']);
}
?>
