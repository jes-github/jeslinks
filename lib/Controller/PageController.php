<?php

namespace OCA\Jeslinks\Controller;

use OCA\Jeslinks\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\IRequest;
use OCP\Util;

class PageController extends Controller {
	public function __construct(IRequest $request, Http $http) {
		parent::__construct(Application::APP_ID, $request);
		$this->http = $http;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * Render default template
	 */
	public function index() {
		Util::addScript(Application::APP_ID, 'jeslinks-main');
		Util::addStyle($this->appName, 'icons');
		$retour = new TemplateResponse(Application::APP_ID, 'main');
		$policy = new ContentSecurityPolicy();
		$policy->addAllowedFormActionDomain('https://mijnplaats.jes.be');
		$retour->setContentSecurityPolicy($policy);
		//Util::addAllowedFormActionDomain('https://mijnplaats.jes.be');
		return $retour;
	}


}
