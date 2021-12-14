<?php

namespace core\traits;

trait LibraryTrait
{
    public function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    public function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    public function generateToken($length = 20)
    {
        return bin2hex(random_bytes($length));
    }

    public function generateBigNumber($length = 30)
    {
        # prevent the first number from being 0
        $output = rand(1, 9);

        for ($i = 0; $i < $length; $i++) {
            $output .= rand(0, 9);
        }

        return $output;
    }
}
