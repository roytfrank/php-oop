<?php declare (strict_types = 1);
namespace App\Logger;
use App\Exception\InvalidLogLevelArgument;
use App\Contracts\LoggerInterface;
use App\Helpers\App;

class Logger implements LoggerInterface{

	public function emergency($message, array $context = array()) {
		$this->addRecord(LogLevel::EMERGENCY, $message, $context);

	}

	public function alert($message, array $context = array()) {
		$this->addRecord(LogLevel::ALERT, $message, $context);

	}

	public function critical($message, array $context = array()) {
		$this->addRecord(LogLevel::CRITICAL, $message, $context);


	}

	public function error($message, array $context = array()) {
		$this->addRecord(LogLevel::ERROR, $message, $context);

	}

	public function warning($message, array $context = array()) {
		$this->addRecord(LogLevel::WARNING, $message, $context);


	}

	public function notice($message, array $context = array()) {
		$this->addRecord(LogLevel::NOTICE, $message, $context);

	}

	public function info($message, array $context = array()) {
		$this->addRecord(LogLevel::INFO, $message, $context);


	public function debug($message, array $context = array()) {
		$this->addRecord(LogLevel::DEBUG, $message, $context);
	}


	public function log($level, $message, array $context = array()) {

		  $obj = new \ReflectionClass(LogLevel::class);
		  $logLevels = $obj->getConstants();

		  if(!in_array($level, $logLevels)){
              throw new InvalidLogLevelArgument($level, $logLevels);
		  }

		$this->addRecord($level, $message, $context);

	}

	private function addRecord($level, $message, array $context = array()) {

		$app = new App;

		//Get config with app
		$env = $app->getEnvironment();
		$date = $app->getServerDateTime()->format('Y-m-d H:i:s');
		$logPath = $app->getLogPath();

		$details = sprintf("%s - Level: %s - Message: %s - Content: %s", $date, $level, $message, json_encode($context)).PHP_EOL;

		$logfilename = sprintf("%s/%s-%s.log", $logPath, $env, date('j.n.Y'));

		//create log
		file_put_contents($logfilename, $details, FILE_APPEND);

	}

}

?>