<?php
require_once __DIR__ . '/../config/config.php';

function sendConfirmation($toEmail, $toName, $bodyHtml){
    $fullHtml = '
        <html>
            <body style="font-family:Arial,sans-serif">
                <h2 style="color:#0d6efd;margin-top:0">Confirmación de tu Reserva EasyFly</h2>'
                .$bodyHtml.'
                <p style="margin-top:2rem">
                    <b>
                        <i>
                            La información de tu pase de abordar, que incluye el número de 
                            la puerta de embarque y los detalles finales de embarque serán 
                            enviados a este correo lo más pronto posible (aprox. 24 horas) 
                            antes de tu vuelo.
                        </i>
                    </b>
                </p>
                <p style="color:#0021F3;margin-top:2rem">
                    <b>¡Gracias por volar con EasyFly! &#128513;</b>
                </p>
            </body>
        </html>
    ';

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
