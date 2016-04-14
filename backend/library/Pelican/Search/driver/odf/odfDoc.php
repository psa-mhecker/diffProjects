<?php
class odfDoc
{
    public $file; // File name
    public $content; // File content extracted from the content.xml file
    public $vars = array(); // Array with all the data to change

    public function odfDoc($file)
    {
        $this->file = $file;
        $zip = new ZipArchive();
        if ($zip->open($this->file) === true) {
            $this->content = $zip->getFromName('content.xml');
            $zip->close();
        } else {
            exit("Error while Opening the file '$file' - Check your odt file\n");
        }
    }

    public function setVars($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function parse()
    {
        if ($this->content != null) {
            $this->content = str_replace(array_keys($this->vars), array_values($this->vars), $this->content);
        } else {
            exit("Nothing to parse - check that the content.xml file is correctly formed\n");
        }
    }

    public function printVars()
    {
        echo '<pre>';
        print_r($this->vars);
        echo '</pre>';
    }

    public function save($newfile)
    {
        if ($newfile != $this->file) {
            copy($this->file, $newfile);
            $this->file = $newfile;
        }
        $zip = new ZipArchive();
        if ($zip->open($this->file, ZIPARCHIVE::CREATE) === true) {
            if (!$zip->addFromString('content.xml', $this->content)) {
                exit('Error during the file saving');
            }
            $zip->close();
        } else {
            exit('Error during the file saving');
        }
    }
}
