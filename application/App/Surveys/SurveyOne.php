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
        $this->questions = $surveyQuestionData->getQuestions();
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

    /**
     * Calculate methodic
     */
    public function calculate(array $answers = [], string $methodicKey = "")
    {
        $result = [];
        $result['all'] = 0;
        $result['scales'] = [];
        $questions = $this->questions[$methodicKey];

        switch ($methodicKey) {
            case 'Methodic 6':
                $allScore = 0;
                $characteristics = [];
                foreach ($questions as $scale) {
                    foreach ($scale['questions'] as $question) {
                        // Текущий балл
                        $currentScore = $question['relation'] === 1 ?
                            (int) $answers[$question['id']] :
                            -(int) $answers[$question['id']];

                        // Посчитаем по Характеристике
                        $characteristics[$question['characteristic']]['score'] += $currentScore;
                        // Посчитаем Всё
                        $allScore += $currentScore;
                    }
                }
                $result['all'] = $allScore;
                $result['characteristics'] = $characteristics;
                break;
            
            default:
                if ($methodicKey === "Methodic 3") {
                    $result['all'] = $answers['question-1'];
                    return $result;
                }
                foreach ($answers as $answer) {
                    $result['all'] += (int) $answer;
                }
                foreach ($questions as $scale) {
                    $scaleScore = 0;
                    foreach ($scale['questions'] as $question) {
                        $scaleScore += (int) $answers[$question['id']];
                    }
                    $result['scales'][$scale['id']]['score'] = $scaleScore;
                }
                break;
        }
    
        return $result;
    }

    /**
     * Calculate methodics
     */
    public function calculateAll(array $methodics = [])
    {
        $result = [];
        foreach ($methodics as $key => $methodic) {
            $result[$key] = $this->calculate($methodic, $key);
        }
        return $result;
    }

    /**
     * Interpret results
     */
    public function interpret(array $calculatedMethodics)
    {
        $result = $calculatedMethodics;
        foreach ($calculatedMethodics as $key => $calculatedMethodic) {
            switch ($key) {
                case 'Methodic 2':
                    if ($calculatedMethodic['all'] <= 60) {
                        $calculatedMethodic['conclusion'] = "Низкий уровень толерантности";
                    } elseif ($calculatedMethodic['all'] >= 100) {
                        $calculatedMethodic['conclusion'] = "Высокий уровень толерантности";
                    } else {
                        $calculatedMethodic['conclusion'] = "Cредний уровень толерантности";
                    }

                    foreach ($calculatedMethodic['scales'] as $scaleKey => $scale) {
                        switch ($scaleKey) {
                            case 'scale-1':
                                if ($scale['score'] <= 19) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Этническая толерантность: {$scale['score']} баллов (низкий уровень)";
                                } elseif ($scale['score'] >= 32) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Этническая толерантность: {$scale['score']} баллов (высокий уровень)";
                                } else {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Этническая толерантность: {$scale['score']} баллов (средний уровень)";
                                }
                                break;
                            case 'scale-2':
                                if ($scale['score'] <= 22) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Социальная толерантность: {$scale['score']} баллов (низкий уровень)";
                                } elseif ($scale['score'] >= 37) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Социальная толерантность: {$scale['score']} баллов (высокий уровень)";
                                } else {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Социальная толерантность: {$scale['score']} баллов (средний уровень)";
                                }
                                break;
                            case 'scale-3':
                                if ($scale['score'] <= 19) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Толерантность как черта личности: {$scale['score']} баллов (низкий уровень)";
                                } elseif ($scale['score'] >= 17) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Толерантность как черта личности: {$scale['score']} баллов (высокий уровень)";
                                } else {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] = "Толерантность как черта личности: {$scale['score']} баллов (средний уровень)";
                                }
                                break;
                        }
                    }

                    break;
                case 'Methodic 6':
                    foreach ($calculatedMethodic['characteristics'] as $characteristicKey => $characteristic) {
                        if ($characteristic['score'] <= 7) {
                            $calculatedMethodic['characteristics'][$characteristicKey]['characteristicConclusion'] = "{$characteristic['score']} баллов (низкий уровень)";
                        } elseif ($characteristic['score'] >= 17) {
                            $calculatedMethodic['characteristics'][$characteristicKey]['characteristicConclusion'] = "{$characteristic['score']} баллов (высокий уровень)";
                        } else {
                            $calculatedMethodic['characteristics'][$characteristicKey]['characteristicConclusion'] = "{$characteristic['score']} баллов (средний уровень)";
                        }
                    }
                    break;
            }
            $result[$key] = $calculatedMethodic;
        }
        return $result;
    }
}
