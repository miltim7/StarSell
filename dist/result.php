<?php

require __DIR__ . '/vendor/autoload.php';

$data = file_get_contents('php://input');
$data = json_decode($data, true);

$sum = $data['amount'] ?? '';

$message = "Сумма: $sum руб.\n";

$custom_fields = isset($data['custom_fields']) ? json_decode($data['custom_fields'], true) : null;

if(isset($custom_fields['stars_quantity']))
{
	$stars_quantity = $custom_fields['stars_quantity'] ?? '';

	$message .= "Количество звёзд: $stars_quantity шт.\n";
}

if(isset($custom_fields['telegram_account_nickname']))
{
	$telegram_account_nickname = $custom_fields['telegram_account_nickname'] ?? '';

	$message .= "Telegram Account: $telegram_account_nickname";
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'mail.hosting.reg.ru';
$mail->SMTPAuth = true;
$mail->Username = 'sender@starsellpro.com';
$mail->Password = 'zU6#hA2kRlY1#yC';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->CharSet = 'UTF-8';

$mail->setFrom('sender@starsellpro.com', 'Starsellpro');
$mail->addAddress('starstelegram110@mail.ru', 'No');

$mail->Subject = 'Отправлено через Starsellpro (SMTP)';
$mail->Body = $message;
$mail->AltBody = $message;

$mail->send();

# mail('starstelegram110@gmail.com', 'Новая оплата с сайта starsellpro.com', $message);

file_put_contents('result.log', json_encode($data, true) . "\n", FILE_APPEND);
