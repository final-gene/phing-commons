<?php

class ComparePropertiesTask extends Task
{
    private $dir;
    private $templatefile;
    private $outputfile;

    /**
     * Set dir for property files
     *
     * @param string $dir
     */
    public function setDir($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Set template property file
     *
     * @param string $file
     */
    public function setTemplatefiles($file)
    {
        $this->templatefile = $file;
    }

    /**
     * Set output property file
     *
     * @param string $file
     */
    public function setOutputfile($file)
    {
        $this->outputfile = $file;
    }

    /**
     * The main entry point method.
     */
    public function main()
    {
        if (empty($this->dir)) {
            throw new BuildException('You must specify a value for dir attribute.');
        }
        if (empty($this->templatefile)) {
            throw new BuildException('You must specify a value for templatefile attribute.');
        }
        if (empty($this->outputfile)) {
            throw new BuildException('You must specify a value for outputfile attribute.');
        }

        $outputfile = (!empty($this->dir)) ? $this->dir . '/' . $this->outputfile : $this->outputfile;
        if (!file_exists($outputfile)) {
            throw new BuildException('Output property file \'' . $outputfile . '\' does not exists.');
        }

        // check template properties
        $propTemplate = $this->extractProperties($this->dir, $this->templatefile);
        if (!empty($propTemplate)) {
            $propOutput = $this->extractProperties($this->dir, $this->outputfile);
            if (!empty($propOutput)) {
                foreach (array_keys($propTemplate) as $prop) {
                    if (!in_array($prop, array_keys($propOutput))) {
                        throw new BuildException('The property \'' . $prop . '\' is not defined in project.');
                    }
                }
            }
        }
    }

    /**
     * Extract template properties from file.
     *
     * @param  string   $file           (path to file)
     * @return array                    (properties from the file)
     */
    public function getPropertiesFromFile($file)
    {
        $phingFile = new PhingFile($file);
        $props = new Properties();
        $props->load($phingFile);
        return $props->getProperties();
    }

    /**
     * Extract template properties from files.
     *
     * @param  string   $dir            (directory name)
     * @param  int      $file           (file name)
     * @return array    $return
     */
    public function extractProperties($dir, $file)
    {
        $return = [];

        if (empty($file)) {
            throw new BuildException('Empty parameter file in ' . __METHOD__);
        }

        $files = explode(',', $file);
        foreach ($files as $f) {
            $filepath = (!empty($dir)) ? $dir . '/' . trim($f) : trim($f);
            if (file_exists($filepath)) {
                $properties = $this->getPropertiesFromFile($filepath);
                if (!empty($properties)) {
                    $return = array_merge($return, $properties);
                }
            } else {
                throw new BuildException('Template property file \'' . $filepath . '\' does not exists.');
            }
        }
        return $return;
    }
}
