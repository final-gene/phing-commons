<?php
/**
 *
 * @copyright   project media
 * @package     phing-commons
 * @author      Maik Burkert <maik.burkert@project.de>
 */

/**
 * Test class for prepareenvironmentXmlTest
 */
class prepareenvironmentXmlTest extends BuildFileTest
{
    protected function setUp()
    {
        $this->configureProject('includes/prepareenvironment.xml');
    }

    /**
     * Test target prepare-environment no property was set
     *
     * @return void
     */
    public function testPrepareEnvironmentNoPropertyWasSet()
    {
        $this->expectBuildExceptionContaining(
            'prepare-environment',
            'not all properties are defined',
            'not set'
        );
    }

    /**
     * Test target prepare-environment property project.path.target not set
     *
     * @return void
     */
    public function testPrepareEnvironmentPropertyPathTargetNotSet()
    {
        $this->project->setUserProperty('project.path.source', 'test/path/source');

        $this->expectBuildExceptionContaining(
            'prepare-environment',
            'not all properties are defined',
            'Property project.path.target not set'
        );
    }

    /**
     * Test target prepare-environment property project.path.source not set
     *
     * @return void
     */
    public function testPrepareEnvironmentPropertyPathSourceNotSet()
    {
        $this->project->setUserProperty('project.path.target', 'test/path/target');

        $this->expectBuildExceptionContaining(
            'prepare-environment',
            'not all properties are defined',
            'Property project.path.source not set'
        );
    }

    /**
     * Test target prepare-environment properties successfull set
     *
     * @return void
     */
    public function testPrepareEnvironmentSuccess()
    {
        $dataPath = __DIR__ . '/../data/prepareenvironmentXmlTest/testPrepareEnvironmentSuccess';
        $sourcePath = $dataPath . '/source';
        $targetPath = $dataPath . '/target';
        $targetFileName = 'template.yaml';
        $expectedFileName = 'expected.yaml';

        $this->project->setUserProperty('project.path.source', $sourcePath);
        $this->project->setUserProperty('project.path.target', $targetPath);

        $this->project->setUserProperty('project.database.hostname', 'testhost');
        $this->project->setUserProperty('project.database.port', 'testport');
        $this->project->setUserProperty('project.database.username', 'testuser');
        $this->project->setUserProperty('project.database.password', 'testpass');
        $this->project->setUserProperty('project.database.dbname', 'testdb');

        // ensure that prepare-evnironment creates template.yaml and doesn't use a leftover version of that file
        @unlink($targetPath . '/' . $targetFileName);
        $this->assertFileNotExists($targetPath . '/' . $targetFileName);

        $this->executeTarget('prepare-environment');

        $this->assertFileEquals(
            $dataPath . '/' . $expectedFileName,
            $targetPath . '/' . $targetFileName,
            'Not all properties in target file ' . $targetFileName . ' are defined'
        );

        unlink($targetPath . '/' . $targetFileName);
    }
}
 