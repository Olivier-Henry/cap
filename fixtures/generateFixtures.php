<?php

class GenerateFixtures {

    private $maxLetters = 26;
    private $minLetters = 1;
    private $filePaths;
    private $generatedPhrases;
    private $fileSize;
    private $letters = "abcdefghijklmnopqrstuvwxyzéàèç";
    private $punctuation = ".?!:;";
    private $currentSize = 0;

    public function __construct($fileSize, $filePaths = []) {
        if (count($filePaths) !== 2) {
            throw new Exception("Two filepaths are needed to generates fixtures files");
        }

        if ($fileSize < 8) {
            throw new Exception("file size must be set greater than 7");
        }

        $this->filePaths = $filePaths;
        $this->generatedPhrases = [];
        $this->fileSize = $fileSize;

        $this->process();
    }

    private function process() {


        $dir = substr($this->filePaths[0], 0, strrpos($this->filePaths[0], DIRECTORY_SEPARATOR));
        if (!is_dir($dir)) {
            throw new Exception("the directory doesn't exist for " . $this->filePaths[0]);
        }
        $this->createFile(0);

        $this->currentSize = 0;

        $dir = substr($this->filePaths[1], 0, strrpos($this->filePaths[1], DIRECTORY_SEPARATOR));
        if (!is_dir($dir)) {
            throw new Exception("the directory doesn't exist for " . $this->filePaths[1]);
        }
        $this->createFile(1);
    }

    private function createFile($position) {


        while ($this->currentSize < $this->fileSize) {
            $phrasestoAppend = '';
            $phrasesBeforeReturn = mt_rand(1, 15);

            for ($i = 0; $i < $phrasesBeforeReturn; $i++) {

                $p = $this->createPhrase();
                array_push($this->generatedPhrases, $p);
                $phrasestoAppend .= $p;
            }
            
            echo mb_detect_encoding($phrasestoAppend);
            file_put_contents($this->filePaths[$position],  $phrasestoAppend . PHP_EOL    , FILE_APPEND | LOCK_EX);
            echo $position . PHP_EOL;
        }
    }

    private function createPhrase() {
        $phrase = "";
        $wordNumber = $this->getWordNumber();

        $wl = $this->getWordLength();
        for ($i = 0; $i < $wl; $i++) {
            $phrase .= $this->getRandomLetter();
        }

        for ($i = 1; $i <= $wordNumber; $i++) {

            $phrase .= ' ';
            $wt = $this->getWordType();
            if ($wt === 'float') {
                $phrase .= mt_rand(1000, 10000) / 10001;
                continue;
            }


            $wl = $this->getWordLength();
            for ($i = 0; $i < $wl; $i++) {
                $phrase .= $this->getRandomLetter();
            }
        }

        $phrase .= substr($this->punctuation, mt_rand(0, strlen($this->punctuation)), 1);

        $this->currentSize += strlen($phrase) * 8;

        return $phrase;
    }

    private function getWordNumber() {
        return mt_rand(4, 25);
    }

    private function getWordType() {
        $types = ['string', 'float'];
        $n = mt_rand(1, 100);
        $n = $n < 5 ? 1 : 0;
        return $types[$n];
    }

    private function getWordLength() {
        return mt_rand($this->minLetters, $this->maxLetters);
    }

    private function getRandomLetter() {
        return substr($this->letters, mt_rand(0, strlen($this->letters)), 1);
    }

    private function addDuplicatePhrase() {
        
    }

}


new GenerateFixtures(8000000, array('C:\wamp\www\clickandboat\text1.txt', 'C:\wamp\www\clickandboat\text2.txt'));

