<?php

namespace MyBB\Core\Database\Repositories\Eloquent;

use Mockery;

class ProfileFieldGroupRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $model = Mockery::mock('MyBB\Core\Database\Models\ProfileFieldGroup');
        $repository = new ProfileFieldGroupRepository($model);
        static::assertInstanceOf('MyBB\Core\Database\Repositories\Eloquent\ProfileFieldGroupRepository', $repository);
    }

    public function testCanRetrieveProfileFieldGroupBySlug()
    {
        $profileFieldGroup = Mockery::mock('MyBB\Core\Database\Models\ProfileFieldGroup');

        $builder = Mockery::mock('\Illuminate\Database\Eloquent\Builder');
        $builder->shouldReceive('first')
            ->withNoArgs()
            ->andReturn($profileFieldGroup);

        $model = Mockery::mock('MyBB\Core\Database\Models\ProfileFieldGroup');
        $model->shouldReceive('where')
            ->with('slug', 'foo')
            ->andReturn($builder);

        $repository = new ProfileFieldGroupRepository($model);
        static::assertEquals($repository->getBySlug('foo'), $profileFieldGroup);
    }
}
