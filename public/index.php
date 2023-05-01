<?php

declare(strict_types = 1);

ini_set('suhosin.executor.disable_eval', false);

use App\Kernel;
use Liuggio\Fastest\Environment\FastestEnvironment;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return static function (array $context): Kernel {
    chdir(dirname(__DIR__));

    if (class_exists(FastestEnvironment::class)) {
        FastestEnvironment::setFromRequest();
    }

    return new Kernel($context['APP_ENV'], (bool)$context['APP_DEBUG']);
};
