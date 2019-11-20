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
            if (empty($value) && $this->options[$key] === 'required') {
                $errors[$key] = "{$key} Не может быть пустым";
            }
        }
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $errors[$key] = "{$key} Не может быть пустым";
            }
        }
        return $errors;
    }
}
