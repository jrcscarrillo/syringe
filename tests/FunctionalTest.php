<?php

use PHPUnit\Framework\TestCase;
include_once '../src/Container.php';
include_once '../src/JsonServiceFactory.php';
include_once '../src/InMemoryServiceRepository.php';
include_once '../src/CircularDependencyException.php';

include_once '../tests/dummy/ClassA.php';
include_once '../tests/dummy/ClassB.php';
include_once '../tests/dummy/ClassC.php';
include_once '../tests/dummy/ClassD.php';

class FunctionalTest extends TestCase
{

    private $container;

    protected function setUp()
    {
        $this->container = new Syringe\Container(new \Syringe\JsonServiceFactory(__DIR__ . '/services.json'));
    }

    public function testCorrectInstantiation()
    {
        $this->assertInstanceOf('DummyServices\ClassA', $this->container->get('class-a'));
    }

    public function testCorrectInstantiationWithArgs()
    {
        $this->assertInstanceOf('DummyServices\ClassB', $this->container->get('class-b'));
    }

    /**
     * @expectedException Syringe\CircularDependencyException
     */
    public function testDetectCircularDependencies()
    {
        $this->container->get('class-c');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotExistingService()
    {
        $this->container->get('class-x');
    }

}
