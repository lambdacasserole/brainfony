<?php

/**
 * PHP implementation of an interpreter for the Brainfuck programming language.
 */
class Brainfuck
{
    /**
     * The program this interpreter will execute.
     *
     * @var string
     */
    private $program;

    /**
     * The length of the program to execute.
     *
     * @var int
     */
    private $length;

    /**
     * The program instruction pointer.
     *
     * @var int
     */
    private $p;

    /**
     * The interpreter's memory storage.
     *
     * @var array
     */
    private $memory;

    /**
     * The program memory pointer.
     *
     * @var int
     */
    private $m;

    /**
     * The program input.
     *
     * @var string
     */
    private $input;

    /**
     * The program input pointer.
     *
     * @var int
     */
    private $i;

    /**
     * Initialises a new instance of an interpreter for the Brainfuck programming language.
     *
     * @param string $program   The program to execute.
     */
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
    }

    /**
     * Initialises the current memory cell if not initialised already.
     */
    private function init()
    {
        if (!isset($this->memory[$this->m])) {
            $this->memory[$this->m] = 0;
        }
    }

    /**
     * Moves the memory pointer to the next cell and initialises it if necessary.
     */
    private function next()
    {
        $this->m++;
        $this->init();
    }

    /**
     * Moves the memory pointer to the previous cell and initialises it if necessary.
     */
    private function previous()
    {
        $this->m--;
        $this->init();
    }

    /**
     * Jumps the program pointer to a matching character.
     *
     * @param bool $forward Whether or not to jump the pointer forward.
     * @param array $tokens The tokens and their jump values.
     */
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

    /**
     * Runs the program with the specified input, returning the output.
     *
     * @param string $input The program input.
     * @return string
     */
    public function run($input)
    {
        /*
         * Initialise input.
         */
        $this->input = $input;
        $this->i = 0;

        // Loop through instructions.
        $output = '';
        while ($this->p < $this->length) {
            switch ($this->program[$this->p]) {
                case '>':
                    // Move to next memory cell.
                    $this->next();
                    break;
                case '<':
                    // Move to previous memory cell.
                    $this->previous();
                    break;
                case '+';
                    // Increment current memory cell.
                    $this->memory[$this->m]++;
                    break;
                case '-';
                    // Decrement current memory cell.
                    $this->memory[$this->m]--;
                    break;
                case '[':
                    // If current memory cell is 0, jump past the matching ']' symbol.
                    if ($this->memory[$this->m] == 0) {
                        $this->jump(true, array('[' => 1, ']' => -1));
                    }
                    break;
                case ']':
                    // If current memory cell is not 0, jump back to the matching '[' symbol.
                    if ($this->memory[$this->m] != 0) {
                        $this->jump(false, array('[' => -1, ']' => 1));
                    }
                    break;
                case '.':
                    // Output character.
                    $output .= chr($this->memory[$this->m]);
                    break;
                case ',':
                    // Read character from input.
                    $this->memory[$this->m] = $this->read();
                    break;
                default:
                    // Do nothing.
            }
            $this->p++;
        }

        // Return result.
        return $output;
    }
}