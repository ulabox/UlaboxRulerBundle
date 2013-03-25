<?php

namespace Ulabox\Bundle\RulerBundle\Model;

/**
 * The simple term interface definition
 */
interface SimpleTermInterface extends TermInterface
{
    /**
     * Get term factor.
     *
     * @return FactorInterface
     */
    public function getFactor();

    /**
     * Set term factor.
     *
     * @param FactorInterface $factor
     */
    public function setFactor(FactorInterface $factor);
}
