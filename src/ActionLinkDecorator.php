<?php declare(strict_types = 1);

namespace WebChemistry\Link;

interface ActionLinkDecorator
{

	/**
	 * @param mixed[] $parameters
	 */
	public function decorate(ActionLink $link, array $parameters = []): ActionLink;

}
