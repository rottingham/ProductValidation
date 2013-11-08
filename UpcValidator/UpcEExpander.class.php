<?
namespace ProductValidator\UpcEValidator;
require_once __DIR__ . '/UpcException.class.php';
require_once __DIR__ . '/UpcValidator.class.php';

use ProductValidator\UpcException as UpcException;

/**
 * UPC-E Expander
 *
 * Expands a 6, 7 or 8 digit UPC-E code into its 12 digit UPC-A format
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
class UpcEExpander {

	/**
	 * Expand
	 *
	 * Expand the 6, 7 or 8 digit UPC-E code into its 12 digit
	 * UPC-A equivalent.
	 *
	 * @param string $upc UPC-E value to expand
	 * @return Returns the expanded, 12 digit UPC-A code, or
	 * FALSE if the UPC-E code is less than 6 digits or more than 8 digits.
	 */
	public static function expand($upc) {
		$upc = trim($upc);
		$length = strlen($upc);

		if ($length === 6) {
			return UpcEExpander::expand6($upc);
		} else if ($length === 7) {
			return UpcEExpander::expand7($upc);
		} else if ($length === 8) {
			return UpcEExpander::expand8($upc);
		}

		return false;
	}

	/**
	 * Expand 6
	 *
	 * Expand a 6 digit UPC-E code to UPC-A 12 digit code.
	 * The last digit is the UPC-E Check Number
	 *
	 * @param string $upc UPC-E Code to expand
	 * @return Returns the expanded UPC code
	 */
	private static function expand6($upc) {

		// Last digit is the UPC-E Check Number
		$last = substr($upc, count($upc)-2, 1);

		return UpcEExpander::createUpcA($upc, $last);
	}

	/**
	 * Epand 7
	 *
	 * Expand a 7-digit UPC-E code to UPC-A 12 digit code.
	 * The first digit becomes the UPC-E Check Number and the last
	 * digit is stripped.
	 *
	 * @param string $upc UPC-E Code to expand
	 * @return Returns the expanded UPC code
	 */
	private static function expand7($upc) {
		// 7 Digit UPC-E uses the first digit as the UPC-E Check Number (normally the last digit)
		$last = substr($upc, 0, 1);

		// Discard literal last digit
		$upc = substr($upc, 1, 6);

		return UpcEExpander::createUpcA($upc, $last);
	}

	/**
	 * Epand 8
	 *
	 * Expand a 8-digit UPC-E code to UPC-A 12 digit code.
	 * The first digit becomes the UPC-E Check Number and then the
	 * first and last digits are stripped. The last digit is then
	 * re-appended as the check digit;
	 *
	 * @param string $upc UPC-E Code to expand
	 * @return Returns the expanded UPC code
	 */
	private static function expand8($upc) {
		// Get literal first and last digits
		$first = substr($upc, 0, 1);
		$last = substr($upc, 7, 1);

		// Strip UPC to middle 6 digits
		$upc = substr($upc, 1, 6);

		// Create UPC from 6 digit value
		$upc = UpcEExpander::createUpcA($upc, $last, $last);

		// Get new check digit and append
		$checkDigit = \ProductValidator\UpcValidator\UpcValidator::getCheckDigit($upc);
		$upc = substr($upc, 0, 11) . $checkDigit;

		return $upc;
	}

	/**
	 * Create Upc A
	 *
	 * Create the UPC-A code
	 *
	 * @param string $upcE code
	 * @param integer $parityNumber Parity number to prepend
	 * @return Returns the 12-Digit UPC-A code
	 */
	 public static function createUpcA($upcE, $lastDigit, $checkDigit = null) {
		$parity = UpcEExpander::getParity($upcE);

		switch ($lastDigit) {
			case 0:
				$upc = $upcE[0] . $upcE[1] . '00000' . $upcE[2] . $upcE[3] . $upcE[4];
				break;
			case 1:
				$upc = $upcE[0] . $upcE[1] . '10000' . $upcE[2] . $upcE[3] . $upcE[4];
				break;
			case 2:
				$upc = $upcE[0] . $upcE[1] . '20000' . $upcE[2] . $upcE[3] . $upcE[4];
				break;
			case 3:
				$upc = $upcE[0] . $upcE[1] . $upcE[2] . '00000' . $upcE[3] . $upcE[4];
				break;
			case 4:
				$upc = $upcE[0] . $upcE[1] . $upcE[2] . $upcE[3] . $upcE[4] . '0000' . $upcE[5];
				break;
			case 5:
				$upc = $upcE[0] . $upcE[1] . $upcE[2] . $upcE[3] . $upcE[4] . '0000';
				break;
			case 6:
				$upc = $upcE[0] . $upcE[1] . $upcE[2] . $upcE[3] . $upcE[4] . '0000';
				break;
			case 7:
				$upc = $upcE[0] . $upcE[1] . $upcE[2] . $upcE[3] . $upcE[4] . '0000';
				break;
			case 8:
				$upc = $upcE[0] . $upcE[1] . $upcE[2] . $upcE[3] . $upcE[4] . '0000';
				break;
			case 9:
				$upc = $upcE[0] . $upcE[1] . $upcE[2] . $upcE[3] . $upcE[4] . '0000';
				break;
		}

		$upcA = $parity['parity'] . $upc . $parity['checkDigit'];

		return $upcA;
	}

	/**
	 * Match Parity Pattern
	 *
	 * @param string $upc UPC
	 * @param string Pattern Even/Odd pattern, IE EEEOOE
	 * @return Returns TRUE if the UPC matches the parity pattern,
	 * FALSE otherwise.
	 */
	private static function matchParityPattern($upc, $pattern) {
		return $pattern == UpcEExpander::getParityPattern($upc);
	}

	/**
	 * Get Parity Pattern
	 *
	 * @param string $upc UPC to decipher
	 * @return Returns the Parity Pattern, IE EEEOOE
	 */
	private static function getParityPattern($upc) {
		$parityPattern = '';

		for ($i = 0; $i < 6; $i++) {
			$digit = $upc[$i];

			if ((intval($digit) & 1) === 0) {
				$parityPattern .= 'E';
			} else {
				$parityPattern .= 'O';
			}
		}
		return $parityPattern;
	}

	/**
	 * Get Parity
	 *
	 * Gets the parity pattern number (0 or 1) and the associated check digit.
	 *
	 * When creating an expanded UPC-A value, the parity number is prepended
	 * to the expanded value and the check digit is appended.
	 *
	 * @param string $upc UPC to Match
	 *
	 */
	private static function getParity($upc) {
		$upcCheckDigit = null;
		$upcParityDigit = null;

		$checkDigitParityPattern = array(
			0 => array ('EEEOOO' => 0, 'OOOEEE' => 1),
			1 => array ('EEOEOO' => 0, 'OOEOEE' => 1),
			2 => array ('EEOOEO' => 0, 'OOEEOE' => 1),
			3 => array ('EEOOOE' => 0, 'OOEEEO' => 1),
			4 => array ('EOEEOO' => 0, 'OEOOEE' => 1),
			5 => array ('EOOEEO' => 0, 'OEEOOE' => 1),
			6 => array ('EOOOEE' => 0, 'OEEEOO' => 1),
			7 => array ('EOEOEO' => 0, 'OEOEOE' => 1),
			8 => array ('EOEOOE' => 0, 'OEOEEO' => 1),
			9 => array ('EOOEOE' => 0, 'OEEOEO' => 1),
		);


		// Loop through each check digit pattern
		// If our UPC matches either parity pattern, set the check
		// digit value and the parity value
		foreach ($checkDigitParityPattern as $checkDigit => $patterns) {
			foreach ($patterns as $pattern => $parity) {
				if (UpcEExpander::matchParityPattern($upc, $pattern)) {
					$upcCheckDigit = $checkDigit;
					$upcParityDigit = $parity;
				}
			}
		}

		return array (
			'checkDigit' => $upcCheckDigit,
			'parity' => $upcParityDigit
		);
	}
}
