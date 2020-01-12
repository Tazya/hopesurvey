<?php

namespace App;

/**
 * This class contains the session for the application.
 *
 * @package App
 */
class Repository
{
    public function sessionInit()
    {
        $lifetime = 172800; // 48 hours
        session_start();
        setcookie(session_name(),session_id(),time()+$lifetime);
        $id = rand(100000, 999999);
        $_SESSION['id'] = substr(session_id(), 0, 8);
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

    public function setComplete(int $i = 1)
    {
        $_SESSION['complete'] = $i;
    }

    public function isComplete()
    {
        return isset($_SESSION['complete']);
    }

    public function getId()
    {
        return $_SESSION['id'];
    }
}
