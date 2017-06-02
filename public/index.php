<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 02.06.17
 * Time: 11:15
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response) {

    $s = new DB\PostDB();
    return $response->withJson($s->getAll(), 201);

});


$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');

    return $response->withJson(array('name' => $name), 201);
});

$app->run();