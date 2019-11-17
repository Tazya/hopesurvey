<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Preferences;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Surveys\SurveyOne;

class SurveyController extends AbstractTwigController
{
    /**
     * @var Preferences
     */
    private $preferences;

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
        $survey = new SurveyOne();
        $questions = $survey->getQuestions();
        return $this->render($response, 'survey.twig', [
            'pageTitle' => 'Survey',
            'questions' => $questions,
            'rootPath' => $this->preferences->getRootPath(),
        ]);
    }
}
