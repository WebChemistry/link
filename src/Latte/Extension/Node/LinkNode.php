<?php declare(strict_types = 1);

namespace WebChemistry\Link\Latte\Extension\Node;

use DomainException;
use Latte\Compiler\PrintContext;
use Nette\Application\UI\Component;
use Nette\Bridges\ApplicationLatte\Nodes\LinkNode as NetteLinkNode;
use WebChemistry\Link\UI\ActionLinkComponent;

final class LinkNode extends NetteLinkNode
{

	public function print(PrintContext $context): string
	{
		if (in_array($this->mode, ['href', 'phref'], true)) {
			$context->beginEscape()->enterHtmlAttribute(null, '"');
			$res = $context->format(
				<<<'XX'
						echo ' href="'; echo %modify(%raw::createLink(%raw, %node, %node?)) %line; echo '"';
					XX,
				$this->modifier,
				self::class,
				$this->mode === 'phref' ? '$this->global->uiPresenter' : '$this->global->uiControl',
				$this->destination,
				$this->args,
				$this->position,
			);
			$context->restoreEscape();
			return $res;
		}

		return $context->format(
			'echo %modify(%raw::createLink(%raw, %node, %node?)) %line;',
			$this->modifier,
			self::class,
			$this->mode === 'plink' ? '$this->global->uiPresenter' : '$this->global->uiControl',
			$this->destination,
			$this->args,
			$this->position,
		);
	}

	/**
	 * @internal
	 * @param mixed[] $arguments
	 */
	public static function createLink(Component $component, string|object $destination, array $arguments = []): string
	{
		if (is_object($destination) && $component instanceof ActionLinkComponent) {
			return $component->linkToAction($destination, ... $arguments);
		}

		if (is_object($destination)) {
			throw new DomainException(
				sprintf(
					'Destination is object, but component %s does not implements %s.',
					$component::class,
					ActionLinkComponent::class
				)
			);
		}

		return $component->link($destination, $arguments);
	}

}
