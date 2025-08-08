<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $recaptcha_secret = "6LeH0J4rAAAAAG7POiG6vn_FbXhaLnDJlYevFRLp";


    $recaptcha_token = $_POST['g-recaptcha-response'] ?? '';

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_token);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"] || $responseKeys["score"] < 0.5) {
        header("Location: /?status=error&reason=robot");
        exit;
    }

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

  
    $to      = "info@affilialeads.com";
    $subject = "Nuevo mensaje desde el formulario de Affilialeads";
    $body    = "Nombre: $name\nEmail: $email\nTeléfono: $phone\n\nMensaje:\n$message";

    $headers  = "From: info@affilialeads.com\r\n";
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


