<?php

namespace PhpBench\Tests\Unit\Bridge\Console;

use PHPUnit\Framework\TestCase;
use PhpBench\Bridge\Console\MethodToConsoleOptionsBroker;
use Symfony\Component\Console\Input\InputDefinition;

class MethodToConsoleOptionsBrokerTest extends TestCase
{
    /**
     * @var MethodToInputDefinitionConverter
     */
    private $converter;

    public function testEmptyDefinitionIfMethodDoesNotExist()
    {
        $definition = $this->converter(TestSubject::class, 'idontexist')->inputDefinition();
        $this->assertInstanceOf(InputDefinition::class, $definition);
    }

    public function testNoParameters()
    {
        $definition = $this->converter(TestSubject::class, 'noParameters')->inputDefinition();
        $this->assertInstanceOf(InputDefinition::class, $definition);
    }

    public function testScalarOption()
    {
        $definition = $this->converter(TestSubject::class, 'scalarSingleOption')->inputDefinition();
        $this->assertInstanceOf(InputDefinition::class, $definition);
        $this->assertCount(1, $definition->getOptions());
        $this->assertEquals('foo', $definition->getOption('foo')->getName());
        $this->assertTrue($definition->getOption('foo')->isValueRequired());
    }

    public function testBooleanOption()
    {
        $definition = $this->converter(TestSubject::class, 'booleanSingleOption')->inputDefinition();
        $this->assertInstanceOf(InputDefinition::class, $definition);
        $this->assertCount(1, $definition->getOptions());
        $this->assertEquals('foo', $definition->getOption('foo')->getName());
        $this->assertFalse($definition->getOption('foo')->isValueRequired());
    }

    public function testArrayOption()
    {
        $definition = $this->converter(TestSubject::class, 'arraySingleOption')->inputDefinition();
        $this->assertInstanceOf(InputDefinition::class, $definition);
        $this->assertCount(1, $definition->getOptions());
        $this->assertEquals('foo', $definition->getOption('foo')->getName());
        $this->assertTrue($definition->getOption('foo')->isArray());
    }

    private function converter(string $className, string $method)
    {
        return new MethodToConsoleOptionsBroker($className, $method);
    }
}

class TestSubject
{
    public function noParameters()
    {
    }

    public function scalarSingleArg(string $foo)
    {
    }

    public function scalarSingleOption(string $foo = 'foo')
    {
    }

    public function arraySingleOption(array $foo = [])
    {
    }

    public function booleanSingleOption(bool $foo = false)
    {
    }
}
