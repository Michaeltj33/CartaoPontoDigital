<?php
$to      = 'Michael_Anderson_86@outlook.com';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: costelinhatj23@hotmail.com' . "\r\n" .
    'Reply-To: costelinhatj23@hotmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();


ini_set("SMTP", "email-smtp.sa-east-1.amazonaws.com");
//ini_set("sendmail_from", "costelinhatj23@hotmail.com");
ini_set("smtp_port", "2587");

$teste = mail($to, $subject, $message, $headers);

echo "To:" . $to;
echo "<br>To:" . $subject;
echo "<br>To:" . $message;
echo "<br>To:" . $headers;


echo "<pre>";
print_r($teste);
echo "</pre><br>";

var_dump($teste);
if($teste){
    echo "<br>Enviado com Sucesso";
}else{
    echo "<br>Não foi possível enviar e-mail";
}


?>