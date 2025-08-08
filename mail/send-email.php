<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {


    function limpiar($dato) {
        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');

        return str_ireplace(array("\r", "\n", "%0a", "%0d"), '', $dato);
    }


    $name    = limpiar($_POST['name'] ?? '');
    $email   = limpiar($_POST['email'] ?? '');
    $phone   = limpiar($_POST['phone'] ?? '');
    $message = limpiar($_POST['message'] ?? '');


    if (empty($name) || empty($email) || empty($message)) {
        header("Location: /?status=error&reason=empty");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /?status=error&reason=email");
        exit;
    }

 
    if (strlen($message) > 2000) {
        header("Location: /?status=error&reason=toolong");
        exit;
    }

    $to      = "support@affilialeads.com";
    $subject = "Nuevo mensaje desde el formulario de Affilialeads";
    $body    = "Nombre: $name\nEmail: $email\nTeléfono: $phone\n\nMensaje:\n$message";

    $headers  = "From: support@affilialeads.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

 
    if (mail($to, $subject, $body, $headers)) {
        header("Location: /?status=ok");
    } else {
        header("Location: /?status=error&reason=sendfail");
    }
    exit;
}
?>

