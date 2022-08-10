<?php declare(strict_types = 1);

namespace WebChemistry\Link;

interface ActionLinkFactory
{

	/**
	 * @param mixed[] $parameters
	 */
	public function create(object $object, ?string $action, array $parameters): ?ActionLink;

}
