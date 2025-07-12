<?php
header('Content-Type: application/json');
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'طلب غير مسموح']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!$name || !$email || !$phone || !$password) {
    echo json_encode(['status' => 'error', 'message' => 'يرجى ملء جميع الحقول']);
    exit;
}

// تحقق هل البريد موجود مسبقًا
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'البريد مستخدم مسبقًا']);
    exit;
}

// إدخال المستخدم
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())");
if ($stmt->execute([$name, $email, $phone, $hashed])) {
    echo json_encode(['status' => 'success', 'message' => 'تم إنشاء الحساب بنجاح 🎉']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'فشل في إنشاء الحساب']);
}
?>
