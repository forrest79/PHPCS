<?php declare(strict_types=1);

namespace Forrest79CodingStandard\Sniffs\NamingConventions;

class FooClass
{

	public function fooMethod(): void
	{
		$correctVariable;
		$incorrect_variable;
		$obj->object_variable;
		MyClass::$class_variable;
		$_POST;
	}

}
