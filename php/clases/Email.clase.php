<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{
    protected static $mail;

    protected static $template = [
        "VALIDATE_EMAIL" => PATH_TEMPLATE_EMAIL."validateEmail.html",
        "FOOTER" => PATH_TEMPLATE_EMAIL."footer.html"
    ];

    static protected function configPhpMailer()
    {
        //Crear una instancia de PHPMailer
        self::$mail = new PHPMailer(true);
        //Definir que vamos a usar SMTP
        self::$mail->IsSMTP();
        //Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
        // 0 = off (producción)
        // 1 = client messages
        // 2 = client and server messages
        self::$mail->SMTPDebug  = 2;
        //Ahora definimos gmail como servidor que aloja nuestro SMTP
        self::$mail->Host       = 'smtp.gmail.com';
        //El puerto será el 587 ya que usamos encriptación TLS
        self::$mail->Port       = 587;
        //Definmos la seguridad como TLS
        self::$mail->SMTPSecure = 'tls';
        //Tenemos que usar gmail autenticados, así que esto a TRUE
        self::$mail->SMTPAuth   = true;
        //Definimos la cuenta que vamos a usar. Dirección completa de la misma
        self::$mail->Username   = USER_EMAIL;
        //Introducimos nuestra contraseña de gmail
        self::$mail->Password   = PASSWORD_EMAIL;
        //Definimos el remitente (dirección y, opcionalmente, nombre)
        self::$mail->SetFrom(USER_EMAIL, USER_NAME_EMAIL);
        //Esta línea es por si queréis enviar copia a alguien (dirección y, opcionalmente, nombre)
        //$mail->AddReplyTo('replyto@correoquesea.com', 'El de la réplica');
    }

    static public function send($to, $asunto, $Body, $keyTemplate = "", $dataTemplate=[], $attachedFile=[])
    {

        try {
            self::configPhpMailer();
            //Y, ahora sí, definimos el destinatario (dirección y, opcionalmente, nombre)
            foreach ($to as $value) {
                self::$mail->AddAddress($value["email"], $value["name"]);
            }
            
            //Definimos el tema del email
            self::$mail->Subject = $asunto;

            if ($attachedFile) {
                self::$mail->AddAttachment(
                    $attachedFile["path"],
                    $attachedFile["name"]
                );
            }

            if ($keyTemplate) {
                self::$mail->MsgHTML(
                    self::generateTemplate(
                        $keyTemplate,
                        $dataTemplate
                    )
                );
            }else {
                self::$mail->Body = $Body;
            }
            
            //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
            //$mail->MsgHTML(file_get_conte nts('correomaquetado.html'), dirname(ruta_al_archivo));
            //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
            //self::$mail->AltBody = 'This is a plain-text message body';
            //Enviamos el correo
            if (!self::$mail->Send()) {
                echo "Error: " . self::$mail->ErrorInfo;
            } else {
                echo "Enviado!";
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: " . self::$mail->ErrorInfo;
        }
    }

    static protected function generateTemplate($keyTemplate, $data = [])
    {

        $message = file_get_contents(self::$template[$keyTemplate]);

        if ($data) {
            foreach ($data as $key => $value) {
                $message = str_replace('%%' . $key . '%%', $value, $message);
            }
        }

        $message .= file_get_contents(self::$template["FOOTER"]);

        return $message;
    }

    
}
