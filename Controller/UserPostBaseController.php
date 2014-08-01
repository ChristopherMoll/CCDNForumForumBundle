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

use CCDNForum\ForumBundle\Entity\Post;
use CCDNForum\ForumBundle\Form\Handler\User\Post\PostDeleteFormHandler;
use CCDNForum\ForumBundle\Form\Handler\User\Post\PostUpdateFormHandler;
use CCDNForum\ForumBundle\Form\Handler\User\Topic\TopicUpdateFormHandler;

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
class UserPostBaseController extends BaseController
{
    protected $postUpdateFormHandler;
    protected $postDeleteFormHandler;
    protected $topicUpdateFormHandler;


    public function __construct(PostUpdateFormHandler $postUpdateFormHandler, PostDeleteFormHandler $postDeleteFormHandler, TopicUpdateFormHandler $topicUpdateFormHandler)
    {
        $this->postUpdateFormHandler = $postUpdateFormHandler;
        $this->postDeleteFormHandler = $postDeleteFormHandler;
        $this->topicUpdateFormHandler = $topicUpdateFormHandler;
    }

    /**
     * @param \CCDNForum\ForumBundle\Form\Handler\User\Post\PostUpdateFormHandler $postUpdateFormHandler
     */
    public function setPostUpdateFormHandler($postUpdateFormHandler)
    {
        $this->postUpdateFormHandler = $postUpdateFormHandler;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Post                        $post
     * @return \CCDNForum\ForumBundle\Form\Handler\PostUpdateFormHandler
     */
    protected function getFormHandlerToEditPost(Post $post)
    {
        // If post is the very first post of the topic then use a topic handler so user can change topic title.
        if ($post->getTopic()->getFirstPost()->getId() == $post->getId()) {
            $formHandler = $this->topicUpdateFormHandler;
        } else {
            $formHandler = $this->postUpdateFormHandler;
        }

        $formHandler->setPost($post);
        $formHandler->setUser($this->getUser());
        $formHandler->setRequest($this->getRequest());

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Post                        $post
     * @return \CCDNForum\ForumBundle\Form\Handler\PostDeleteFormHandler
     */
    protected function getFormHandlerToDeletePost(Post $post)
    {
        $formHandler = $this->postDeleteFormHandler;

        $formHandler->setPost($post);
        $formHandler->setUser($this->getUser());
        $formHandler->setRequest($this->getRequest());

        return $formHandler;
    }
}
