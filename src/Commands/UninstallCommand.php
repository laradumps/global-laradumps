<?php

namespace LaraDumps\GlobalLaraDumps\Commands;

use LaraDumps\GlobalLaraDumps\Traits\{AskConfirmation, PHPIni};
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'uninstall',
    description: 'Uninstall LaraDumps globally.',
    hidden: false
)]
class UninstallCommand extends Command
{
    use PHPIni;
    use AskConfirmation;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');

        if (!$this->askConfirmation($input, $output, 'Are you sure you want to remove Global LaraDumps?')) {
            return Command::SUCCESS;
        }

        if ($this->updateIniAutoPretendFile(strval($this->findPhpIniPath($input, $output)))) {
            $output->writeln('');
            $output->writeln('   ✅  <info>Global LaraDumps has been uninstalled.</info>');
            $output->writeln('');
            $output->writeln('  ⚠️  If you want to install, run <comment>global-laradumps install</comment>');
            $output->writeln('');

            return Command::SUCCESS;
        } else {
            $output->writeln('  ❌ Unable to update PHP ini. Check your access permissions.');
        }

        return Command::SUCCESS;
    }
}
