<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php'; 

// Cambia aquí tu email destinatario
$receiving_email_address = 'albertodh1200@gmail.com';

// Solo aceptar POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Recoger y limpiar datos
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Validar campos
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor, completa todos los campos correctamente.";
        exit;
    }

    // Crear objeto PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP de Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tu-email@gmail.com';       // <-- Cambia aquí por tu email Gmail
        $mail->Password   = 'tu-contraseña-de-aplicación'; // <-- Cambia aquí por tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom($email, $name);
        $mail->addAddress($receiving_email_address);

        // Contenido del email
        $mail->Subject = $subject;
        $mail->Body    = "Nombre: $name\nCorreo: $email\n\nMensaje:\n$message";

        // Enviar email
        $mail->send();
        echo "OK";

    } catch (Exception $e) {
        http_response_code(500);
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }

} else {
    http_response_code(403);
    echo "Acceso no permitido.";
}
