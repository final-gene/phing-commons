<?php

require_once 'phing/BuildFileTest.php';

class ComparePropertiesTaskTest extends BuildFileTest
{
    private $datadir = 'tests/data/ComparePropertiesTaskTest';

    public function testGetPropertiesFromFile1()
    {
        $file = $this->datadir . '/properties1.dist';
        $compareProperties = new ComparePropertiesTask();
        $props = $compareProperties->getPropertiesFromFile($file);
        $this->assertArrayHasKey('property1', $props);
        $this->assertArrayHasKey('property2', $props);
    }

    public function testGetPropertiesFromFile2()
    {
        $file = $this->datadir . '/properties2.dist';
        $compareProperties = new ComparePropertiesTask();
        $props = $compareProperties->getPropertiesFromFile($file);
        $this->assertEmpty($props);
    }

}
