<?php declare(strict_types = 1);

namespace WebChemistry\Link\UI;

interface ActionLinkComponent
{

	/**
	 * @return never
	 */
	public function redirectToAction(object $destination, mixed ... $arguments): void;

	public function linkToAction(object $destination, mixed ... $arguments): string;

}
