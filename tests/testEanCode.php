<?

// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.php';

$code = ProductValidator\ProductValidator::checkCode('9781560213635');

var_dump($code);
