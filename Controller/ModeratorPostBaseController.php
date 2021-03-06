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
use CCDNForum\ForumBundle\Form\Handler\Moderator\Post\PostUnlockFormHandler;

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
class ModeratorPostBaseController extends BaseController
{
    protected $postUnlockFormHandler;

//    public function __construct(PostUnlockFormHandler $postUnlockFormHandler)
//    {
//        $this->postUnlockFormHandler = $postUnlockFormHandler;
//    }

    /**
     * @param \CCDNForum\ForumBundle\Form\Handler\Moderator\Post\PostUnlockFormHandler $postUnlockFormHandler
     */
    public function setPostUnlockFormHandler(PostUnlockFormHandler $postUnlockFormHandler)
    {
        $this->postUnlockFormHandler = $postUnlockFormHandler;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Post $post
     * @return \CCDNForum\ForumBundle\Form\Handler\Moderator\Post\PostUnlockFormHandler
     */
    protected function getFormHandlerToUnlockPost(Post $post)
    {
        $formHandler = $this->postUnlockFormHandler;

        $formHandler->setPost($post);
        $formHandler->setUser($this->getUser());
        $formHandler->setRequest($this->getRequest());

        return $formHandler;
    }
}
