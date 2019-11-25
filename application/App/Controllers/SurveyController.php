<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Preferences;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Surveys\SurveyOne;
use App\Repository;
use App\Validator;

class SurveyController extends AbstractTwigController
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
     * @var Validator
     */
    public $validator;

    /**
     * @var Repository
     */
    public $repository;

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
        $this->validator = new Validator();
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
        $questions = $this->survey->getQuestions();
        $results = $this->repository->allAnswers();
        $currentSurvey = 'survey.twig';

        if (isset($results['Methodic 1'])) {
            return $this->result($request, $response, $args);
            $currentSurvey = 'result.twig';
        }

        return $this->render($response, $currentSurvey, [
            'pageTitle' => 'Survey',
            'questions' => $questions,
            'rootPath' => $this->preferences->getRootPath(),
        ]);
    }

    public function check($request, $response, $args)
    {
        $bodyData = $request->getParsedBody();
        $data = $bodyData["data"];

        $errors = $this->validator->validate($data);
        if (count($errors) === 0) {
            $this->repository->saveAnswers($data);
            return $response->withHeader('Location', '/result')
            ->withStatus(302);
        }

        $questions = $this->survey->getQuestions();
        // var_dump($errors);
        print_r(json_encode($data));
        $params = [
            'pageTitle' => 'Survey',
            'questions' => $questions,
            'rootPath' => $this->preferences->getRootPath(),
            'data' => $data,
            'errors' => $errors,
        ];
        $response = $response->withStatus(422);
        return $this->render($response, "survey.twig", $params);
    }

    public function result($request, $response, $args)
    {
        $questions = $this->survey->getQuestions();
        $results = $this->repository->allAnswers();
        print_r(json_encode($results));
        $params = [
            'pageTitle' => 'Survey Result',
            'questions' => $questions,
            'rootPath' => $this->preferences->getRootPath(),
            'results' => $results,
        ];
        return $this->render($response, "result.twig", $params);
    }
}
