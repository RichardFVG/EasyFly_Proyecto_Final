<?php
require_once __DIR__ . '/../config/config.php';

function sendConfirmation($toEmail, $toName, $bodyHtml){
    $fullHtml = '
        <html><body style="font-family:Arial,sans-serif">
        <h2 style="color:#0d6efd;margin-top:0">Confirmación de tu Reserva EasyFly</h2>'
        .$bodyHtml.
        '<p style="margin-top:2rem">¡Gracias por volar con EasyFly!</p></body></html>';

    $data = [
        'Messages' => [[
            'From' => ['Email' => 'confirmacionreservavuelo@proyectofinaleasyfly.fun',
                       'Name' => 'EasyFly'],
            'To'   => [[ 'Email' => $toEmail, 'Name' => $toName ]],
            'Subject' => 'Tu reserva en EasyFly',
            'HTMLPart' => $fullHtml
        ]]
    ];
    $ch = curl_init('https://api.mailjet.com/v3.1/send');
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, MAILJET_API_PUBLIC . ':' . MAILJET_API_PRIVATE);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);
}
?>
