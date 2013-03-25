<?php

namespace Ulabox\Bundle\RulerBundle\Entity;

use Ulabox\Bundle\RulerBundle\Model\Factor as BaseFactor;
use Ulabox\Bundle\RulerBundle\Model\SimpleTermInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * The compount term class definition
 *
 * @ORM\Entity()
 * @ORM\Table(name="ulabox_ruler_factors")
 */
class Factor extends BaseFactor
{
    /**
     * Factor id.
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
}
