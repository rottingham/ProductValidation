<?php
// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.php';

$upcA = '748196000441';
$upcE6Expanded = '065100004327'; // 654321 UPC-E
$upcE6Normal = '654321';
$upcE8Normal = '10055564';

// Check Code to see what Type is is:
//$code = ProductValidator\ProductValidator::checkCode($upcA);
//var_dump($code);

//$code = ProductValidator\ProductValidator::checkCode($upcE6Normal);
//var_dump($code);

$code = ProductValidator\ProductValidator::checkCode('012800000890');
var_dump($code);


