<?php

namespace Ulabox\Bundle\RulerBundle\Manager;

use Ulabox\Bundle\RulerBundle\Model\CompountTermInterface;

/**
 * The compount-term manager interface definition
 */
interface CompountTermManagerInterface
{
    /**
     * Creates new compount-term object.
     *
     * @return CompountTermInterface
     */
    public function createCompountTerm();
}
