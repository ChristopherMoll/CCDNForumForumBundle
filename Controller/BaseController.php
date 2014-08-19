<?php

/*
 * This file is part of the CCDNForum ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\Controller;

use CCDNForum\ForumBundle\Component\Helper\PaginationConfigHelper as PageHelper;
use CCDNForum\ForumBundle\Component\Security\Authorizer;
use CCDNForum\ForumBundle\Model\FrontModel;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Bundle\TwigBundle\TwigEngine as Templating;
use Symfony\Component\HttpFoundation\RequestStack;

use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\Post;
use Symfony\Component\Security\Core\SecurityContext;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
class BaseController implements ControllerInterface
{
    /**
     *
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    protected $router;

    /**
     *
     * @var \Symfony\Bundle\TwigBundle\Debug\TwigEngine $templating
     */
    protected $templating;

    /**
     *
     * @var RequestStack
     */
    protected $request;

    /**
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface  $dispatcher;
     */
    protected $dispatcher;

    /**
     *
     * @var string;
     */
    protected $engine;

    /**
     * @var PageHelper
     */
    protected $pageHelper;

    /**
     * @var mixed
     */
    protected $crumbs;

    /**
     *
     * @var \Symfony\Component\Security\Core\SecurityContext $securityContext
     */
    protected $securityContext;

    /**
     *
     * @var \CCDNForum\ForumBundle\Component\Security\Authorizer $authorizer;
     */
    protected $authorizer;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\ForumModel $forumModel
     */
    protected $forumModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel $categoryModel
     */
    protected $categoryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     */
    protected $boardModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     */
    protected $topicModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
     */
    protected $postModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel $registryModel
     */
    protected $registryModel;

    /**
     *
     * @var \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel $subscriptionModel
     */
    protected $subscriptionModel;

    public function __construct()
    {

    }

    /**
     *
     * @access protected
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected function getRouter()
    {
        return $this->router;
    }

    /**
     *
     * @access protected
     * @param  string $route
     * @param  Array  $params
     * @return string
     */
    protected function path($route, $params = array())
    {
        return $this->getRouter()->generate($route, $params);
    }

    /**
     *
     * @access protected
     * @return \Symfony\Bundle\TwigBundle\Debug\TwigEngine
     */
    protected function getTemplating()
    {
        return $this->templating;
    }

    /**
     *
     * @access protected
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->request->getCurrentRequest();
    }

    /**
     *
     * @access protected
     * @param  string $prefix
     * @param bool $enforceNumericType
     * @return Array
     */
    protected function getCheckedItemIds($prefix = 'check_', $enforceNumericType = true)
    {
        $request = $this->getRequest();

        $sanitarisedIds = array();

        if ($request->request->has($prefix)) {
            $itemIds = $request->request->get($prefix);

            foreach ($itemIds as $id => $val) {
                if ($enforceNumericType == true) {
                    if (! is_numeric($id)) {
                        continue;
                    }
                }

                $sanitarisedIds[] = $id;
            }
        }

        return $sanitarisedIds;
    }

    /**
     *
     * @access protected
     * @param  string $template
     * @param  Array  $params
     * @param  string $engine
     * @return string
     */
    protected function renderResponse($template, $params = array(), $engine = null)
    {
        return $this->getTemplating()->renderResponse($template . ($engine ?: $this->getEngine()), $params);
    }

    /**
     *
     * @access protected
     * @param  string                                             $url
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponse($url)
    {
        return new RedirectResponse($url);
    }

    /**
     *
     * @access protected
     * @param  string                                             $forumName
     * @param  \CCDNForum\ForumBundle\Entity\Topic                $topic
     * @param  \CCDNForum\ForumBundle\Entity\Post                 $post
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponseForTopicOnPageFromPost($forumName, Topic $topic, Post $post)
    {
        //$page = $this->getTopicModel()->getPageForPostOnTopic($topic, $topic->getLastPost()); // Page of the last post.
        $response = $this->redirectResponse($this->path('ccdn_forum_user_topic_show', array(
            'forumName' => $forumName,
            'topicId' => $topic->getId(),
            /*'page' => $page*/
        )) /* . '#' . $topic->getLastPost()->getId()*/);

        return $response;
    }

    /**
     *
     * @access protected
     * @return string
     */
    protected function getEngine()
    {
        return $this->engine;
    }

    /**
     *
     * @access protected
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    protected function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     *
     * @access protected
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    protected function getUser()
    {
        return $this->getSecurityContext()->getToken()->getUser();
    }

    /**
     *
     * @access protected
     * @param  string $role
     * @return bool
     */
    protected function isGranted($role)
    {
        if (! $this->getSecurityContext()->isGranted($role)) {
            return false;
        }

        return true;
    }

    /**
     *
     * @access protected
     * @param  string $role |boolean $role
     * @return bool
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    protected function isAuthorised($role)
    {
        if (is_bool($role)) {
            if ($role == false) {
                throw new AccessDeniedException('You do not have permission to use this resource.');
            }

            return true;
        }

        if (! $this->isGranted($role)) {
            throw new AccessDeniedException('You do not have permission to use this resource.');
        }

        return true;
    }

    /**
     *
     * @access protected
     * @param  \Object $item
     * @param  string $message
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return bool
     */
    protected function isFound($item, $message = null)
    {
        if (null == $item) {
            throw new NotFoundHttpException($message ?: 'Page you are looking for could not be found!');
        }

        return true;
    }

    protected function getQuery($query, $default)
    {
        return $this->getRequest()->query->get($query, $default);
    }

    protected function dispatch($name, Event $event)
    {
        $this->dispatcher->dispatch($name, $event);
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Component\Security\Authorizer
     */
    protected function getAuthorizer()
    {
        return $this->authorizer;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\ForumModel
     */
    protected function getForumModel()
    {
        return $this->forumModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel
     */
    protected function getCategoryModel()
    {
        return $this->categoryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\BoardModel
     */
    protected function getBoardModel()
    {
        return $this->boardModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\TopicModel
     */
    protected function getTopicModel()
    {
        return $this->topicModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\PostModel
     */
    protected function getPostModel()
    {
        return $this->postModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel
     */
    protected function getRegistryModel()
    {
        return $this->registryModel;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel
     */
    protected function getSubscriptionModel()
    {
        return $this->subscriptionModel;
    }

    /**
     *
     * @access protected
     */
    protected function getCrumbs()
    {
        return $this->crumbs;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Component\Helper\PaginationConfigHelper
     */
    protected function getPageHelper()
    {
        return $this->pageHelper;
    }

    /**
     *
     * @access public
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @return Templating
     */
    public function setTemplating(Templating $templating)
    {
        $this->templating = $templating;
    }

    /**
     *
     * @access public
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     * @return Router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\Security\Core\SecurityContext $securityContext
     * @return SecurityContext
     */
    public function setSecurityContext(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     *
     * @access public
     * @param $engine
     * @return string
     */
    public function setEngine($engine)
    {
        $this->engine = $engine;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     * @return Request
     */
    public function setRequest(RequestStack $request)
    {
        $this->request = $request;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @return Dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Security\Authorizer $authorizer
     * @return Authorizer
     */
    public function setAuthorizer(Authorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ForumModel $forumModel
     * @return FrontModel\ForumModel
     */
    public function setForumModel(FrontModel\ForumModel $forumModel)
    {
        $this->forumModel = $forumModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel $categoryModel
     * @return FrontModel\CategoryModel
     */
    public function setCategoryModel(FrontModel\CategoryModel $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     * @return FrontModel\BoardModel
     */
    public function setBoardModel(FrontModel\BoardModel $boardModel)
    {
        $this->boardModel = $boardModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
     * @return FrontModel\PostModel
     */
    public function setPostModel(FrontModel\PostModel $postModel)
    {
        $this->postModel = $postModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel $registryModel
     * @return FrontModel\RegistryModel
     */
    public function setRegistryModel(FrontModel\RegistryModel $registryModel)
    {
        $this->registryModel = $registryModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel $subscriptionModel
     * @return FrontModel\SubscriptionModel
     */
    public function setSubscriptionModel(FrontModel\SubscriptionModel $subscriptionModel)
    {
        $this->subscriptionModel = $subscriptionModel;
    }

    /**
     *
     * @access public
     *
     */
    public function setCrumbs($crumbs)
    {
        $this->crumbs = $crumbs;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Helper\PaginationConfigHelper $pageHelper
     * @return PageHelper
     */
    public function setPageHelper(PageHelper $pageHelper)
    {
        $this->pageHelper = $pageHelper;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     * @return FrontModel\SubscriptionModel
     */
    public function setTopicModel(FrontModel\TopicModel $topicModel)
    {
        $this->topicModel = $topicModel;
    }
}
