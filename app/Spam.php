<?php

namespace App;

class Spam
{
    public function detect($body)
    {
        //detect invalid keywords
        $this->detectInvalidKeywords($body);

        return false; //if an exception is not thrown, we return the spam detection as false
    }

    public function detectInvalidKeywords($body)
    {
        $invalidKeywords = [
            'yahoo customer support'
        ];

        foreach ($invalidKeywords as $keyword) {       
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }
    }
}