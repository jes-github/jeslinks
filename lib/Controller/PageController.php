<?php

namespace OCA\Jeslinks\Controller;

use OCA\Jeslinks\AppInfo\Application;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IRequest;
use OCP\Util;

class PageController extends Controller {
	public function __construct(IRequest $request) {
		parent::__construct(Application::APP_ID, $request);
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
		return new TemplateResponse(Application::APP_ID, 'main');
		$csp = new EmptyContentSecurityPolicy();
        $csp->addAllowedFormActionDomain('https://mijnplaats.jes.be');
	}


}
