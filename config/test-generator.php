<?php

return [
    'formators' => [
        'feature' => \AUnhurian\LaravelTestGenerator\Formators\FeatureFormator::class,
        'unit' => \AUnhurian\LaravelTestGenerator\Formators\UnitFormator::class,
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
