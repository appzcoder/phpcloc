<?php

namespace Appzcoder\PHPCloc\Analyzers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveCallbackFilterIterator;
use SplFileObject;

/**
 * ClocAnalyzer.
 *
 * @author  Sohel Amin
 */
class ClocAnalyzer
{
    /**
     * Keep the stats.
     * @var array
     */
    protected $stats = [];

    /**
     * Language extensions.
     * @var array
     */
    protected $extensions = [
        'as'          =>   'ActionScript',
        'ada'         =>   'Ada',
        'adb'         =>   'Ada',
        'ads'         =>   'Ada',
        'Ant'         =>   'Ant',
        'adoc'        =>   'AsciiDoc',
        'asciidoc'    =>   'AsciiDoc',
        'asm'         =>   'Assembly',
        'S'           =>   'Assembly',
        's'           =>   'Assembly',
        'awk'         =>   'Awk',
        'bat'         =>   'Batch',
        'btm'         =>   'Batch',
        'bb'          =>   'BitBake',
        'cbl'         =>   'COBOL',
        'cmd'         =>   'Batch',
        'bash'        =>   'BASH',
        'sh'          =>   'Bourne Shell',
        'c'           =>   'C',
        'carp'        =>   'Carp',
        'csh'         =>   'C Shell',
        'ec'          =>   'C',
        'erl'         =>   'Erlang',
        'hrl'         =>   'Erlang',
        'pgc'         =>   'C',
        'capnp'       =>   'Cap\'n Proto',
        'chpl'        =>   'Chapel',
        'cs'          =>   'C#',
        'clj'         =>   'Clojure',
        'coffee'      =>   'CoffeeScript',
        'cfm'         =>   'ColdFusion',
        'cfc'         =>   'ColdFusion CFScript',
        'cmake'       =>   'CMake',
        'cc'          =>   'C++',
        'cpp'         =>   'C++',
        'cxx'         =>   'C++',
        'pcc'         =>   'C++',
        'c++'         =>   'C++',
        'cr'          =>   'Crystal',
        'css'         =>   'CSS',
        'cu'          =>   'CUDA',
        'd'           =>   'D',
        'dart'        =>   'Dart',
        'dtrace'      =>   'DTrace',
        'dts'         =>   'Device Tree',
        'dtsi'        =>   'Device Tree',
        'e'           =>   'Eiffel',
        'elm'         =>   'Elm',
        'el'          =>   'LISP',
        'exp'         =>   'Expect',
        'ex'          =>   'Elixir',
        'exs'         =>   'Elixir',
        'feature'     =>   'Gherkin',
        'fish'        =>   'Fish',
        'fr'          =>   'Frege',
        'fst'         =>   'F*',
        'F#'          =>   'F#',
        'GLSL'        =>   'GLSL',
        'vs'          =>   'GLSL',
        'shader'      =>   'HLSL',
        'cg'          =>   'HLSL',
        'cginc'       =>   'HLSL',
        'hlsl'        =>   'HLSL',
        'lean'        =>   'Lean',
        'hlean'       =>   'Lean',
        'lgt'         =>   'Logtalk',
        'lisp'        =>   'LISP',
        'lsp'         =>   'LISP',
        'lua'         =>   'Lua',
        'ls'          =>   'LiveScript',
        'sc'          =>   'LISP',
        'f'           =>   'FORTRAN Legacy',
        'f77'         =>   'FORTRAN Legacy',
        'for'         =>   'FORTRAN Legacy',
        'ftn'         =>   'FORTRAN Legacy',
        'pfo'         =>   'FORTRAN Legacy',
        'f90'         =>   'FORTRAN Modern',
        'f95'         =>   'FORTRAN Modern',
        'f03'         =>   'FORTRAN Modern',
        'f08'         =>   'FORTRAN Modern',
        'go'          =>   'Go',
        'groovy'      =>   'Groovy',
        'gradle'      =>   'Groovy',
        'h'           =>   'C Header',
        'hs'          =>   'Haskell',
        'hpp'         =>   'C++ Header',
        'hh'          =>   'C++ Header',
        'html'        =>   'HTML',
        'hx'          =>   'Haxe',
        'hxx'         =>   'C++ Header',
        'idr'         =>   'Idris',
        'il'          =>   'SKILL',
        'io'          =>   'Io',
        'ipynb'       =>   'Jupyter Notebook',
        'jai'         =>   'JAI',
        'java'        =>   'Java',
        'js'          =>   'JavaScript',
        'jl'          =>   'Julia',
        'json'        =>   'JSON',
        'jsx'         =>   'JSX',
        'kt'          =>   'Kotlin',
        'lds'         =>   'LD Script',
        'less'        =>   'LESS',
        'Objective-C' =>   'Objective-C',
        'Matlab'      =>   'MATLAB',
        'Mercury'     =>   'Mercury',
        'md'          =>   'Markdown',
        'markdown'    =>   'Markdown',
        'nix'         =>   'Nix',
        'nsi'         =>   'NSIS',
        'nsh'         =>   'NSIS',
        'nu'          =>   'Nu',
        'ML'          =>   'OCaml',
        'ml'          =>   'OCaml',
        'mli'         =>   'OCaml',
        'mll'         =>   'OCaml',
        'mly'         =>   'OCaml',
        'mm'          =>   'Objective-C++',
        'maven'       =>   'Maven',
        'makefile'    =>   'Makefile',
        'mustache'    =>   'Mustache',
        'm4'          =>   'M4',
        'l'           =>   'lex',
        'nim'         =>   'Nim',
        'php'         =>   'PHP',
        'pas'         =>   'Pascal',
        'PL'          =>   'Perl',
        'pl'          =>   'Perl',
        'pm'          =>   'Perl',
        'plan9sh'     =>   'Plan9 Shell',
        'pony'        =>   'Pony',
        'ps1'         =>   'PowerShell',
        'text'        =>   'Plain Text',
        'txt'         =>   'Plain Text',
        'polly'       =>   'Polly',
        'proto'       =>   'Protocol Buffers',
        'py'          =>   'Python',
        'pxd'         =>   'Cython',
        'pyx'         =>   'Cython',
        'r'           =>   'R',
        'R'           =>   'R',
        'raml'        =>   'RAML',
        'Rebol'       =>   'Rebol',
        'red'         =>   'Red',
        'Rmd'         =>   'RMarkdown',
        'rake'        =>   'Ruby',
        'rb'          =>   'Ruby',
        'rkt'         =>   'Racket',
        'rhtml'       =>   'Ruby HTML',
        'rs'          =>   'Rust',
        'rst'         =>   'ReStructuredText',
        'sass'        =>   'Sass',
        'scala'       =>   'Scala',
        'scss'        =>   'Sass',
        'scm'         =>   'Scheme',
        'sed'         =>   'sed',
        'stan'        =>   'Stan',
        'sml'         =>   'Standard ML',
        'sol'         =>   'Solidity',
        'sql'         =>   'SQL',
        'swift'       =>   'Swift',
        't'           =>   'Terra',
        'tex'         =>   'TeX',
        'thy'         =>   'Isabelle',
        'tla'         =>   'TLA',
        'sty'         =>   'TeX',
        'tcl'         =>   'Tcl/Tk',
        'toml'        =>   'TOML',
        'ts'          =>   'TypeScript',
        'mat'         =>   'Unity-Prefab',
        'prefab'      =>   'Unity-Prefab',
        'Coq'         =>   'Coq',
        'vala'        =>   'Vala',
        'Verilog'     =>   'Verilog',
        'csproj'      =>   'MSBuild script',
        'vcproj'      =>   'MSBuild script',
        'vim'         =>   'VimL',
        'xml'         =>   'XML',
        'XML'         =>   'XML',
        'xsd'         =>   'XSD',
        'xsl'         =>   'XSLT',
        'xslt'        =>   'XSLT',
        'wxs'         =>   'WiX',
        'yaml'        =>   'YAML',
        'yml'         =>   'YAML',
        'y'           =>   'Yacc',
        'zep'         =>   'Zephir',
        'zsh'         =>   'Zsh',
    ];

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

