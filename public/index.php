<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

$repo = new App\Repository();

$container = new Container();
$container->set('renderer', function () {
    // Параметром передается базовая директория в которой будут храниться шаблоны
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $response->write('Welcome!');
});

$app->get('/dates', function ($request, $response) use ($repo) {
    $term = $request->getQueryParam('term');
    $dateQuery = $request->getQueryParam('days');
    $dates = $repo->all();
    if (!empty($term) || !empty($dateQuery)) {
    	$filtered_dates = array_filter($dates, function ($date) use ($term, $dateQuery) {
            if (strpos(trim(ucfirst($date['title'])), ucfirst($term)) || $date['date'] === $dateQuery) {
                return $date;
            }
    	});
    	$params = ['dates' => $filtered_dates, 'term' => $term];
    } else {
    	$params = ['dates' => $dates, 'term' => []];
    }
    return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});

$app->post('/dates', function ($request, $response) use ($repo) {
    $validator = new App\Validator();
    $date = $request->getParsedBodyParam('date');
    $errors = $validator->validate($date);
    if (count($errors) === 0) {
        $repo->save($date);
        return $response->withHeader('Location', '/dates')
          ->withStatus(302);
    }

    //price
    $subCosts = [];
    if ($date['adults'] > 4) {
        $subCosts['adults'] = $date['price'] * $date['adults'];
    } else {
        $subCosts['adults'] = $date['price'] * $date['adults'];
    }
    $subCosts['children'] = $date['children'] != 0? $date['price'] * $date['children'] * 0.5 : 0;
    $subCosts['transfer'] = ($date['adults'] + $date['children'] * 0.5) * 10;
    $total = array_sum($subCosts);

    $params = [
        'date' => $date,
        'errors' => $errors,
        'subCosts' => $subCosts,
        'cost' => $date['price'],
        'total' => $total
    ];
    $response = $response->withStatus(422);
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});

$app->get('/dates/new', function ($request, $response) {
    $params = [
        'date' => [],
        'errors' => []
    ];
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
});

$app->run();