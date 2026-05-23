<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendResetEmail($email, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'kahhooi0915@gmail.com';
        $mail->Password = 'cpge iops pkbi xyxn';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kahhooi0915@gmail.com', 'DysCover');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Your DysCover Password';
        $mail->Body = "
            <h2>Password Reset Request</h2>
            <p>Click the link below to reset your password:</p>
            <a href='$resetLink'>$resetLink</a>
            <p>This link will expire in 30 minutes.</p>
        ";

        return $mail->send();

    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
?>