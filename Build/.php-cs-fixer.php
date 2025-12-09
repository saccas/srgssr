<?php

declare(strict_types=1);

use TYPO3\CodingStandards\CsFixerConfig;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/../',
    ])
    ->exclude([
        '.Build',
        'var',
        'config',
    ]);

return CsFixerConfig::create()
    ->addRules([
        'fully_qualified_strict_types' => [
            'import_symbols' => true,
            'leading_backslash_in_global_namespace' => false,
        ],
        'global_namespace_import' => false,
        'header_comment' => ['header' => ''],
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_line_empty_body' => false,
        'no_trailing_comma_in_singleline_array' => true,
        'php_unit_test_annotation' => ['style' => 'annotation'],
    ])
    ->setFinder($finder);
