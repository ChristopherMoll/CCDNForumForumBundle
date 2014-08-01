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

use CCDNForum\ForumBundle\Entity\Forum;
use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Entity\PostInterface;
use CCDNForum\ForumBundle\Entity\Topic;
use CCDNForum\ForumBundle\Entity\TopicInterface;
use CCDNForum\ForumBundle\Form\Handler\User\Post\PostCreateFormHandler;
use CCDNForum\ForumBundle\Form\Handler\User\Topic\TopicCreateFormHandler;

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
class UserTopicBaseController extends BaseController
{
    protected $postCreateFormHandler;
    protected $topicCreateFormHandler;



    public function __construct(PostCreateFormHandler $postCreateFormHandler, TopicCreateFormHandler $topicCreateFormHandler)
    {
        $this->postCreateFormHandler = $postCreateFormHandler;
        $this->topicCreateFormHandler = $topicCreateFormHandler;
    }
    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Forum                        $forum
     * @param  \CCDNForum\ForumBundle\Entity\Board                        $board
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicCreateFormHandler
     */
    protected function getFormHandlerToCreateTopic(Forum $forum, Board $board)
    {
        $formHandler = $this->topicCreateFormHandler;

        $formHandler->setForum($forum);
        $formHandler->setBoard($board);
        $formHandler->setUser($this->getUser());
        $formHandler->setRequest($this->getRequest());

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\TopicInterface $topic
     * @param \CCDNForum\ForumBundle\Entity\PostInterface $postToQuote
     * @return \CCDNForum\ForumBundle\Form\Handler\User\Topic\TopicCreateFormHandler
     */
    protected function getFormHandlerToReplyToTopic(TopicInterface $topic, $postToQuote = null)
    {
        $formHandler = $this->postCreateFormHandler;

        $formHandler->setTopic($topic);
        $formHandler->setUser($this->getUser());
        $formHandler->setRequest($this->getRequest());

        if(is_subclass_of($postToQuote, 'CCDNForum\ForumBundle\Entity\PostInterface')) {
            $formHandler->setPostToQuote($postToQuote);
        }

        return $formHandler;
    }
}
