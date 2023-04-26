<?php declare(strict_types = 1);

namespace WebChemistry\Link\UI;

interface ActionLinkComponent
{

	/**
	 * @param mixed[] $parameters
	 * @param mixed[] $context
	 * @return never
	 */
	public function redirectToAction(object $destination, ?string $action = null, array $parameters = [], array $context = []): void;

	/**
	 * @param mixed[] $parameters
	 * @param mixed[] $context
	 */
	public function linkToAction(object $destination, ?string $action = null, array $parameters = [], array $context = []): string;

}
