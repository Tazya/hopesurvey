<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Preferences;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Surveys\SurveyOne;
use App\Surveys\SurveyCollections;

class CollectionsController extends AbstractTwigController
{
    /**
     * @var Preferences
     */
    private $preferences;

    /**
     * @var Survey
     */
    public $survey;

    /**
     * @var SurveyCollections
     */
    public $surveyCollections;

    /**
     * SurveyController constructor.
     *
     * @param Twig        $twig
     * @param Preferences $preferences
     */
    public function __construct(Twig $twig, Preferences $preferences)
    {
        parent::__construct($twig);

        $this->preferences = $preferences;
        $this->survey = new SurveyOne();
        $this->surveyCollections = new SurveyCollections();
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
        $surveyCollectionsData = [1];
        $pageTitle = "Все результаты";
        $template = "results.twig";
        
        return $this->render($response, $template, [
            'pageTitle' => $pageTitle,
            'collections' => $surveyCollectionsData,
            'rootPath' => $this->preferences->getRootPath(),
        ]);
    }
}
