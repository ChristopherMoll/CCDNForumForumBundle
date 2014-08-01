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
use CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumCreateFormHandler;
use CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumDeleteFormHandler;
use CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumUpdateFormHandler;

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
class AdminForumBaseController extends BaseController
{
    protected $forumCreateFormHandler;
    protected $forumUpdateFormHandler;
    protected $forumDeleteFormHandler;


    public function __construct(ForumCreateFormHandler $forumCreateFormHandler, ForumUpdateFormHandler $forumUpdateFormHandler, ForumDeleteFormHandler $forumDeleteFormHandler)
    {
        $this->forumCreateFormHandler = $forumCreateFormHandler;
        $this->forumUpdateFormHandler = $forumUpdateFormHandler;
        $this->forumDeleteFormHandler = $forumDeleteFormHandler;
    }

    /**
     * @param \CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumCreateFormHandler $forumCreateFormHandler
     */
    public function setForumCreateFormHandler(ForumCreateFormHandler $forumCreateFormHandler)
    {
        $this->forumCreateFormHandler = $forumCreateFormHandler;
    }

    /**
     * @param \CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumUpdateFormHandler $forumUpdateFormHandler
     */
    public function setForumUpdateFormHandler(ForumUpdateFormHandler $forumUpdateFormHandler)
    {
        $this->forumUpdateFormHandler = $forumUpdateFormHandler;
    }

    /**
     * @param \CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumDeleteFormHandler $forumDeleteFormHandler
     */
    public function setForumDeleteFormHandler(ForumDeleteFormHandler $forumDeleteFormHandler)
    {
        $this->forumDeleteFormHandler = $forumDeleteFormHandler;
    }

    /**
     *
     * @access protected
     * @return \CCDNForum\ForumBundle\Form\Handler\ForumCreateFormHandler
     */
    protected function getFormHandlerToCreateForum()
    {
        $formHandler = $this->forumCreateFormHandler;

        $formHandler->setRequest($this->getRequest());

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Forum $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\ForumUpdateFormHandler
     */
    protected function getFormHandlerToUpdateForum(Forum $forum)
    {
        $formHandler = $this->forumUpdateFormHandler;

        $formHandler->setRequest($this->getRequest());

        $formHandler->setForum($forum);

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Forum $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\ForumDeleteFormHandler
     */
    protected function getFormHandlerToDeleteForum(Forum $forum)
    {
        $formHandler = $this->forumDeleteFormHandler;

        $formHandler->setRequest($this->getRequest());

        $formHandler->setForum($forum);

        return $formHandler;
    }
}
