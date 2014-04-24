<?
namespace ProductValidator\UpcValidator;
require_once __DIR__ . '/UpcException.class.php';

use ProductValidator\UpcException as UpcException;

/**
 * UPC Validator
 *
 * Validates a given string to ensure it follows the
 * UPC (Universal Product Code) 12 digit UPC-A Standard.
 *
 * Author: Ralph Brickley <brickleyralp@gmail.com>
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 rottingham
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
class UpcValidator {

	/**
	 * Validate
	 *
	 * Determines if the UPC value is valid, IE the check digit
	 * matches.
	 *
	 * @param string $upc UPC to be inspected
	 * @throws UpcException If the UPC value is less than 12 digits.
	 * @return Returns TRUE if the UPC matches the UPC standard,
	 * FALSE otherwise.
	 */
	public static function validate($upc) {
		$upc = trim($upc);
		$length = strlen($upc);

		// Attempt to validate a UPC-E Code
		if ($length > 12) {
			throw new UpcException\UpcException('UPC Value is more than 12 digits in length. UPC: ' .
				$upc . ' ( length = '.strlen($upc).')',
				UpcException\UpcException::CODE_INVALID);
		}

		if ($length < 12) {
			throw new UpcException\UpcException('UPC Value is less than 12 digits in length. Try using UpcEvalidator. UPC: ' .
				$upc . ' ( length = '.strlen($upc).')',
				UpcException\UpcException::CODE_INVALID);
		}

		if (!is_numeric($upc)) {
			throw new UpcException\UpcException('UPC Value can only contain numbers. UPC: ' .
				$upc, UpcException\UpcException::CODE_CONTAINS_CHARACTERS);
		}

		// If the UPC is 6, 7 or 8 digits, validate as UPC-E

		$checkDigit = UpcValidator::getCheckDigit($upc);

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
		$checkDigit = ($modulo10 !== 0) ? 10 - $modulo10 : $modulo10;
		return $checkDigit;
	}
}
