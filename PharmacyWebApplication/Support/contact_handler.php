<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST["name"] ?? ""));
    $email = filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL);
    $subject = htmlspecialchars(trim($_POST["subject"] ?? "General Question"));
    $message = htmlspecialchars(trim($_POST["message"] ?? ""));

    if (!$name || !$email || !$message) {
        http_response_code(400);
        echo "Please fill in all required fields.";
        exit;
    }

    $to = "support@medicare.com"; // Replace with your real email
    $headers = "From: $name <$email>\r\nReply-To: $email\r\n";
    $body = "Name: $name\nEmail: $email\nSubject: $subject\n\n$message";

    if (mail($to, "Support Request: $subject", $body, $headers)) {
        echo "Thank you! Your message has been sent.";
    } else {
        http_response_code(500);
        echo "Error sending message. Please try again later.";
    }
}
?>
