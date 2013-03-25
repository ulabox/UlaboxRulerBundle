<?php

namespace Ulabox\Bundle\RulerBundle\Model;

use Ulabox\Bundle\RulerBundle\Variable\VariableInterface;
use Ulabox\Bundle\RulerBundle\Manager\RulerManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Ruler\RuleBuilder;
use Ruler\Context;

/**
 * The factor class definition
 *
 * @ORM\MappedSuperclass()
 */
class Factor implements FactorInterface
{
    /**
     * Factor leftOperand.
     *
     * @var string
     *
     * @ORM\Column(name="leftOperand", type="string", length=255)
     */
    protected $leftOperand;

    /**
     * Factor operator.
     *
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=255)
     */
    protected $operator;

    /**
     * Factor rightOperand.
     *
     * @var string
     *
     * @ORM\Column(name="rightOperand", type="string", length=255)
     */
    protected $rightOperand;

    /**
     * {@inheritdoc}
     */
    public function getLeftOperand()
    {
        return $this->leftOperand;
    }

    /**
     * {@inheritdoc}
     */
    public function setLeftOperand($leftOperand)
    {
        $this->leftOperand = $leftOperand;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRightOperand()
    {
        return $this->rightOperand;
    }

    /**
     * {@inheritdoc}
     */
    public function setRightOperand($rightOperand)
    {
        $this->rightOperand = $rightOperand;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildRule(RuleBuilder $ruleBuilder)
    {
        // Create a Rule equivalent to $a == $b condition
        // $rule = $ruleBuilder->create($ruleBuilder['a']->equalTo($ruleBuilder['b']));
        return $ruleBuilder[$this->leftOperand]->{$this->operator}($ruleBuilder[$this->rightOperand]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(Context $context, RulerManagerInterface $rulerManager)
    {
        // find operand variables
        $leftOperand = $rulerManager->getVariableByAlias($this->leftOperand);
        $rightOperand = $rulerManager->getVariableByAlias($this->rightOperand);

        $leftOperand->buildContext($context);
        $rightOperand->buildContext($context);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array(
            'leftOperand'  => $this->getLeftOperand(),
            'operator'     => $this->getOperator(),
            'rightOperand' => $this->getRightOperand()
        );
    }
}
