<?php declare(strict_types = 1);

namespace WebChemistry\Link\Decorator;

use WebChemistry\Link\ActionLink;
use WebChemistry\Link\ActionLinkDecorator;

final class AbsoluteDecorator implements ActionLinkDecorator
{

	public function __construct(
		private string $contextName = 'absolute',
	)
	{
	}

	public function decorate(ActionLink $link): ActionLink
	{
		$parameters = $link->getParameters();
		$context = $link->getContext();

		if (($context[$this->contextName] ?? false) === true) {
			$link = $link->withAbsolute();
		} else if (($parameters[$this->contextName] ?? false) === true) {
			trigger_error(sprintf('Parameter "%s" is deprecated, use context instead.', $this->contextName), E_USER_DEPRECATED);

			$link = $link->withAbsolute();
		}

		return $link;
	}

}
