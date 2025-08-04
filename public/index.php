<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Инициализация Slim
$app = AppFactory::create();

// Массив кодов и ответов
$codes = [
    "ABC123" => "✅ Ваш код действителен! Доставка ожидается 15 июня.",
    "XYZ456" => "❌ Код не найден. Проверьте правильность ввода.",
    "PROMO2024" => "🎁 Специальный промо-код! Скидка 20%."
];

// Роут для главной страницы (отдаём HTML)
$app->get('/', function (Request $request, Response $response) {
    $html = file_get_contents(__DIR__ . '/../templates/index.html');
    $response->getBody()->write($html);
    return $response;
});

// Роут для проверки кода (API)
$app->post('/check-code', function (Request $request, Response $response) use ($codes) {
    $data = $request->getParsedBody();
    $code = $data['code'] ?? '';

    // Проверяем код
    $result = $codes[$code] ?? "⚠️ Код недействителен или уже использован.";

    // Возвращаем JSON
    $response->getBody()->write(json_encode(['message' => $result], JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
