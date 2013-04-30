<?php

namespace Ulabox\Bundle\RulerBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * The term class definition
 *
 * @ORM\MappedSuperclass()
 */
abstract class Term implements TermInterface
{
    /**
     * Term operator.
     *
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=255, nullable=true)
     */
    protected $operator;

    /**
     * Term order.
     *
     * @var string
     *
     * @ORM\Column(name="term_order", type="integer")
     */
    protected $order;

    /**
     * {@inheritdoc}
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * {@inheritdoc}
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }
}
