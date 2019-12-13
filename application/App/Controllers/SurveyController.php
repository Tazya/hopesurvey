<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Preferences;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use App\Surveys\SurveyOne;
use App\Surveys\SurveyCollections;
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
        $this->validator = new Validator();
        $this->surveyCollections = new SurveyCollections();
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
        $pageTitle = "Методика №1 - Оцените суждения";
        $currentSurvey = 'survey.twig';
        $questionsKey = 'Methodic 1';

        if (isset($results['Methodic 1'])) {
            // return $this->result($request, $response, $args);
            $pageTitle = "Методика №2 - Оцените утверждения";
            $currentSurvey = 'survey_two.twig';
            $questionsKey = 'Methodic 2';
        }

        if (isset($results['Methodic 2'])) {
            // return $this->result($request, $response, $args);
            $pageTitle = "Методика №3 - Шкала Кинси";
            $currentSurvey = 'survey_three.twig';
            $questionsKey = 'Methodic 3';
        }

        if (isset($results['Methodic 3'])) {
            $pageTitle = "Методика №4 - Решетка Клейна";
            $currentSurvey = 'survey_four.twig';
            $questionsKey = 'Methodic 4';
        }

        if (isset($results['Methodic 4'])) {
            $pageTitle = "Методика №5 - Кто Я?";
            $currentSurvey = 'survey_five.twig';
            $questionsKey = 'Methodic 5';
        }

        if (isset($results['Methodic 5'])) {
            $pageTitle = "Методика №6 - Оценка характеристик личности";
            $currentSurvey = 'survey_six.twig';
            $questionsKey = 'Methodic 6';
        }

        if (isset($results['Methodic 6'])) {
            return $response->withHeader('Location', '/final')
            ->withStatus(302);
        }

        if (isset($results['final'])) {
            return $response->withHeader('Location', '/result')
            ->withStatus(302);
        }

        return $this->render($response, $currentSurvey, [
            'pageTitle' => $pageTitle,
            'questions' => $questions[$questionsKey],
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
            return $response->withHeader('Location', '/survey')
            ->withStatus(302);
        }

        $questions = $this->survey->getQuestions();
        $results = $this->repository->allAnswers();
        $pageTitle = "Методика №1 - Оцените суждения";
        $currentSurvey = 'survey.twig';
        $questionsKey = 'Methodic 1';

        if (isset($results['final'])) {
            return $response->withHeader('Location', '/result')
            ->withStatus(302);
        }

        if (isset($results['Methodic 1'])) {
            // return $this->result($request, $response, $args);
            $pageTitle = "Методика №2 - Оцените утверждения";
            $currentSurvey = 'survey_two.twig';
            $questionsKey = 'Methodic 2';
        }

        if (isset($results['Methodic 2'])) {
            // return $this->result($request, $response, $args);
            $pageTitle = "Методика №3 - Шкала Кинси";
            $currentSurvey = 'survey_three.twig';
            $questionsKey = 'Methodic 3';
        }

        if (isset($results['Methodic 3'])) {
            $pageTitle = "Методика №4 - Решетка Клейна";
            $currentSurvey = 'survey_four.twig';
            $questionsKey = 'Methodic 4';
        }

        if (isset($results['Methodic 4'])) {
            $pageTitle = "Методика №5 - Кто Я?";
            $currentSurvey = 'survey_five.twig';
            $questionsKey = 'Methodic 5';
        }

        if (isset($results['Methodic 5'])) {
            $pageTitle = "Методика №6 - Оценка характеристик личности";
            $currentSurvey = 'survey_six.twig';
            $questionsKey = 'Methodic 6';
        }

        if (isset($results['Methodic 6'])) {
            return $response->withHeader('Location', '/final')
            ->withStatus(302);
        }

        if (isset($results['final'])) {
            return $response->withHeader('Location', '/result')
            ->withStatus(302);
        }


        // print_r(json_encode($data));
        $params = [
            'pageTitle' => $pageTitle,
            'questions' => $questions[$questionsKey],
            'rootPath' => $this->preferences->getRootPath(),
            'data' => $data,
            'errors' => $errors,
        ];
        $response = $response->withStatus(422);
        return $this->render($response, $currentSurvey, $params);
    }

    public function userResult($request, $response, $args)
    {
        $questions = $this->survey->getQuestions();
        $results = $this->repository->allAnswers();
        // print_r(json_encode($questions));
        // print_r(json_encode($results));
        $scores = $this->survey->calculateAll($results);
        $interpreted = $this->survey->interpret($scores);

        // print_r($messageBody);
        // print_r(json_encode($interpreted));
        // print_r(json_encode($scores));

        $errors = [];
        if (!$this->repository->isComplete() && isset($results['Methodic 6'])) {
            $emailStatus = $this->survey->sendEmail() ? true : false;
            $this->repository->setComplete();
            if ($this->survey->saveAnswersToFile(false)) {
                $this->repository->setComplete();
            } else {
                $errors['file'] = "Не удалось сохранить результаты теста. Попробуйте ещё раз";
            }
        }

        $params = [
            'pageTitle' => 'Результаты опроса',
            'questions' => $questions,
            'rootPath' => $this->preferences->getRootPath(),
            'results' => $results,
            'scores' => $interpreted,
            'errors' => $errors,
        ];
        return $this->render($response, "result.twig", $params);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function results(Request $request, Response $response, array $args = []): Response
    {
        $surveyCollectionsData = $this->surveyCollections->getAllResults();
        $pageTitle = "Все результаты";
        $template = "results.twig";
        return $this->render($response, $template, [
            'pageTitle' => $pageTitle,
            'collections' => $surveyCollectionsData,
            'rootPath' => $this->preferences->getRootPath(),
        ]);
    }

    public function result($request, $response, array $args = [])
    {
        $questions = $this->survey->getQuestions();
        $collection = $this->surveyCollections->getResult($args['name']);
        $results = $collection['answers'];
        $scores = $this->survey->calculateAll($results);
        $interpreted = $this->survey->interpret($scores);
        
        // print_r(json_encode($results));
        $params = [
            'pageTitle' => 'Результаты опроса: ' . $id,
            'questions' => $questions,
            'rootPath' => $this->preferences->getRootPath(),
            'results' => $results,
            'scores' => $interpreted,
            'errors' => $errors,
            'name' => $args['name'],
        ];
        return $this->render($response, "admin_result.twig", $params);
    }

    public function final($request, $response, array $args = [])
    {
        $results = $this->repository->allAnswers();
        if (isset($results['final'])) {
            return $response->withHeader('Location', '/result')
            ->withStatus(302);
        }
        $params = [
            'pageTitle' => 'Укажите, пожалуйста, ваши данные',
            'rootPath' => $this->preferences->getRootPath(),
        ];
        return $this->render($response, "final.twig", $params);
    }

    public function finalSend($request, $response, array $args = [])
    {
        $bodyData = $request->getParsedBody();
        $data = $bodyData["data"];
        $results = $this->repository->allAnswers();
        if (isset($results['final'])) {
            return $response->withHeader('Location', '/result')
            ->withStatus(302);
        }
        $errors = $this->validator->validate($data);

        if (count($errors) === 0) {
            $this->repository->saveAnswers($data);
            return $response->withHeader('Location', '/result')
            ->withStatus(302);
        }

        $params = [
            'pageTitle' => 'Укажите, пожалуйста, ваши данные',
            'rootPath' => $this->preferences->getRootPath(),
            'data' => $data,
            'errors' => $errors,
        ];
        return $this->render($response, "final.twig", $params);
    }
}
