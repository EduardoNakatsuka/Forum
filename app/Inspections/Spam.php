<?php

namespace App\Inspections;


class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];
    
    public function detect($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }
        
        return false; //if an exception is not thrown, we return the spam detection as false
    }

}