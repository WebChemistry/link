<?php declare(strict_types = 1);

namespace WebChemistry\Link\UI;

use WebChemistry\Link\LinkGenerator;

trait LinkToActionTrait
{

	private LinkGenerator $_linkGenerator;

	final public function injectLinkParser(LinkGenerator $linkGenerator): void
	{
		$this->_linkGenerator = $linkGenerator;
	}

	/**
	 * @return never
	 */
	public function redirectToAction(object $destination, mixed ... $arguments): void
	{
		$link = $this->_linkGenerator->createActionLink($destination, $this->parseArguments($arguments));
		$destination = ':' . $link->getDestination();
		$destination = ($link->isAbsolute() ? '//' : '') . $destination;

		$this->getPresenter()->redirect($destination, $link->getParameters());
	}

	public function linkToAction(object $destination, mixed ... $arguments): string
	{
		$link = $this->_linkGenerator->createActionLink($destination, $this->parseArguments($arguments));
		$destination = ':' . $link->getDestination();
		$destination = ($link->isAbsolute() ? '//' : '') . $destination;

		return $this->getPresenter()->link($destination, $link->getParameters());
	}

	/**
	 * @param mixed[] $arguments
	 * @return mixed[]
	 */
	private function parseArguments(array $arguments): array
	{
		return count($arguments) === 1 && isset($arguments[0]) && is_array($arguments[0])
			? $arguments[0]
			: $arguments;
	}

}
