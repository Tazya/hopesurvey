<?php

namespace App\Surveys;

use App\Surveys\SurveyAbstractClass;
use App\Surveys\SurveyQuestionData;
use App\Repository;
use App\Validator;

class SurveyOne extends SurveyAbstractClass
{
    
    /**
     * @var questions
     */
    public $questions = [];

    /**
     * @var Repository $repository
     */
    public $repository;
 

    /**
     * SurveyOneClass constructor.
     *
     * @param questions $questions
     */
    public function __construct()
    {
        $surveyQuestionData = new SurveyQuestionData();
        $this->repository = new Repository();
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
                        if (isset($characteristics[$question['characteristic']]['score'])) {
                            $characteristics[$question['characteristic']]['score'] += $currentScore;
                        } else {
                            $characteristics[$question['characteristic']]['score'] = $currentScore;
                        }

                        // Посчитаем Всё
                        $allScore += $currentScore;
                    }
                }
                $result['all'] = $allScore;
                $result['characteristics'] = $characteristics;
                break;
            
            default:
                if ($methodicKey === "Methodic 3") {
                    if ($answers['question-1'] === "X") {
                        $answers['question-1'] = -1;
                    }

                    $result['all'] = $answers['question-1'];
                    return $result;
                }
                foreach ($answers as $answer) {
                    $result['all'] += (int) $answer;
                }
                if ($methodicKey !== "Methodic 4") {
                    foreach ($questions as $scale) {
                        $scaleScore = 0;
                        foreach ($scale['questions'] as $question) {
                            $scaleScore += (int) $answers[$question['id']];
                        }
                        $result['scales'][$scale['id']]['score'] = $scaleScore;
                    }
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
                case 'Methodic 1':
                    if ($calculatedMethodic['all'] <= 45) {
                        $calculatedMethodic['conclusion'] = "Высокая степень толерантности";
                    } elseif ($calculatedMethodic['all'] > 45 && $calculatedMethodic['all'] <= 85) {
                        $calculatedMethodic['conclusion'] = "Средняя степень толерантности";
                    } else {
                        $calculatedMethodic['conclusion'] = "Низкая степень толерантности";
                    }
                    break;
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
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Этническая толерантность: {$scale['score']} баллов (низкий уровень)";
                                } elseif ($scale['score'] >= 32) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Этническая толерантность: {$scale['score']} баллов (высокий уровень)";
                                } else {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Этническая толерантность: {$scale['score']} баллов (средний уровень)";
                                }
                                break;
                            case 'scale-2':
                                if ($scale['score'] <= 22) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Социальная толерантность: {$scale['score']} баллов (низкий уровень)";
                                } elseif ($scale['score'] >= 37) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Социальная толерантность: {$scale['score']} баллов (высокий уровень)";
                                } else {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Социальная толерантность: {$scale['score']} баллов (средний уровень)";
                                }
                                break;
                            case 'scale-3':
                                if ($scale['score'] <= 19) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Толерантность как черта личности: {$scale['score']} баллов (низкий уровень)";
                                } elseif ($scale['score'] >= 17) {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Толерантность как черта личности: {$scale['score']} баллов (высокий уровень)";
                                } else {
                                    $calculatedMethodic['scales'][$scaleKey]['scaleConclusion'] =
                                        "Толерантность как черта личности: {$scale['score']} баллов (средний уровень)";
                                }
                                break;
                        }
                    }

                    break;
                case 'Methodic 4':
                        $inter = round($calculatedMethodic['all'] / 21, 2);
                        $calculatedMethodic['conclusion'] = "{$calculatedMethodic['all']} / 21 = {$inter}";
                    break;
                case 'Methodic 6':
                    foreach ($calculatedMethodic['characteristics'] as $characteristicKey => $characteristic) {
                        if ($characteristic['score'] <= 7) {
                            $calculatedMethodic['characteristics'][$characteristicKey]['characteristicConclusion'] =
                                "{$characteristic['score']} баллов (низкий уровень)";
                        } elseif ($characteristic['score'] >= 17) {
                            $calculatedMethodic['characteristics'][$characteristicKey]['characteristicConclusion'] =
                                "{$characteristic['score']} баллов (высокий уровень)";
                        } else {
                            $calculatedMethodic['characteristics'][$characteristicKey]['characteristicConclusion'] =
                                "{$characteristic['score']} баллов (средний уровень)";
                        }
                    }
                    break;
            }
            $result[$key] = $calculatedMethodic;
        }
        return $result;
    }

    /**
     * Get Email Body
     */
    public function getEmailBody()
    {
        $questions = $this->getQuestions();
        $results = $this->repository->allAnswers();
        $preScores = $this->calculateAll($results);
        $scores = $this->interpret($preScores);

        $thStyle = "border: 1px solid #ccccee;padding: 6px 14px;font-weight: bold;";
        $tdStyle = "border: 1px solid #ccccee;padding: 4px 10px;";
        $resultsColl = [];

        $resultsColl[] = "<p> Пол: {$results['final']['gender']}, Возраст: {$results['final']['age']}, Ориентация: {$results['final']['orientation']}</p>";

        foreach ($questions as $key => $methodic) {
            switch ($key) {
                case 'Methodic 2':
                    $resultsColl[$key] = "<h2>Методика 2. Толерантность — {$scores[$key]['all']}</h2>\r\n
                                        <table>\r\n
                                        <tr><th style=\"$thStyle\">{$key}</th>\r\n
                                        <th style=\"$thStyle\">Всего: {$scores[$key]['all']} баллов</th>\r\n</tr>\r\n";
                    foreach ($methodic as $scale) {
                        $resultsColl[$key] = "{$resultsColl[$key]}<tr><td style=\"$tdStyle\">{$scale['name']}</td>\r\n
                        <td style=\"$tdStyle\">{$scores[$key]['scales'][$scale['id']]['score']} баллов</td>\r\n
                        <td style=\"$tdStyle\">{$scores[$key]['scales'][$scale['id']]['scaleConclusion']}</td>\r\n</tr>\r\n";
                    }
                    $resultsColl[$key] = "{$resultsColl[$key]}</table>\r\n";
                    break;
                
                case 'Methodic 3':
                    $resultsColl[$key] = "<h2>Методика 3. Кинси</h2>\r\n
                                        <table>\r\n
                                        <tr><th style=\"$thStyle\">{$key}</th>\r\n
                                        <th style=\"$thStyle\">Результат:</th>\r\n</tr>\r\n";
                    foreach ($methodic as $scale) {
                        $resultsColl[$key] = "{$resultsColl[$key]}<tr><td style=\"$tdStyle\">{$scale['name']}</td>\r\n
                                            <td style=\"$tdStyle\">{$results[$key]['question-1']}</td>\r\n</tr>\r\n";
                    }
                    $resultsColl[$key] = "{$resultsColl[$key]}</table>\r\n";
                    break;
                case 'Methodic 4':
                    $resultsColl[$key] = "<h2>Методика 4. Решетка Клейна — {$scores[$key]['all']}</h2>\r\n
                                        <table>\r\n
                                        <tr><th style=\"$thStyle\">{$key}</th>\r\n
                                        <th style=\"$thStyle\">Всего: {$scores[$key]['all']} баллов</th>\r\n</tr>\r\n
                                        <th style=\"$thStyle\">{$scores[$key]['conclusion']}</th>\r\n</tr>\r\n";
                    $resultsColl[$key] = "{$resultsColl[$key]}</table>\r\n";
                    break;
                case 'Methodic 5':
                    $resultsColl[$key] = "<h2>Методтка 5. О себе</h2>\r\n
                        <table>\r\n
                        <tr><th style=\"$thStyle\">{$key}</th>\r\n</tr>\r\n";
                    foreach ($methodic as $scale) {
                        foreach ($scale['questions'] as $question) {
                            $resultsColl[$key] = "{$resultsColl[$key]}<tr>
                                <td style=\"$tdStyle\">{$question['title']}</td>\r\n
                                <td style=\"$tdStyle\">{$results[$key]['select-' . $question['id']]}</td>\r\n
                                <td style=\"$tdStyle\">{$results[$key][$question['id']]}</td>\r\n</tr>\r\n";
                        }
                        $resultsColl[$key] = "{$resultsColl[$key]}<tr>
                            <td style=\"$tdStyle\"><strong>Вывод</strong></td>\r\n
                            <td style=\"$tdStyle\"><strong>{$results[$key]['userConclusion']}</strong></td>\r\n</tr>\r\n";
                    }
                    $resultsColl[$key] = "{$resultsColl[$key]}</table>\r\n";
                    break;
                case 'Methodic 6':
                    $resultsColl[$key] = "<h2>Методика 6. Характеристики личности — {$scores[$key]['all']}</h2>\r\n
                        <table>\r\n
                        <tr><th style=\"$thStyle\">{$key}</th>\r\n
                        <th style=\"$thStyle\">Всего: {$scores[$key]['all']} баллов</th>\r\n</tr>\r\n";
                    foreach ($scores[$key]['characteristics'] as $subkey => $characteristic) {
                        $resultsColl[$key] = "{$resultsColl[$key]}<tr><td style=\"$tdStyle\">{$subkey}</td>\r\n
                            <td style=\"$tdStyle\"><strong>{$characteristic['characteristicConclusion']}</strong></td>\r\n";
                    }
                    $resultsColl[$key] = "{$resultsColl[$key]}</table>\r\n";
                    break;
                default:
                    $resultsColl[$key] = "<h2>{$key} — {$scores[$key]['all']}</h2>\r\n<th>{$scores[$key]['conclusion']}</th>\r\n
                        <table>\r\n
                        <tr><th style=\"$thStyle\">{$key}</th>\r\n
                        <th style=\"$thStyle\">Всего: {$scores[$key]['all']} баллов</th>\r\n</tr>\r\n";
                    foreach ($methodic as $scale) {
                        $resultsColl[$key] = "{$resultsColl[$key]}<tr><td style=\"$tdStyle\">{$scale['name']}</td>\r\n
                            <td style=\"$tdStyle\">{$scores[$key]['scales'][$scale['id']]['score']} баллов</td>\r\n</tr>\r\n";
                    }
                    $resultsColl[$key] = "{$resultsColl[$key]}</table>\r\n";
                    break;
            }
        }

        $emailBody = implode(" ", $resultsColl);
        return $emailBody;
    }

    /**
     * Save Answers to file
     */
    public function saveAnswersToFile(bool $emailStatus)
    {
        $id = $this->repository->getId() ? $this->repository->getId() : uniqid();
        $answers = [
            'id' => $id,
            'emailStatus' => $emailStatus,
            'userName' => $this->makeUniqueName($this->repository->allAnswers()),
            'answers' => $this->repository->allAnswers(),
        ];

        date_default_timezone_set('Asia/Almaty');

        $date = date("Y-m-d-H-i");
        $json = json_encode($answers, JSON_PRETTY_PRINT);

        $fd = fopen("../results/" . $date . "_" . $id . ".json", 'a');
        
        $result = fwrite($fd, $json);

        fclose($fd);
        return $result;
    }

    /**
     * Save Answers to file
     */
    public function makeUniqueName(array $answers = ["final" => ["gender" => "Женский"]])
    {
        $gender = mb_strtoupper($answers["final"]["gender"]);
        $maleAnimals = [
            "Кенгуру",
            "Медведь",
            "Медвежонок",
            "Лис",
            "Бурундук",
            "Муравьед",
            "Кабан",
            "Утконос",
            "Капибара",
            "Рассондиус",
            "Утёнок",
            "Коала",
            "Магнитофон",
            "Кот",
            "Росомаха",
            "Волк",
            "Барсук",
            "Тушканчик"
        ];

        $femaleAnimals = [
            "Кенгуру",
            "Медведица",
            "Акула",
            "Лисица",
            "Бурундучиха",
            "Муравьедиха",
            "Мышка",
            "Утконосиха",
            "Капибара",
            "Рассондиусиха",
            "Утка",
            "Коала",
            "Драконица",
            "Кошка",
            "Росомаха",
            "Волчица",
            "Бобриха",
            "Орлица"
        ];

        $maleColors = [
            "Красный",
            "Зелёный",
            "Лиловый",
            "Синий",
            "Розовый",
            "Оранжевый",
            "Желтый",
            "Лаймовый",
            "Голубой",
            "Коричневый",
            "Серый",
        ];
        $femaleColors = [
            "Красная",
            "Зелёная",
            "Лиловая",
            "Синяя",
            "Розовая",
            "Оранжевая",
            "Желтая",
            "Лаймовая",
            "Голубая",
            "Коричневая",
            "Серая",
        ];
        
        if (mb_substr($gender, 0, 1) === "М") {
            $first = $maleColors[rand(0, count($maleColors) - 1)];
            $second = $maleAnimals[rand(0, count($maleAnimals) - 1)];

            $result = $first . " " . $second;
        } else {
            $first = $femaleColors[rand(0, count($femaleColors) - 1)];

            $second = $femaleAnimals[rand(0, count($femaleAnimals) - 1)];
            $result = $first . " " . $second;
        }
        return $result;
    }

    /**
     * Adoptation text for Email
     */
    public function adopt($text)
    {
        return '=?UTF-8?B?' . base64_encode($text) . '?=';
    }

    /**
     * Send Email
     */
    public function sendEmail()
    {
        $project_name = "Hope Survey";
        $admin_email  = "nmarchenko97@gmail.com, pavel@profcom-asia.kz";
        $sender_email = "sender@hope-survey.kz";
        $userId = $this->repository->getId();
        $form_subject = "Результаты опроса {$userId} c сайта";

        $message = $this->getEmailBody();

        $headers = "MIME-Version: 1.0" . PHP_EOL .
        "Content-Type: text/html; charset=utf-8" . PHP_EOL .
        'From: ' . $this->adopt($project_name) . ' <' . $sender_email . '>' . PHP_EOL .
        'Reply-To: ' . $admin_email . '' . PHP_EOL;

        return mail($admin_email, $this->adopt($form_subject), $message, $headers);
    }
}
