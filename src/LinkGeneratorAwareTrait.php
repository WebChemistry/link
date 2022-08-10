<?php declare(strict_types = 1);

namespace WebChemistry\Link;

trait LinkGeneratorAwareTrait
{

	private LinkGenerator $linkGenerator;

	public function setLinkGenerator(LinkGenerator $linkGenerator): void
	{
		$this->linkGenerator = $linkGenerator;
	}

}
