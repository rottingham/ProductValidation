<?php
namespace ProductValidator;

require_once __DIR__ . '/UpcValidator/UpcValidator.class.php';
require_once __DIR__ . '/UpcValidator/UpcEValidator.class.php';
require_once __DIR__ . '/UpcValidator/UpcEExpander.class.php';
require_once __DIR__ . '/EanValidator/EanValidator.class.php';
require_once __DIR__ . '/IsbnValidator/IsbnValidator.class.php';

use ProductValidator\UpcValidator as UpcValidator;
use ProductValidator\UpcEValidator as UpcEValidator;
use ProductValidator\EanValidator as EanValidator;
use ProductValidator\IsbnValidator as IsbnValidator;

/**
 * Product Validator
 *
 * Product validator is a utility class that
 * includes UPC, EAN and ISBN validators.
 *
 * The main focus is to determine what type of code is bring
 * provide by smartly detecting which is valid. 13 Digit EAN's also
 * pass as ISBN numbers, so unless ISBN and EAN both validate, it will
 * be considered an EAN.
 *
 *
 * Author: Ralph Brickley <brickleyralph@gmail.com>
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
class ProductValidator {

	/**
	 * checkCode
	 *
	 * Check Code will attempt to smartly detect the code type,
	 * IE UPC, EAN or ISBN10/13.
	 *
	 * Since most 13 DIGIT EAN's also validate as an ISBN, the type will be ISBN
	 * if the code validates as both.
	 *
	 * @param string $code Code to check.
	 * @return Returns an array with the code type, check digit and if
	 * the code is an ISBN, the ISBN parts. FALSE otherwise.
	 */
	public static function checkCode($code) {
		$isbn = array();
		$upcValid = false;
		$upcEValid = false;
		$eanValid = false;
		$isbnValid = false;

		try {
			$upcValid = ProductValidator::checkUpcA($code);
		} catch (UpcException\UpcException $e) {

		}

		try {
			$upcEValid = ProductValidator::checkUpcE($code);
		} catch (UpcException\UpcException $e) {

		}

		try {
			$eanValid = ProductValidator::checkEan($code);
		} catch (EanException\EanException $e) {

		}

		try {
			$isbnValid = ProductValidator::checkIsbn($code, $isbn);
		} catch (IsbnException\IsbnException $e) {

		}

		$type = '';
		$checkDigit = 0;

		// UPC Code
		if ($upcValid) {
			$type = 'UPC-A';
			$checkDigit = UpcValidator\UpcValidator::getCheckDigit($code);
		} else if ($upcEValid) {
			$type = 'UPC-E - ' . strlen($code);
			$code = UpcEValidator\UpcEExpander::expand($code);
			$checkDigit = UpcEValidator\UpcEValidator::getCheckDigit($code);
		} else if ($eanValid) {
			$type = 'EAN';
			$checkDigit = EanValidator\EanValidator::getCheckDigit($code);
		} else if ($isbnValid && !$eanValid) {
			$type = 'ISBN';
			$checkDigit = IsbnValidator\IsbnValidator::getCheckDigit($code);
		} else {
			return false;
		}

		// Return array of values
		return array(
			'type' => $type,
			'checkDigit' => $checkDigit,
			'isbn' => $isbn
		);
	}

	/**
	 * Check UPC Code
	 *
	 * @param string $code UPC-E Code to validate
	 * @return Returns TRUE if the UPC validates, otherwise FALSE
	 */
	public static function checkUpcA($code) {
		return UpcValidator\UpcValidator::validate($code);
	}

	/**
	 * Check UPC-E Code
	 *
	 * @param string $code UPC-A Code to validate
	 * @return Returns TRUE if the UPC validates, otherwise FALSE
	 */
	public static function checkUpcE($code) {
		return UpceValidator\UpceValidator::validate($code);
	}

	/**
	 * Check EAN Code
	 *
	 * @param string $code EAN Code to validate
	 * @return Returns TRUE if the EAN validates, otherwise FALSE
	 */
	public static function checkEan($code) {
		return EanValidator\EanValidator::validate($code);
	}

	/**
	 * Check ISBN Code
	 *
	 * @param string $code ISBN Code to validate
	 * @param array &$isbn Reference Array to fill with ISBN info
	 * @return Returns TRUE if the ISBN validates, otherwise FALSE
	 */
	public static function checkIsbn($code, &$isbn) {
		return IsbnValidator\IsbnValidator::validate($code, $isbn);
	}
}


