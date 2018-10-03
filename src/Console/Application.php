<?php

namespace pxgamer\Arionum\Console;

use pxgamer\Arionum\Console\Output\Factory;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 */
class Application extends BaseApplication
{
    const NAME = 'Arionum Light Wallet';
    const VERSION = '@git-version@';

    /**
     * @var Factory
     */
    private $outputFactory;

    /**
     * Application constructor.
     *
     * @param null|string $name
     * @param null|string $version
     */
    public function __construct(?string $name = null, ?string $version = null)
    {
        $this->outputFactory = new Factory();

        parent::__construct(
            $name ?: static::NAME,
            $version ?: (static::VERSION === '@'.'git-version@' ? 'source' : static::VERSION)
        );
    }

    /**
     * @return array|\Symfony\Component\Console\Command\Command[]
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        // General Commands
        $commands[] = new Commands\BalanceCommand();
        $commands[] = new Commands\BlockCommand();
        $commands[] = new Commands\DecryptCommand();
        $commands[] = new Commands\EncryptCommand();
        $commands[] = new Commands\ExportCommand();
        $commands[] = new Commands\GenerateCommand();
        $commands[] = new Commands\MinerCommand();
        $commands[] = new Commands\SendCommand();
        $commands[] = new Commands\TransactionCommand();
        $commands[] = new Commands\TransactionsCommand($this->outputFactory);

        // Alias Commands
        $commands[] = new Commands\Alias\SendCommand();
        $commands[] = new Commands\Alias\SetCommand();

        // Masternode Commands
        $commands[] = new Commands\Masternode\CreateCommand();
        $commands[] = new Commands\Masternode\PauseCommand();
        $commands[] = new Commands\Masternode\ReleaseCommand();
        $commands[] = new Commands\Masternode\ResumeCommand();

        return $commands;
    }
}
