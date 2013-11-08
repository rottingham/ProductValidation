<?
namespace ProductValidator\UpcEValidator;
require_once __DIR__ . '/UpcException.class.php';

use ProductValidator\UpcException as UpcException;

/**
 * UPC-E Suppressor
 *
 * Supresses a 12 digit UPC code down to its 6, 7 or 8 digit format.
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
class UpcESupressor {

	/**
	 * Supress
	 *
	 * Supresses a UPC string based on 4 conditional options.
	 *
	 * @param string $upc 12-Digit UPC to Suppress
	 * @throws UpcException If the UPC is less than or more than 12 digits
	 * @return Returns the 6, 7 or 8 digit suppressed UPC-E code
	 */
	public static function suppress($upc) {
		$conditionA = UpcESupressor::suppressConditionA($upc);
		if ($conditionA !== false) {
			return $conditionA;
		}

		$conditionB = UpcESupressor::suppressConditionB($upc);
		if ($conditionA !== false) {
			return $conditionB;
		}

		$conditionC = UpcESupressor::suppressConditionC($upc);
		if ($conditionA !== false) {
			return $conditionC;
		}

		$conditionD = UpcESupressor::suppressConditionD($upc);
		if ($conditionA !== false) {
			return $conditionD;
		}

		return false;
	}

	/**
	 * UPCE Supression Condition A
	 *
	 * (Example: 023456000073 = 02345673)
	 *
	 * If D11 equals 5, 6, 7, 8 or 9, and D6 is not 0, and D7-D10 are all 0, then,
	 * DataToEncode = D1 D2 D3 D4 D5 D6 D11 D12
	 *
	 * @param string $upc UPC to Suppress to UPC-E standard.
	 * @return Returns FALSE if the string is not suppressed.
	 */
	private static function suppressConditionA($upc) {

		$upcE = '';

		$checkDigits = array (5,6,7,8,9);
		if (in_array($upc[10], $checkDigits) &&
			$upc[5] != 0 && $upc[6] == 0 &&
			$upc[7] == 0 && $upc[8] == 0 && $upc[9] == 0) {

			$upcE = substr($upc, 0, 6);
			$upcE .= $upc[10] . $upc[11];

			return $upcE;
		}

		return false;
	}


	/**
	 * UPCE Supression Condition B
	 *
	 * (Example: 023450000017 = 02345147)
	 *
	 * If D6-D10 are 0 and D5 is not 0, then,
	 * DataToEncode = D1 D2 D3 D4 D5 D11 "4" D12
	 *
	 * @param string $upc UPC to Suppress to UPC-E standard.
	 * @return Returns FALSE if the string is not suppressed.
	 */
	private static function suppressConditionB($upc) {

		$upcE = '';

		if ($upc[4] != 0 && $upc[5] == 0 && $upc[6] == 0 &&
			$upc[7] == 0 && $upc[8] == 0 && $upc[9] == 0) {

			$upcE = substr($upc, 0, 5);
			$upcE .= $upc[10] . $upc[11];

			return $upcE;
		}

		return false;
	}


	/**
	 * UPCE Supression Condition c
	 *
	 * (Example: 063200009716 = 06397126)
	 *
	 * If D5-D8 = 0 and D4 = 0, 1 or 2, then,
	 * DataToEncode = D1 D2 D3 D9 D10 D11 D4 D12
	 *
	 * @param string $upc UPC to Suppress to UPC-E standard.
	 * @return Returns FALSE if the string is not suppressed.
	 */
	private static function suppressConditionC($upc) {

		$upcE = '';

		$checkDigits = array(0,1,2);
		if ($upc[4] == 0 && $upc[5] == 0 && $upc[6] == 0 &&
			$upc[7] == 0 && (in_array($upc[3], $checkDigits))) {

			$upcE = substr($upc, 0, 3);
			$upcE .= $upc[8] . $upc[9] . $upc[10] . $upc[3] . $upc[11];

			return $upcE;
		}

		return false;
	}



	/**
	 * UPCE Supression Condition D
	 *
	 * (Example: 086700000939 = 08679339)
	 *
	 * If D5-D9 = 0 and D4 = 3-9, then,
	 * DataToEncode = D1 D2 D3 D4 D10 D11 "3" D12
	 *
	 * @param string $upc UPC to Suppress to UPC-E standard.
	 * @return Returns FALSE if the string is not suppressed.
	 */
	private static function suppressConditionD($upc) {

		$upcE = '';

		if ($upc[3] >= 3 && $upc[3] <= 9 && $upc[4] == 0 &&
			$upc[5] == 0 && $upc[6] == 0 && $upc[7] == 0 && $upc[8] == 0) {

			$upcE = substr($upc, 0, 4);
			$upcE .= $upc[9] . $upc[10] . $upc[11];

			return $upcE;
		}

		return false;
	}
}
