<?php
session_start();

// Dummy payment processing - in a real app, integrate with payment gateway
// For now, just redirect to confirmation

header("Location: confirmation.php");
exit();
?>