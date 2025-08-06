<?php
/**
 * contact-handler.php
 * 
 * A robust form handler that uses:
 * - Composer's autoload (for PHPMailer)
 * - Honeypot anti-spam
 * - reCAPTCHA v3 verification
 * - Basic validations & GDPR check
 * - PHPMailer (SMTP) to send mail
 */

// 1. Load Composer’s autoloader
require __DIR__ . '/vendor/autoload.php';

// 2. Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 3. Ensure this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /'); // Or display an error
    exit;
}

// 4. Honeypot check
$secretHoney = $_POST['secretHoney'] ?? '';
if (!empty($secretHoney)) {
    // If it's filled, we assume spam/bot submission
    header('Location: /thank-you.html');
    exit;
}

// 5. reCAPTCHA verification
// Replace with your actual secret key from Google's reCAPTCHA console
$recaptchaSecret = '6Le5QtkqAAAAAPf-kqCStWR3tFIEGZtzWRqMvKXf';
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (!empty($recaptchaSecret) && !empty($recaptchaResponse)) {
    $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents(
        $verifyUrl . '?secret=' . urlencode($recaptchaSecret)
        . '&response=' . urlencode($recaptchaResponse)
    );
    $responseKeys = json_decode($response, true);

    // Check if success is true and (for reCAPTCHA v3) if the score is acceptable
    if (
        empty($responseKeys['success']) ||
        $responseKeys['success'] !== true ||
        (isset($responseKeys['score']) && $responseKeys['score'] < 0.5)
    ) {
        // Could be spam or invalid token
        header('Location: /error.html?error=spam');
        exit;
    }
}

// 6. Retrieve & sanitize form data
$firstName   = trim((string)filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_SPECIAL_CHARS));
$lastName    = trim((string)filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_SPECIAL_CHARS));
$phone       = trim((string)filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS));
$email       = trim((string)filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$locations   = filter_input(INPUT_POST, 'location', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?: [];
$subject     = trim((string)filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS));
$message     = trim((string)filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));
$gdprConsent = $_POST['gdprConsent'] ?? '';

// 7. Validate required fields
$errors = [];

// Required fields
if (!$firstName) {
    $errors[] = 'First Name is required.';
}
if (!$lastName) {
    $errors[] = 'Last Name is required.';
}
if (!$phone) {
    $errors[] = 'Phone is required.';
}
if (empty($locations)) {
    $errors[] = 'Please select at least one location.';
}
if (!$subject) {
    $errors[] = 'Subject is required.';
}
if (!$message) {
    $errors[] = 'Message is required.';
}
if ($gdprConsent !== 'accepted') {
    $errors[] = 'Please accept the GDPR checkbox.';
}

// If Email is provided, check format
if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address.';
}

// 8. If any errors, handle them
if (!empty($errors)) {
    // Option A: redirect with error messages
    $errorString = urlencode(implode(' ', $errors));
    header("Location: /error.html?errors=$errorString");
    exit;
}

// 9. Build the email body
$fullName  = $firstName . ' ' . $lastName;
$locString = implode(', ', $locations);

$emailBody = <<<EOD
Name: $fullName
Phone: $phone
Email: $email
Locations: $locString
Subject: $subject

Message:
$message

GDPR Consent: $gdprConsent
EOD;

// 10. Send with PHPMailer
$mail = new PHPMailer(true);

try {
    // Server settings (SMTP)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // For example, smtp.gmail.com
    $mail->SMTPAuth   = true;
    $mail->Username   = 'contact@oscarespinoza.me'; // Your SMTP username
    $mail->Password   = 'bpsuczisllaokami';     // Or app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // or ENCRYPTION_SMTPS
    $mail->Port       = 587;                            // or 465 if using SSL

    // Recipients
    $mail->setFrom('contact@oscarespinoza.me', 'Oscar Contact Form');
    $mail->addAddress('contact@oscarespinoza.me');

    // Email content
    $mail->Subject = 'Portraits WS - New Contact Form: ' . $subject;
    $mail->Body    = $emailBody;

    // For HTML email (optional):
    // $mail->isHTML(true);
    // $mail->Body = '<p><strong>HTML version of email</strong></p>';

$mail->SMTPDebug = 3; // or 2 for less verbose
$mail->Debugoutput = 'error_log'; // Logs to your error log

    // Send it
    $mail->send();

    // Redirect or confirm success
    header('Location: /thank-you.html');
    exit;
} catch (Exception $e) {
    // If sending fails, log or handle error
    // $mail->ErrorInfo has the error details
    error_log('Mailer Error: ' . $mail->ErrorInfo);
    header('Location: /error.html?mail_error=' . urlencode($mail->ErrorInfo));
    exit;
}
