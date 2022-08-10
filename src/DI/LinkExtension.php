<?php declare(strict_types = 1);

namespace WebChemistry\Link\DI;

use Nette\Application\UI\TemplateFactory;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\MissingServiceException;
use Typertion\Php\TypeAssert;
use WebChemistry\Link\Latte\Extension\LinkExtension as LatteLinkExtension;
use WebChemistry\Link\LinkGenerator;

final class LinkExtension extends CompilerExtension
{

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('generator'))
			->setFactory(LinkGenerator::class);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$latte = $builder->addDefinition($this->prefix('latte.extension'))
			->setFactory(LatteLinkExtension::class);

		try {
			$definition = TypeAssert::instanceOf(
				$builder->getDefinitionByType(LatteFactory::class),
				FactoryDefinition::class
			);

			$definition->getResultDefinition()
				->addSetup('addExtension', [$latte]);
		} catch (MissingServiceException) {
			// no need
		}

		try {
			$definition = TypeAssert::instanceOf(
				$builder->getDefinitionByType(TemplateFactory::class),
				ServiceDefinition::class,
			);

			$definition->addSetup(
				'?->onCreate[] = fn ($template) => $template->getLatte()->addExtension(?)',
				['@self', $latte]
			);
		} catch (MissingServiceException) {
			// no need
		}
	}

}
