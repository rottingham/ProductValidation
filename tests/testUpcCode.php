<?

// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.inc';

// Check Code to see what Type is is:
$code = ProductValidator\ProductValidator::checkCode('748196000441');

var_dump($code);
