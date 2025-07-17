<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feltatechvoc";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'Connection failed';
    exit;
}

// Check if email is provided
if (!isset($_POST['email']) || empty($_POST['email'])) {
    echo 'Email is required';
    exit;
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email format';
    exit;
}

try {
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo 'You are already subscribed';
        exit;
    }

    // Insert new subscriber
    $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
    $stmt->execute([$email]);
    
    echo 'Thank you for subscribing!';
} catch(PDOException $e) {
    echo 'Subscription failed';
}
?> 