<?php
/**
 * Author: Taras "Dr.Wolf" Supyk (w@enigma-lab.org)
 * Date: 02.06.17
 * Time: 11:15
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Managers\DatasetManager;
use Managers\PostManager;
use Models\Dataset;
use Models\Post;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App;

$app->post('/', function (Request $request, Response $response) {
    $datasetManager = new DatasetManager();
    return $response->withJson($datasetManager->create());
});

$app->get('/{dataset}', function (Request $request, Response $response) {
    $guid = $request->getAttribute('dataset');
    return $response->withJson(new Dataset($guid));
});

$app->delete('/{dataset}', function (Request $request, Response $response) {
    $guid = $request->getAttribute('dataset');
    $datasetManager = new DatasetManager();
    $datasetManager->delete($guid);
    return $response->withJson(array('guid' => $guid));
});

$app->post('/{dataset}/', function (Request $request, Response $response) {
    $datasetGuid = $request->getAttribute('dataset');
    $metadata = json_decode($request->getBody(), true);
    $postManager = new PostManager();
    return $response->withJson($postManager->create($metadata, $datasetGuid));
});

$app->post('/{dataset}/{post}', function (Request $request, Response $response) {
    $datasetGuid = $request->getAttribute('dataset');
    $postGuid = $request->getAttribute('post');
    $metadata = json_decode($request->getBody(), true);
    $postManager = new PostManager();
    return $response->withJson($postManager->update($metadata, $postGuid, $datasetGuid));
});

$app->get('/{dataset}/{post}', function (Request $request, Response $response) {
    $datasetGuid = $request->getAttribute('dataset');
    $postGuid = $request->getAttribute('post');
    return $response->withJson(new Post($postGuid, $datasetGuid));
});

$app->delete('/{dataset}/{post}', function (Request $request, Response $response) {
    $datasetGuid = $request->getAttribute('dataset');
    $postGuid = $request->getAttribute('post');
    $postManager = new PostManager();
    $postManager->delete($postGuid, $datasetGuid);
    return $response->withJson(array('guid' => $postGuid));
});

$container = $app->getContainer();
$container['errorHandler'] = function ($container) {
    return function (Request $request, Response $response, $exception) {
        return $response->withJson(array("message" => $exception->getMessage()))->withStatus($exception->getCode());
    };
};

$app->run();