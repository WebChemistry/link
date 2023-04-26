<?php declare(strict_types = 1);

namespace WebChemistry\Link\DI;

use Nette\Application\UI\TemplateFactory;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\MissingServiceException;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;
use Typertion\Php\TypeAssert;
use WebChemistry\Link\Decorator\AbsoluteDecorator;
use WebChemistry\Link\Decorator\BacklinkDecorator;
use WebChemistry\Link\Latte\Extension\LinkExtension as LatteLinkExtension;
use WebChemistry\Link\LinkGenerator;

final class LinkExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'decorators' => Expect::structure([
				'backlink' => Expect::structure([
					'enabled' => Expect::bool(true),
					'context' => Expect::string('backlink'),
					'parameter' => Expect::string('backlink'),
				]),
				'absolute' => Expect::structure([
					'enabled' => Expect::bool(true),
					'context' => Expect::string('absolute'),
				]),
			]),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		/** @var stdClass $config */
		$config = $this->getConfig();

		$builder->addDefinition($this->prefix('generator'))
			->setFactory(LinkGenerator::class);

		if ($config->decorators->backlink->enabled) {
			$builder->addDefinition($this->prefix('decorator.backlink'))
				->setFactory(BacklinkDecorator::class, [
					'contextName' => $config->decorators->backlink->context,
					'parameterName' => $config->decorators->backlink->parameter,
				]);
		}

		if ($config->decorators->absolute->enabled) {
			$builder->addDefinition($this->prefix('decorator.absolute'))
				->setFactory(AbsoluteDecorator::class, [
					'contextName' => $config->decorators->absolute->context,
				]);
		}
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
