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
 * /src/Command/ApiKey/EditApiKeyCommand.php
 *
 */

namespace Platform\Command\ApiKey;

use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\DTO\ApiKey\ApiKey as ApiKeyDto;
use Platform\Entity\ApiKey as ApiKeyEntity;
use Platform\Form\Type\Console\ApiKeyType;
use Platform\Resource\ApiKeyResource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;


/**
 *
 */
#[AsCommand(
    name: self::NAME,
    description: 'Command to edit existing API key',
)]
class EditApiKeyCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'api-key:edit';

    public function __construct(
        private readonly ApiKeyResource $apiKeyResource,
        private readonly ApiKeyHelper $apiKeyHelper,
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
        $io = $this->getSymfonyStyle($input, $output);
        $apiKey = $this->apiKeyHelper->getApiKey($io, 'Which API key you want to edit?');
        $message = $apiKey instanceof ApiKeyEntity ? $this->updateApiKey($input, $output, $apiKey) : null;

        if ($input->isInteractive()) {
            $io->success($message ?? ['Nothing changed - have a nice day']);
        }

        return 0;
    }

    /**
     * Method to update specified API key via specified form.
     *
     * @return array<int, string>
     *
     * @throws Throwable
     */
    private function updateApiKey(InputInterface $input, OutputInterface $output, ApiKeyEntity $apiKey): array
    {
        // Load entity to DTO
        $dtoLoaded = new ApiKeyDto();
        $dtoLoaded->load($apiKey);

        /** @var FormHelper $helper */
        $helper = $this->getHelper('form');

        /** @var ApiKeyDto $dtoEdit */
        $dtoEdit = $helper->interactUsingForm(
            ApiKeyType::class,
            $input,
            $output,
            [
                'data' => $dtoLoaded,
            ]
        );

        // Patch API key
        $this->apiKeyResource->patch($apiKey->getId(), $dtoEdit);

        return $this->apiKeyHelper->getApiKeyMessage('API key updated - have a nice day', $apiKey);
    }
}
