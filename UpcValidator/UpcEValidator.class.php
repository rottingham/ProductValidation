<?
namespace ProductValidator\UpcEValidator;
require_once __DIR__ . '/UpcEExpander.class.php';
require_once __DIR__ . '/UpcESupressor.class.php';
require_once __DIR__ . '/UpcException.class.php';

use ProductValidator\UpcException as UpcException;

/**
 * UPC-E (GTIN12) Validator
 *
 * Validates a given string to ensure it follows the
 * UPC (Universal Product Code) 12 digit UPC-E Standard.
 *
 * Author: Ralph Brickley <brickleyralp@gmail.com>
 */
class UpcEValidator {



	/**
	 * Validate
	 *
	 * Determines if the UPC value is valid, IE the check digit
	 * matches.
	 *
	 * @param string $upc UPC to be inspected
	 * @throws UpcException If the UPC value is less than 6 digits or more than 12
	 * @return Returns TRUE if the UPC matches the UPC-E (GTIN12) standard,
	 * FALSE otherwise.
	 */
	public static function validate($upc) {
		$upc = trim($upc);


		if (!is_numeric($upc)) {
			throw new UpcException\UpcException('UPC Value can only contain numbers. UPC: ' .
				$upc, UpcException\UpcException::CODE_CONTAINS_CHARACTERS);
		}

		// Expand UPC
		$upc = UpcEExpander::expand($upc);
		$length = strlen($upc);

		if (!$upc) {
			return false;
		}

		$checkDigit = UpcEValidator::getCheckDigit($upc);

		return intval($upc[$length-1]) === intval($checkDigit);
	}

	/**
	 * getCheckDigit
	 *
	 * Calculates the check digit of a UPC code and returns in.
	 * @param string $upc UPC value to
	 * @return Returns the check digit.
	 */
	public static function getCheckDigit($upc) {

		$length = strlen($upc);
		$oddSum = 0;
		$evenSum = 0;

		for ($i = 1; $i < $length; $i++) {
			if (($i % 2) === 0) {
				$evenSum += $upc[$i-1];
			} else {
				$oddSum += $upc[$i-1];
			}
		}

		$totalSum = $evenSum + ($oddSum * 3);
		$modulo10 = $totalSum % 10;
		$checkDigit = 10 - $modulo10;

		return $checkDigit;
	}
}
