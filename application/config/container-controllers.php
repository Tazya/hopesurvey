<?php

declare(strict_types=1);

use App\Controllers\ExceptionDemoController;
use App\Controllers\HelloController;
use App\Controllers\SurveyController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Preferences;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

return [
    ExceptionDemoController::class => function (ContainerInterface $container): ExceptionDemoController {
        return new ExceptionDemoController();
    },
    HelloController::class => function (ContainerInterface $container): HelloController {
        return new HelloController($container->get('view'), $container->get(LoggerInterface::class));
    },
    SurveyController::class => function (ContainerInterface $container): SurveyController {
        return new SurveyController($container->get('view'), $container->get(Preferences::class));
    },
    HomeController::class => function (ContainerInterface $container): HomeController {
        return new HomeController($container->get('view'), $container->get(Preferences::class));
    },
    AdminController::class => function (ContainerInterface $container): AdminController {
        return new AdminController($container->get('view'), $container->get(Preferences::class));
    }
];
