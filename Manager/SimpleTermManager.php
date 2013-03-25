<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Entity\SimpleTerm;

/**
 * The simple-term manager class definition
 */
class SimpleTermManager implements SimpleTermManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createSimpleTerm()
    {
        return new SimpleTerm();
    }
}
