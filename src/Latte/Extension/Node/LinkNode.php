<?php declare(strict_types = 1);

namespace WebChemistry\Link\Latte\Extension\Node;

use Latte\Compiler\PrintContext;
use Nette\Bridges\ApplicationLatte\Nodes\LinkNode as NetteLinkNode;

final class LinkNode extends NetteLinkNode
{

	public function print(PrintContext $context): string
	{
		if (in_array($this->mode, ['href', 'phref'], true)) {
			$context->beginEscape()->enterHtmlAttribute(null, '"');
			$res = $context->format(
				'echo \' href="\';'
				. 'echo %modify('
				. ($this->mode === 'phref' ? '$this->global->uiPresenter' : '$this->global->uiControl')
				. '->linkToAction(%node, %node?)) %line;'
				. 'echo \'"\';',
				$this->modifier,
				$this->destination,
				$this->args,
				$this->position,
			);
			$context->restoreEscape();
			return $res;
		}

		return $context->format(
			'echo %modify('
			. ($this->mode === 'plink' ? '$this->global->uiPresenter' : '$this->global->uiControl')
			. '->linkToAction(%node, %node?)) %line;',
			$this->modifier,
			$this->destination,
			$this->args,
			$this->position,
		);
	}

}
