<?
namespace ProductValidator\IsbnException;

/**
 * UpcException
 *
 * ProductValidator ISBN Exception Class
 *
 * Author: Ralph Brickley <brickleyralph@gmail.com>
 */
class IsbnException extends \Exception {

	/*
	 * STATIC ERROR CODES
	 */
	const CODE_INVALID	= 0;
	const CODE_CONTAINS_CHARACTERS = 1;

	/**
	 * Public toString
	 *
	 * @return string Returns a string of Class, Exception Code and Exception message
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

}
