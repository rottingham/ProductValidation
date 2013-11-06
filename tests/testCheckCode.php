<?

require_once __DIR__ . '/../ProductValidator.class.php';

$upc = '748196000441';
$ean = '9781560213635';
$isbn = '1560213639';

var_dump(ProductValidator\ProductValidator::checkCode($upc));
var_dump(ProductValidator\ProductValidator::checkCode($ean));
var_dump(ProductValidator\ProductValidator::checkCode($isbn));
