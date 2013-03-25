<?php

namespace Ulabox\Bundle\RulerBundle\Model;

use Ulabox\Bundle\RulerBundle\Manager\RulerManagerInterface;
use Doctrine\Common\Collections\Collection;
use Ruler\RuleBuilder;
use Ruler\Context;
use Ruler\Rule;

/**
 * The expression interface definition
 */
interface ExpressionInterface
{
    /**
     * Get expression name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set expression name.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get permalink/slug.
     *
     * @return string
     */
    public function getSlug();

    /**
     * Set the permalink.
     *
     * @param string $slug
     */
    public function setSlug($slug);

    /**
     * This should return true only when expression has terms.
     *
     * @return Boolean
     */
    public function hasTerms();

    /**
     * Returns all expression terms.
     *
     * @return Collection
     */
    public function getTerms();

    /**
     * Sets all expression terms.
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
     * Removes term from expression.
     *
     * @param TermInterface $term
     */
    public function removeTerm(TermInterface $term);

    /**
     * Checks whether expression has given term.
     *
     * @param TermInterface $term
     *
     * @return Boolean
     */
    public function hasTerm(TermInterface $term);

    /**
     * Get creation time.
     *
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * Get the time of last update.
     *
     * @return DateTime
     */
    public function getUpdatedAt();

    /**
     * Build rule for this expression.
     *
     * @param RuleBuilder $ruleBuilder The rule builder
     *
     * @return Rule
     */
    public function buildRule(RuleBuilder $ruleBuilder);

    /**
     * Build the context for this expression.
     *
     * @param Context               $context     The context instance
     * @param RulerManagerInterface $ruleManager The rule manager
     */
    public function buildContext(Context $context, RulerManagerInterface $rulerManager);

    /**
     * Get an array representation of expression.
     *
     * @return array
     */
    public function toArray();
}
