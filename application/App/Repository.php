<?php

namespace App;

/**
 * This class contains the session for the application.
 *
 * @package App
 */
class Repository
{
    public function initialize()
    {
        session_start();
    }

    public function all()
    {
        return array_values($_SESSION);
    }
    
    public function allAnswers()
    {
        return $_SESSION['answers'];
    }

    public function findAnswers(string $name)
    {
        return $_SESSION['answers'][$name];
    }

    public function destroyAnswers(string $name)
    {
        unset($_SESSION['answers'][$name]);
    }

    public function saveAnswers(array $answers)
    {
        // if (empty($survey['title']) || $survey['paid'] == '') {
        //     $json = json_encode($survey);
        //     throw new \Exception("Wrong data: {$survey}");
        // }
        if (!isset($answers['name'])) {
            return false;
        }
        $_SESSION['answers'][$answers['name']] = $answers;
        return $answers['answers']['name'];
    }
}
