<?php

namespace Ulabox\Bundle\RulerBundle\Model;

use Ruler\Context;

/**
 * The action interface definition
 */
interface ActionInterface
{
    /**
     * Execute the action.
     *
     * @param Context $context The context instance
     */
    public function execute(Context $context);
}
