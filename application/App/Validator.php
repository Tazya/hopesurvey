<?php

namespace App;

class Validator
{
    private const OPTIONS = [
        'adults' => 'required'
    ];
    private $options = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge(self::OPTIONS, $options);
    }

    public function validate(array $data)
    {
        $errors = [];
        if ($data["name"] !== "Methodic 5") {
            foreach ($data as $key => $value) {
                if ($value === "") {
                    $errors[$key] = "Не может быть пустым .";
                }
            }
        } elseif ($data["name"] === "Methodic 5") {
            $counter = 0;
            foreach ($data as $key => $value) {
                if (substr($key, 0, 6) === "select" && $value !== "" && $data[substr($key, 7, strlen($key) - 6)] === "") {
                    $errors[$key] = 'Пожалуйста укажите ответ на вопрос';
                }
                if ($value !== "" && $data["select-" . $key] === "") {
                    $errors[$key] = "Пожалуйста дайте оценку этому ответу на вопрос";
                }
                if ($value !== "" && substr($key, 0, 8) === "question") {
                    $counter += 1;
                }
            }
            if ($counter < 12) {
                $errors["min"] = "Пожалуйста заполните как минимум 12 полей из 20";
            }
            if ($data["userConclusion"] == "") {
                $errors["userConclusion"] = "Пожалуйста, напишите нам пару слов";
            }
        }

        if ($data["name"] === "final") {
            if ((int) trim($data["age"]) < 16 || (int) trim($data["age"]) > 55) {
                $errors["incorrect_age"] = "Допустимый интервал возраста: От 16 до 55 лет";
            }
        }

        return $errors;
    }
}
