<?php declare(strict_types = 1);

namespace WebChemistry\Link\Latte\Extension;

use Generator;
use Latte\Compiler\Node;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Extension;
use stdClass;
use WebChemistry\Link\Latte\Extension\Node\LinkNode;

final class LinkExtension extends Extension
{

	/**
	 * Returns a list of parsers for Latte tags.
	 *
	 * @return array<string, callable(Tag, TemplateParser): (Node|Generator|void)|stdClass>
	 */
	public function getTags(): array
	{
		return [
			'n:href' => [LinkNode::class, 'create'],
			'n:phref' => [LinkNode::class, 'create'],
			'plink' => [LinkNode::class, 'create'],
			'link' => [LinkNode::class, 'create'],
		];
	}

}
