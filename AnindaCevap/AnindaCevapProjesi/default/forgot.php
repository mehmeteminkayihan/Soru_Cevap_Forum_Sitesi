<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'assets/PHPMailer/src/Exception.php';
require 'assets/PHPMailer/src/PHPMailer.php';
require 'assets/PHPMailer/src/SMTP.php';

$mailer = new PHPMailer();

$mailer->isSMTP();
$mailer->SMTPKeepAlive = true;
$mailer->SMTPAuth = true ;
$mailer->SMTPSecure = 'tls';

$mailer->Port= 587;
$mailer->Host = "smtp.gmail.com";

$mailer->Username = "sifreunuttum14@gmail.com";
$mailer->Password = "Memo123!";

$mailer->setFrom("sifreunuttum14@gmail.com","AnındaCevap");
$mailer->addAddress("mehmetemin.kyhn@gmail.com","Kullanıcı");

$mailer->Subject = "Şifre sıfırlama kodunuz.";
$mailer->Body = "sa";


if($mailer->send()){
    echo 'mail gitti';
} else {
    echo 'gitmedi';
    echo 'Hata: ' . $mailer->ErrorInfo;
}
?>



