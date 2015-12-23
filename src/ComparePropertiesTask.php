<?php
namespace FinalGene\Phing;

class ComparePropertiesTask extends \Task
{
    private $templateFilePath;
    private $outputFilePath;

    /**
     * Set template property file
     *
     * @param string $templateFilePath path to template file
     */
    public function setTemplateFile($templateFilePath)
    {
        $this->templateFilePath = $templateFilePath;
    }

    /**
     * Set output property file
     *
     * @param string $outputFilePath
     */
    public function setOutputFile($outputFilePath)
    {
        $this->outputFilePath = $outputFilePath;
    }

    /**
     * The main entry point method.
     */
    public function main()
    {
        if (empty($this->templateFilePath)) {
            throw new \BuildException('Value for template file attribute was not defined.');
        }
        if (empty($this->outputFilePath)) {
            throw new \BuildException('Value for output file attribute was not defined.');
        }

        if (!file_exists($this->outputFilePath)) {
            throw new \BuildException('Output property file \'' . $this->outputFilePath . '\' does not exist.');
        }

        $properties = new PropertyFileReader();

        $templateFileProperties = $properties->load($this->templateFilePath);
        if (empty($templateFileProperties)) {
            return;
        }

        $outputFileProperties = $properties->load($this->outputFilePath);
        if (empty($outputFileProperties)) {
            return;
        }

        $undefinedProperties = [];
        foreach (array_keys($templateFileProperties) as $templateFileProperty) {
            if (!in_array($templateFileProperty, array_keys($outputFileProperties))) {
                $undefinedProperties[] = $templateFileProperty;
            }
        }

        if (!empty($undefinedProperties)) {
            throw new \BuildException(
                'These properties are not defined: ' . implode(', ', $undefinedProperties)
            );
        }
    }
}
