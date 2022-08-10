<?php declare(strict_types = 1);

namespace WebChemistry\Link\Decorator;

use WebChemistry\Link\ActionLink;
use WebChemistry\Link\ActionLinkDecorator;

final class AbsoluteDecorator implements ActionLinkDecorator
{

	public function decorate(ActionLink $link, array $parameters = []): ActionLink
	{
		if (($parameters['absolute'] ?? false) === true) {
			$link = $link->withAbsolute();
		}

		return $link;
	}

}
