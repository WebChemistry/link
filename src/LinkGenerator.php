<?php declare(strict_types = 1);

namespace WebChemistry\Link;

use Nette\Application\LinkGenerator as NetteLinkGenerator;
use Typertion\Php\TypeAssert;
use WebChemistry\Link\Exception\NoHandlerException;

final class LinkGenerator
{

	/**
	 * @param ActionLinkFactory[] $factories
	 * @param ActionLinkDecorator[] $decorators
	 */
	public function __construct(
		private NetteLinkGenerator $linkGenerator,
		private array $factories = [],
		private array $decorators = [],
	)
	{
		foreach ($this->decorators as $decorator) {
			if ($decorator instanceof LinkGeneratorAware) {
				$decorator->setLinkGenerator($this);
			}
		}
	}

	public function link(string|object $destination, mixed ... $parameters): string
	{
		$link = $destination instanceof ActionLink ? $destination : $this->createActionLink($destination, $parameters);

		return $this->linkGenerator->link($link->getDestination(), $link->getParameters());
	}

	public function withReferenceUrl(string $url): self
	{
		return new self(
			$this->linkGenerator->withReferenceUrl($url),
			$this->factories,
			array_map(
				fn (ActionLinkDecorator $decorator) => clone $decorator,
				$this->decorators,
			),
		);
	}

	/**
	 * @param mixed[] $parameters
	 */
	public function createActionLink(string|object $dest, array $parameters = []): ActionLink
	{
		if (is_object($dest)) {
			if ($dest instanceof ActionLink) {
				return $dest;
			}

			$action = TypeAssert::stringOrNull($parameters[0] ?? null, 'action parameter');
			$link = null;

			foreach ($this->factories as $factory) {
				if ($link = $factory->create($dest, $action, $parameters)) {
					break;
				}
			}

			if (!$link) {
				throw new NoHandlerException(
					sprintf(
						'No handler for "%s" and %s.',
						$dest::class,
						$action ? sprintf('action "%s"', $action) : 'empty action',
					),
				);
			}

			foreach ($this->decorators as $decorator) {
				$link = $decorator->decorate($link, $parameters);
			}

		} else {
			$link = new StringActionLink($dest, ... $parameters);

		}

		return $link;
	}

}
