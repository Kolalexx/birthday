<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$codes = [
    "ABC123" => "✅ Ваш код действителен! Доставка ожидается 15 июня.",
    "XYZ456" => "❌ Код не найден. Проверьте правильность ввода.",
    "PROMO2024" => "🎁 Специальный промо-код! Скидка 20%."
];

$app->get('/', function (Request $request, Response $response) {
    $html = file_get_contents(__DIR__ . '/../templates/index.html');
    $response->getBody()->write($html);
    return $response;
});

$app->post('/check-code', function (Request $request, Response $response) use ($codes) {
    $data = $request->getParsedBody();
    $code = $data['code'] ?? '';

    $result = htmlspecialchars($codes[$code] ?? "⚠️ Код недействителен или уже использован.");

    $response->getBody()->write(json_encode(['message' => $result], JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/favicon.ico', function (Request $request, Response $response) {
    return $response->withStatus(404);
});

$app->map(['GET', 'POST'], '/{routes:.+}', function ($request, $response) {
    return $response->withStatus(404)->write('Страница не найдена');
});

$app->run();
