<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Entity\Expression;

/**
 * The expression manager class definition
 */
class ExpressionManager implements ExpressionManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createExpression()
    {
        return new Expression();
    }
}
