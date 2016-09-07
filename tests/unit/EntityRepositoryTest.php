<?php

namespace Voonne\TestModel;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\UnitOfWork;
use Mockery;
use Mockery\MockInterface;
use stdClass;
use UnitTester;
use Voonne\Model\EntityRepository;
use Voonne\Model\IOException;


class EntityRepositoryTest extends Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var MockInterface
	 */
	private $entityManager;

	/**
	 * @var MockInterface
	 */
	private $classMetadata;

	/**
	 * @var TestEntityRepository
	 */
	private $repository;


	protected function _before()
	{
		$this->entityManager = Mockery::mock(EntityManagerInterface::class);
		$this->classMetadata = Mockery::mock(ClassMetadata::class);

		$this->repository = new TestEntityRepository($this->entityManager, $this->classMetadata);
	}


	protected function _after()
	{
		Mockery::close();
	}


	public function testFind()
	{
		$user = Mockery::mock(stdClass::class);

		$this->entityManager->shouldReceive('find')
			->once()
			->with(null, 1, null, null)
			->andReturn($user);

		$this->entityManager->shouldReceive('find')
			->once()
			->with(null, 2, null, null)
			->andReturn(null);

		$this->assertEquals($user, $this->repository->find(1));

		$this->expectException(IOException::class);
		$this->repository->find(2);
	}


	public function testFindOneBy()
	{
		$unitOfWork = Mockery::mock(UnitOfWork::class);
		$entityPersister = Mockery::mock(EntityPersister::class);
		$user = Mockery::mock(stdClass::class);

		$this->entityManager->shouldReceive('getUnitOfWork')
			->twice()
			->withNoArgs()
			->andReturn($unitOfWork);

		$unitOfWork->shouldReceive('getEntityPersister')
			->twice()
			->with(null)
			->andReturn($entityPersister);

		$entityPersister->shouldReceive('load')
			->once()
			->with(['email' => 'example1@example.com'], null, null, [], null, 1, null)
			->andReturn($user);

		$entityPersister->shouldReceive('load')
			->once()
			->with(['email' => 'example2@example.com'], null, null, [], null, 1, null)
			->andReturn(null);

		$this->repository->findOneBy(['email' => 'example1@example.com']);

		$this->expectException(IOException::class);
		$this->repository->findOneBy(['email' => 'example2@example.com']);
	}

}

class TestEntityRepository extends EntityRepository
{

}
