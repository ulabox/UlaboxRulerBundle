<?php

namespace Ulabox\Bundle\RulerBundle\Variable;

use Ruler\Context;

/**
 * The variable interface definition
 */
interface VariableInterface
{
    /**
     * Build context for given variable.
     *
     * @param Context $context The ruler context
     */
    public function buildContext(Context $context);

    /**
     * Return the variable name
     *
     * @return string
     */
    public function getName();

    /**
     * Return the variable group name
     *
     * @return string
     */
    public function getGroup();

    /**
     * Return the variable alias name
     *
     * @return string
     */
    public function getAlias();
}
