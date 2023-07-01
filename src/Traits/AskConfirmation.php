<?php

namespace LaraDumps\GlobalLaraDumps\Traits;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

trait AskConfirmation
{
    protected function askConfirmation(InputInterface $input, OutputInterface $output, string $message): bool
    {
        if ($input->hasOption('interactive') && $input->getOption('interactive') === 'false') {
            return true;
        }

        $question = new ConfirmationQuestion('  👋️  ' . $message . ' <comment>(Y/n)</comment>', true);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        return boolval($helper->ask($input, $output, $question));
    }
}
