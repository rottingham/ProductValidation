<?

// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.php';
use ProductValidator\UpcEValidator as UpcEValidator;

$upcE6Expanded = '065100004327';

// Expand the 6 digit code.
$code = UpcEValidator\UpcESupressor::suppress($upcE6Expanded);

var_dump($code);
