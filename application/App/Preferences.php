<?php

declare(strict_types=1);

namespace App;

/**
 * This class contains the preferences for the application.
 *
 * @package App
 */
class Preferences
{
    /**
     * @var string
     */
    private $rootPath;
    private $surveysPath;

    /**
     * Preferences constructor.
     *
     * @param string $rootPath
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        $this->surveysPath = $rootPath . '/results';
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * @return string
     */
    public function getSurveysPath(): string
    {
        return $this->surveysPath;
    }
}
