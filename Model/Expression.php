<?php

namespace Ulabox\Bundle\RulerBundle\Model;

use Ulabox\Bundle\RulerBundle\Manager\RulerManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Ruler\RuleBuilder;
use Ruler\Context;

/**
 * The expression class definition
 *
 * @ORM\MappedSuperclass()
 */
class Expression implements ExpressionInterface
{
    /**
     * Expression name.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="ulabox_ruler.expression.name.not_blank")
     */
    protected $name;

    /**
     * Permalink for the expression.
     * Used in url to access it.
     *
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    protected $slug;

    /**
     * Expression terms.
     *
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Term", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="ulabox_ruler_expressions_terms",
     *     joinColumns={@ORM\JoinColumn(name="expression_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="term_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    protected $terms;

    /**
     * Creation time.
     *
     * @var DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * Last update time.
     *
     * @var DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->terms = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
      return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->setSlug($name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
      return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function buildRule(RuleBuilder $ruleBuilder)
    {
        $postfixNotation = $this->toPostfixNotation();
        $stack = array();

        foreach ($postfixNotation as $symbol) {
            // if symbol is operand
            if ($symbol instanceof FactorInterface) {
                // create a Rule for this Factor
                // and push the rule to stack
                array_push($stack, $symbol->buildRule($ruleBuilder));
            } else {
                // the symbol is an operator (logicalAnd, logicalOr)
                // extract two operands from the stack and create the rule
                // the result rule should be returned to the stack
                $leftRule  = array_pop($stack);
                $rightRule = array_pop($stack);
                $rule = $ruleBuilder->{$symbol}($leftRule, $rightRule);

                array_push($stack, $rule);
            }
        }

        // the final rule is the top of the stack
        return $ruleBuilder->create(array_pop($stack));
    }

    /**
     * {@inheritdoc}
     */
    public function buildContext(Context $context, RulerManagerInterface $rulerManager)
    {
        $infixNotation = $this->toInfixNotation();

        // while not end of expresion
        foreach ($infixNotation as $symbol) {
            // if symbol is operand
            if ($symbol instanceof FactorInterface) {
                // build the operand context
                $symbol->buildContext($context, $rulerManager);
            }
        }
    }

    /**
     * Convert expression to infix notation
     *
     * @return array
     */
    public function toInfixNotation()
    {
        $infixNotation = new ArrayCollection();
        foreach ($this->getTerms() as $term) {
            $this->toInfixRecursive($infixNotation, $term);
        }

        return $infixNotation;
    }

    /**
     * Helper function to convert expression to infix notation
     *
     * @param Collection    $infixNotation The infix notation
     * @param TermInterface $term          The current term instance
     */
    private function toInfixRecursive(Collection $infixNotation, TermInterface $term)
    {
        if ($term->getType() == 'simple') {
            if ($term->getOperator() !== null && $term->getOperator() !== '') {
                $infixNotation->add($term->getOperator());
            }

            $infixNotation->add($term->getFactor());
        } else {
            $infixNotation->add($term->getOperator());
            $infixNotation->add('(');

            foreach ($term->getTerms() as $term) {
                $this->toInfixRecursive($infixNotation, $term);
            }

            $infixNotation->add(')');
        }
    }

    /**
     * Convert infix notation to postfix notation
     *
     * @return array
     */
    public function toPostfixNotation()
    {
        $postfixNotation = new ArrayCollection();
        $infixNotation   = $this->toInfixNotation();

        // set operator stack to empty
        $operatorStack   = array();

        // while not end of expresion
        foreach ($infixNotation as $symbol) {
            // if symbol is operand
            if ($symbol instanceof FactorInterface) {
                // add operand to postfix
                $postfixNotation->add($symbol);
            } else {
                // if symbol is ")"
                if ($symbol == ')') {
                    // pop all operators on the stack ultil it finds its matching "("
                    while ($operatorStack[count($operatorStack) - 1] != '(') {
                        // add operator to postfix
                        $postfixNotation->add(array_pop($operatorStack));
                    }

                    // pop "(" from the stack
                    array_pop($operatorStack);
                } else {
                    // push operator to stack
                    array_push($operatorStack, $symbol);
                }
            }
        }

        // while operator stack not empty
        while (count($operatorStack) > 0) {
            // pop top element and add it to postfix
            $postfixNotation->add(array_pop($operatorStack));
        }

        return $postfixNotation;
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
            'name'  => $this->getName(),
            'slug'  => $this->getSlug(),
            'terms' => $terms
        );
    }
}
