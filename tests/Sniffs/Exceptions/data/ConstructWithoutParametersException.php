<?php declare(strict_types=1);

namespace Forrest79CodingStandard\Sniffs\Exceptions;

class ConstructWithoutParametersException extends \Exception
{

	public function __construct()
	{
		parent::__construct('error', 0, null);
	}

}
