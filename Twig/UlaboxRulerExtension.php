<?php

namespace Ulabox\Bundle\RulerBundle\Twig;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Ulabox\Bundle\RulerBundle\Manager\RulerManagerInterface;

/**
 * The ulabox ruler twig extension
 */
class UlaboxRulerExtension extends \Twig_Extension
{
    /**
     * Ruler manager.
     *
     * @var RulerManagerInterface
     */
    private $rulerManager;

    /**
     * Constructor
     *
     * @param RulerManagerInterface $rulerManager
     */
    public function __construct(RulerManagerInterface $rulerManager)
    {
        $this->rulerManager = $rulerManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'ulabox_ruler_variables' => new \Twig_Function_Method($this, 'getRulerVariables'),
            'ulabox_ruler_operators' => new \Twig_Function_Method($this, 'getRulerOperators'),
        );
    }

    /**
     * Get variables by a given groups.
     *
     * @param array $groups The varaible groups
     *
     * @return ArrayCollection
     */
    public function getRulerVariables(array $groups = array())
    {
        return $this->rulerManager->getVariablesByGroups($groups);
    }

    /**
     * Get operators.
     *
     * @return ArrayCollection
     */
    public function getRulerOperators()
    {
        return $this->rulerManager->getOperators();
    }

   /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'deaterra_core';
    }
}