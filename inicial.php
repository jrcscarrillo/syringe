<?php
namespace Syringe;
include_once 'Psr4AutoloaderClass.php';
class MockPsr4AutoloaderClass extends Psr4AutoloaderClass
{
    protected $files = array();

    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    protected function requireFile($file)
    {
        return in_array($file, $this->files);
    }
}

class Psr4AutoloaderClassTest extends \PHPUnit_Framework_TestCase
{
    protected $loader;

    protected function setUp()
    {
        $this->loader = new MockPsr4AutoloaderClass;

        $this->loader->setFiles(array(
            '/vendor/syringe/src/ClassName.php',
            '/vendor/syringe/src/DoomClassName.php',
            '/vendor/syringe/tests/ClassNameTest.php',
            '/vendor/syringedoom/src/ClassName.php',
            '/vendor/syringe.baz.dib/src/ClassName.php',
            '/vendor/syringe.baz.dib.zim.gir/src/ClassName.php',
        ));

        $this->loader->addNamespace(
            'Syringe',
            '/vendor/syringe/src'
        );

        $this->loader->addNamespace(
            'Syringe',
            '/vendor/syringe/tests'
        );

        $this->loader->addNamespace(
            'SyringeDoom',
            '/vendor/syringedoom/src'
        );

        $this->loader->addNamespace(
            'Syringe\Baz\Dib',
            '/vendor/syringe.baz.dib/src'
        );

        $this->loader->addNamespace(
            'Syringe\Baz\Dib\Zim\Gir',
            '/vendor/syringe.baz.dib.zim.gir/src'
        );
    }

    public function testExistingFile()
    {
        $actual = $this->loader->loadClass('Syringe\ClassName');
        $expect = '/vendor/syringe/src/ClassName.php';
        $this->assertSame($expect, $actual);

        $actual = $this->loader->loadClass('Syringe\ClassNameTest');
        $expect = '/vendor/syringe/tests/ClassNameTest.php';
        $this->assertSame($expect, $actual);
    }

    public function testMissingFile()
    {
        $actual = $this->loader->loadClass('No_Vendor\No_Package\NoClass');
        $this->assertFalse($actual);
    }

    public function testDeepFile()
    {
        $actual = $this->loader->loadClass('Syringe\Baz\Dib\Zim\Gir\ClassName');
        $expect = '/vendor/syringe.baz.dib.zim.gir/src/ClassName.php';
        $this->assertSame($expect, $actual);
    }

    public function testConfusion()
    {
        $actual = $this->loader->loadClass('Syringe\DoomClassName');
        $expect = '/vendor/syringe/src/DoomClassName.php';
        $this->assertSame($expect, $actual);

        $actual = $this->loader->loadClass('SyringeDoom\ClassName');
        $expect = '/vendor/syringedoom/src/ClassName.php';
        $this->assertSame($expect, $actual);
    }
}
