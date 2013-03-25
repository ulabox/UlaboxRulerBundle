<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Model\SimpleTermInterface;

/**
 * The simple-term manager interface definition
 */
interface SimpleTermManagerInterface
{
    /**
     * Creates new simple-term object.
     *
     * @return SimpleTermInterface
     */
    public function createSimpleTerm();
}
