<?php

namespace App;

use App\Surveys\SurveyCollections;
use App\Surveys\SurveyOne;

class Statistic
{
    
    /**
     * @var data
     */
    public $data;

    /**
     * @var SurveyCollections $surveyCollections
     */
    public $surveyCollections;

    /**
     * @var SurveyOne $survey
     */
    public $survey;

    /**
     * Statistic constructor.
     *
     */
    public function __construct()
    {
        $this->surveyCollections = $surveyCollections = new SurveyCollections();
        $this->data = $surveyCollections->getAllData();
        $this->survey = new SurveyOne();
    }

    /**
     * Find gender
     */
    public function findGender(string $sex)
    {
        $gender = mb_strtoupper($sex);
        
        if (mb_substr($gender, 0, 1) === "М" || mb_substr($gender, 0, 1) === "П") {
            $result = "male";
        } elseif (mb_substr($gender, 0, 1) === "Ж" || mb_substr($gender, 0, 1) === "Д") {
            $result = "female";
        } else {
            $result = "kvir";
        }

        return $result;
    }

    /**
     * Find orientation
     */
    public function findOrientation(string $orientation)
    {
        $orient = mb_strtoupper($orientation);
        
        if (stripos($orient, "ГЕТ") !== false || stripos($orient, "het") !== false) {
            $result = "hetero";
        } elseif (stripos($orient, "ГЕЙ") !== false || stripos($orient, "ГОМ") !== false || stripos($orient, "ЛЕ") !== false || mb_substr(mb_strtoupper($orient), 0, 1) === "Л" || stripos($orient, "ГЕИ") !== false || stripos($orient, "СВОЕГО") !== false) {
            $result = "homo";
        } elseif (stripos($orient, "БИ") !== false) {
            $result = "bi";
        } elseif (stripos($orient, "ПАН") !== false) {
            $result = "pan";
        } elseif (stripos($orient, "АС") !== false || stripos($orient, "НЕ") !== false) {
            $result = "as";
        } else {
            $result = "undef";
        }

        return $result;
    }

    /**
     *  Get all results
     *  */
    public function getAllResults()
    {
        $data = $this->data;
        $men = array_filter($data, function ($el) {
            return $this->findGender($el['sex']) === 'male';
        });
        $women = array_filter($data, function ($el) {
            return $this->findGender($el['sex']) === 'female';
        });
        $homo = array_filter($data, function ($el) {
            return $this->findOrientation($el['orientation']) === 'homo';
        });
        $hetero = array_filter($data, function ($el) {
            return $this->findOrientation($el['orientation']) === 'hetero';
        });
        $bi = array_filter($data, function ($el) {
            return $this->findOrientation($el['orientation']) === 'bi';
        });
        $pan = array_filter($data, function ($el) {
            return $this->findOrientation($el['orientation']) === 'pan';
        });
        $undef = array_filter($data, function ($el) {
            return $this->findOrientation($el['orientation']) === 'undef';
        });
        $as = array_filter($data, function ($el) {
            return $this->findOrientation($el['orientation']) === 'as';
        });

        $lgbt = array_merge($as, $bi, $homo, $pan);
        $lgbtMen = array_filter($lgbt, function ($el) {
            return $this->findGender($el['sex']) === 'male';
        });
        $lgbtWomen = array_filter($lgbt, function ($el) {
            return $this->findGender($el['sex']) === 'female';
        });
        $heteroMen = array_filter($hetero, function ($el) {
            return $this->findGender($el['sex']) === 'male';
        });
        $heteroWomen = array_filter($hetero, function ($el) {
            return $this->findGender($el['sex']) === 'female';
        });

        return [
            "all" => $this->data,
            "men" => $men,
            "women" => $women,
            "homo" => $homo,
            "hetero" => $hetero,
            "bi" => $bi,
            "pan" => $pan,
            "undef" => $undef,
            "as" => $as,
            "lgbt" => $lgbt,
            "lgbtMen" => $lgbtMen,
            "lgbtWomen" => $lgbtWomen,
            "heteroMen" => $heteroMen,
            "heteroWomen" => $heteroWomen,
        ];
    }

    /**
     * Count All results
     */
    public function countAllResults()
    {
        $all = $this->getAllResults();
        $result = array_map(function ($el) {
            return count($el);
        }, $all);

        return $result;
    }

    public function getMiddleResults(string $methodic, array $data)
    {

        $allScore = array_reduce($data, function ($carry, $elem) use ($methodic) {
            if (!$elem['data']['answers'][$methodic]) {
                return $carry;
            }
            $calculated = $this->survey->calculate($elem['data']['answers'][$methodic], $methodic);
            return $carry += $calculated['all'];
        }, 0);

        if (count($data) === 0) {
            return 0;
        }

        return round($allScore / count($data), 1);
    }

    public function getMiddleResultsMethodic6(array $data)
    {
        $allScore = array_reduce($data, function ($carry, $elem) {
            if (!$elem['data']['answers']['Methodic 6']) {
                return $carry;
            }
            $calculated = $this->survey->calculate($elem['data']['answers']['Methodic 6'], 'Methodic 6');
            $carry['all'] += $calculated['all'];
            $carry['s'] += $calculated['characteristics']['Сила']['score'];
            $carry['o'] += $calculated['characteristics']['Оценка']['score'];
            $carry['a'] += $calculated['characteristics']['Активность']['score'];
            return $carry;
        }, ['all' => 0, 's' => 0, 'o' => 0, 'a' => 0]);
        $qty = count($data);
        if ($qty === 0) {
            return ['all' => 0, 's' => 0, 'o' => 0, 'a' => 0];
        }
        $result = array_map(function ($el) use ($qty) {
            return round($el / $qty, 1);
        }, $allScore);
        return $result;
    }

