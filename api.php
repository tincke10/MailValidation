<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require 'vendor/autoload.php';

function validarEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, 'MX');
}

$app = AppFactory::create();

$app->post('/validarCorreo', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

    if (validarEmail($email)) {
        $result = array('email' => $email, 'valid' => true);
    } else {
        $result = array('email' => $email, 'valid' => false);
    }

    $response->getBody()->write(json_encode($result));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();