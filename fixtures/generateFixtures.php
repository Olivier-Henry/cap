<?php

class GenerateFixtures {
    
    private $maxLetters = 26;
    private $minLetters = 1;
    private $filePaths;
    
    public function __construct($phrasesNb, $filePaths = []) {
         if(count($filePaths) < 2){
             throw new Exception("Two filepaths are needed to generates fixtures files");
         }   
    }
}

