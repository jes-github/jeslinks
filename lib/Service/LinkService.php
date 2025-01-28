<?php

namespace OCA\Jeslinks\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\Jeslinks\Db\Link;
use OCA\Jeslinks\Db\LinkMapper;

class LinkService {

	/** @var LinkMapper */
	private $mapper;

	public function __construct(LinkMapper $mapper) {
		$this->mapper = $mapper;
	}

	public function vindCategorieen(string $wie): array {
		//throw new \Exception( "Vind reservaties van $wie" );
		return $this->mapper->findAll($wie);
	}


	public function vindJouwCategorieen(string $wie): array {
		//throw new \Exception( "Vind reservaties van $wie" );
		return $this->mapper->findAllJouw($wie);
	}

	public function findPerCategorie($categorie, $wie, $groep): array {
		//throw new \Exception( "Vind reservaties van $wie" );
		return $this->mapper->findPerCategorie($categorie, $wie, $groep);
	}

	public function findAfgeschermde($categorie, $wie, $groep): array {
		//throw new \Exception( "Vind reservaties van $wie" );
		return $this->mapper->findPerCategorie($categorie, $wie, $groep);
	}


    private function handleException(Exception $e): void {
		if ($e instanceof DoesNotExistException ||
			$e instanceof MultipleObjectsReturnedException) {
			throw new LinkNotFound($e->getMessage());
		} else {
			throw $e;
		}
	}

	public function findMaand($userId, $cat, $wie, $dagenAr): array {
		$mAr = explode(' ', $cat);
		$maand = $mAr[0];
		$jaar = $mAr[1];
		//throw new \Exception( "Vind maand $maand van $userId voor het jaar $jaar" );
		return $this->mapper->vindOfMaakMaand($userId, $maand, $jaar, $wie, $dagenAr);
	}

	
	public function findMaandenVan($wie): array {
		//throw new \Exception( "Vind maanden van  $wie" );
		return $this->mapper->vindMaandenVan($wie);
	}

	
	public function create($wie, $email, $url, $naam, $pasw, $categorie, $groep) {
		//throw new \Exception( "Maak met boeking $boeking" );
		$note = new Link();
		$note->setWie($wie);
		$note->setEmail($email);
		$note->setUrl($url);
        $note->setNaam($naam);
		$note->setPasw($pasw);
		$note->setCategorie($categorie);
		$note->setGroep($groep);
		return $this->mapper->insert($note);
		
	}
	
	public function update($id, $wie, $email, $url, $naam, $pasw, $categorie, $groep) {
		try {
		$note = $this->mapper->find($id);
		$note->setWie($wie);
		$note->setEmail($email);
		$note->setUrl($url);
        $note->setNaam($naam);
		$note->setPasw($pasw);
		$note->setCategorie($categorie);
		$note->setGroep($groep);
		return $this->mapper->update($note);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	public function delete($id) {
		try {
			$note = $this->mapper->find($id);
			$this->mapper->delete($note);
			return ['Link is verwijderd'];
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	
}

