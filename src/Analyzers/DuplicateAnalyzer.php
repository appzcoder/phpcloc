<?php

namespace Appzcoder\PHPCloc\Analyzers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use SplFileObject;

/**
 * DuplicateAnalyzer.
 *
 * @author  Sohel Amin
 */
class DuplicateAnalyzer
{
    /**
     * Keep the stats.
     * @var array
     */
    protected $stats = [];

    /**
     * Constructor method.
     *
     * @param string $path
     * @param string $ext
     * @param string $exclude
     */
    public function __construct($path, $ext, $exclude)
    {
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $filterIterator = new RecursiveCallbackFilterIterator(
            $directoryIterator,
            function ($current, $key, $iterator) use ($ext, $exclude) {
                // Allow recursion
                if ($iterator->hasChildren() && !in_array($current->getFilename(), explode(',', $exclude))) {
                    return true;
                }

                if ($current->isFile() && in_array($current->getExtension(), explode(',', $ext))) {
                    return true;
                }

                return false;
            }
        );
        $files = new RecursiveIteratorIterator($filterIterator);

        foreach ($files as $file) {
            if ($file->isFile()) {
                $this->processLines($file->openFile());
            }
        }
    }

    /**
     * Process the lines from a file.
     *
     * @param  SplFileObject $file
     *
     * @return void
     */
    protected function processLines(SplFileObject $file)
    {
        $filename = $file->getPathname();

        $lines = [];
        $duplicates = [];
        foreach ($file as $line) {
            $trimLine = trim($line);
            $lineNo = ($file->key() + 1);

            if ($foundIndex = array_search($trimLine, array_column($lines, 'code'))) {
                $duplicates[] = $lines[$foundIndex]['lineNo'];
                $duplicates[] = $lineNo;
            }

            if (strlen($trimLine) > 3) {
                $lines[] = [
                    'lineNo' => $lineNo,
                    'code' => $trimLine,
                ];
            }
        }

        $totalDuplicates = count($duplicates);
        if ($totalDuplicates > 0) {
            sort($duplicates);
            $this->stats[$filename] = [
                'file' => $filename,
                'duplicate' => $totalDuplicates,
                'line_no' => implode(',', $duplicates),
            ];
        }
    }

    /**
     * Return the stats.
     *
     * @return array
     */
    public function stats()
    {
        return $this->stats;
    }
}
