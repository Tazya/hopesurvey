<?php

namespace App\Surveys;

class SurveyAbstractClass
{
    /**
     * @var questions
     */
    public $questions = [];

    /**
     * @var header
     */
    public $header = "Hope's Survey";

    /**
     * @var instructions
     */
    public $instructions = "";

    /**
     * SurveyAbstractClass constructor.
     *
     * @param questions $questions
     */
    public function __construct()
    {
        $this->questions = $questions;
        $this->questions = $header;
        $this->questions = $instructions;
    }
}
