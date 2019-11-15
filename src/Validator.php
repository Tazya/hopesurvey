<?php

namespace App;

class Validator implements ValidatorInterface
{
    const OPTIONS = [
        'adults' => 'required'
    ];
    private $options = [];

    public function __construct(array $options = [])
    {
        $this->options = array_merge(self::OPTIONS, $options);
    }

    public function validate(array $date)
    {
        $errors = [];
        foreach ($date as $key => $value) {
            if (empty($value) && $this->options[$key] === 'required') {
                $errors[$key] = "{$key} Не может быть пустым";
            }
        }
        if ($date['date'] === '2019-09-10') {
            $errors['date'] = "2019-09-10? Сначала создайте машину времени";
        }
        if ($date['confirm'] == '') {
            $errors['confirm'] = "Напишите чтонибудь для отправки на сервер, иначе вам будет показываться цена";
        }
        if ($date['children'] < $date['adults']) {
            $errors['children_quantity'] = "Этот ответ не может быть больше, чем предыдущий";
        }
        return $errors;
    }
}