    public function getAllMiddleResults($data, $selection = ["men", "women", "lgbt", "hetero", "lgbtMen", "lgbtWomen", "heteroMen", "heteroWomen"])
    {
        $result = array_map(function ($el) use ($data) {
            return [
                "Methodic 1" => $this->getMiddleResults("Methodic 1", $data[$el]),
                "Methodic 2" => $this->getMiddleResults("Methodic 2", $data[$el]),
                "Methodic 6" => $this->getMiddleResultsMethodic6($data[$el])
            ];
        }, $selection);

        return array_combine($selection, $result);
    }

    public function getComparisonMethodic3($data)
    {
        $selection = ['X', '0', '1', '2', '3', '4' , '5' , '6'];
        $result = array_map(function ($selected) use ($data) {
            $filteredData = array_filter($data['all'], function ($userResult) use ($selected) {
                return $userResult['data']['answers']['Methodic 3']['question-1'] === $selected;
            });
            $filteredWomen = array_filter($data['women'], function ($userResult) use ($selected) {
                return $userResult['data']['answers']['Methodic 3']['question-1'] === $selected;
            });
            $filteredMen = array_filter($data['men'], function ($userResult) use ($selected) {
                return $userResult['data']['answers']['Methodic 3']['question-1'] === $selected;
            });

            return [
                "Methodic 1" => [
                    "all" => $this->getMiddleResults("Methodic 1", $filteredData),
                    "women" => $this->getMiddleResults("Methodic 1", $filteredWomen),
                    "men" => $this->getMiddleResults("Methodic 1", $filteredMen),
                ],
                "Methodic 2" => [
                    "all" => $this->getMiddleResults("Methodic 2", $filteredData),
                    "women" => $this->getMiddleResults("Methodic 2", $filteredWomen),
                    "men" => $this->getMiddleResults("Methodic 2", $filteredMen),
                ]
            ];
        }, $selection);

        return array_combine($selection, $result);
    }

    public function getComparisonMethodic4($data)
    {
        $selection = ['1.5', '2.0', '2.5', '3.0', '3.5', '4.0' , '4.5', '5.0' , '5.5', '6.0', '6.5', '7.0'];
        $result = array_map(function ($selected) use ($data) {
            $filteredData = array_filter($data['all'], function ($userResult) use ($selected) {
                $calculated = array_reduce($userResult['data']['answers']["Methodic 4"], function ($carry, $el) {
                    if ($el === "Methodic 4") {
                        return $carry;
                    }
                    return $carry + $el;
                }, 1);
                return $calculated / 21 <= $selected && $calculated / 21 >= $selected - 0.5;
                // $calculated = $this->survey->calculate($userResult['data']['answers']["Methodic 4"], "Methodic 4");
                // return $calculated['all'] / 21 <= $selected;
            });
            $filteredWomen = array_filter($data['women'], function ($userResult) use ($selected) {
                $calculated = array_reduce($userResult['data']['answers']["Methodic 4"], function ($carry, $el) {
                    if ($el === "Methodic 4") {
                        return $carry;
                    }
                    return $carry + $el;
                }, 1);
                return $calculated / 21 <= $selected && $calculated / 21 >= $selected - 0.5;
                // $calculated = $this->survey->calculate($userResult['data']['answers']["Methodic 4"], "Methodic 4");
                // return $calculated['all'] / 21 <= $selected;
            });
            $filteredMen = array_filter($data['men'], function ($userResult) use ($selected) {
                $calculated = array_reduce($userResult['data']['answers']["Methodic 4"], function ($carry, $el) {
                    if ($el === "Methodic 4") {
                        return $carry;
                    }
                    return $carry + $el;
                }, 1);
                return $calculated / 21 <= $selected && $calculated / 21 >= $selected - 0.5;
                // $calculated = $this->survey->calculate($userResult['data']['answers']["Methodic 4"], "Methodic 4");
                // return $calculated['all'] / 21 <= $selected;
            });

            return [
                "Methodic 1" => [
                    "all" => $this->getMiddleResults("Methodic 1", $filteredData),
                    "women" => $this->getMiddleResults("Methodic 1", $filteredWomen),
                    "men" => $this->getMiddleResults("Methodic 1", $filteredMen),
                ],
                "Methodic 2" => [
                    "all" => $this->getMiddleResults("Methodic 2", $filteredData),
                    "women" => $this->getMiddleResults("Methodic 2", $filteredWomen),
                    "men" => $this->getMiddleResults("Methodic 2", $filteredMen),
                ]
            ];
        }, $selection);

        return array_combine($selection, $result);
    }

    public function getIndividualsMethodic5($data)
    {
        $result = array_reduce($data, function ($carry, $el) {
            $userResult = $el['data']['answers']['Methodic 5'];
            unset($userResult['userConclusion']);
            $filtered = array_filter($userResult, function ($innEl) {
                if (empty($innEl)) {
                    return false;
                }
                return !preg_match("(^[+±?-])", $innEl);
            });
            $sameResults = [];

            $reduced = array_reduce($filtered, function ($innCarry, $innEl) use (&$sameResults) {
                $prepared = trim(mb_ereg_replace("((^[Я])|(БУДУЩАЯ)|(БУДУЩИЙ)|([.]))", "", (trim(mb_strtoupper($innEl)))));

                if (in_array($prepared, $sameResults)) {
                    return $innCarry;
                }
                // print_r($sameResults);
                $sameResults[] = $prepared;
                if (isset($innCarry[$prepared])) {
                    $innCarry[$prepared] += 1;
                    return $innCarry;
                }
                $innCarry[$prepared] = 1;
                return $innCarry;
            }, $carry);
            return $reduced;
        }, []);
        unset($result['METHODIC 5']);
        arsort($result);
        return $result;
    }
}
