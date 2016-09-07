<?php

/**
 * This file is part of the Voonne platform (http://www.voonne.org)
 *
 * Copyright (c) 2016 Jan Lavička (mail@janlavicka.name)
 *
 * For the full copyright and license information, please view the file licence.md that was distributed with this source code.
 */

namespace Voonne\Model;


abstract class EntityRepository extends \Kdyby\Doctrine\EntityRepository
{

	/**
	 * Finds an entity by its primary key / identifier.
	 *
	 * @param mixed $id
	 * @param int $lockMode
	 * @param null $lockVersion
	 *
	 * @return object
	 *
	 * @throws IOException
	 */
	public function find($id, $lockMode = null, $lockVersion = null)
	{
		$result = parent::find($id, $lockMode, $lockVersion);

		if ($result === null) {
			throw new IOException('Not found', 404);
		}

		return $result;
	}


	/**
	 * Finds a single entity by a set of criteria.
	 *
	 * @param array $criteria
	 * @param array $orderBy
	 *
	 * @return object
	 *
	 * @throws IOException
	 */
	public function findOneBy(array $criteria, array $orderBy = null)
	{
		$result = parent::findOneBy($criteria, $orderBy);

		if ($result === null) {
			throw new IOException('Not found', 404);
		}

		return $result;
	}

}
