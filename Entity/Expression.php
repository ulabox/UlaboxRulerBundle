<?php

namespace Ulabox\Bundle\RulerBundle\Entity;

use Ulabox\Bundle\RulerBundle\Model\Expression as BaseExpression;
use Doctrine\ORM\Mapping as ORM;

/**
 * The expresion class definition
 *
 * @ORM\Entity()
 * @ORM\Table(name="ulabox_ruler_expressions")
 */
class Expression extends BaseExpression
{
    /**
     * Expression id.
     *
     * @var integer id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
}
