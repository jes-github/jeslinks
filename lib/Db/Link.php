<?php

namespace OCA\Jeslinks\Db;

use JsonSerializable;

// Alle velden die in de databank gebruikt worden moeten hier staan
// een hoofdletter in een veldnaam wordt omgezet in een underscore plus de betreffende letter


use OCP\AppFramework\Db\Entity;

class Link extends Entity implements JsonSerializable {
	
	protected $wie;
	protected $email;
	protected $url;
    protected $naam;
	protected $pasw;
	protected $categorie;
	protected $groep;
	
	public function jsonSerialize(): array {
		
		return [
			'id' => $this->id,
			'wie' => $this->wie,
			'email' => $this->email,
			'url' => $this->url,
            'naam' => $this->naam,
			'pasw' => $this->pasw,
			'categorie' => $this->categorie,
			'groep' => $this->groep,
		];
	}
}

