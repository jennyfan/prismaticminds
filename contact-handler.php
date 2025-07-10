<?php
// Contact form handler for Prismatic Minds
// Remove the debug lines below when deploying to production:
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Sanitize and validate form data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Get form data
$first_name = sanitize_input($_POST['first_name'] ?? '');
$last_name = sanitize_input($_POST['last_name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = sanitize_input($_POST['phone'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');
$referral_provider = sanitize_input($_POST['referral_provider'] ?? '');
$other_details = sanitize_input($_POST['other_details'] ?? '');

// Handle "How did you find me?" checkboxes
$found_me = [];
if (isset($_POST['found']) && is_array($_POST['found'])) {
    foreach ($_POST['found'] as $method) {
        $found_me[] = sanitize_input($method);
    }
}

// Validation
$errors = [];

if (empty($first_name)) {
    $errors[] = "First name is required";
}
if (empty($last_name)) {
    $errors[] = "Last name is required";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email address is required";
}
if (empty($phone)) {
    $errors[] = "Phone number is required";
}
if (empty($message)) {
    $errors[] = "Message is required";
}

// If there are errors, return them
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Please fix the following errors:',
        'errors' => $errors
    ]);
    exit;
}

// Email configuration
$to = 'fan.jenny@gmail.com';
$subject = 'New Contact Form Submission from ' . $first_name . ' ' . $last_name;

// Format the "How did you find me?" section
$found_me_text = '';
if (!empty($found_me)) {
    $found_me_formatted = [];
    foreach ($found_me as $method) {
        if ($method === 'Referral' && !empty($referral_provider)) {
            $found_me_formatted[] = $method . ' (' . $referral_provider . ')';
        } elseif ($method === 'Other' && !empty($other_details)) {
            $found_me_formatted[] = $method . ' (' . $other_details . ')';
        } else {
            $found_me_formatted[] = $method;
        }
    }
    $found_me_text = implode(', ', $found_me_formatted);
}

// Create email body
$email_body = "New Contact Form Submission\n";
$email_body .= "============================\n\n";
$email_body .= "Name: " . $first_name . " " . $last_name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Phone: " . $phone . "\n\n";
$email_body .= "Message:\n" . $message . "\n\n";

if (!empty($found_me_text)) {
    $email_body .= "How they found you: " . $found_me_text . "\n\n";
}

$email_body .= "---\n";
$email_body .= "This email was sent from the Prismatic Minds contact form.\n";
$email_body .= "Submitted on: " . date('Y-m-d H:i:s') . "\n";

// Email headers
$headers = "From: " . $email . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
if (mail($to, $subject, $email_body, $headers)) {
    // Success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Thank you! Your message has been sent successfully. I\'ll get back to you soon.',
        'additional_info' => 'If you need immediate assistance, please call (410) 262-7372'
    ]);
} else {
    // Error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Oops! Something went wrong.',
        'additional_info' => 'There was an error sending your message. Please try again or call me directly at (410) 262-7372'
    ]);
}
?> 