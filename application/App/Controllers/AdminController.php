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
use App\Statistic;

class AdminController extends AbstractTwigController
{
    /**
     * @var Preferences $preferences
     */
    private $preferences;

    /**
     * @var SurveyCollections $SurveyCollections
     */
    private $SurveyCollections;

    /**
     * @var string $authorized
     */
    private $authorized;

    /**
     * @var Repository $repository
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
        $this->SurveyCollections = new SurveyCollections($preferences->getSurveysPath());

        $this->repository = $repo = new Repository();
        $this->authorized = $repo->isAuth();
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
        if (!$this->authorized) {
            return $this->toAuthForm($request, $response, $args);
        }

        $helloMessages = [
            "Привет, Хоуп!",
            "Как дела, Админ?",
            "Хоуп, добрая Хоуп",
            "Хоуп, милая Хоуп",
            "Сайт рад тебя видеть, Надя!",
            "Что нового?",
            "Как поживает Укулеле?",
            "Добро пожаловать, Психологиня!",
            "Как дела во внешней вселенной?",
            "Хэээээй!"
        ];

        $message = $helloMessages[rand(0, count($helloMessages) - 1)];
        $surveyCollectionsData = $this->SurveyCollections->getAllResults();

        $results = array_filter($surveyCollectionsData, function ($value) {
            return $value['new'];
        });

        return $this->render($response, 'admin/admin.twig', [
            'pageTitle' => 'Админка',
            'rootPath' => $this->preferences->getRootPath(),
            'results' => $results,
            'message' => $message
        ]);
    }

   /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function toAuthForm(Request $request, Response $response, array $args = [])
    {
        return $this->render($response, "admin/signin.twig", [
            'pageTitle' => 'Войти в админку',
            'rootPath' => $this->preferences->getRootPath(),
        ]);
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
        if (!$this->authorized) {
            return $this->toAuthForm($request, $response, $args);
        }

        $dir = $this->preferences->getSurveysPath();
        $surveyCollectionsData = $this->SurveyCollections->getAllResults();
        // print_r($surveyCollectionsData);
        $pageTitle = "Все результаты";
        $template = "admin/results.twig";
        return $this->render($response, $template, [
            'pageTitle' => $pageTitle,
            'collections' => $surveyCollectionsData,
            'rootPath' => $this->preferences->getRootPath(),
        ]);
    }

    public function result($request, $response, array $args = [])
    {
        if (!$this->authorized) {
            return $this->toAuthForm($request, $response, $args);
        }

        $survey = new SurveyOne();
        
        $questions = $survey->getQuestions();
        $collection = $this->SurveyCollections->getResult($args['name'], $this->preferences->getSurveysPath());
        $results = $collection['answers'];
        $userName = $collection['userName'];
        $scores = $survey->calculateAll($results);
        $interpreted = $survey->interpret($scores);

        $this->SurveyCollections->setViewed($args['name']);

        // $randomUserName = $survey->makeUniqueName($results);
        // print_r($randomUserName);
        $params = [
            'pageTitle' => 'Результаты опроса: ' . $id,
            'questions' => $questions,
            'rootPath' => $this->preferences->getRootPath(),
            'results' => $results,
            'scores' => $interpreted,
            'errors' => $errors,
            'name' => $args['name'],
            'userName' => $userName,
        ];
        return $this->render($response, "admin/result.twig", $params);
    }

    public function signIn($request, $response, array $args = [])
    {
        $bodyData = $request->getParsedBody();
        $data = $bodyData["data"];

        $errors = [];

        if ($data['username'] !== "Хоуп") {
            $errors['username'] = "Неверное имя";
        } elseif (md5($data['password']) !== md5("укулеле")) {
            $errors['password'] = "Неверный пароль";
        }

        if (count($errors) === 0) {
            $this->repository->setAuth();
            return $response->withHeader('Location', '/admin')
            ->withStatus(302);
        }
        
        $params = [
            'pageTitle' => 'Войти в админку',
            'rootPath' => $this->preferences->getRootPath(),
            'data' => $data,
            'errors' => $errors,
        ];
        $response = $response->withStatus(422);
        return $this->render($response, "admin/signin.twig", $params);
    }

    public function signOut($request, $response, array $args = [])
    {
            $this->repository->unsetAuth();
            return $response->withHeader('Location', '/admin')
            ->withStatus(302);
    }

   /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function statistic(Request $request, Response $response, array $args = []): Response
    {
        if (!$this->authorized) {
            return $this->toAuthForm($request, $response, $args);
        }

        $statistic = new Statistic();

        $allResults = $statistic->countAllResults();
        
        $pageTitle = "Все результаты";
        $template = "admin/statistic.twig";
        return $this->render($response, $template, [
            'pageTitle' => $pageTitle,
            'allResults' => $allResults,
            'rootPath' => $this->preferences->getRootPath(),
        ]);
    }
}
