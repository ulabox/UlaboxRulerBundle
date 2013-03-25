<?php

namespace Ulabox\Bundle\RulerBundle\Variable;


/**
 * The variable class definition
 */
abstract class Variable implements VariableInterface
{
    /**
     * {@inheritdoc}
     */
    public function getGroup()
    {
        return 'default';
    }
}
