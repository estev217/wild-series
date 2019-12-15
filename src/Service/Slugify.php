<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $input = str_replace('/\s\s+/', '-', strtolower(trim(strip_tags($input))));
        $input = preg_replace('/[^A-Za-z0-9\-]/', '', $input);
        return $input;
    }
}
