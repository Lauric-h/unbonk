<?php



use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        'src',
        'tests',
    ])
    ->withSets([
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
        PHPUnitSetList::PHPUNIT_100
    ])
    ->withAttributesSets(symfony: true)
    ->withRules([
        AddTypeToConstRector::class,
        ClosureToArrowFunctionRector::class,
        StaticClosureRector::class,
        StaticArrowFunctionRector::class,
    ])
    ->withPreparedSets(phpunitCodeQuality: true, symfonyCodeQuality: true);
