<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector;
use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\CodingStyle\Rector\If_\NullableCompareToNullRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\ValueObject\PhpVersion;

$paths = [
    __DIR__ . '/../../config',
    __DIR__ . '/../../migrations',
    __DIR__ . '/../../modules',
    __DIR__ . '/../../tests',
    __DIR__ . '/../../web',
];

$skippedRules = [
    /* PATHS */
    __DIR__ . '/../../tests/_support/_generated',
    /* RULES */
    BooleanInBooleanNotRuleFixerRector::class,
    CatchExceptionNameMatchingTypeRector::class,
    DisallowedEmptyRuleFixerRector::class,
    ExplicitBoolCompareRector::class,
    FlipTypeControlToUseExclusiveTypeRector::class,
    NewlineAfterStatementRector::class,
    NewlineAfterStatementRector::class,
    NullableCompareToNullRector::class,
    SimplifyEmptyCheckOnEmptyArrayRector::class,
    SwitchNegatedTernaryRector::class,
];

return RectorConfig::configure()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
        rectorPreset: true,
        symfonyCodeQuality: true,
    )
    ->withPaths($paths)
    ->withPhpVersion(PhpVersion::PHP_84)
    ->withImportNames(removeUnusedImports: true)
    ->withSkip($skippedRules);
