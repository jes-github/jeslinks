<?php

namespace OCA\Jeslinks\Db;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;


class LinkMapper extends QBMapper {
		
	public function __construct(IDBConnection $db) {
			parent::__construct($db, 'jeslinks', Link::class);
		}
	
			
		

    	/**
	 * @param int $id
	 * @param string $wie
	 * @return Entity|Link
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 * @throws DoesNotExistException
	 */
	public function find(int $id): Link {
		//throw new \Exception( "Vind reservaties van $userId" );
		/* @var $qb IQueryBuilder */
		$qb = $this->db->getQueryBuilder();
		$qb->select('*')
			->from('jeslinks')
			->where($qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT)));
		return $this->findEntity($qb);
	}

	/**
	 * @param string $wie
	 * @return array
	 * alle favorieten van JES
     */

	public function findAll(string $wie): array {
		//throw new \Exception( "Vind reservaties van $wie" );
		/* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
			->from('jeslinks')
			->where($qb->expr()->eq('wie', $qb->createNamedParameter($wie)))
            ->groupBy('categorie')
			->orderby('categorie', 'asc');
			return $this->findEntities($qb);
	}

    /**
	 * @param string $wie
     * @param string $categorie
	 * @return array
	 * alle favorieten van JES
     */

	public function findPerCategorie($categorie, $wie): array {
		//throw new \Exception( "Vind reservaties van $wie" );
		/* @var $qb IQueryBuilder */
        $qb = $this->db->getQueryBuilder();
        $qb->select('*')
			->from('jeslinks')
			->where($qb->expr()->eq('wie', $qb->createNamedParameter($wie)))
            ->andWhere($qb->expr()->eq('categorie', $qb->createNamedParameter($categorie)))
            ->orderby('naam', 'asc');
			return $this->findEntities($qb);
	}

    /**
	 * @param string $wie
	 * @return array
	 */
	public function findAljouw(string $wie): array {
		//throw new \Exception( "Vind reservaties van $wie" );
		/* @var $qb IQueryBuilder */
        $nieuwearray = [];
		$qb = $this->db->getQueryBuilder();
        $qb->select('*')
			->from('jeslinks')
			->where($qb->expr()->eq('wie', $qb->createNamedParameter($wie)))
            ->groupBy('categorie')
			->orderby('categorie', 'asc');
			return $this->findEntities($qb);
	}


}
