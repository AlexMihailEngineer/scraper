<?php

namespace App\Scrapers;

use App\Scrapers\Contracts\ParserInterface;
use App\Scrapers\Parsers\DefaultParser; // Create a fallback parser
use Exception;

class ParserFactory
{
    public static function make(?string $parserClass): ParserInterface
    {
        // If the database has a class defined and it exists, use it
        if ($parserClass && class_exists($parserClass)) {
            $parser = new $parserClass();

            if ($parser instanceof ParserInterface) {
                return $parser;
            }
        }

        // Fallback if no specific parser is found
        return new DefaultParser();
    }
}
