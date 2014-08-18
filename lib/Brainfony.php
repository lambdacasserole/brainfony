<?php

require_once 'Brainfuck.php';

class Brainfony
{

    public static function render($program, $input)
    {
        $engine = new Brainfuck($program);
        echo $engine->run($input);
    }

    public static function renderFile($path, $input)
    {
        $file = fopen($path, 'r');
        $program = fread($file, filesize($path));
        self::render($program, $input);
    }

}