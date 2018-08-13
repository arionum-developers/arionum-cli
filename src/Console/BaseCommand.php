<?php

namespace pxgamer\Arionum\Console;

use pxgamer\Arionum\Api;
use pxgamer\Arionum\Wallet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class BaseCommand
 */
class BaseCommand extends Command
{
    /**
     * @var QuestionHelper
     */
    protected $questionHelper;
    /**
     * @var Wallet
     */
    protected $wallet;

    /**
     * @var bool
     */
    protected $requiresExistingWallet = true;

    protected function configure()
    {
        $this->addOption(
            'peer',
            null,
            InputOption::VALUE_REQUIRED,
            'A custom peer to use for API calls.'
        );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->questionHelper = $this->getHelper('question');

        // Set a custom peer if it's been provided
        Api::$customPeer = $input->getOption('peer');

        $this->wallet = new Wallet();

        if ($this->requiresExistingWallet && !$this->wallet->exists()) {
            throw new \Exception('A wallet file is required for this command.');
        }

        if ($this->wallet->exists()) {
            $this->decryptWallet($input, $output);
            $this->wallet->decode();

            $output->writeln('<info>Your address is: '.$this->wallet->getAddress().'</info>');
            $output->writeln('');
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string|null     $message
     * @return mixed
     */
    protected function askForPassword(InputInterface $input, OutputInterface $output, string $message = null)
    {
        $passwordQuestion = new Question($message ?? 'Enter your password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);

        return $this->questionHelper->ask($input, $output, $passwordQuestion);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function decryptWallet(InputInterface $input, OutputInterface $output)
    {
        if ($this->wallet->isEncrypted()) {
            $output->writeln('This wallet is encrypted.');
            do {
                $password = $this->askForPassword($input, $output);

                $this->wallet->decrypt($password);

                if (!$this->wallet->isEncrypted()) {
                    break;
                }

                $output->writeln('<error>Invalid password!</error>');
            } while (true);
        }
    }

    /**
     * This command supports output file formats.
     */
    protected function supportsOutput()
    {
        $this->addOption('output-json', null, InputOption::VALUE_NONE, 'Output to a JSON file.', false);
        $this->addOption('output-xml', null, InputOption::VALUE_NONE, 'Output to an XML file.', false);
    }
}
