<?php declare(strict_types = 1);

namespace WebChemistry\Link;

interface ActionLinkFactory
{

	/**
	 * @param mixed[] $arguments
	 */
	public function create(object $object, ?string $action, array $arguments): ?ActionLink;

}
