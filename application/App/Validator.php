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
        foreach ($data as $key => $value) {
            if ($value === "") {
                $errors[$key] = "Не может быть пустым .";
            }
        }
        return $errors;
    }
}
