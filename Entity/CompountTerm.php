<?php

namespace Ulabox\Bundle\RulerBundle\Entity;

use Ulabox\Bundle\RulerBundle\Model\CompountTermInterface;
use Ulabox\Bundle\RulerBundle\Model\TermInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The compount term class definition
 *
 * @ORM\Entity()
 */
class CompountTerm extends Term implements CompountTermInterface
{
    /**
     * CompountTerm terms.
     *
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Term", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="ulabox_ruler_compount_terms_terms",
     *     joinColumns={@ORM\JoinColumn(name="compount_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="term_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $terms;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->terms = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function hasTerms()
    {
        return !$this->terms->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * {@inheritdoc}
     */
    public function setTerms(Collection $terms)
    {
        $this->terms = $terms;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTerm(TermInterface $term)
    {
        if (!$this->hasOption($term)) {
            $this->terms->add($term);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTerm(TermInterface $term)
    {
        if ($this->hasOption($term)) {
            $this->terms->removeElement($term);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTerm(TermInterface $term)
    {
        return $this->terms->contains($term);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'compount';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $list = $this->getTerms()->toArray();
        usort($list, function($a, $b){
            if ($a->getOrder() == $b->getOrder()) {
                return 0;
            }

            return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
        });

        $terms = array();
        foreach ($list as $term) {
            $terms[] = $term->toArray();
        }

        return array(
            'type'     => 'compount-term',
            'operator' => $this->getOperator(),
            'terms'    => $terms
        );
    }
}
