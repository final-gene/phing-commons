<?php
/**
 *
 * @copyright   project media
 * @package     phing-commons
 * @author      Michael Kühn <michael.kuehn@project.de>
 *              Maik Burkert <maik.burkert@project.de>
  */

/**
 * Test class for comparepropertiesXmlTest
 */
class comparepropertiesXmlTest extends BuildFileTest
{
    protected function setUp()
    {
        $this->configureProject('includes/compareproperties.xml');
    }

    /**
     * Test target compare-properties properties successfull set
     *
     * @return void
     */
    public function testComparePropertiesSuccess()
    {
        $dataPath = __DIR__ . '/../data/comparepropertiesXmlTest/testComparePropertiesSuccess';
        $this->project->setUserProperty('project.properties.template', $dataPath . '/expected.properties');
        $this->project->setUserProperty('project.properties.output', $dataPath . '/exported.properties');

        $this->project->setUserProperty('phpunit.expected1', 'test1');

        $this->executeTarget('compare-properties');

        // risky test because we just want to check that no exception is thrown.
        // see: https://github.com/sebastianbergmann/phpunit-documentation/issues/171
        // $this->assertTrue(true);
    }

    /**
    * Test target compare-properties set properties fail when wrong properties set
    *
    * @return void
    */
    public function testCompareFailWhenWrongPropertiesSet()
    {
        $dataPath = __DIR__ . '/../data/comparepropertiesXmlTest/testComparePropertiesFail';
        $this->project->setUserProperty('project.properties.template', $dataPath . '/expected.properties');
        $this->project->setUserProperty('project.properties.output', $dataPath . '/exported.properties');

        $this->project->setUserProperty('phpunit.expected1', 'test1');
        $this->project->setUserProperty('phpunit.expected2', 'test2');

        $this->expectBuildExceptionContaining(
            'compare-properties',
            'not all properties are defined',
            'These properties are not defined: phpunit.expecteda, phpunit.expectedb, phpunit.expectedc'
        );
    }

    /**
     * Test target compare-properties set properties fail when not all properties set
     *
     * @return void
     */
    public function testCompareFailWhenNotAllPropertiesSet()
    {
        $dataPath = __DIR__ . '/../data/comparepropertiesXmlTest/testComparePropertiesFail';
        $this->project->setUserProperty('project.properties.template', $dataPath . '/expected.properties');
        $this->project->setUserProperty('project.properties.output', $dataPath . '/exported.properties');

        $this->project->setUserProperty('phpunit.expecteda', 'a');
        $this->project->setUserProperty('phpunit.expectedc', 'c');

        $this->expectBuildExceptionContaining(
            'compare-properties',
            'not all properties are defined',
            'These properties are not defined: phpunit.expectedb'
        );
    }

    /**
    * Test target compare-properties delete outputfile when no error is thrown
    *
    * @return void
    */
    public function testComparePropertiesDeleteOutputfileWhenNoErrorIsThrown()
    {
        $dataPath = __DIR__ . '/../data/comparepropertiesXmlTest/testComparePropertiesSuccess';
        $this->project->setUserProperty('project.properties.template', $dataPath . '/expected.properties');
        $this->project->setUserProperty('project.properties.output', $dataPath . '/exported.properties');

        $this->project->setUserProperty('phpunit.expected1', 'test1');

        $this->executeTarget('compare-properties');

        $this->assertFileNotExists(
            $dataPath . '/exported.properties',
            'Output file exists after executing target compare-properties'
        );
    }

    /**
     * Test target compare-properties delete outputfile when an error is thrown
     *
     * @return void
     */
    public function testComparePropertiesDeleteOutputfileWhenAnErrorIsThrown()
    {
        $dataPath = __DIR__ . '/../data/comparepropertiesXmlTest/testComparePropertiesFail';
        $this->project->setUserProperty('project.properties.template', $dataPath . '/expected.properties');
        $this->project->setUserProperty('project.properties.output', $dataPath . '/exported.properties');

        $this->project->setUserProperty('phpunit.expecteda', 'a');
        $this->project->setUserProperty('phpunit.expectedc', 'c');

        $this->expectBuildExceptionContaining(
            'compare-properties',
            'not all properties are defined',
            'These properties are not defined: phpunit.expectedb'
        );

        $this->assertFileNotExists(
            $dataPath . '/exported.properties',
            'Output file exists after executing target compare-properties'
        );
    }
}
 