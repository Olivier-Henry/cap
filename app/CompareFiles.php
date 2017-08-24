<?php

/**
 * Description of CompareFiles
 *
 * @author olivier
 */
class CompareFiles {

    private $filesPath;
    private $phrases;
    private $punctuation = ".?!:;";

    public function __construct($filePaths) {
       
        if (!is_array($filePaths) || !count($filePaths) === 2) {
            throw new Exception("invalid arguments: two paths are needed");
        }
        
        if(!file_exists($filePaths[0])){
            throw new Exception("File " . basename($filePaths[0]) . "doesn't exist");
        }
        
        if(!file_exists($filePaths[1])){
            throw new Exception("File " . basename($filePaths[1]) . "doesn't exist");
        }
        
        $this->filesPath = $filePaths;
        $this->filesPath = $this->setSmallestFileFirst();
        
        $this->readFile(0);
    }

    /**
     * Reverse the files paths to get smallest file size as first index
     * @return array with smallest file size path as first index;
     */
    protected function setSmallestFileFirst() {
        return filesize($this->filesPath[0]) > filesize($this->filesPath[1]) ? array_reverse($this->filesPath) : $this->filesPath;
    }
    
    protected function process(){
        
    }
    
    protected function readFile($position){
        if($handle = fopen($this->filesPath[$position], 'rb')){
            while (!feof($handle)){
                $buffer = fread($handle, 8192);
                
                echo $buffer;
                die();
            }
        }else{
            throw new Exception("File " . basename($this->filesPath[0]) . "can not be read");
        }
    }
    
    protected function extractPhrases(){
        
    }

}

new CompareFiles(array('/Library/Server/Web/Data/Sites/Default/cap/text1.txt', '/Library/Server/Web/Data/Sites/Default/cap/text2.txt'));
