<?php declare(strict_types = 1);

namespace WebChemistry\Link;

interface LinkGeneratorAware
{

	public function setLinkGenerator(LinkGenerator $linkGenerator);

}
