<?php

namespace Tests\DockerSecrets\Reader;

use DockerSecrets\Reader\SecretsReader;

class SecretsReaderTest extends \PHPUnit_Framework_TestCase
{

    /** @var  SecretsReader */
    protected $secretReaderMock;

    /** @var array */
    protected $testContent = [
        'my_secret_data_1' => 'testSecretDataContent1',
        'my_secret_data_2' => 'testSecretDataContent2',
    ];

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->secretReaderMock = $this->getMock('DockerSecrets\Reader\SecretsReader', ['getSecretsDir']);
        $this->secretReaderMock
            ->expects($this->any())
            ->method('getSecretsDir')
            ->willReturn(__DIR__.'/../../test-secrets-dir');
    }

    /**
     * Test ReadAll method
     */
    public function testReadAll()
    {
        $allSecrets = $this->secretReaderMock->readAll();
        $this->assertNotEmpty($allSecrets);
        $this->assertCount(2, $allSecrets);
        $this->assertEquals($this->testContent, $allSecrets);
    }

    /**
     * Test Read one secret method
     */
    public function testReadMultiSecrets()
    {
        $myTestSecret = $this->secretReaderMock->read('my_secret_data_1');
        $this->assertNotEmpty($myTestSecret);
        $this->assertEquals($myTestSecret, $this->testContent['my_secret_data_1']);

        $myTestSecret2 = $this->secretReaderMock->read('my_secret_data_2');
        $this->assertNotEmpty($myTestSecret2);
        $this->assertEquals($myTestSecret2, $this->testContent['my_secret_data_2']);
    }

    /**
     * Test Read one secret method
     */
    public function testReadOneSecret()
    {
        $myTestSecret = $this->secretReaderMock->read('my_secret_data_1');
        $this->assertNotEmpty($myTestSecret);
        $this->assertEquals($myTestSecret, $this->testContent['my_secret_data_1']);
    }


    /**
     * Test Secret not exist Exception
     * @expectedException     DockerSecrets\Exception\SecretFileNotFoundException
     */
    public function testSecretNotExistException()
    {
        $myTestSecret = $this->secretReaderMock->read('not_existing_secret');
    }

    /**
     * Test Secret Dir Not Exist Exception
     * @expectedException     DockerSecrets\Exception\SecretDirNotExistException
     */
    public function testSecretDirNotExistException()
    {
        $dockerSecretInstance = new SecretsReader();
        $dockerSecretInstance->readAll();
    }
}
