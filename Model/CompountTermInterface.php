<?php

namespace Ulabox\Bundle\RulerBundle\Model;

use Doctrine\Common\Collections\Collection;

/**
 * The compount term interface definition
 */
interface CompountTermInterface extends TermInterface
{
    /**
     * This should return true only when compount-term has terms.
     *
     * @return Boolean
     */
    public function hasTerms();

    /**
     * Returns all compount-term terms.
     *
     * @return Collection
     */
    public function getTerms();

    /**
     * Sets all compount-term terms.
     *
     * @param Collection $terms
     */
    public function setTerms(Collection $terms);

    /**
     * Adds term.
     *
     * @param TermInterface $term
     */
    public function addTerm(TermInterface $term);

    /**
     * Removes term from compount-term.
     *
     * @param TermInterface $term
     */
    public function removeTerm(TermInterface $term);

    /**
     * Checks whether compount-term has given term.
     *
     * @param TermInterface $term
     *
     * @return Boolean
     */
    public function hasTerm(TermInterface $term);
}
