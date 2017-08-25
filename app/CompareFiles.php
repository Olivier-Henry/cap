<?php

/**
 * Description of CompareFiles
 *
 * @author olivier
 */
class CompareFiles {

    private $filesPath;
    private $phrases;
    private $punctuation = array(".", "?", "!", ":", ";");

    public function __construct($filePaths) {
        if (!is_array($filePaths) || !count($filePaths) === 2) {
            throw new Exception("invalid arguments: two paths are needed");
        }
        if (!file_exists($filePaths[0])) {
            throw new Exception("File " . basename($filePaths[0]) . " doesn't exist at " . $filePaths[0]);
        }
        if (!file_exists($filePaths[1])) {
            throw new Exception("File " . basename($filePaths[1]) . " doesn't exist at " . $filePaths[1]);
        }
        $this->filesPath = $filePaths;
        $this->filesPath = $this->setSmallestFileFirst();
        $this->readFile(0);
        $this->readFile(1);
    }

    /**
     * Reverse the files paths to get smallest file size as first index
     * @return array with smallest file size path as first index;
     */
    protected function setSmallestFileFirst() {
        return filesize($this->filesPath[0]) > filesize($this->filesPath[1]) ? array_reverse($this->filesPath) : $this->filesPath;
    }

    protected function readFile($position) {

        $lastChunkResidue = '';

        if ($handle = fopen($this->filesPath[$position], 'rb')) {
            while (!feof($handle)) {
                $buffer = $lastChunkResidue . fread($handle, 8192);
                $strlen = strlen($buffer);
                $index = 0;

                for ($i = 0; $i < $strlen; $i++) {
                    if (in_array($buffer{$i}, $this->punctuation)) {
                        if ($buffer{$i} === "." && $i > 0 && $i < $strlen - 1 && is_numeric($buffer{$i - 1}) && is_numeric($buffer{$i + 1})) {
                            continue;
                        }
                        $plen = $i - $index + 1;
                        $p = str_replace(PHP_EOL , '', substr($buffer, $index, $plen));
                        $index = $i + 1;
                        $hash = md5($p);
                        if ($position === 0) {
                            $this->phrases[$hash] = $plen;
                            continue;
                        }
                        if (isset($this->phrases[$hash]) && $this->phrases[$hash] === $plen) {
                            echo $p . PHP_EOL;
                            unset($this->phrases[$hash]);
                        }
                    }
                }

                if ($index !== $strlen - 1) {
                    $lastChunkResidue = str_replace(PHP_EOL , '', substr($buffer, $index, $strlen - 1 - $index));
                }
            }
        } else {
            throw new Exception("File " . basename($this->filesPath[0]) . "cannot be read");
        }
    }

}

new CompareFiles(array('/Library/Server/Web/Data/Sites/Default/cap/text1.txt', '/Library/Server/Web/Data/Sites/Default/cap/text2.txt'));
