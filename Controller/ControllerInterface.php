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

use CCDNForum\ForumBundle\Model\FrontModel;
use CCDNForum\ForumBundle\Component\Helper\PaginationConfigHelper as PageHelper;
use CCDNForum\ForumBundle\Component\Security\Authorizer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as Dispatcher;
use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine as Templating;


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
interface ControllerInterface
{
    /**
     *
     * @access public
     * @param \Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine $templating
     * @return Templating
     */
    public function setTemplating(Templating $templating);

    /**
     *
     * @access public
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     * @return Router
     */
    public function setRouter(Router $router);

    /**
     *
     * @access public
     * @param \Symfony\Component\Security\Core\SecurityContext $securityContext
     * @return SecurityContext
     */
    public function setSecurityContext(SecurityContext $securityContext);

    /**
     *
     * @access public
     * @param $engine
     * @return string
     */
    public function setEngine($engine);

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\RequestStack $request
     * @return Request
     */
    public function setRequest(RequestStack $request);

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @return Dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Security\Authorizer $authorizer
     * @return Authorizer
     */
    public function setAuthorizer(Authorizer $authorizer);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ForumModel $forumModel
     * @return FrontModel\ForumModel
     */
    public function setForumModel(FrontModel\ForumModel $forumModel);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel $categoryModel
     * @return FrontModel\CategoryModel
     */
    public function setCategoryModel(FrontModel\CategoryModel $categoryModel);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     * @return FrontModel\BoardModel
     */
    public function setBoardModel(FrontModel\BoardModel $boardModel);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
     * @return FrontModel\PostModel
     */
    public function setPostModel(FrontModel\PostModel $postModel);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\RegistryModel $registryModel
     * @return FrontModel\RegistryModel
     */
    public function setRegistryModel(FrontModel\RegistryModel $registryModel);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\SubscriptionModel $subscriptionModel
     * @return FrontModel\SubscriptionModel
     */
    public function setSubscriptionModel(FrontModel\SubscriptionModel $subscriptionModel);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     * @return FrontModel\SubscriptionModel
     */
    public function setTopicModel(FrontModel\TopicModel $topicModel);

    /**
     *
     * @access public
     *
     */
    public function setCrumbs($crumbs);

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Component\Helper\PaginationConfigHelper $pageHelper
     * @return PageHelper
     */
    public function setPageHelper(PageHelper $pageHelper);
}
