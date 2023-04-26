<?php declare(strict_types = 1);

namespace WebChemistry\Link;

class ActionLink
{

	private bool $absolute = false;

	/**
	 * @param mixed[] $parameters
	 * @param mixed[] $context
	 */
	final public function __construct(
		private string $destination,
		private array $parameters = [],
		private array $context = [],
	)
	{
	}

	public function withContext(string|int $name, mixed $value): static
	{
		$clone = clone $this;
		$clone->context[$name] = $value;

		return $clone;
	}

	public function withParameter(string|int $name, mixed $value): static
	{
		$clone = clone $this;
		$clone->parameters[$name] = $value;

		return $clone;
	}

	public function withAbsolute(): self
	{
		$clone = clone $this;
		$clone->absolute = true;

		return $clone;
	}

	public function isAbsolute(): bool
	{
		return $this->absolute;
	}

	public function getDestination(): string
	{
		return $this->destination;
	}

	/**
	 * @return mixed[]
	 */
	public function getContext(): array
	{
		return $this->context;
	}

	/**
	 * @return mixed[]
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

}
