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

namespace CCDNForum\ForumBundle\Form\Handler\Admin\Forum;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminForumEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
use CCDNForum\ForumBundle\Entity\ForumInterface;

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
class ForumUpdateFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Forum\ForumUpdateFormType $forumUpdateFormType
     */
    protected $forumUpdateFormType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\ForumModel $forumModel
     */
    protected $forumModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Forum $forum
     */
    protected $forum;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\Form\FormFactory $factory
     * @param $forumUpdateFormType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ModelInterface $forumModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $forumUpdateFormType, ModelInterface $forumModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->forumUpdateFormType = $forumUpdateFormType;
        $this->forumModel = $forumModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\ForumInterface $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\Admin\Forum\ForumUpdateFormHandler
     */
    public function setForum(ForumInterface $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     *
     * @access public
     * @throws \Exception
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        if (null == $this->form) {
            if (!is_object($this->forum) && !$this->forum instanceof ForumInterface) {
                throw new \Exception('Forum object must be specified to edit.');
            }

            $this->dispatcher->dispatch(ForumEvents::ADMIN_FORUM_EDIT_INITIALISE, new AdminForumEvent($this->request, $this->forum));

            $this->form = $this->factory->create($this->forumUpdateFormType, $this->forum);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\ForumInterface $forum
     */
    protected function onSuccess(ForumInterface $forum)
    {
        $this->dispatcher->dispatch(ForumEvents::ADMIN_FORUM_EDIT_SUCCESS, new AdminForumEvent($this->request, $forum));

        $this->forumModel->updateForum($forum);

        $this->dispatcher->dispatch(ForumEvents::ADMIN_FORUM_EDIT_COMPLETE, new AdminForumEvent($this->request, $forum));
    }
}
