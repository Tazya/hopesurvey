<?php

namespace App\Surveys;

use App\Surveys\SurveyAbstractClass;
use App\Surveys\SurveyQuestionData;
use App\Repository;
use App\Validator;

class SurveyOne extends SurveyAbstractClass
{

    /**
     * @var header
     */
    public $header = "Исследование на тему: 
    «Взаимосвязь индивидуально - психологических особенностей и толерантности личности»";
    
    /**
     * @var instructions
     */
    public $instructions = "Цель нашего исследования — 
    выявление взаимосвязи между уровнем толерантности и ориентацией личности.
    Исследование является анонимным. Интерпретация данных будет проводиться на основании результатов
    статистической обработки. Индивидуальные данные не разглашаются и не интерпретируются.
    Для заполнения бланка внимательно прочитайте инструкцию и дайте ответ соответствующий вашим представлениям.";
    
    /**
     * @var questions
     */
    public $questions = [];
 

    /**
     * SurveyOneClass constructor.
     *
     * @param questions $questions
     */
    public function __construct()
    {
        $surveyQuestionData = new SurveyQuestionData();
        $this->questions = $surveyQuestionData->questions;
    }

    /**
     * Get all questions.
     */
    public function getQuestions()
    {
        return $this->questions;
    }


    public function saveForm($request, $response, $repo)
    {
        $validator = new App\Validator();
        $date = $request->getParsedBodyParam('date');
        $errors = $validator->validate($date);

        if (count($errors) === 0) {
            $repo->save($date);
            return $response->withHeader('Location', '/dates')
            ->withStatus(302);
        }

        $params = [
            'date' => $date,
            'errors' => $errors,
        ];
        $response = $response->withStatus(422);
        return $this->get('renderer')->render($response, "users/new.phtml", $params);
    }
}
