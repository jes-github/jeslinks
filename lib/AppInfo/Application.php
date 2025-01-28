<?php
namespace OCA\Jeslinks\AppInfo;

use Closure;
use OCA\Werkstaat\Notification\Notifier;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Notification\IManager as NotificationManager;
use OCP\User\Events;

class Application extends App implements IBootstrap {

	public const APP_ID = 'jeslinks';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}
    

    public function register(IRegistrationContext $context): void {
        // ... registration logic goes here ...

        // Register the composer autoloader for packages shipped by this app, if applicable
        //include_once __DIR__ . '/../../vendor/autoload.php';

        $context->registerEventListener(
            BeforeUserDeletedEvent::class,
            UserDeletedListener::class
        );


    }

    public function boot(IBootContext $context): void {
        // ... boot logic goes here ...
		$context->injectFn(Closure::fromCallable([$this, 'registerNotifications']));
        /** @var IManager $manager */
       // $manager = $context->getAppContainer()->query(IManager::class);
        //$manager->registerNotifierService(\OCA\Aanvragen\Notification\Notifier::class);
        }
    public function registerNotifications(NotificationManager $notificationManager): void {
            $notificationManager->registerNotifierService(Notifier::class);
        }

}
