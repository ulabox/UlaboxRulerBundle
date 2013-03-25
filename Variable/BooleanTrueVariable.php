<?php

namespace Ulabox\Bundle\RulerBundle\Variable;

use Ruler\Context;

/**
 * The boolean true variable class definition
 */
class BooleanTrueVariable implements VariableInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildContext(Context $context)
    {
        $context[$this->getAlias()] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'True';
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
        return 'boolean.true';
    }
}