<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

function validarEmail($email) {
    // Primero, validar la sintaxis del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Luego, realizar la verificación MX
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, 'MX');
}

$app = new \Slim\App;

$app->post('/validarCorreo', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

    // Validar el correo electrónico
    if (validarEmail($email)) {
        $result = array('email' => $email, 'valid' => true);
    } else {
        $result = array('email' => $email, 'valid' => false);
    }

    return $response->withJson($result);
});

$app->run();