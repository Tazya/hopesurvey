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
                $fileData = $this->getResult($id);
                $userName = $fileData['userName'];
                $sex = $fileData['answers']['final']['gender'];
                $new = $this->isNew($id);
                $results[] = ["date" => $date, "id" => $id, "new" => $new, 'userName' => $userName, 'sex' => $sex];
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

    public function setViewed(string $name)
    {
        if (!$this->isNew($name)) {
            return false;
        }

        $file = "../data/viewed.txt";
        if (is_file($file)) {
            $fd = fopen($file, 'a');
            fwrite($fd, $name . ",");
            fclose($fd);
            return true;
        }
        return false;
    }

    public function isNew(string $name)
    {
        $file = "../data/viewed.txt";
        if (is_file($file)) {
            $content = file_get_contents($file);
            $data = explode(',', $content);
            return !in_array($name, $data);
        }
        return false;
    }
}
