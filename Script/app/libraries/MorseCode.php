<?php

namespace Fir\Libraries;

class MorseCode {

    /**
     * The string to be manipulated
     * @var
     */
    private $string;

    public function __construct($string) {
        $this->string = $string;
    }

    /**
     * A list with all the available characters
     *
     * @return  array
     */
    private static function getList() {
        return array(' ' => '/ ', 'a' => '.- ', 'b' => '-... ', 'c' => '-.-. ', 'd' => '-.. ', 'e' => '. ', 'f' => '..-. ', 'g' => '--. ', 'h' => '.... ', 'i' => '.. ', 'j' => '.--- ', 'k' => '-.- ', 'l' => '.-.. ', 'm' => '-- ', 'n' => '-. ', 'o' => '--- ', 'p' => '.--. ', 'q' => '--.- ', 'r' => '.-. ', 's' => '... ', 't' => '- ', 'u' => '..- ', 'v' => '...- ', 'w' => '.-- ', 'x' => '-.. ', 'y' => '-.-- ', 'z' => '--.. ');
    }

    /**
     * @return  string
     */
    public function encode() {
        return str_replace(array_keys($this->getList()), $this->getList(), mb_strtolower($this->string));
    }

    /**
     * @return  string
     */
    public function decode() {
        $morse = array_map('trim', $this->getList());

        $output = '';
        foreach(explode(' ', $this->string) as $value) {
            $output .= array_search($value, $morse);
        }
        
        return mb_strtoupper($output);
    }
}