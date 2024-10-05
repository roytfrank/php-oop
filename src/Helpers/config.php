<?php
namespace App\Helpers;
use App\Exception\NotFoundException;

class Config {

	public static function get(string $filename, string $key = null) {

		$content = self::getFileContent($filename);

		if ($key === null) {
			return $content;
		}
		return isset($content[$key]) ? $content[$key] : [];
	}

	public static function getFileContent(string $filename): array{

		$fileContent = [];

			$path = realpath(sprintf(__DIR__ . "/../Config/%s.php", $filename));

			if (file_exists($path)) {
				$fileContent = require $path;
			}else{
			throw new NotFoundException(
				sprintf("The file " . $filename . " is not found"));
		    }

		return $fileContent;
	}
}

?>
