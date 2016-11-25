<?php
namespace App;

class Mail
{
    public static function send()
    {
        $mail = new \PHPMailer;
        //$mail->SMTPDebug = 3;
        $mail->isSMTP();
        $mail->Host = 'smtp.yandex.ru';
        $mail->SMTPAuth = true;
        $mail->Username = 'test@winfin.org';
        $mail->Password = 'abrakadabra';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('test@winfin.org', 'Сайт Проект');
        $mail->addAddress('vs@zorca.org', 'Администратор');
        $mail->isHTML(true);
        $mail->Subject = 'Новая регистрация на сайте Проект';
        $mail->Body    = 'На вашем сайте заргеистрировался новый пользователь';
        if (!$mail->send()) {
            App::set('error', 'Ошибка отправки почты: ' . $mail->ErrorInfo);
        }
    }
}
