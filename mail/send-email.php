<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $phone   = htmlspecialchars($_POST['phone']);
    $message = htmlspecialchars($_POST['message']);

    $to      = "info@affilialeads.com";
    $subject = "Nuevo mensaje desde el formulario de Affilialeads";
    $body    = "Nombre: $name\nEmail: $email\nTeléfono: $phone\n\nMensaje:\n$message";

    $headers  = "From: info@affilialeads.com\r\n"; // Desde tu dominio
    $headers .= "Reply-To: $email\r\n";           // Para poder responder al usuario
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "Mensaje enviado correctamente.";
    } else {
        echo "Error al enviar el mensaje.";
    }
}
?>
