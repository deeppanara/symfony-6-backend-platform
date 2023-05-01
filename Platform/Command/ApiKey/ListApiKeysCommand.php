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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Command/ApiKey/ListApiKeysCommand.php
 *
 */

namespace Platform\Command\ApiKey;

use Closure;
use Platform\Entity\ApiKey;
use Platform\Entity\UserGroup;
use Platform\Resource\ApiKeyResource;
use Platform\Security\RolesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function array_map;
use function implode;
use function sprintf;


/**
 *
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to list API keys',
)]
class ListApiKeysCommand extends Command
{
    final public const NAME = 'api-key:list';

    public function __construct(
        private readonly ApiKeyResource $apiKeyResource,
        private readonly RolesService $rolesService,
    ) {
        parent::__construct();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->write("\033\143");

        $headers = [
            'Id',
            'Token',
            'Description',
            'Groups',
            'Roles (inherited)',
        ];

        $io->title('Current API keys');
        $io->table($headers, $this->getRows());

        return 0;
    }

    /**
     * Getter method for formatted API key rows for console table.
     *
     * @return array<int, string>
     *
     * @throws Throwable
     */
    private function getRows(): array
    {
        return array_map(
            $this->getFormatterApiKey(),
            $this->apiKeyResource->find(orderBy: [
                'token' => 'ASC',
            ])
        );
    }

    /**
     * Getter method for API key formatter closure. This closure will format
     * single ApiKey entity for console table.
     */
    private function getFormatterApiKey(): Closure
    {
        $userGroupFormatter = static fn (UserGroup $userGroup): string => sprintf(
            '%s (%s)',
            $userGroup->getName(),
            $userGroup->getRole()->getId(),
        );

        return fn (ApiKey $apiToken): array => [
            $apiToken->getId(),
            $apiToken->getToken(),
            $apiToken->getDescription(),
            implode(",\n", $apiToken->getUserGroups()->map($userGroupFormatter)->toArray()),
            implode(",\n", $this->rolesService->getInheritedRoles($apiToken->getRoles())),
        ];
    }
}
