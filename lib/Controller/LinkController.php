<?php
namespace OCA\Jeslinks\Controller;

use OCA\Jeslinks\AppInfo\Application;
use OCA\Jeslinks\Service\LinkService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\IRequest;

use OCP\Notification\INotification;
use OCP\Notification\IManager;



class LinkController extends Controller {
	/** @var LinkService */
	private $service;

	/** @var string */
	private $userId;

	use Errors;

	public function __construct(IRequest $request,
								LinkService $service,
								$userId) {
		parent::__construct(Application::APP_ID, $request);
		$this->service = $service;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 */
	public function index(): DataResponse {
		//throw new \Exception( "Controller op $userId");
		return new DataResponse($this->service->vindCategorieen('JES'));
	}

	/**
	 * @NoAdminRequired
	 */
	public function jouwlinks(): DataResponse {
		//throw new \Exception( "Controller op $userId" );
		return new DataResponse($this->service->vindJouwCategorieen($this->userId));
	}

    /**
	 * @NoAdminRequired
	 */
	public function percategorie($categorie, $wie): DataResponse {
		//throw new \Exception( "Controller op $categorie" );
		return new DataResponse($this->service->findPerCategorie($categorie, $wie));
	}



	/**
	 * @NoAdminRequired
	 */
	public function zoeken($cat, $wie, $dagenAr): DataResponse {
		//throw new \Exception( "Controller op $cat" );
		return new DataResponse($this->service->findMaand($this->userId, $cat, $wie, $dagenAr));
	}
	
	/**
	 * @NoAdminRequired
	 */
	public function vanwie($wie): DataResponse {
		//throw new \Exception( "Controller op $wie" );
		return new DataResponse($this->service->findMaandenVan($wie));
	}

	/**
	 * @NoAdminRequired
	 */
	public function alles($cat): DataResponse {
		//throw new \Exception( "Controller op $cat" );
		return new DataResponse($this->service->findAlles($cat));
	}

	/**
	 * @NoAdminRequired
	 */
	public function show(int $id): DataResponse {
		return $this->handleNotFound(function () use ($id) {
			return $this->service->find($id, $this->userId);
		});
	}

	/**
	 * @NoAdminRequired
	 */
	public function create($wie, $email, $url, $naam, $pasw, $categorie, $groep): DataResponse {
		return new DataResponse($this->service->create($wie, $email, $url, $naam, $pasw, $categorie, $groep));
	}


	/**
	 * @NoAdminRequired
	 */
	public function update(int $id, $wie, $email, $url, $naam, $pasw, $categorie, $groep): DataResponse {
		//throw new \Exception( "Route OK? $dagstartuur" );
		return $this->handleNotFound(function () use ($id, $wie, $email, $url, $naam, $pasw, $categorie, $groep) {
			return $this->service->update($id, $wie, $email, $url, $naam, $pasw, $categorie, $groep);
		});
	}

	/**
	 * @NoAdminRequired
	 */
	public function destroy(int $id): DataResponse {
		return $this->handleNotFound(function () use ($id) {
			return $this->service->delete($id);
		});
	}

	public function stuurN($id, $coordid, $user) {
		$manager = \OC::$server->getNotificationManager();
		$notification = $manager->createNotification();
	
		$notification->setApp('reserveren') // My app ID
			->setUser($coordid)
			->setDateTime(new \DateTime())
			->setObject('reservatie', $id) // What are these for?
			->setSubject('reservatie')
			->setMessage('reservatie door '.$user)
		;
		$manager->notify($notification);
		}

}
