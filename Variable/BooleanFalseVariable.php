<?php

namespace Ulabox\Bundle\RulerBundle\Variable;

use Ruler\Context;

/**
 * The boolean false variable class definition
 */
class BooleanFalseVariable implements VariableInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildContext(Context $context)
    {
        $context[$this->getAlias()] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'False';
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup()
    {
        return 'Boolean';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'boolean.false';
    }
}