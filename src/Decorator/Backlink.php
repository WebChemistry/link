<?php declare(strict_types = 1);

namespace WebChemistry\Link\Decorator;

use InvalidArgumentException;
use Nette\Application\UI\Link;
use WebChemistry\Link\ActionLink;
use WebChemistry\Link\LinkGenerator;

final class Backlink
{

	public function __construct(
		private string|ActionLink|Link $link,
		private ?string $parameterName = null,
	)
	{
	}

	public function addToLink(ActionLink $link, LinkGenerator $linkGenerator, string $defaultParameterName = 'backlink'): ActionLink
	{
		$backlink = $this->link;

		if (is_string($backlink)) {
			return $link->withParameter($this->parameterName ?? $defaultParameterName, $backlink);

		} else if ($backlink instanceof ActionLink) {
			return $link->withParameter($this->parameterName ?? $defaultParameterName, $linkGenerator->link($backlink));

		} else if ($backlink instanceof Link) {
			return $link->withParameter($this->parameterName ?? $defaultParameterName, (string) $backlink);

		}
	}

	public static function createFromMixed(mixed $value): self
	{
		if ($value instanceof self) {
			return $value;
		}

		if (!is_string($value) && !$value instanceof ActionLink && !$value instanceof Link) {
			throw new InvalidArgumentException(
				sprintf('Backlink expected to be a string or %s, %s given.', ActionLink::class, get_debug_type($value))
			);
		}

		return new self($value);
	}

}
