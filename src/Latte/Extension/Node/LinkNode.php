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
				'$ʟ_destination = %node;'
				. '$ʟ_method = is_string($ʟ_destination) ? "link" : "linkToAction";'
				. 'echo \' href="\';'
				. 'echo %modify('
				. ($this->mode === 'phref' ? '$this->global->uiPresenter' : '$this->global->uiControl')
				. '->$ʟ_method($ʟ_destination, %node?)) %line;'
				. 'echo \'"\';',
				$this->destination,
				$this->modifier,
				$this->args,
				$this->position,
			);
			$context->restoreEscape();
			return $res;
		}

		return $context->format(
			'$ʟ_destination = %node;'
			. '$ʟ_method = is_string($ʟ_destination) ? "link" : "linkToAction";'
			. 'echo %modify('
			. ($this->mode === 'plink' ? '$this->global->uiPresenter' : '$this->global->uiControl')
			. '->$ʟ_method($ʟ_destination, %node?)) %line;',
			$this->destination,
			$this->modifier,
			$this->args,
			$this->position,
		);
	}

}
