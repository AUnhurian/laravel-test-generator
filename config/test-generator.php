<?php

use AUnhurian\LaravelTestGenerator\Enums\FormatorTypes;

return [
    'formators' => [
        FormatorTypes::FEATURE->value => \AUnhurian\LaravelTestGenerator\Formators\FeatureFormator::class,
        FormatorTypes::UNIT->value => \AUnhurian\LaravelTestGenerator\Formators\UnitFormator::class,
    ],
    'list_methods' => [
        'exclude' =>   [
            "__construct",
            "middleware",
            "getMiddleware",
            "callAction",
            "__call",
            "authorize",
            "authorizeForUser",
            "parseAbilityAndArguments",
            "normalizeGuessedAbilityName",
            "authorizeResource",
            "resourceAbilityMap",
            "resourceMethodsWithoutModels",
            "validateWith",
            "validate",
            "validateWithBag",
            "getValidationFactory"
        ],
    ],
];
