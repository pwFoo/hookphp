<?php

error_reporting(E_ALL);
require 'src/H.php';
use SciActive\H as H;

echo "Testing...<br>\n";

class Test {
	public $test = 'right';

	public function testFunction($argument) {
		echo "Function redefinition failed.<br>\n";
	}

	public function testFunctionReal($argument) {
		echo "Output: $argument<br>\n";
		return 2;
	}
}

$obj = new Test;
H::hookObject($obj, 'Test->');
H::addCallback('Test->testFunction', -2, function(&$arguments, $name, &$object, &$function, &$data){
	$arguments[0] = 'Still Failure!';
	if ($object->test !== 'right') {
		echo "Object check failed.<br>\n";
	} else {
		echo "Object check passed.<br>\n";
	}
	if ($name !== 'Test->testFunction') {
		echo "Name check failed.<br>\n";
	} else {
		echo "Name check passed.<br>\n";
	}
});
H::addCallback('Test->testFunction', -1, function(&$arguments, $name, &$object, &$function, &$data){
	$arguments[0] = 'Success!';
	$data['test'] = 1;
	$function = array($object, 'testFunctionReal');
});
H::addCallback('Test->testFunction', 1, function(&$return, $name, &$object, &$function, &$data){
	if ($data['test'] !== 1) {
		echo "Data check failed.<br>\n";
	} else {
		echo "Data check passed.<br>\n";
	}
	if ($name !== 'Test->testFunction') {
		echo "Name check failed.<br>\n";
	} else {
		echo "Name check passed.<br>\n";
	}
	if ($return[0] !== 2) {
		echo "Return value check failed.<br>\n";
	} else {
		echo "Return value check passed.<br>\n";
	}
});

$obj->testFunction('Failure!');
