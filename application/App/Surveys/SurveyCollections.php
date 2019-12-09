<?php

namespace App\Surveys;

use App\Surveys\SurveyOne;

class SurveyCollections
{

    /**
     * @var SurveyOne $survey
     */
    public $survey;
 

    /**
     * SurveyResultsClass constructor.
     *
     */
    public function __construct()
    {
        $this->survey = new SurveyOne();
    }

    public function getAllResults()
    {
        $dir    = '../results';
        $files = scandir($dir, 1);

        $results = [];

        foreach ($files as $file) {
            if (is_file($dir . '/' . $file)) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $exploded = explode('_', $filename);
                $date = $exploded[0];
                $id = $exploded[1];
                $results[] = ["date" => $date, "id" => $id];
            }
        }
        return $results;
    }

    public function getResult(string $name)
    {
        $dir    = '../results';
        $files = scandir($dir, 1);

        foreach ($files as $file) {
            if (is_file($dir . '/' . $file)) {
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $exploded = explode('_', $filename);
                $date = $exploded[0];
                $id = $exploded[1];

                if ($id == $name) {
                    $content = file_get_contents($dir . '/' . $file);
                    return json_decode($content, true);
                }
            }
        }
        return ["ee"];
    }
}
