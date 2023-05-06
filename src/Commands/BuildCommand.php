<?php

namespace LaraDumps\GlobalLaraDumps\Commands;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'build',
    description: 'Build the LaraDumps Phar',
    hidden: false
)]
class BuildCommand extends Command
{
    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Building phars...');

        $temporaryDestination = __DIR__ . "/../../phar-generator/laradumps.phar";
        $phpVersion           = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

        $finalDestination = __DIR__ . "/../../phars/laradumps_{$phpVersion}.phar";

        if (!file_exists($temporaryDestination)) {
            throw new Exception('Could not generate phar');
        }

        $result = rename(
            $temporaryDestination,
            $finalDestination
        );

        if (!$result) {
            throw new Exception('Could not generate phar');
        }

        $output->writeln('Successfully built LaraDumps Phar.');

        return Command::SUCCESS;
    }
}
