<?php
namespace OCA\Jeslinks\Service;

use OCP\HintException;
use OCP\IConfig;

class UitconfigService {
    private $config;
    private $appName;

    public function __construct(IConfig $config, string $appName){
        $this->config = $config;
        $this->appName = $appName;
    }

    public function getSystemValue(string $key) {
        return $this->config->getSystemValue($key);
    }

    public function setSystemValue(string $key, $value): void {
        try {
            $this->config->setSystemValue($key, $value);
        } catch (HintException $e) {
            // Handle exception, e.g. when config file is read-only
        }
    }
}
