<?php

namespace CodePress\CodeDatabase\Tests;

use Mockery as m;
use CodePress\CodeDatabase\AbstractRepository;
use CodePress\CodeDatabase\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CodePress\CodeDatabase\Model\Category;

class AbstractRepositoryTest extends AbstractTestCase
{

	public function setUp()
	{
		parent::setUp();
		$this->migrate();
		Category::create(['name'=>'name category', 'description'=>'description category']);
		//echo Category::all()->first()->name;
	}

	public function test_if_implements_repositoryInterface()
	{
		$mock = m::mock(AbstractRepository::class);
		$this->assertInstanceOf(RepositoryInterface::class, $mock);
	}

	public function test_should_return_all_without_arguments()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$mockStd = m::mock(\StdClass::class);

		$mockStd->id = 1;
		$mockStd->name = 'name';
		$mockStd->description = 'description';

		$mockRepository
			->shouldReceive('all')
			->andReturn([$mockStd, $mockStd, $mockStd]);

		$result = $mockRepository->all();
		$this->assertCount(3, $result);
		$this->assertInstanceOf(\StdClass::class, $result[0]);
	}

	public function test_should_return_all_with_arguments()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$mockStd = m::mock(\StdClass::class);

		$mockStd->id = 1;
		$mockStd->name = 'name';

		$mockRepository
			->shouldReceive('all')
			->with(['id', 'name'])
			->andReturn([$mockStd, $mockStd, $mockStd]);

		$this->assertCount(3, $mockRepository->all(['id', 'name']));
		$this->assertInstanceOf(\StdClass::class, $mockRepository->all(['id', 'name'])[0]);
	}

	public function test_should_return_create()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$mockStd = m::mock(\StdClass::class);

		$mockStd->id = 1;
		$mockStd->name = 'name';

		$mockRepository
			->shouldReceive('create')
			->with(['name'=>'stdClassName'])
			->andReturn($mockStd);

		$result = $mockRepository->create(['name'=>'stdClassName']);
		$this->assertEquals(1, $result->id);
		$this->assertInstanceOf(\StdClass::class, $result);
	}

	public function test_should_return_update_success()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$mockStd = m::mock(\StdClass::class);

		$mockStd->id = 1;
		$mockStd->name = 'name';

		$mockRepository
			->shouldReceive('update')
			->with(['name'=>'stdClassName'], 1)
			->andReturn($mockStd);

		$result = $mockRepository->update(['name'=>'stdClassName'], 1);
		$this->assertEquals(1, $result->id);
		$this->assertInstanceOf(\StdClass::class, $result);
	}

	/**
	 * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function test_should_return_update_fail()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$throw = new ModelNotFoundException();
		$throw->setModel(\StdClass::class);

		$mockRepository
			->shouldReceive('update')
			->with(['name'=>'stdClassName'], 0)
			->andThrow($throw);


		$mockRepository->update(['name'=>'stdClassName'], 0);
	}

	public function test_should_return_delete_success()
	{
		$mockRepository = m::mock(AbstractRepository::class);

		$mockRepository
			->shouldReceive('delete')
			->with(1)
			->andReturn(true);

		$result = $mockRepository->delete(1);
		$this->assertEquals(true, $result);
	}

	/**
	 * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function test_should_return_delete_fail()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$throw = new ModelNotFoundException();
		$throw->setModel(\stdClass::class);

		$mockRepository
			->shouldReceive('delete')
			->with(0)
			->andThrow($throw);

		$result = $mockRepository->delete(0);
	}

	public function test_should_return_find_without_columns_success()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$mockStd = m::mock(\StdClass::class);
		$mockStd->id = 1;
		$mockStd->name = 'name';
		$mockStd->description = 'description';

		$mockRepository
			->shouldReceive('find')
			->with(1)
			->andReturn($mockStd);

		$this->assertInstanceOf(\StdClass::class, $mockRepository->find(1));
	}

	public function test_should_return_find_with_columns_success()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$mockStd = m::mock(\StdClass::class);
		$mockStd->id = 1;
		$mockStd->name = 'name';

		$mockRepository
			->shouldReceive('find')
			->with(1, ['id', 'name'])
			->andReturn($mockStd);

		$this->assertInstanceOf(\StdClass::class, $mockRepository->find(1, ['id', 'name']));
	}

	/**
	 * @expectedException \Illuminate\Database\Eloquent\ModelNotFoundException
	 */
	public function test_should_return_find_fail()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$throw = new ModelNotFoundException();
		$throw->setModel(\stdClass::class);;

		$mockRepository
			->shouldReceive('find')
			->with(0)
			->andThrow($throw);

		$mockRepository->find(0);
	}

	public function test_should_return_findby_with_columns_success()
	{
		$mockRepository = m::mock(AbstractRepository::class);
		$mockStd = m::mock(\StdClass::class);
		$mockStd->id = 1;
		$mockStd->name = 'name';

		$mockRepository
			->shouldReceive('findBy')
			->with('name', 'my-data', ['id', 'name'])
			->andReturn([$mockStd, $mockStd, $mockStd]);

		$result = $mockRepository->findBy('name', 'my-data', ['id', 'name']);
		$this->assertCount(3, $result);
		$this->assertInstanceOf(\StdClass::class, $result[0]);
	}

	public function test_should_return_findby_with_columns_empty()
	{
		$mockRepository = m::mock(AbstractRepository::class);

		$mockRepository
			->shouldReceive('findBy')
			->with('name', '', ['id', 'name'])
			->andReturn([]);

		$result = $mockRepository->findBy('name', '', ['id', 'name']);
		$this->assertCount(0, $result);
	}


}