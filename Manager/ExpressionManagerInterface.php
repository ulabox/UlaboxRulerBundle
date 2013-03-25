<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Model\ExpressionInterface;

/**
 * The expression manager interface definition
 */
interface ExpressionManagerInterface
{
    /**
     * Creates new expression object.
     *
     * @return ExpressionInterface
     */
    public function createExpression();
}
