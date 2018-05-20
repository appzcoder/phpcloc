<?php

namespace Appzcoder\PHPCloc\Commands;

use Appzcoder\PHPCloc\Analyzers\ClocAnalyzer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * ClocCommand.
 *
 * @author  Sohel Amin
 */
class ClocCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('cloc')
            ->setDescription('Count the total lines of code.')
            ->addArgument('path', InputArgument::REQUIRED, 'The path to scan.')
            ->addOption(
                'ext',
                null,
                InputOption::VALUE_REQUIRED,
                'Which extension are you looking for?'
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

        $stats = array_values((new ClocAnalyzer($path, $ext, $exclude))->stats());
        array_push(
            $stats,
            new TableSeparator(),
            [
                'Total',
                array_reduce($stats, function ($carry, $item) {
                    return $carry + $item['files'];
                }),
                array_reduce($stats, function ($carry, $item) {
                    return $carry + $item['blank'];
                }),
                array_reduce($stats, function ($carry, $item) {
                    return $carry + $item['comment'];
                }),
                array_reduce($stats, function ($carry, $item) {
                    return $carry + $item['code'];
                }),
            ]
        );

        $table = new Table($output);
        $table
            ->setHeaders(['Language', 'files', 'blank', 'comment', 'code'])
            ->setRows($stats)
        ;
        $table->setColumnWidths([20, 10, 10, 10, 10]);
        $table->render();
    }
}
