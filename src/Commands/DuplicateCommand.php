<?php

namespace Appzcoder\PHPCloc\Commands;

use Appzcoder\PHPCloc\Analyzers\DuplicateAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * DuplicateCommand.
 *
 * @author  Sohel Amin
 */
class DuplicateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('duplicate')
            ->setDescription('Check code duplicates.')
            ->addArgument('path', InputArgument::REQUIRED, 'The path to scan.')
            ->addOption(
                'ext',
                null,
                InputOption::VALUE_REQUIRED,
                'Which extension are you looking for?',
                'php'
            )
            ->addOption(
                'exclude',
                null,
                InputOption::VALUE_REQUIRED,
                'Dir(s) to exclude. eg. --exclude=vendor,node_modules'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $ext = $input->getOption('ext');
        $exclude = $input->getOption('exclude');

        $stats = (new DuplicateAnalyzer($path, $ext, $exclude))->stats();
        array_push(
            $stats,
            new TableSeparator(),
            [
                'Total',
                array_reduce($stats, function ($carry, $item) {
                    return $carry + $item['line'];
                }),
                array_reduce($stats, function ($carry, $item) {
                    return $carry + $item['duplicate'];
                }),
            ]
        );

        $table = new Table($output);
        $table
            ->setHeaders(['File', 'Line', 'Duplicate'])
            ->setRows($stats)
        ;
        $table->render();
    }
}
