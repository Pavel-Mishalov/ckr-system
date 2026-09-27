<?php

use App\Main;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

require __DIR__ . '/../vendor/autoload.php';

$app = new Main;

try {
    $app->run();
} catch (LoaderError $e) {
    echo LoaderError::class;
} catch (RuntimeError $e) {
    echo RuntimeError::class;
} catch (SyntaxError $e) {
    echo SyntaxError::class;
}
