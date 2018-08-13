<?php

namespace pxgamer\Arionum\Console\Commands\Masternode;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ResumeCommand
 */
class ResumeCommand extends MasternodeCommand
{
    protected function configure()
    {
        $this
            ->setName('masternode:resume')
            ->setDescription('Resume the masternode mining.');

        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        try {
            $result = $this->sendCommand(self::COMMAND_VERSION_RESUME);

            $output->writeln('<info>Masternode resume command sent!</info>');
            $output->writeln('<info>ID: '.$result['data'].'</info>');
        } catch (\Exception $exception) {
            $output->writeln('<error>'.$exception->getMessage().'</error>');
        }
    }
}