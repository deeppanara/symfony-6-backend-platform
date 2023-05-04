<?php
/*
 * *************************************************************************
 * Copyright (C) 2023, Inc - All Rights Reserved
 * This file is part of the Dom bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author   Deep Panara <panaradeep@gmail.com>
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /ecs.php
 *
 * Configuration for `EasyCodingStandard` tool.
 *
 */

use PhpCsFixer\Fixer\ArrayNotation\NoMultilineWhitespaceAroundDoubleArrowFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;
use PhpCsFixer\Fixer\FunctionNotation\SingleLineThrowFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\NamespaceNotation\NoBlankLinesBeforeNamespaceFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\IncrementStyleFixer;
use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocAlignFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoPackageFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSummaryFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocToCommentFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->sets([SetList::PSR_12, SetList::CLEAN_CODE, SetList::COMMON]);

    $ruleConfigurations = [
        [
            IncrementStyleFixer::class,
            ['style' => 'post'],
        ],
        [
            CastSpacesFixer::class,
            ['space' => 'none'],
        ],
        [
            YodaStyleFixer::class,
            [
                'equal' => false,
                'identical' => false,
                'less_and_greater' => false,
            ],
        ],
        [
            ConcatSpaceFixer::class,
            ['spacing' => 'one'],
        ],
        [
            CastSpacesFixer::class,
            ['space' => 'none'],
        ],
        [
            OrderedImportsFixer::class,
            ['imports_order' => ['class', 'function', 'const']],
        ],
        [
            NoSuperfluousPhpdocTagsFixer::class,
            [
                'remove_inheritdoc' => false,
                'allow_mixed' => true,
                'allow_unused_params' => false,
            ],
        ],
        [
            DeclareEqualNormalizeFixer::class,
            ['space' => 'single'],
        ],
        [
            BlankLineBeforeStatementFixer::class,
            ['statements' => ['continue', 'declare', 'return', 'throw', 'try']],
        ],
        [
            BinaryOperatorSpacesFixer::class,
            ['operators' => ['&' => 'align']],
        ],
    ];

    array_map(static fn ($parameters) => $ecsConfig->ruleWithConfiguration(...$parameters), $ruleConfigurations);

    $ecsConfig->skip([
        NoMultilineWhitespaceAroundDoubleArrowFixer::class => null,
        PhpdocNoPackageFixer::class => null,
        PhpdocSummaryFixer::class => null,
        PhpdocSeparationFixer::class => null,
        BlankLineAfterOpeningTagFixer::class => null,
        ClassAttributesSeparationFixer::class => null,
        NoBlankLinesBeforeNamespaceFixer::class => null,
        NotOperatorWithSuccessorSpaceFixer::class => null,
        SingleLineThrowFixer::class => null,
        PhpdocAlignFixer::class => null,
        PhpdocToCommentFixer::class => null,
        NativeFunctionInvocationFixer::class => null,
    ]);
};
