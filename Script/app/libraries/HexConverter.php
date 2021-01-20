<?php

namespace Fir\Libraries;

class HexConverter {
    /**
     * @var
     */
    private $hex;

    public function __construct($hex) {
        if(strlen($hex) == 3) {
            $this->hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        } else {
            $this->hex = $hex;
        }
    }

    /**
     * Return the hex color
     *
     * @return  string
     */
    public function hex() {
        return mb_strtoupper($this->hex);
    }

    /**
     * Convert hex color to rgb
     *
     * @return  array
     */
    public function rgb() {
        if(strlen($this->hex) == 3) {
            $r = hexdec(substr($this->hex,0,1).substr($this->hex,0,1));
            $g = hexdec(substr($this->hex,1,1).substr($this->hex,1,1));
            $b = hexdec(substr($this->hex,2,1).substr($this->hex,2,1));
        } else {
            $r = hexdec(substr($this->hex,0,2));
            $g = hexdec(substr($this->hex,2,2));
            $b = hexdec(substr($this->hex,4,2));
        }

        return array($r, $g, $b);
    }

    /**
     * Convert hex color to hsl
     *
     * @return  array
     */
    public function hsl() {
        $color = array($this->hex[0].$this->hex[1], $this->hex[2].$this->hex[3], $this->hex[4].$this->hex[5]);
        $rgb = array_map(function($part) {
            return hexdec($part) / 255;
        }, $color);

        $max = max($rgb);
        $min = min($rgb);

        $l = ($max+$min)/2;

        if($max == $min) {
            $h = $s = 0;
        } else {
            $d = $max-$min;
            $s = $l > 0.5 ? $d/(2-$max-$min) : $d/($max+$min);

            switch($max) {
                case $rgb[0]:
                    $h = ($rgb[1]-$rgb[2])/$d+($rgb[1] < $rgb[2] ? 6 : 0);
                    break;
                case $rgb[1]:
                    $h = ($rgb[2]-$rgb[0])/$d+2;
                    break;
                case $rgb[2]:
                    $h = ($rgb[0]-$rgb[1])/$d+4;
                    break;
            }

            $h *= 60;
        }

        return array(round($h), round($s*100), round($l*100));
    }

    /**
     * Convert hex color to cmyk
     *
     * @return  array
     */
    public function cmyk() {
        $rgb = $this->rgb();
        $cyan = 255-$rgb[0];
        $magenta = 255-$rgb[1];
        $yellow = 255-$rgb[2];

        $black = min($cyan, $magenta, $yellow);

        $cyan = (((($cyan-$black)/(255-$black))*255)/255)*100;
        $magenta = (((($magenta-$black)/(255-$black))*255)/255)*100;
        $yellow = (((($yellow-$black)/(255-$black))*255)/255)*100;

        $black = ($black/255)*100;

        return array(round($cyan), round($magenta), round($yellow), round($black));
    }
}