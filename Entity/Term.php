<?php

namespace Ulabox\Bundle\RulerBundle\Entity;

use Ulabox\Bundle\RulerBundle\Model\Term as BaseTerm;
use Ulabox\Bundle\RulerBundle\Model\CompountTermInterface;
use Ulabox\Bundle\RulerBundle\Model\ExpressionInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * The compount term class definition
 *
 * @ORM\Entity()
 * @ORM\Table(name="ulabox_ruler_terms")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *         "simple"   = "SimpleTerm",
 *         "compount" = "CompountTerm"
 * })
 */
abstract class Term extends BaseTerm
{
    /**
     * Term id
     *
     * @var integer $id
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

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'term';
    }
}
