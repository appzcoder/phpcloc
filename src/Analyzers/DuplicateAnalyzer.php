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
        $totalLines = 0;
        $code = [];
        while ($file->valid()) {
            $currentLine = $file->fgets();
            $trimLine = trim($currentLine);

            // Ignoring the last new line
            if ($file->eof() && empty($trimLine)) {
                break;
            }

            $totalLines ++;

            if (!empty($trimLine)) {
                $code[] = $trimLine;
            }
        }

        $totalCode = count($code);
        $uniqueCode = array_unique($code);
        $totalUniqueCode = count($uniqueCode);
        $duplicate = $totalCode - $totalUniqueCode;

        if ($duplicate > 0) {
            $this->stats[$filename] = [
                'file' => $filename,
                'line' => $totalLines,
                'duplicate' => $duplicate,
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
