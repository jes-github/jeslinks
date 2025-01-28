<?php
declare(strict_types=1);

namespace OCA\Werkstaat\Notification;

use DateTime;
use OCA\Werkstaat\AppInfo\Application;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\L10N\IFactory;
use OCP\Notification\INotification;
use OCP\Notification\INotifier;




class Notifier implements \OCP\Notification\INotifier {

    protected $factory;
	protected $url;
    
	public function __construct(\OCP\L10N\IFactory $factory,
								\OCP\IURLGenerator $urlGenerator) {
		$this->factory = $factory;
		$this->url = $urlGenerator;
        }

	/**
	 * Identifier of the notifier, only use [a-z0-9_]
	 * @return string
	 */

     public function getID(): string {
                return 'werkstaat';
	}

	/**
	 * Human readable name describing the notifier
	 * @return string
	 */

     public function getName(): string {
		return $this->factory->get('werkstaat')->t('werkstaat');
	}

	/**
	 * @param INotification $notification
	 * @param string $languageCode The code of the language that should be used to prepare the notification
	 */

     public function prepare(INotification $notification, string $languageCode): INotification {
           
		if ($notification->getApp() !== 'werkstaat') {
           // Not my app => throw
			throw new \InvalidArgumentException();
		}

		// Read the language from the notification
		$l = $this->factory->get('werkstaat', $languageCode);
        $l  = "nl";
        $s = $notification->getSubject();
        
        //throw new \Exception( "We zitten hier en \$l = $l en \$languageCode = $languageCode" );
        switch ($notification->getSubject()) {
			// Deal with known subjects
            	case 'VTOK-aanvraag':
				//throw new \Exception( "Een VTOK-aanvraag en \$s = $s" );
                // $notification->setIcon($this->url->getAbsoluteURL($this->url->imagePath('core', 'actions/share.svg')));
		    	//->setLink($this->url->linkToRouteAbsolute('files_sharing.RemoteShare.overview', ['id' => $notification->getObjectId()]));
				// Set rich subject, see https://github.com/nextcloud/server/issues/1706 for more information
				// and https://github.com/nextcloud/server/blob/master/lib/public/RichObjectStrings/Definitions.php
				// for a list of defined objects and their parameters.
				$parameters = $notification->getSubjectParameters();
				$notification->setRichSubject($notification->getMessage());
			    // Set the plain text subject automatically
				$this->setParsedSubjectFromRichSubject($notification);
				return $notification;
				case 'Geldaanvraag':
				$parameters = $notification->getSubjectParameters();
				$notification->setRichSubject($notification->getMessage());
				// Set the plain text subject automatically
				$this->setParsedSubjectFromRichSubject($notification);
				return $notification;	
                default:
                //in test zie je zo waar we zitten    
                //throw new \Exception( "We zitten onderaan en \$l = $l" );
				// Unknown subject => Unknown notification => throw
				throw new \InvalidArgumentException();

		}
    }
// This is a little helper function which automatically sets the simple parsed subject
	// based on the rich subject you set.
	protected function setParsedSubjectFromRichSubject(INotification $notification) {
		$placeholders = $replacements = [];
		foreach ($notification->getRichSubjectParameters() as $placeholder => $parameter) {
			$placeholders[] = '{' . $placeholder . '}';
			if ($parameter['type'] === 'file') {
				$replacements[] = $parameter['path'];
			} else {
				$replacements[] = $parameter['name'];
			}
		}

		$notification->setParsedSubject(str_replace($placeholders, $replacements, $notification->getRichSubject()));
	}
//throw new \Exception( "Hier" );
}