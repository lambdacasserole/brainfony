<?php

require_once 'Brainfuck.php';

/**
 * The complex underlying core Brainfony framework class.
 */
class Brainfony
{
    /**
     * Renders a program with the specified input to the page.
     * 
     * @param String $program Brainfuck code to interpret.
     * @param String $input The input to the program.
     */
    public static function render($program, $input = '')
    {
        $engine = new Brainfuck($program);
        echo $engine->run($input);
    }
    
    /**
     * Renders a program with the specified input to the page from a file.
     * 
     * @param String $program The path of the Brainfuck code file to interpret.
     * @param String $input The input to the program.
     */
    public static function renderFile($path, $input = '')
    {
        $file = fopen($path, 'r');
        $program = fread($file, filesize($path));
        self::render($program, $input);
    }
}
