<?php
namespace OCA\Jeslinks\Controller;

use OCA\Jeslinks\AppInfo\Application;
use OCA\Jeslinks\Service\UitconfigService;
use OCP\AppFramework\Controller;
use OCP\IRequest;



class UitconfigController extends Controller {
	/** @var Uitconfigservice */
	private $service;

	/** @var string */
	private $userId;

	use Errors;

	public function __construct(IRequest $request,
								UitconfigService $service,
								$userId) {
		parent::__construct(Application::APP_ID, $request);
		$this->service = $service;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 */
	public function index() {
		//throw new \Exception( "Controller op $userId");
		return $this->service->getSystemValue('jeslinks');
	}

}
