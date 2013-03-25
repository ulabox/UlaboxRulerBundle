<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Entity\Factor;

/**
 * The factor manager class definition
 */
class FactorManager implements FactorManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createFactor()
    {
        return new Factor();
    }
}
