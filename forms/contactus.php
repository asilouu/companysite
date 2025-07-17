<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require 'db_connect.php'; // include the DB connection

    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo "All fields are required.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "Your message has been saved. Thank you!";
    } else {
        http_response_code(500);
        echo "Failed to save your message.";
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(403);
    echo "Invalid request.";
}
?>
