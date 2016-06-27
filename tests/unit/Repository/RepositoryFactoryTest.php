<?php

namespace MyBB\Core\Repository;

use Mockery;

class RepositoryFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $registry = Mockery::mock('MyBB\Core\Repository\RepositoryRegistry');
        $application = Mockery::mock('Illuminate\Contracts\Foundation\Application');

        $factory = new RepositoryFactory($registry, $application);
        static::assertInstanceOf('MyBB\Core\Repository\RepositoryFactory', $factory);
    }

    public function testCanBuildUsingRegistryAndApplication()
    {
        $registry = Mockery::mock('MyBB\Core\Repository\RepositoryRegistry');
        $registry->shouldReceive('get')
            ->with('foo')
            ->andReturn('Foo\Bar');

        $application = Mockery::mock('Illuminate\Contracts\Foundation\Application');
        $application->shouldReceive('make')
            ->with('Foo\Bar')
            ->andReturn(Mockery::mock('MyBB\Core\Repository\RepositoryInterface'));

        $factory = new RepositoryFactory($registry, $application);
        $repository = $factory->build('foo');

        static::assertInstanceOf('MyBB\Core\Repository\RepositoryInterface', $repository);
    }

    public function testCanHandleWhenRegistryReturnsNull()
    {
        $registry = Mockery::mock('MyBB\Core\Repository\RepositoryRegistry');
        $registry->shouldReceive('get')
            ->with('foo')
            ->andReturnNull();

        $application = Mockery::mock('Illuminate\Contracts\Foundation\Application');
        $application->shouldReceive('make')
            ->never();

        $factory = new RepositoryFactory($registry, $application);

        static::assertNull($factory->build('foo'));
    }
}
