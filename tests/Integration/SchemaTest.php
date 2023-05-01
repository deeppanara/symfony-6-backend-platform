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
 * @date     01/05/23, 12:13 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/SchemaTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Doctrine\DBAL\Types\EnumLanguageType;
use Platform\Doctrine\DBAL\Types\EnumLocaleType;
use Platform\Doctrine\DBAL\Types\EnumLogLoginType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function array_walk;
use function implode;

/**
 * Class SchemaTest
 *
 * @package App\Tests\Integration
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class SchemaTest extends KernelTestCase
{
    #[TestDox('Test that entity mappings are valid')]
    public function testThatMappingsAreValid(): void
    {
        $errors = $this->getValidator()->validateMapping();

        $messages = [];

        $formatter = static function (array $errors, string $className) use (&$messages): void {
            $messages[] = $className . ': ' . implode(', ', $errors);
        };

        array_walk($errors, $formatter);

        self::assertEmpty($errors, implode("\n", $messages));
    }

    #[TestDox('Test that database schema is sync with entity metadata')]
    public function testThatSchemaInSyncWithMetadata(): void
    {
        self::assertTrue(
            $this->getValidator()->schemaInSyncWithMetadata(),
            'The database schema is not in sync with the current mapping file.'
        );
    }

    private function getValidator(): SchemaValidator
    {
        self::bootKernel();

        if (!Type::hasType('EnumLanguage')) {
            Type::addType('EnumLanguage', EnumLanguageType::class);
        }

        if (!Type::hasType('EnumLocale')) {
            Type::addType('EnumLocale', EnumLocaleType::class);
        }

        if (!Type::hasType('EnumLogLogin')) {
            Type::addType('EnumLogLogin', EnumLogLoginType::class);
        }

        /** @var ManagerRegistry $managerRegistry */
        $managerRegistry = self::$kernel->getContainer()->get('doctrine');

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $managerRegistry->getManager();

        return new SchemaValidator($entityManager);
    }
}