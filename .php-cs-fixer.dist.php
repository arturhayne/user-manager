<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$rules = [
    '@Symfony' => true,
    '@PHP80Migration' => true,
    'linebreak_after_opening_tag' => true,
    'class_definition' => [
        'multi_line_extends_each_single_line' => true,
        'single_item_single_line' => true,
    ],
    'modernize_strpos' => true, // needs PHP 8+ or polyfill
    'concat_space' => ['spacing' => 'one'],
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'new_with_braces' => false,
    'no_superfluous_phpdoc_tags' => false,
    'ordered_imports' => true,
    'phpdoc_align' => false,
    'phpdoc_indent' => false,
    'phpdoc_annotation_without_dot' => false,
    'simplified_null_return' => true,
    'single_line_throw' => false,
    'single_trait_insert_per_statement' => false,
    'yoda_style' => false,
];

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/app',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->notName('Kernel.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$config = new Config();
$config
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true);

return $config;
