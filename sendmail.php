<?php
// Включение отображения ошибок (для отладки, в продакшене убрать)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Method Not Allowed']));
}

// Настройки
$mailTo = "evgeniy.novikov19940534@mail.ru";
$mailFrom = "gidroprofi@list.ru";
$subject = "Новая заявка с сайта";

// Функция для очистки входящих данных
function cleanInput($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
return $data;
}

// Получение и валидация данных
$name = isset($_POST['Имя']) ? cleanInput($_POST['Имя']) : '';
$phone = isset($_POST['Телефон']) ? cleanInput($_POST['Телефон']) : '';
$email = isset($_POST['Email']) ? cleanInput($_POST['Email']) : '';
$service = isset($_POST['services']) ? cleanInput($_POST['services']) : '';
$city = isset($_POST['Город']) ? cleanInput($_POST['Город']) : '';
$message = isset($_POST['Сообщение']) ? cleanInput($_POST['Сообщение']) : '';

// Валидация обязательных полей
$errors = [];
if (empty($name)) $errors[] = 'Имя обязательно для заполнения';
if (empty($phone)) $errors[] = 'Телефон обязателен для заполнения';

// Валидация email, если он указан
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
$errors[] = 'Некорректный формат email';
}

// Если есть ошибки - возвращаем их
if (!empty($errors)) {
http_response_code(400);
header('Content-Type: application/json');
die(json_encode(['errors' => $errors]));
}

// Формирование тела письма
$body = "
<html>
<head>
    <title>$subject</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        h2 { color: #333; }
        p { margin: 5px 0; }
        strong { color: #555; }
    </style>
</head>
<body>
<h2>$subject</h2>
<p><strong>Имя:</strong> $name</p>
<p><strong>Телефон:</strong> $phone</p>
" . (!empty($email) ? "<p><strong>Email:</strong> $email</p>" : "") . "
" . (!empty($service) ? "<p><strong>Услуга:</strong> $service</p>" : "") . "
" . (!empty($city) ? "<p><strong>Город:</strong> $city</p>" : "") . "
" . (!empty($message) ? "<p><strong>Сообщение:</strong><br>$message</p>" : "") . "
</body>
</html>
";

// Заголовки письма
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";
$headers .= "From: $mailFrom\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Логирование перед отправкой (для отладки)
file_put_contents('mail_log.txt', date('Y-m-d H:i:s') . " - Attempting to send mail to: $mailTo\n", FILE_APPEND);

// Отправка письма
try {
$mailSent = mail($mailTo, $subject, $body, $headers);

if ($mailSent) {
// Логирование успешной отправки
file_put_contents('mail_log.txt', date('Y-m-d H:i:s') . " - Mail sent successfully\n", FILE_APPEND);

// Возвращаем успешный ответ
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Сообщение успешно отправлено']);
} else {
// Получаем последнюю ошибку PHP
$error = error_get_last();
$errorMsg = $error ? $error['message'] : 'Неизвестная ошибка при отправке письма';

// Логирование ошибки
file_put_contents('mail_log.txt', date('Y-m-d H:i:s') . " - Mail send failed: $errorMsg\n", FILE_APPEND);

// Возвращаем ошибку
http_response_code(500);
header('Content-Type: application/json');
echo json_encode(['error' => 'Ошибка при отправке сообщения', 'details' => $errorMsg]);
}
} catch (Exception $e) {
// Логирование исключения
file_put_contents('mail_log.txt', date('Y-m-d H:i:s') . " - Exception: " . $e->getMessage() . "\n", FILE_APPEND);

// Возвращаем ошибку
http_response_code(500);
header('Content-Type: application/json');
echo json_encode(['error' => 'Ошибка при отправке сообщения', 'details' => $e->getMessage()]);
}
?>