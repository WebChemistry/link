<?php declare(strict_types = 1);

namespace WebChemistry\Link;

interface ActionLinkDecorator
{

	public function decorate(ActionLink $link): ActionLink;

}
