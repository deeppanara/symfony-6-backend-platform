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
 * /src/Command/ApiKey/RemoveApiKeyCommand.php
 *
 */

namespace Platform\Command\ApiKey;

use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\Entity\ApiKey;
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
    description: 'Console command to remove existing API key',
)]
class RemoveApiKeyCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'api-key:remove';

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
        $apiKey = $this->apiKeyHelper->getApiKey($io, 'Which API key you want to remove?');
        $message = $apiKey instanceof ApiKey ? $this->delete($apiKey) : null;

        if ($input->isInteractive()) {
            $io->success($message ?? ['Nothing changed - have a nice day']);
        }

        return 0;
    }

    /**
     * @return array<int, string>
     *
     * @throws Throwable
     */
    private function delete(ApiKey $apiKey): array
    {
        $this->apiKeyResource->delete($apiKey->getId());

        return $this->apiKeyHelper->getApiKeyMessage('API key deleted - have a nice day', $apiKey);
    }
}
