<?php
// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.php';
use ProductValidator\UpcEValidator as UpcEValidator;



$upcE6 = '654321';
$upcE7 = '654321';
$upcE8 = '10055564';

// Expand the 6 digit code.
//$code = UpcEValidator\UpcEExpander::expand($upcE6);
//var_dump($code);

$code = UpcEValidator\UpcEExpander::expand('049871');
var_dump($code);
