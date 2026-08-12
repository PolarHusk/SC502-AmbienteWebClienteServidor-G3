<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../PHPMailer-master/src/SMTP.php';

class Mailer {

    public static function enviarBienvenida($correo, $nombre) {

        $mail = new PHPMailer(true);

        try {

            
            $mail->SMTPDebug = 0;

       
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

           
            $mail->isSMTP();

           
            $mail->Host = 'smtp.gmail.com';

        
            $mail->SMTPAuth = true;

           
            $mail->Username = 'sidegeek.tienda@gmail.com';

           
            $mail->Password = 'gcgx xqhj lfpa bjxf';

            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

           
            $mail->Port = 587;

            
            $mail->CharSet = 'UTF-8';


      

            $mail->setFrom(
                'sidegeek.tienda@gmail.com',
                'SideGeek'
            );


       

            $mail->addAddress(
                $correo,
                $nombre
            );


    

            $mail->isHTML(true);

            $mail->Subject = 'Bienvenido a SideGeek';

            $nombreSeguro = htmlspecialchars(
                $nombre,
                ENT_QUOTES,
                'UTF-8'
            );

            $mail->Body = "
                <div style='font-family: Arial, sans-serif;'>

                    <h2>
                        ¡Bienvenido a SideGeek!
                    </h2>

                    <p>
                        Hola <strong>{$nombreSeguro}</strong>,
                    </p>

                    <p>
                        Tu cuenta se ha creado correctamente.
                    </p>

                    <p>
                        Ya puedes iniciar sesión y realizar tus compras.
                    </p>

                    <p>
                        Gracias por registrarte en SideGeek.
                    </p>

                    <p>
                        <strong>Equipo SideGeek</strong>
                    </p>

                </div>
            ";

          
            $mail->AltBody =
                "Hola {$nombre}. "
                . "Tu cuenta en SideGeek se ha creado correctamente. "
                . "Ya puedes iniciar sesión y realizar tus compras.";




            $mail->send();

            return true;


        } catch (Exception $e) {
            return false;
        }
    }
}