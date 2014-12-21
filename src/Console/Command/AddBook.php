<?php

namespace SWP\Exchange\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddBook extends \Knp\Command\Command
{
    protected function configure()
    {
        $this
            ->setName("addbook")
            ->setDescription("Add a book to the repository");
        }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->getSilexApplication()['debug']) {
            $output->writeln("It works!");
        }
    }
}
