<?php

namespace App;

use App\Surveys\SurveyCollections;

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
     * Statistic constructor.
     *
     */
    public function __construct()
    {
        $this->surveyCollections = $surveyCollections = new SurveyCollections();
        $this->data = $surveyCollections->getAllData();
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
     * Count All results
     */
    public function countAllResults()
    {
        $data = $this->data;
        $men = array_filter($data, function ($el) {
            return $this->findGender($el['sex']) === 'male';
        });
        $women = array_filter($data, function ($el) {
            return $this->findGender($el['sex']) === 'female';
        });

        return ["all" => count($this->data), "men" => count($men), "women" => count($women)];
    }
}
