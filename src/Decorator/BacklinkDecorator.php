<?php declare(strict_types = 1);

namespace WebChemistry\Link\Decorator;

use WebChemistry\Link\ActionLink;
use WebChemistry\Link\ActionLinkDecorator;
use WebChemistry\Link\LinkGeneratorAware;
use WebChemistry\Link\LinkGeneratorAwareTrait;

final class BacklinkDecorator implements ActionLinkDecorator, LinkGeneratorAware
{

	use LinkGeneratorAwareTrait;

	public function __construct(
		private string $contextName = 'backlink',
		private string $parameterName = 'backlink',
	)
	{
	}

	public function decorate(ActionLink $link): ActionLink
	{
		$backlink = $link->getContext()[$this->contextName] ?? null;

		if (!$backlink) {
			return $link;
		}

		return Backlink::createFromMixed($backlink)->addToLink($link, $this->linkGenerator, $this->parameterName);
	}

}
