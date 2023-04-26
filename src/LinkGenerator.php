<?php declare(strict_types = 1);

namespace WebChemistry\Link;

use Nette\Application\LinkGenerator as NetteLinkGenerator;
use Nette\Application\UI\InvalidLinkException;
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

	/**
	 * @throws InvalidLinkException
	 */
	public function link(object $destination, ?string $action = null, mixed ... $arguments): string
	{
		$link = $this->createActionLink($destination, $action, ... $arguments);

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

	public function createActionLink(object $destination, ?string $action = null, mixed ... $arguments): ActionLink
	{
		if ($destination instanceof ActionLink) {
			return $this->decorate($destination);
		}

		$link = null;

		foreach ($this->factories as $factory) {
			if ($link = $factory->create($destination, $action, $arguments)) {
				break;
			}
		}

		if (!$link) {
			throw new NoHandlerException(
				sprintf(
					'No handler for "%s" and %s.',
					$destination::class,
					$action ? sprintf('action "%s"', $action) : 'empty action',
				),
			);
		}

		return $this->decorate($link);
	}

	private function decorate(ActionLink $link): ActionLink
	{
		foreach ($this->decorators as $decorator) {
			$link = $decorator->decorate($link);
		}

		return $link;
	}

}
