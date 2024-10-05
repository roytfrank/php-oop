<?php declare (strict_types = 1);
namespace App\Exception;
use Exception;
use Throwable;

abstract class BaseException extends Exception {
	protected $data = [];

	public function __construct(string $message = " ", array $data = [], int $code = 0, Throwable $previous = null) {

		//to get data
		$this->data = $data;

		parent::__construct($message, $code, $previous);
	}

	//if we are setting data passwed with exception. We can set more data that a single d array
	public function setData($key, $value): void{
		$this->data[$key] = $value;
	}

	public function getExtraData(): array{

		if (count($this->data) > 0) {
			return json_decode(json_encode($this->data), true);
		}
		return $this->data;
	}

}

?>