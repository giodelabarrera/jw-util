<?php
/**
 * Created by PhpStorm.
 * User: giorgio
 * Date: 14/12/16
 * Time: 16:32
 */

namespace AppBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestVoter
 * @package AppBundle\Menu
 */
class RequestVoter implements VoterInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * RequestVoter constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param ItemInterface $item
     * @return bool|null
     */
    public function matchItem(ItemInterface $item)
    {
        $request = $this->requestStack->getCurrentRequest();

//        dump($item->getUri());
//        dump($request->getRequestUri());
//        dump($request->getBaseUrl());

        if ($item->getUri() === $request->getRequestUri()) {
            // URL's completely match
            return true;
        } else if($item->getUri() !== $request->getBaseUrl().'/' && (substr($request->getRequestUri(), 0, strlen($item->getUri())) === $item->getUri())) {
            // URL isn't just "/" and the first part of the URL match
            return true;
        }
        return null;
    }
}


