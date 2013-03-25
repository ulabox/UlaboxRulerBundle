<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Entity\CompountTerm;

/**
 * The compount-term manager class definition
 */
class CompountTermManager implements CompountTermManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCompountTerm()
    {
        return new CompountTerm();
    }
}
