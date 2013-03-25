<?php

namespace Ulabox\Bundle\RulerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass that registers all variables.
 *
 */
class RegisterRulerVariablesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $manager = $container->getDefinition('ulabox_ruler.manager.ruler');

        foreach ($container->findTaggedServiceIds('ulabox_ruler.variable') as $id => $attributes) {
            $manager->addMethodCall('registerVariable', array(new Reference($id)));
        }
    }
}
