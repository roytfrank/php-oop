<?php declare (strict_types = 1);

namespace App\Exception;
use App\Helpers\App;
use ErrorException;
use Throwable;

class ExceptionHandler {

	public function handle(Throwable $exception): void{
		$app = new App;

		if ($app->isDebugMode()) {
			var_dump($exception);
		} else {
			echo "An error occured!. Please try again or contact support";
		}
		exit;
	}

	public function converWarningsAndNoticesToException($severity, $message, $file, $line) {
		throw new \ErrorException($message, $severity, $severity, $file, $line);
	}
}
?>