<?php

namespace App\Helpers;
use App\Helpers\Config;
use DateTime;
use DateTimeInterface;
use DateTimezone;

class App {
	private $configs = [];

	public function __construct() {
		$this->configs = Config::get("app");
	}

	public function isDebugMode(): bool {
		if (!isset($this->configs['debug'])) {
			return false;
		}

		//this will return the boolean we have for this
		return $this->configs['debug'];
	}

	public function getEnvironment(): string {
		if (!isset($this->configs['env'])) {
			return "production";
		}

		return $this->isTestMode() ? 'test' : $this->configs['env'];
	}

	public function getServerDateTime(): DateTimeInterface {
		return new DateTime("now", new DateTimezone("America/New_York"));

	}

	public function getLogPath(): string {
		if (!isset($this->configs['log_path'])) {
			throw new \App\Exception\NotFoundException('Log path is not defined');
		}

		return $this->configs['log_path'];
	}

	public function isRunningFromConsole() {
		return php_sapi_name() == "cli" || php_sapi_name() == "phpbg";
	}

	public function isTestMode(){
		if($this->isRunningFromConsole() && defined("PHPUNIT_TEST") && PHPUNIT_TEST === true){
			return true;
		}

	   return false;
	}

}

?>