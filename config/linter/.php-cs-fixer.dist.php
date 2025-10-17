<?php

/** @noinspection SpellCheckingInspection */

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

/**
 * PHP CS Fixer configuration file.
 *
 * Defines coding standards and formatting rules for the project.
 * Adjusts file search paths, excluded directories, and specific fixer rules.
 * For more information on available rules, see: https://cs.symfony.com/doc/ruleSets/index.html
 */
$finder = Finder::create()
    ->in(__DIR__ . '/../../')
    ->notPath([
        'tests/_output',
        'tests/_support/_generated',
        'tests/bin',
    ])
    ->exclude([
        'runtime',
        'vendor',
        'web',
    ]);

return new Config()
    ->setCacheFile(__DIR__ . '/../../runtime/linter/.php_cs.cache')
    ->setRules([
        '@PhpCsFixer' => true,
        '@PHP81Migration' => true,
        'ereg_to_preg' => true,                              /* Replace deprecated ereg functions with preg */
        'no_alias_functions' => true,                        /* Use main functions instead of aliases */
        'random_api_migration' => true,                      /* Replace rand, srand, getrandmax with mt_* or random_int */
        'set_type_to_cast' => true,                          /* Use type cast instead of settype */
        'non_printable_character' => true,                   /* Remove zero-width space, NBSP, and other invisible Unicode characters */
        'octal_notation' => false,                           /* Use 0o* notation for octal numbers */
        'cast_spaces' => ['space' => 'none'],                /* No space between cast and variable */
        'modernize_types_casting' => true,                   /* Replace *val functions with type cast operators */
        'ordered_class_elements' => [/* Order elements in classes/interfaces/traits/enums */
            'order' => [
                'use_trait',
            ],
        ],
        'protected_to_private' => false,                     /* Convert protected to private where possible */
        'comment_to_phpdoc' => [/* Comments with annotation should be DocBlock */
            'ignored_tags' => [
                'codeCoverageIgnoreStart',
                'codeCoverageIgnoreEnd',
            ],
        ],
        'single_line_comment_style' => [/* Single line comments should start with // */
            'comment_types' => [
                'hash',
            ],
        ],
        'no_superfluous_elseif' => false,                    /* Replace unnecessary elseif with if */
        'no_unneeded_control_parentheses' => false,          /* Remove unnecessary parentheses around control statements */
        'fopen_flag_order' => true,                          /* Flags in fopen should be ordered (b and t last) */
        'method_argument_space' => [/* Spaces after comma in method arguments and calls */
            'on_multiline' => 'ignore',                      /* How to handle multiline argument lists */
        ],
        'global_namespace_import' => [/* Import or fully qualify global classes/functions/constants */
            'import_classes' => true,
            'import_constants' => false,
            'import_functions' => false,
        ],
        'dir_constant' => true,                              /* Replace dirname(__FILE__) with __DIR__ */
        'is_null' => true,                                   /* Replace is_null($var) with null === $var */
        'no_homoglyph_names' => true,                        /* Replace accidental use of homoglyphs in names */
        'assign_null_coalescing_to_coalesce_equal' => false, /* Use ??= operator where possible */
        'concat_space' => [/* Concatenation spacing configuration */
            'spacing' => 'one',
        ],
        'logical_operators' => true,                         /* Use && and || instead of and and or */
        'no_useless_concat_operator' => true,                /* No useless concat operations */
        'echo_tag_syntax' => ['format' => 'short'],          /* Echo tag format */
        'no_superfluous_phpdoc_tags' => [/* Remove useless @param/@var/@return tags */
            'allow_mixed' => true,
            'remove_inheritdoc' => true,
        ],
        'phpdoc_align' => [/* Align phpdoc tags left or vertically */
            'tags' => [
                'method',
                'param',
                'property',
                'property-read',
                'property-write',
                'return',
                'throws',
                'type',
                'var',
            ],
            'align' => 'left',
        ],
        'phpdoc_order' => [/* Order phpdoc annotations */
            'order' => ['param', 'return', 'throws'],
        ],
        'phpdoc_to_comment' => [/* Allow single-line phpDoc on elements */
            'ignored_tags' => ['var'],
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',                   /* No multiline whitespace before semicolon */
        ],
        'semicolon_after_instruction' => false,              /* Instructions must end with semicolon */
        'strict_comparison' => true,                         /* Use strict comparison */
        'blank_line_before_statement' => [/* Blank line before listed statements */
            'statements' => [
                'break',
                'case',
                'continue',
                'declare',
                'default',
                'exit',
                'goto',
                'return',
                'switch',
                'throw',
                'try',
            ],
        ],
        'blank_line_between_import_groups' => true,          /* Insert blank lines between use groups */
        'lambda_not_used_import' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
