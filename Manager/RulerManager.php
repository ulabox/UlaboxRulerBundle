<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Ulabox\Bundle\RulerBundle\Variable\VariableInterface;
use Ulabox\Bundle\RulerBundle\Model\ExpressionInterface;
use Ulabox\Bundle\RulerBundle\Model\ActionInterface;
use Ruler\RuleBuilder;
use Ruler\Context;

/**
 * The ruler manager class definition
 */
class RulerManager implements RulerManagerInterface
{
    /**
     * Ruler action.
     *
     * @var ActionInterface
     */
    protected $action;

    /**
     * Registered variables.
     *
     * @var array
     */
    protected $variables;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->variables = new ArrayCollection();
        $this->action = null;
    }

    /**
     * {@inheritdoc}
     */
    public function registerVariable(VariableInterface $variable)
    {
        if (!$this->variables->containsKey($variable->getGroup())) {
            $this->variables->set($variable->getGroup(), new ArrayCollection());
        }

        if ($this->variables->get($variable->getGroup())->containsKey($variable->getAlias())) {
            throw new \InvalidArgumentException(sprintf('Variable with alias "%s" is already registered', $alias));
        }

        $this->variables->get($variable->getGroup())->set($variable->getAlias(), $variable);
    }

    /**
     * {@inheritdoc}
     */
    public function getVariableByAlias($alias)
    {
        foreach ($this->variables as $group => $variables) {
            foreach ($variables as $key => $variable) {
                if ($key == $alias) {
                    return $variable;
                }
            }
        }

        throw new \InvalidArgumentException(sprintf('Variable with alias "%s" is not registered', $alias));
    }

    /**
     * {@inheritdoc}
     */
    public function getVariablesByGroups(array $groups)
    {
        $result = new ArrayCollection();
        foreach ($groups as $group) {
            if ($this->variables->containsKey($group)) {
                $result->set($group, $this->variables->get($group)->getValues());
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperators()
    {
        return array('equalTo', 'greaterThan', 'greaterThanOrEqualTo', 'lessThan', 'lessThanOrEqualTo', 'notEqualTo');
    }

    /**
     * {@inheritdoc}
     */
    public function setAction(ActionInterface $action)
    {
        $this->action = $action;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(ExpressionInterface $expression, array $values = array())
    {
        if ($this->action !== null) {
            // create rule context and builder
            $context     = new Context($values);
            $ruleBuilder = new RuleBuilder();

            // build rule
            $rule = $expression->buildRule($ruleBuilder);
            // build context
            $expression->buildContext($context, $this);

            // if ruler is valid then execute some action
            if ($rule->evaluate($context)) {
                $this->action->execute($context);
            }
        }
    }
}
