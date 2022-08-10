<?php declare(strict_types = 1);

namespace WebChemistry\Link;

class ActionLink
{

	/** @var mixed[] */
	private array $parameters;

	private bool $absolute = false;

	/** @var mixed[] */
	private array $context = [];

	final public function __construct(
		private string $destination,
		mixed ... $parameters,
	)
	{
		$this->parameters = $parameters;
	}

	public static function createWithContext(string $destination, array $context, mixed ...$parameters): static
	{
		$static = new static($destination, ...$parameters);
		$static->context = $context;

		return $static;
	}

	public function withParameter(string $name, mixed $value): static
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
