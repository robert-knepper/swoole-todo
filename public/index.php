<?php

use App\User\Application\Service\UserService;
use App\User\Infrastructure\Adapter\Persistence\InMemoryUserRepositoryAdapter;

require_once __DIR__.'/../vendor/autoload.php';

$repo = new InMemoryUserRepositoryAdapter();
$service = new UserService($repo);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? 'John Doe';
    $email = $_POST['email'] ?? 'john@example.com';
    $userDto = $service->registerUser($name, $email);
    echo json_encode([
        'status' => '✅ User registered',
        'user' => $userDto
    ]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? 1;
    $userDto = $service->getUser((int)$id);
    if ($userDto) {
        echo json_encode([
            'status' => '👤 User found',
            'user' => $userDto
        ]);
    } else {
        echo json_encode([
            'status' => '❌ User not found'
        ]);
    }
}