                if (!$ext || ($current->isFile() && in_array($current->getExtension(), explode(',', $ext)))) {
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
        $extension = strtolower($file->getExtension());

        $totalLines = 0;
        $totalBlankLines = 0;
        $totalComments = 0;
        $isMultilines = false;
        while ($file->valid()) {
            $currentLine = $file->fgets();
            $trimLine = trim($currentLine);

            // Ignoring the last new line
            if ($file->eof() && empty($trimLine)) {
                break;
            }

            $totalLines ++;
            if (empty($trimLine)) {
                $totalBlankLines ++;
            }

            // Detecting comments
            if (strpos($trimLine, '//') === 0
                || strpos($trimLine, '#') === 0) {
                $totalComments ++;
            }

            // Detecting multilines comments
            if (strpos($trimLine, '/*') === 0) {
                $isMultilines = true;
            }
            if ($isMultilines) {
                $totalComments ++;
            }
            if (strpos($trimLine, '*/') === 0) {
                $isMultilines = false;
            }
        }

        if (!isset($this->extensions[$extension])) {
            return;
        }

        $this->setStats($extension, [
            'language' => $this->extensions[$extension],
            'files' => 1,
            'blank' => $totalBlankLines,
            'comment' => $totalComments,
            'code' => $totalLines - ($totalBlankLines + $totalComments),
        ]);
    }

    /**
     * Set or increment the stat.
     *
     * @param string $extension
     * @param array  $stat
     *
     * @return void
     */
    protected function setStats($extension, $stat)
    {
        if (isset($this->stats[$extension])) {
            $this->stats[$extension]['files'] += $stat['files'];
            $this->stats[$extension]['blank'] += $stat['blank'];
            $this->stats[$extension]['comment'] += $stat['comment'];
            $this->stats[$extension]['code'] += $stat['code'];
        } else {
            $this->stats[$extension] = $stat;
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
