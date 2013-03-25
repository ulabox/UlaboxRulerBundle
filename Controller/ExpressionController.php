<?php

namespace Ulabox\Bundle\RulerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Expression controller.
 */
class ExpressionController extends Controller
{
    /**
     * The object manager
     *
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Constructor.
     *
     * @param ObjectManager $manager The object manager instance.
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Shows a expression.
     *
     * @param integer $id The expression id
     *
     * @return Response
     */
    public function showAction($id)
    {
        $expression = $this->findExpressionOr404($id);

        return $this->container->get('templating')->renderResponse('UlaboxRulerBundle:Expression:show.html.'.$this->getEngine(), array(
            'expression' => $expression
        ));
    }

    /**
     * Lists paginated expressions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $repository = $this->container->get('ulabox_ruler.repository.expression');

        $paginator = $repository->createPaginator()
                        ->setCurrentPage($request->get('page', 1), true, true)
                        ->setMaxPerPage(10);

        return $this->container->get('templating')->renderResponse('UlaboxRulerBundle:Expression:list.html.'.$this->getEngine(), array(
            'paginator' => $paginator
        ));
    }

    /**
     * Creates a new expression.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $expression = $this->container->get('ulabox_ruler.manager.expression')->createExpression();
        $form = $this->container->get('form.factory')->create('ulabox_ruler_expression', $expression);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            $resolver = $this->container->get('ulabox_ruler.resolver.term');
            $expression->setTerms($resolver->resolve($request));

            if ($form->isValid()) {
                // persist the expression
                $this->manager->persist($expression);
                $this->manager->flush();

                return new RedirectResponse($this->container->get('router')->generate('ulabox_ruler_expression_show', array(
                    'id' => $expression->getId()
                )));
            }
        }

        $data = $expression->toArray();

        return $this->container->get('templating')->renderResponse('UlaboxRulerBundle:Expression:create.html.'.$this->getEngine(), array(
            'form'       => $form->createView(),
            'expression' => $expression,
            'terms'      => json_encode($data['terms'])
        ));
    }

    /**
     * Updates a expression.
     *
     * @param Request $request The request
     * @param integer $id      The expression id
     *
     * @return Response
     */
    public function updateAction(Request $request, $id)
    {
        $expression = $this->findExpressionOr404($id);
        $form = $this->container->get('form.factory')->create('ulabox_ruler_expression', $expression);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $resolver = $this->container->get('ulabox_ruler.resolver.term');
                $expression->setTerms($resolver->resolve($request));

                // update the expression
                $this->manager->persist($expression);
                $this->manager->flush();

                return new RedirectResponse($this->container->get('router')->generate('ulabox_ruler_expression_show', array(
                    'id' => $expression->getId()
                )));
            }
        }

        $data = $expression->toArray();

        return $this->container->get('templating')->renderResponse('UlaboxRulerBundle:Expression:update.html.'.$this->getEngine(), array(
            'form'       => $form->createView(),
            'expression' => $expression,
            'terms'      => json_encode($data['terms'])
        ));
    }

    /**
     * Deletes expressions.
     *
     * @param integer $id The expression id
     *
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $expression = $this->findExpressionOr404($id);

        // remove the expression
        $this->manager->remove($expression);
        $this->manager->flush();

        return new RedirectResponse($this->container->get('router')->generate('ulabox_ruler_expression_list'));
    }

    /**
     * Tries to find expression with given id.
     * Throws a special http exception with code 404 if unsuccessful.
     *
     * @param integer $id The expression id
     *
     * @return ExpressionInterface
     *
     * @throws NotFoundHttpException
     */
    protected function findExpressionOr404($id)
    {
        if (!$expression = $this->container->get('ulabox_ruler.repository.expression')->findOneById($id)) {
            throw new NotFoundHttpException('Requested expression does not exist');
        }

        return $expression;
    }

    /**
     * Set flash shortcut method.
     *
     * @param string $name
     * @param mixed  $value
     */
    protected function setFlash($name, $value)
    {
        $this->container->get('session')->setFlash($name, $value);
    }

    /**
     * Returns templating engine name.
     *
     * @return string
     */
    protected function getEngine()
    {
        return $this->container->getParameter('ulabox_ruler.engine');
    }
}