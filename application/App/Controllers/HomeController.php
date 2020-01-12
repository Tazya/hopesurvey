<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Preferences;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Repository;

class HomeController extends AbstractTwigController
{
    /**
     * @var Preferences
     */
    private $preferences;

    /**
     * @var Repository
     */
    public $repository;


    /**
     * HomeController constructor.
     *
     * @param Twig        $twig
     * @param Preferences $preferences
     */
    public function __construct(Twig $twig, Preferences $preferences)
    {
        parent::__construct($twig);

        $this->preferences = $preferences;
        $this->repository = new Repository();
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args = []): Response
    {
        $results = $this->repository->allAnswers();
        print_r($results);
        return $this->render($response, 'home.twig', [
            'pageTitle' => 'Исследование «Взаимосвязь индивидуально
                 - психологических особенностей и толерантности личности»',
            'rootPath' => $this->preferences->getRootPath(),
            'userId' => $this->repository->getId(),
            'results' => $results,
        ]);
    }
}
