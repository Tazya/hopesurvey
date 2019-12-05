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
                    $errors[$key] = 'Вы должны описать характеристику';
                }
                if ($value !== "" && $data["select-" . $key] === "") {
                    $errors[$key] = "Вы должны дать оценку этой характеристике";
                }
                if ($value !== "" && substr($key, 0, 8) === "question") {
                    $counter += 1;
                }
            }
            if ($counter < 12) {
                $errors["min"] = "Вы должны заполнить как минимум 12 полей из 20";
            }
        }
        return $errors;
    }
}
