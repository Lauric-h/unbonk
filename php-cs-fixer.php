<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$config = new Config();
$config->setParallelConfig(ParallelConfigFactory::detect());

return $config->setRules([
    '@Symfony' => true,
    '@Symfony:risky' => true,

    'declare_strict_types' => false,
    'native_function_invocation' => true,
    'protected_to_private' => false,
    'no_unused_imports' => true,

    'array_syntax' => [
        'syntax' => 'short',
    ],
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'no_multi_line',
    ],
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
    ],
    'concat_space' => [
        'spacing' => 'one',
    ],
    'binary_operator_spaces' => [
        'operators' => [
            '=>' => 'align_single_space_minimal',
        ],
    ],
    'doctrine_annotation_spaces' => [
        'before_argument_assignments' => true,
        'after_argument_assignments' => true,
    ],
])
    ->setRiskyAllowed(true)
    ->setFinder(
        Finder::create()
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/config')
            ->in(__DIR__.'/tests')
            ->exclude('var')
    );
