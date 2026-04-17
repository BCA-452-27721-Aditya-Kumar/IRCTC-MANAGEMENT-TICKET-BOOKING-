<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validation
    $errors = [];

    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
        $errors[] = "Full name must contain only letters and spaces.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (strpos($email, '@') === false) {
        $errors[] = "Email must contain @.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email format is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email already exists.";
        }
        $stmt->close();
    }

    // If no errors, insert user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $hashed_password);

        if ($stmt->execute()) {
            // Success, redirect to login
            header("Location: ../Frontend/index.html?signup=success");
            exit();
        } else {
            $errors[] = "Error creating account. Please try again.";
        }
        $stmt->close();
    }

    // If errors, redirect back with errors
    if (!empty($errors)) {
        $error_string = implode("&", array_map(function($e) { return "error[]=" . urlencode($e); }, $errors));
        header("Location: ../Frontend/Singup.html?$error_string");
        exit();
    }
} else {
    // Not a POST request
    header("Location: ../Frontend/Singup.html");
    exit();
}

$conn->close();
?>