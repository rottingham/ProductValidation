<?

// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.inc';

$code = ProductValidator\ProductValidator::checkCode('9781560213635');

var_dump($code);
