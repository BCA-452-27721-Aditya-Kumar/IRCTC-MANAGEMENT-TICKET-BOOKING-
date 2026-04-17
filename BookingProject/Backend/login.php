<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']); // Assuming username is email
    $password = $_POST['password'];

    // Validation
    $errors = [];

    if (empty($username) || !filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Check user credentials
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $fullname, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Login successful
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $fullname;
                $_SESSION['user_email'] = $username;

                // Redirect to dashboard or home
                header("Location: ../Frontend/book ticket.html"); // Assuming this is the main page
                exit();
            } else {
                $errors[] = "Invalid password.";
            }
        } else {
            $errors[] = "No account found with that email.";
        }
        $stmt->close();
    }

    // If errors, redirect back with errors
    if (!empty($errors)) {
        $error_string = implode("&", array_map(function($e) { return "error[]=" . urlencode($e); }, $errors));
        header("Location: ../Frontend/login.html?$error_string");
        exit();
    }
} else {
    // Not a POST request
    header("Location: ../Frontend/login.html");
    exit();
}

$conn->close();
?>