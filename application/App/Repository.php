<?php

namespace App;

/**
 * This class contains the session for the application.
 *
 * @package App
 */
class Repository
{
    public function __construct()
    {
        session_start();
    }

    public function all()
    {
        return array_values($_SESSION);
    }

    public function findSurvey(string $name)
    {
        return $_SESSION['surveys'][$name];
    }

    public function destroySurvey(string $name)
    {
        unset($_SESSION['surveys'][$name]);
    }

    public function saveSurvey(array $survey)
    {
        // if (empty($survey['title']) || $survey['paid'] == '') {
        //     $json = json_encode($survey);
        //     throw new \Exception("Wrong data: {$survey}");
        // }
        if (!isset($item['name'])) {
            return false;
        }
        $_SESSION['surveys'][$survey['name']] = $survey;
        return $survey['surveys']['name'];
    }
}
