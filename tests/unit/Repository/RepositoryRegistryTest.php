<?php

namespace MyBB\Core\Repository;

class RepositoryRegistryTest extends \PHPUnit_Framework_TestCase
{
	public function testCanBeConstructed()
	{
		$registry = new RepositoryRegistry([
			'foo' => 'Foo\Bar'
		]);

		static::assertInstanceOf('MyBB\Core\Repository\RepositoryRegistry', $registry);
		static::assertInstanceOf('MyBB\Core\Registry\RegistryInterface', $registry);
	}

	public function testCanAddRepository()
	{
		$registry = new RepositoryRegistry();
		$registry->addRepository('foo', 'Foo\Bar');

		static::assertEquals('Foo\Bar', $registry->get('foo'));
		static::assertArrayHasKey('foo', $registry->getAll());
	}
}
