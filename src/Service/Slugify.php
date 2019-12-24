<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        // replace non letter or digits by -
        $input = preg_replace('~[^\\pL\d]+~u', '-', $input);
        // trim
        $input = trim($input, '-');
        // transliterate
        $input = iconv('utf-8', 'us-ascii//TRANSLIT', $input);
        // lowercase
        $input = strtolower($input);
        // remove unwanted characters
        $input = preg_replace('~[^-\w]+~', '', $input);
        if (empty($input)) {
            return 'n-a';
        }
        return $input;
    }
}
