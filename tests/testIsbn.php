<?

// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.inc';

$code = ProductValidator\ProductValidator::checkCode('1560213639');

var_dump($code);
