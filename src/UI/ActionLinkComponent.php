<?php declare(strict_types = 1);

namespace WebChemistry\Link\UI;

interface ActionLinkComponent
{

	/**
	 * @return never
	 */
	public function redirectToAction(object $destination, ?string $action = null, mixed ... $arguments): void;

	public function linkToAction(object $destination, ?string $action = null, mixed ... $arguments): string;

}
