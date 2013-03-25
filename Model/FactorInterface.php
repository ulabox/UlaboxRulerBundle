<?php

namespace Ulabox\Bundle\RulerBundle\Model;

use Ulabox\Bundle\RulerBundle\Manager\RulerManagerInterface;
use Ruler\RuleBuilder;
use Ruler\Context;
use Ruler\Rule;

/**
 * The factor interface definition
 */
interface FactorInterface
{
    /**
     * Get factor leftOperand.
     *
     * @return string
     */
    public function getLeftOperand();

    /**
     * Set factor leftOperand.
     *
     * @param string $leftOperand
     */
    public function setLeftOperand($leftOperand);

    /**
     * Get factor operator.
     *
     * @return string
     */
    public function getOperator();

    /**
     * Set factor operator.
     *
     * @param string $operator
     */
    public function setOperator($operator);

    /**
     * Get factor rightOperand.
     *
     * @return string
     */
    public function getRightOperand();

    /**
     * Set factor rightOperand.
     *
     * @param string $rightOperand
     */
    public function setRightOperand($rightOperand);

    /**
     * Build rule for this factor.
     *
     * @param RuleBuilder $ruleBuilder The rule builder
     *
     * @return Rule
     */
    public function buildRule(RuleBuilder $ruleBuilder);

    /**
     * Build the context for this factor.
     *
     * @param Context               $context     The context instance
     * @param RulerManagerInterface $ruleManager The rule manager
     */
    public function buildContext(Context $context, RulerManagerInterface $rulerManager);

    /**
     * Get an array representation of factor.
     *
     * @return array
     */
    public function toArray();
}
