<?php

namespace Ulabox\Bundle\RulerBundle\Entity;

use Ulabox\Bundle\RulerBundle\Model\SimpleTermInterface;
use Ulabox\Bundle\RulerBundle\Model\FactorInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * The simple term class definition
 *
 * @ORM\Entity()
 */
class SimpleTerm extends Term implements SimpleTermInterface
{
    /**
     * SimpleTerm factor.
     *
     * @var FactorInterface
     *
     * @ORM\OneToOne(targetEntity="Factor", cascade={"persist", "remove"})
     */
    protected $factor;

    /**
     * {@inheritdoc}
     */
    public function getFactor()
    {
        return $this->factor;
    }

    /**
     * {@inheritdoc}
     */
    public function setFactor(FactorInterface $factor)
    {
        $this->factor = $factor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'simple';
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $result = array(
            'type'     => 'simple-term',
            'factor'   => $this->getFactor() ? $this->getFactor()->toArray() : null
        );

        if ($this->getOperator() !== '') {
            $result['operator'] = $this->getOperator();
        }

        return $result;
    }
}
