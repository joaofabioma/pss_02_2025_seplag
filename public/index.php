<?php

use PSS0225Seplag\Config\DBConfig;
use PSS0225Seplag\Services\AuthService;
use PSS0225Seplag\Middleware\AuthMiddleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

require_once __DIR__ . '/../vendor/autoload.php';


$request = Request::createFromGlobals();
try {
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
	$dotenv->load();

	$dsn = DBConfig::pgDsn();
	try {
		$pdo = new PDO($dsn, DBConfig::getUser(), DBConfig::getPass());
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (\PDOException $e) {
		error_log("Erro de conexão com o banco de dados: " . $e->getMessage());

		$response = new JsonResponse([
			'status' => 'error',
			'message' => 'Fail on API',
			'error_code' => 'FAILED'
		], Response::HTTP_SERVICE_UNAVAILABLE);
		$response->headers->set('Content-Type', 'application/json');
		$response->send();
		return $response;
	}
} catch (\Throwable $e) {
	error_log($e->getMessage());
	$response = new JsonResponse([
		'status' => 'error',
		'message' => 'Erro na inicialização da aplicação',
		'error_code' => 'APP_INIT_FAILED'
	], Response::HTTP_INTERNAL_SERVER_ERROR);
	$response->headers->set('Content-Type', 'application/json');
	$response->send();
	return $response;
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(255) NOT NULL,
        role VARCHAR(50) DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$authService = new AuthService($pdo);
$authMiddleware = new AuthMiddleware($pdo);

// Rotas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/register') {
	$data = json_decode(file_get_contents('php://input'), true);
	try {
		$authService->register($data['email'], $data['password'], $data['name']);
		http_response_code(201);
		header('Content-Type: application/json');
		echo json_encode(['message' => 'Usuario registrado com sucesso']);
	} catch (Exception $e) {
		http_response_code(400);
		header('Content-Type: application/json');
		echo json_encode(['error' => $e->getMessage()]);
	}
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] === '/login') {
	$data = json_decode(file_get_contents('php://input'), true);
	if ($data === null) {
		http_response_code(401);
		header('Content-Type: application/json');
		echo json_encode(['error' => 'Invalid Request']);
		return;
	}
	try {
		$token = $authService->login($data['email'], $data['password']);
		http_response_code(200);
		header('Content-Type: application/json');
		echo json_encode(['token' => $token]);
	} catch (Exception $e) {
		http_response_code(401);
		header('Content-Type: application/json');
		echo json_encode(['error' => $e->getMessage()]);
	}
} elseif ($request->getPathInfo() === '/protected' && $request->isMethod('GET')) {
	try {
		$authMiddleware->__invoke($request, function ($request) {
			$user = $request->attributes->get('user');
			$response = new JsonResponse(['message' => 'Access granted', 'user' => $user], Response::HTTP_OK);
			$response->headers->set('Content-Type', 'application/json');
			$response->send();
			return $response;
		});
	} catch (\Exception $e) {
		$response = new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
		$response->headers->set('Content-Type', 'application/json');
		$response->send();
		return $response;
	}
} else {
	http_response_code(404);
	header('Content-Type: application/json');
	echo json_encode(['error' => 'Not found']);
}
