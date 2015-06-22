<?php
// Load Product Validator
// Recommended: Use Auto Loader
require_once __DIR__ . '/../ProductValidator.class.php';

$code = ProductValidator\ProductValidator::checkCode('1560213639');

var_dump($code);
