<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header("Location: users.php");
    exit;
}
   $userId = $_POST['id'];

$sql = "DELETE FROM users WHERE id = $userId";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "User deleted successfully.";
} else {
    $_SESSION['error'] = "Error deleting user: " . $conn->error;
}

header("Location: users.php");
exit;
?>
