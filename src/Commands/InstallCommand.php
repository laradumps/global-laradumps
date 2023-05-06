<?php

namespace LaraDumps\GlobalLaraDumps\Commands;

use LaraDumps\GlobalLaraDumps\Traits\{AskConfirmation, PHPIni};
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'install',
    description: 'Install LaraDumps globally.',
    hidden: false
)]
class InstallCommand extends Command
{
    use PHPIni;
    use AskConfirmation;

    protected function configure(): void
    {
        $this
            ->addOption(
                'interactive',
                'i',
                InputOption::VALUE_OPTIONAL,
                'Defines whether the installation will run without prompts',
                false
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $autoPrependFile = strval(realpath(__DIR__ . "/../scripts/global-laradumps-loader.php"));

        if ($currentPrependFile = ini_get('auto_prepend_file')) {
            $output->writeln('');

            if ($this->laraDumpsIsAlreadyInstalled($output, $currentPrependFile, $autoPrependFile)) {
                return Command::SUCCESS;
            }
        }

        $output->writeln('');

        if (!$this->askConfirmation($input, $output, 'Hello Dev!, Do you Confirm installing LaraDumps globally?')) {
            return Command::SUCCESS;
        }

        if ($currentPrependFile && !$this->askConfirmation($input, $output, 'There is already a file being loaded in auto_prepend_file, would you like to overwrite it?')) {
            return Command::SUCCESS;
        }

        if ($this->updateIniAutoPretendFile(strval($this->findPhpIniPath($input, $output)), $autoPrependFile)) {
            $this->displaySuccessfulInstallation($output);

            return Command::SUCCESS;
        } else {
            $output->writeln('');
            $output->writeln('  ❌  Unable to update PHP ini. Check your access permissions.');
            $output->writeln('');
        }

        $this->displaySuccessfulInstallation($output);

        return Command::SUCCESS;
    }

    protected function laraDumpsIsAlreadyInstalled(OutputInterface $output, string $currentPrependFile, string $autoPrependFile): bool
    {
        $output->writeln('');

        if ($currentPrependFile === $autoPrependFile) {
            $output->writeln('  ✅  <info>LaraDumps is already installed</info>');
            $output->writeln('');
            $output->writeln('  ⚠️  If you want to remove, run <comment>global-laradumps uninstall</comment>');
            $output->writeln('');

            return true;
        } else {
            $output->writeln('  ⚠️  <comment>Current auto_prepend_file: ' . $currentPrependFile . '</comment>');
            $output->writeln('');
        }

        return false;
    }

    protected function displaySuccessfulInstallation(OutputInterface $output): void
    {
        $output->writeln('');
        $output->writeln('  🤘  Global LaraDumps has been installed.');
        $output->writeln('');
    }
}
