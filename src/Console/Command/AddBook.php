<?php

namespace SWP\Exchange\Console\Command;

use SWP\Exchange\Command\AddBook as AddBookCommand;
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
        $app = $this->getSilexApplication();

        $command = new AddBookCommand(
            'iban',
            'whateva'
        );

        $app['commandBus']->handle($command);

        if (['debug']) {
        }
    }
}
