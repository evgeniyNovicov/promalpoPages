<?php
// Файл smtpmail.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = htmlspecialchars($_POST['Имя']);
    $phone = htmlspecialchars($_POST['Телефон']);
    $email = htmlspecialchars($_POST['Email']);
    $service = htmlspecialchars($_POST['services']);
    $city = htmlspecialchars($_POST['Город']);
    $message = htmlspecialchars($_POST['Сообщение']);

    // Преобразуем значение услуги в читаемый текст
    $services = [
        'sistemi-otoplenia' => 'Системы отопления',
'vodosnabgenie' => 'Подвод, развод воды',
'vodootvedenie' => 'Канализация (фикальные и ливневые)',
'materiali' => 'Материалы (помощь в выборе)',
'teplie-poli' => 'Монтаж теплых полов'
];
$serviceText = $services[$service] ?? 'Не указано';

// Формируем содержимое письма
$emailContent = "
<h2>Новая заявка с сайта</h2>
<p><strong>Имя:</strong> $name</p>
<p><strong>Телефон:</strong> $phone</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Услуга:</strong> $serviceText</p>
<p><strong>Город:</strong> $city</p>
<p><strong>Сообщение:</strong> $message</p>
";

// Настройки SendGrid


$fromEmail = 'aleksei22.80@yandex.by';
$toEmail = 'gidro-profi1@yandex.by';

// Формируем JSON для SendGrid API
$data = [
"personalizations" => [
[
"to" => [["email" => $toEmail]],
"subject" => "Новая заявка: $serviceText ($city)"
]
],
"from" => ["email" => $fromEmail],
"content" => [
[
"type" => "text/html",
"value" => $emailContent
]
]
];

// Отправка через cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
'Authorization: Bearer ' . $apiKey,
'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Проверяем результат
if ($httpCode === 202) {
// Перенаправляем после успешной отправки
header('Location: thank-you.html');
exit;
} else {
// Логируем ошибку
error_log("SendGrid Error: $httpCode - $response");
echo "Произошла ошибка при отправке. Пожалуйста, попробуйте позже.";
}
} else {
header('Location: index.html');
exit;
}
?>