ProductValidation
=================

ProductValidator is a simply utility for Shopping cart software or custom projects to validate product codes.

Supports UPC-A, EAN13 and ISBN10/ISBN13.

This software is free to use, distribute and change as you see fit. 

#### Sample Usage

Simply import the `ProductValidator.class.inc` main class file if you are not utilizing auto loaders.

    require '/ProductValidator.class.inc';
    
To use the validator, you can simply call its `checkCode($code)` method:
    
    $result = ProductValidator\ProductValidator::checkCode('1560213639');
    
    var_dump($result);
    
`checkCode` attempts to smartly detect the codes type and returns and array containing the code `type`, (UPC, EAN, ISBN) 
the `checkDigit` for the code,  and the `isbn` pieces if the code is an ISBN.

#### Individual Code Validation

To validate (TRUE|FALSE) a code you already know the type of, use **ProductValidator**'s individual check functions:

    var_dump(ProductValidator\ProductValidator::checkUpc('748196000441');
    var_dump(ProductValidator\ProductValidator::checkEan('9781560213635');
    var_dump(ProductValidator\ProductValidator::checkIsbn('1560213635');

`checkUpc`, `checkEan` and `checkIsbn` return TRUE if the check digit and code validates, or FALSE if they do not. 

#### Validators

  - UpcValidator
  - EanValidator
  - IsbnValidator
