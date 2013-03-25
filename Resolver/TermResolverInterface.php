<?php

namespace Ulabox\Bundle\RulerBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;

/**
 * Term resolver interface.
 */
interface TermResolverInterface
{
    /**
     * Get the terms for given request.
     *
     * @param Request $request
     *
     * @return ArrayCollection
     */
    public function resolve(Request $request);
}