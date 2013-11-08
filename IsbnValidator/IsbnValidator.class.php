<?
namespace ProductValidator\IsbnValidator;
require_once __DIR__ . '/IsbnException.class.php';

use ProductValidator\IsbnException as IsbnException;
/**
 * ISBN Validator
 *
 * Validates International Standard Book Number code.
 *
 * Supports ISBN-10 and ISBN-13.
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
class IsbnValidator {

	/**
	 * Validate
	 *
	 * Validates the ISBN code.
	 *
	 * If it matches the standard for ISBN10 or ISBN13 format,
	 * an array with the EAN, Group, Publisher, Title and Check
	 * Digit will filled in the reference array parameter.
	 *
	 * @param string $isbn ISBN to be inspected
	 * @param array &$refArray Reference to an array to fill with
	 * Publication information
	 * @return Returns TRUE if the ISBN check digit validates, otherwise FALSE
	 */
	public static function validate($isbn, array &$refArray = null) {

		$isbn = trim($isbn);
		$isbn = preg_replace('/[^0-9xX]/', '', strtoupper($isbn));
		$length = strlen($isbn);

		if ($length < 10 || ($length > 10 && $length < 13)  || $length > 13) {
			throw new IsbnException\IsbnException('ISBN length is invalid. ISBN codes must be either 10 or 13 digits in length. ISBN: ' .
				$isbn . ' ( length = '.strlen($isbn).')',
				IsbnException\IsbnException::CODE_INVALID);
		}

		if ($length === 10) {

			// ISBN-10
			$result = self::validateIsbn10($isbn);
			if ($result === false) {
				return false;
			}

			if (isset($refArray)) {
				$refArray = $result;
			}

			return true;

		} else if ($length === 13) {

			// ISBN-13
			$result = self::validateIsbn13($isbn);
			if ($result === false) {
				return false;
			}

			if (isset($refArray)) {
				$refArray = $result;
			}

			return true;
		}

		return false;
	}

	/**
	 * Validate ISBN-10 Format
	 *
	 * Used internally by IsbnValidator::validate() which performs
	 * string formatting before validating.
	 *
	 * @param string $isbn ISBN number to be validated
	 * @return Returns FALSE if the ISBN is not a valid number, otherwise
	 * returns the array set of number parts.
	 */
	private static function validateIsbn10($isbn) {
		$check = IsbnValidator::getCheckDigit($isbn, 10);
		$remainder = $check % 11;

		// If the remainder is empty, code is valid. Fill in the parts
		if ($remainder === 0) {
			return self::getIsbnParts($isbn);
		} else {
			return false;
		}
	}

	/**
	 * Validate ISBN-13 Format
	 *
	 * Used internally by IsbnValidator::validate() which performs
	 * string formatting before validating.
	 *
	 * @param string $isbn ISBN number to be validated
	 * @return Returns FALSE if the ISBN is not a valid number, otherwise
	 * returns the array set of number parts.
	 */
	private static function validateIsbn13($isbn) {
		$check = IsbnValidator::getCheckDigit($isbn, 13);
		$remainder = $check % 10;

		if ($remainder === 0) {
			return self::getIsbnParts($isbn);
		} else {
			return false;
		}
	}

	/**
	 * getCheckDigit
	 *
	 * Gets the Check digit of the ISBN code
	 * @param string $isbn ISBN code
	 * @param integer $format Format is either 10 (ISBN10) or 12 (ISBN12)
	 * @return Returns the Check digit for the code
	 */
	public static function getCheckDigit($isbn, $format = 10) {
		$check = 0;
		$length = strlen($isbn);

		if ($format === 13) {
			for ($i = 0; $i < $length; $i+=2) {
				$check += substr($isbn, $i, 1);
			}
			for ($i = 1; $i < $length - 1; $i+=2) {
				$check += 3 * substr($isbn, $i, 1);
			}
		} else {
			for ($i = 0; $i < $length; $i++) {
				$value = $isbn[$i] === 'X' ? 10 : $isbn[$i];
				$check += (10 - $i) * $value;
			}
		}

		return $check;
	}


	/**
	 * Get ISBN Number Parts
	 *
	 * Splits the ISBN number into seperate Group,
	 * Publisher, Title and Checkdigit and EAN for ISBN-13
	 * and returns the array.
	 *
	 * @param string $isbn ISBN to parse
	 * @return Returns an array of book number items
	 */
	private static function getIsbnParts($isbn) {

		// Get ISBN-10 or ISBN-13 Book Number Parts
		if (strlen($isbn) === 10) {
			$bookItems['Group'] = substr($isbn, 0, 2);
			$bookItems['Publisher'] = substr($isbn, 2, 4);
			$bookItems['Title'] = substr($isbn, 6, 3);
			$bookItems['CheckDigit'] = substr($isbn, 9, 1);
		} else {
			$bookItems['EAN'] = substr($isbn, 0, 3);
			$bookItems['Group'] = substr($isbn, 3, 2);
			$bookItems['Publisher'] = substr($isbn, 5, 4);
			$bookItems['Title'] = substr($isbn, 9, 3);
			$bookItems['CheckDigit'] = substr($isbn, 11, 1);
		}

		return $bookItems;
	}

}
