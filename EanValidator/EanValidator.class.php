<?
namespace ProductValidator\EanValidator;
require_once __DIR__ . '/EanException.class.php';

use ProductValidator\EanException as EanException;

/**
 * EAN Validator
 *
 * Validates European Article Number code. Supports EAN-13.
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
class EanValidator {

	/**
	 * Validate
	 *
	 * Determines if the EAN value is valid, IE the check digit
	 * matches.
	 *
	 * @param string $ean EAN to be inspected
	 * @throws UpcException If the EAN value is less than 12 digits. 12
	 * Digit values will be padded to 13.
	 * @return Returns TRUE if the UPC matches the UPC standard,
	 * FALSE otherwise.
	 */
	public static function validate($ean) {

		$ean = trim($ean);
		$length = strlen($ean);

		if ($length < 13) {
			throw new EanException\EanException('EAN is less than 13 digits in length. EAN: ' .
				$ean . ' ( length = '.strlen($ean).')',
				EanException\EanException::CODE_INVALID);
		} else if ($length > 13) {
			throw new EanException\EanException('EAN is more than 13 digits in length. EAN: ' .
				$ean . ' ( length = '.strlen($ean).')',
				EanException\EanException::CODE_INVALID);
		}

		if (!is_numeric($ean)) {
			throw new EanException\EanException('EAN can only contain numbers. EAN: ' .
				$ean,
				EanException\EanException::CODE_CONTAINS_CHARACTERS);
		}

		$originalCheck = substr($ean, -1);
		$checkDigit = EanValidator::getCheckDigit($ean);
		return (intval($checkDigit) === intval($originalCheck));
	}

	/**
	 * getCheckDigit
	 *
	 * Calculates the check digit of a EAN code and returns in.
	 * @param string $ean Ean value to calculate
	 * @return Returns the check digit.
	 */
	public static function getCheckDigit($ean) {

		$length = strlen($ean);
		$originalCheck = substr($ean, -1);
		$ean = substr($ean, 0, -1);

		$oddSum = 0;
		$evenSum = 0;

		for ($i = 1; $i < $length; $i++) {
			if (($i % 2) === 0) {
				$evenSum += $ean[$i-1];
			} else {
				$oddSum += $ean[$i-1];
			}
		}

		$total = ($evenSum * 3) + $oddSum;
		$checkDigit = (ceil($total / 10) * 10) - $total;

		return intval($checkDigit);
	}

}





































