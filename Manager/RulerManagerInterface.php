<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Variable\VariableInterface;
use Ulabox\Bundle\RulerBundle\Model\ExpressionInterface;
use Ulabox\Bundle\RulerBundle\Model\ActionInterface;

/**
 * The ruler manager interface definition
 */
interface RulerManagerInterface
{
    /**
     * Register new ruler variable.
     *
     * @param VariableInterface $variable The variable instance
     */
    public function registerVariable(VariableInterface $variable);

    /**
     * Get variable by a given alias.
     *
     * @param string $alias The variable alias
     *
     * @return VariableInterface
     */
    public function getVariableByAlias($alias);

    /**
     * Get variables by a given groups.
     *
     * @param array $groups The variable groups
     *
     * @return ArrayCollection
     */
    public function getVariablesByGroups(array $groups);

    /**
     * Get variables.
     *
     * @return ArrayCollection
     */
    public function getVariables();

    /**
     * Get operators.
     *
     * @return array
     */
    public function getOperators();

    /**
     * Set ruler action.
     *
     * @param ActionInterface $action
     */
    public function setAction(ActionInterface $action);

    /**
     * Execute a expression.
     *
     * @param ExpressionInterface $expression The expression instance
     * @param array               $values     The context default values
     */
    public function execute(ExpressionInterface $expression, array $values = array());
}
