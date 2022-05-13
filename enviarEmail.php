<?php
require_once 'email/PHPMailer.php';
require_once 'email/SMTP.php';
require_once 'email/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true); //o modo true habilita modo DEBUG

try{
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;//Autenticar via STP
    $mail->Username = 'costelinha.mas@gmail.com';
    $mail->Password = "gostoso11'";
    $mail->Port = 587;

    $mail->setFrom('Michael_Anderson_86@outlook.com');
    $mail->addAddress('costelinhatj23@hotmail.com');

    $mail->isHTML(true); //habilitar modo HTML
    $mail->Subject = ('Teste de Email via Gmail');
    $mail->Body = 'Chegou um email do <strong>Michael</strong>';
    $mail->AltBody = 'Chegou um email do Michael';

    if($mail->send()){
        echo "Email enviado com Seucesso";
    }else{
        echo 'Email nao Enviado';
    }

}catch(Exception $e){
    echo "Erro ao enviar mensagem: {$mail->ErrorInfo}";
}

?>