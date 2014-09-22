<?php

/**
 * PHP implementation of an interpreter for the Brainfuck programming language by Urban Müller in 1993.
 */
class Brainfuck
{

    private $program;
    private $length;
    private $p;

    private $memory;
    private $m;

    private $input;
    private $i;

    public function __construct($program)
    {
        /*
         * Initialise program and instruction pointer.
         */
        $this->program = $program;
        $this->length = strlen($program);
        $this->p = 0;

        /*
         * Initialise memory.
         */
        $this->m = -1;
        $this->memory = array();
        $this->next();

        /*
         * Initialise loop stack.
         */
        $this->loops = array();
    }

    private function init()
    {
        /*
         * Initialise cell if not initialised already.
         */
        if (!isset($this->memory[$this->m])) {
            $this->memory[$this->m] = 0;
        }
    }

    private function next()
    {
        $this->m++;
        $this->init();
    }

    private function previous()
    {
        $this->m--;
        $this->init();
    }

    private function jump($forward, $tokens)
    {
        $l = 1;
        while ($l > 0) {
            $this->p += $forward ? 1 : -1;
            if (isset($tokens[$this->program[$this->p]])) {
                $l += $tokens[$this->program[$this->p]];
            }
        }
    }

    /**
     * Returns the next character from input, incrementing the input pointer by 1.
     *
     * If the end of the input has been reached, this method will return 0.
     *
     * @return int
     */
    private function read()
    {
        if (isset($this->input[$this->i])) {
            return $this->memory[$this->m] = ord($this->input[$this->i++]);
        }
        return 0;
    }

    public function run($input)
    {
        /*
         * Initialise input.
         */
        $this->input = $input;
        $this->i = 0;

        $output = '';
        while ($this->p < $this->length) {
            switch ($this->program[$this->p]) {
                case '>':
                    $this->next();
                    break;
                case '<':
                    $this->previous();
                    break;
                case '+';
                    $this->memory[$this->m]++;
                    break;
                case '-';
                    $this->memory[$this->m]--;
                    break;
                case '[':
                    if ($this->memory[$this->m] == 0) {
                        $this->jump(true, array('[' => 1, ']' => -1));
                    }
                    break;
                case ']':
                    if ($this->memory[$this->m] != 0) {
                        $this->jump(false, array('[' => -1, ']' => 1));
                    }
                    break;
                case '.':
                    $output .= chr($this->memory[$this->m]);
                    break;
                case ',':
                    $this->memory[$this->m] = $this->read();
                    break;
                default:
                    // Do nothing.
            }
            $this->p++;
        }
        return $output;
    }

}