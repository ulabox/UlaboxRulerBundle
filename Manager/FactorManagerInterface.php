<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Model\FactorInterface;

/**
 * The factor manager interface definition
 */
interface FactorManagerInterface
{
    /**
     * Creates new factor object.
     *
     * @return FactorInterface
     */
    public function createFactor();
}
