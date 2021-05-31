<?php declare(strict_types=1);

namespace Tools\PmgStandard\Sniffs\Methods;

use PHP_CodeSniffer\Util\Tokens;

final class FinalInjectMethodSniff implements \PHP_CodeSniffer\Sniffs\Sniff
{
	/** @var array<bool> */
	private array $isFinalClasses = [];


	/**
	 * @inheritDoc
	 */
	public function register(): array
	{
		return [T_FUNCTION];
	}


	/**
	 * @inheritDoc
	 */
	public function process(\PHP_CodeSniffer\Files\File $phpcsFile, $stackPointer): void
	{
		if (!$this->isFinalClass($phpcsFile) && !$this->isFinalInjectMethod($phpcsFile, $stackPointer)) {
			$phpcsFile->addError('Non final presenter class must have final inject methods', $stackPointer, 'MissingFinal');
		}
	}


	private function isFinalInjectMethod(\PHP_CodeSniffer\Files\File $phpcsFile, int $stackPointer): bool
	{
		$tokens = $phpcsFile->getTokens();
		if (preg_match('~^inject[A-Z]{1}.+~', $phpcsFile->getDeclarationName($stackPointer))) {
			$firstModifier = $phpcsFile->findPrevious(Tokens::$methodPrefixes, $stackPointer - 1);
			$secondModifier = $phpcsFile->findPrevious(Tokens::$methodPrefixes, $firstModifier - 1);
			return in_array(T_FINAL, [$tokens[$firstModifier]['code'], $tokens[$secondModifier]['code']], TRUE);
		}
		return TRUE;
	}


	private function isFinalClass(\PHP_CodeSniffer\Files\File $phpcsFile): bool
	{
		if (!isset($this->isFinalClasses[$phpcsFile->getFilename()])) {
			$tokens = $phpcsFile->getTokens();
			foreach ($tokens as $stackPointer => $token) {
				if ($token['code'] === T_CLASS) {
					$previous = $phpcsFile->findPrevious(Tokens::$methodPrefixes, $stackPointer - 1);
					return $this->isFinalClasses[$phpcsFile->getFilename()] = ($previous !== FALSE && $tokens[$previous]['code'] === T_FINAL);
				}
			}
			return $this->isFinalClasses[$phpcsFile->getFilename()] = FALSE;
		}
		return $this->isFinalClasses[$phpcsFile->getFilename()];
	}

}