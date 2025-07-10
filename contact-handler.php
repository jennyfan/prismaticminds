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

// Basic spam detection
$spam_patterns = [
    'a@a.com', 'test@test.com', 'example@example.com', 
    'noreply@', 'no-reply@', 'admin@admin.com'
];

$is_spam = false;
foreach ($spam_patterns as $pattern) {
    if (stripos($email, $pattern) !== false) {
        $is_spam = true;
        break;
    }
}

// Check for obvious fake names
if (preg_match('/^[a-z][\s]*[a-z]$/i', trim($first_name . ' ' . $last_name)) || 
    strlen(trim($first_name . ' ' . $last_name)) < 3) {
    $is_spam = true;
}

if ($is_spam) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Please provide valid contact information.',
        'additional_info' => 'If you need immediate assistance, please call (410) 262-7372'
    ]);
    exit;
}

// Email configuration
$to = 'jenny@prismaticpractice.com';
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

// Create email body with better formatting
$email_body = "You have received a new contact form submission from your Prismatic Minds website.\n\n";
$email_body .= "CONTACT INFORMATION:\n";
$email_body .= "Name: " . $first_name . " " . $last_name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Phone: " . $phone . "\n\n";
$email_body .= "MESSAGE:\n";
$email_body .= $message . "\n\n";

if (!empty($found_me_text)) {
    $email_body .= "HOW THEY FOUND YOU:\n";
    $email_body .= $found_me_text . "\n\n";
}

$email_body .= "---\n";
$email_body .= "This message was sent from the contact form on prismaticpractice.com\n";
$email_body .= "Submitted: " . date('F j, Y \a\t g:i A T') . "\n";
$email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";

// Email headers - Use domain email for From, user email for Reply-To
$from_email = "chris@prismaticpractice.com";
$from_name = "Prismatic Minds Contact Form";

$headers = "From: " . $from_name . " <" . $from_email . ">\r\n";
$headers .= "Reply-To: " . $first_name . " " . $last_name . " <" . $email . ">\r\n";
$headers .= "Return-Path: " . $from_email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "X-Priority: 3\r\n";
$headers .= "X-MSMail-Priority: Normal\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "MIME-Version: 1.0\r\n";

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