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
	public function redirectToAction(object $destination, ?string $action = null, mixed ... $arguments): void
	{
		$arguments['creator'] = $this;

		$link = $this->_linkGenerator->createActionLink($destination, $action, ... $arguments);
		$destination = ':' . $link->getDestination();
		$destination = ($link->isAbsolute() ? '//' : '') . $destination;

		$this->getPresenter()->redirect($destination, $link->getParameters());
	}

	public function linkToAction(object $destination, ?string $action = null, mixed ... $arguments): string
	{
		$arguments['creator'] = $this;

		$link = $this->_linkGenerator->createActionLink($destination, $action, ... $arguments);
		$destination = ':' . $link->getDestination();
		$destination = ($link->isAbsolute() ? '//' : '') . $destination;

		return $this->getPresenter()->link($destination, $link->getParameters());
	}

}
