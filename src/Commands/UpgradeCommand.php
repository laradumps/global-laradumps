<?php

namespace LaraDumps\GlobalLaraDumps\Commands;

use LaraDumps\GlobalLaraDumps\Traits\{AskConfirmation, PHPIni};
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'upgrade',
    description: 'Upgrade LaraDumps globally.',
    hidden: false
)]
class UpgradeCommand extends Command
{
    use PHPIni;
    use AskConfirmation;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');

        if (!$this->askConfirmation($input, $output, 'Hello Dev!, Do you Confirm upgrade LaraDumps globally?')) {
            return Command::SUCCESS;
        }

        echo passthru('composer global update laradumps/global-laradumps');
        echo passthru('global-laradumps install --interactive=false');

        $this->displaySuccessfulInstallation($output);

        return Command::SUCCESS;
    }

    protected function displaySuccessfulInstallation(OutputInterface $output): void
    {
        $output->writeln('');
        $output->writeln('  🤘  Global LaraDumps has been installed.');
        $output->writeln('');
    }
}
