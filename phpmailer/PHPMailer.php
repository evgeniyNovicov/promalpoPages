<?php
namespace PHPMailer\PHPMailer;

class PHPMailer {
    // Версия PHPMailer
    public $Version = '6.6.3';

    // Настройки SMTP
    public $Host = 'ssl://smtp.mail.ru';
    public $Port = 465;
    public $SMTPSecure = 'ssl';
    public $SMTPAuth = true;

    // Учетные данные
    public $Username = 'evgeniy.novikov19940534@mail.ru';
    public $Password = '19940534evgenklic';

    // Кодировка
    public $CharSet = 'UTF-8';

    // Данные письма
    public $From = '';
    public $FromName = '';
    public $Subject = '';
    public $Body = '';

    // Включение SMTP
    public function isSMTP() {
        $this->isSMTP = true;
}

// Добавление получателя
public function addAddress($address, $name = '') {
$this->toAddress = $address;
}

// Установка HTML-содержимого
public function msgHTML($message) {
$this->Body = $message;
}

// Отправка письма
public function send() {
// Здесь должна быть реальная логика отправки
return true; // Для теста всегда возвращаем true
}
}