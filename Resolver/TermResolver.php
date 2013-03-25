<?php

namespace Ulabox\Bundle\RulerBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Ulabox\Bundle\RulerBundle\Entity\SimpleTerm;
use Ulabox\Bundle\RulerBundle\Entity\CompountTerm;
use Ulabox\Bundle\RulerBundle\Entity\Factor;

/**
 * Term resolver class.
 */
class TermResolver implements TermResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request)
    {
        $result = new ArrayCollection();

        $this->createTerms($request->request->get('ulabox_ruler_terms', array()), $result);

        return $result;
    }

    /**
     * Recursively searches for the terms and added to the collection
     *
     * @param array           $terms      The terms array
     * @param ArrayCollection $collection The result collection
     */
    private function createTerms(array $terms, ArrayCollection $collection)
    {
        $order = 0;
        foreach ($terms as $term) {
            if ($term['type'] == 'simple') {
                $object = new SimpleTerm();

                $factor = new Factor();
                $factor->setLeftOperand($term['leftOperand']);
                $factor->setOperator($term['operator']);
                $factor->setRightOperand($term['rightOperand']);

                $object->setOperator(isset($term['logicalOperator']) ? $term['logicalOperator'] : '');
                $object->setFactor($factor);
                $object->setOrder($order++);
            } else {
                $object = new CompountTerm();
                $object->setOperator($term['logicalOperator']);
                $object->setOrder($order++);

                $this->createTerms($term['terms'], $object->getTerms());
            }

            $collection->add($object);
        }
    }
}