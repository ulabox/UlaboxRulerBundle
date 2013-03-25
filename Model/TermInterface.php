<?php

namespace Ulabox\Bundle\RulerBundle\Model;

/**
 * The term interface definition
 */
interface TermInterface
{
    /**
     * Get term operator.
     *
     * @return string
     */
    public function getOperator();

    /**
     * Set term operator.
     *
     * @param string $operator
     */
    public function setOperator($operator);

    /**
     * Get term type.
     *
     * @return string
     */
    public function getType();

    /**
     * Get term order.
     *
     * @return integer
     */
    public function getOrder();

    /**
     * Set term order.
     *
     * @param integer $order
     */
    public function setOrder($order);

    /**
     * Get an array representation of term.
     *
     * @return array
     */
    public function toArray();
}
