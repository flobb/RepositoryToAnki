<?php

namespace R2A\Command;

use R2A\Application;
use R2A\Repository\RepositoryManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends Command
{
    protected function configure()
    {
        $this
            ->setName('export')
            ->setDescription('Get or update a repository and export the cards in a file for Anki')
            ->addArgument(
                'repository',
                InputArgument::REQUIRED,
                'Git repository url'
            )
            ->addOption(
                'storage',
                's',
                InputOption::VALUE_OPTIONAL,
                'Path to the folder to store the repository',
                null
            )
            ->addOption(
                'export',
                'e',
                InputOption::VALUE_OPTIONAL,
                'Path to the folder where the file will be created',
                null
            )
            ->addOption(
                'name',
                'z',
                InputOption::VALUE_OPTIONAL,
                'Name of the file that will be created',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Application $app */
        $app = $this->getApplication();

        $repositoryUrl = $input->getArgument('repository');
        $storage = $input->getOption('storage');
        $export = $input->getOption('export');
        $name = $input->getOption('name');

        if (empty($storage)) {
            $storage = $app->getAppPath().$app->getParameter('storage');
        }

        if (empty($export)) {
            $export = $app->getAppPath().$app->getParameter('export');
        }

        if (empty($name)) {
            $name = basename($repositoryUrl, '.git').'.csv';
        }

        $repositoryPath = $storage.'/'.basename($repositoryUrl, '.git');
        $manager = new RepositoryManager($repositoryPath, $repositoryUrl);

        if (!file_exists($repositoryPath)) {
            $output->writeln('<info>Cloning...</info>');
            $manager->cloneRepository();
        } else {
            $output->writeln('<info>Pulling...</info>');
            $manager->update();
        }

        $output->writeln('<info>Done');
        $output->writeln('<info>Exporting...</info>');

        $manager->export($export, $name, $app->getParameter('csvDelimiter'), $app->getParameter('csvEnclosure'));

        $output->writeln('<info>Done</info>');
    }
}
