<?php

namespace Appzcoder\PHPCloc\Commands;

use Appzcoder\PHPCloc\Analyzers\DuplicateAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableCell;
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
        if (count($stats) === 0) {
            $output->writeln('No files found.');
            return;
        }

        array_push(
            $stats,
            new TableSeparator(),
            [
                'Total',
                new TableCell(
                    array_reduce($stats, function ($carry, $item) {
                        return $carry + $item['duplicate'];
                    }),
                    ['colspan' => 2]
                ),
            ]
        );

        $table = new Table($output);
        $table
            ->setHeaders(['File', 'Duplicate', 'In Line(s)'])
            ->setRows($stats)
        ;
        $table->render();
    }
}
