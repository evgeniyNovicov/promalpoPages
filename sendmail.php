<?php
// Настройки
$mailTo = "evgeniy.novikov19940534@mail.ru"; // Куда отправлять
$mailFrom = "gidroprofi@list.ru"; // От кого отправлять
$subject = "Новая заявка с сайта"; // Тема письма

// Получение данных из формы
$name = $_POST['Имя'];
$phone = $_POST['Телефон'];
$email = $_POST['Email'];
$service = $_POST['services'];
$city = $_POST['Город'];
$message = $_POST['Сообщение'];

// Формирование тела письма
$body = "
<h2>Новая заявка с сайта</h2>
<p><strong>Имя:</strong> $name</p>
<p><strong>Телефон:</strong> $phone</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Услуга:</strong> $service</p>
<p><strong>Город:</strong> $city</p>
<p><strong>Сообщение:</strong> $message</p>
";

// Заголовки
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: $mailFrom\r\n";

// Отправка письма
$mailSent = mail($mailTo, $subject, $body, $headers);

// Проверка отправки
if ($mailSent) {
echo "<script>alert('Сообщение отправлено!'); window.location.href = window.location.href.split('?')[0];</script>";
} else {
echo "<script>alert('Ошибка при отправке сообщения!'); window.location.href = window.location.href.split('?')[0];</script>";
}
?>