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

namespace CCDNForum\ForumBundle\Form\Handler\User\Topic;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserPostEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
use CCDNForum\ForumBundle\Entity\TopicInterface;
use CCDNForum\ForumBundle\Entity\PostInterface;

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
class TopicUpdateFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\User\Topic\TopicUpdateFormType $formTopicType
     */
    protected $formTopicType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\User\Post\PostUpdateFormType $formPostType
     */
    protected $formPostType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\TopicModel $topicModel
     */
    protected $topicModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\PostModel $postModel
     */
    protected $postModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Post $post
     */
    protected $post;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\Form\FormFactory $factory
     * @param $formTopicType
     * @param $formPostType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ModelInterface $topicModel
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ModelInterface $postModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory,
     $formTopicType, $formPostType, ModelInterface $topicModel, ModelInterface $postModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formTopicType = $formTopicType;
        $this->formPostType = $formPostType;
        $this->topicModel = $topicModel;
        $this->postModel = $postModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\PostInterface $post
     * @return \CCDNForum\ForumBundle\Form\Handler\TopicUpdateFormHandler
     */
    public function setPost(PostInterface $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     *
     * @access public
     * @throws \Exception
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            if (! is_object($this->post) || ! ($this->post instanceof PostInterface)) {
                throw new \Exception('Post must be specified to be update a Topic in TopicUpdateFormHandler');
            }

            $topic = $this->post->getTopic();

            if (! is_object($topic) || ! ($topic instanceof TopicInterface)) {
                throw new \Exception('Post must have a valid Topic in TopicUpdateFormHandler');
            }

            $topicOptions = array(
                'auto_initialize' => false,
            );

            $this->dispatcher->dispatch(ForumEvents::USER_POST_EDIT_INITIALISE, new UserPostEvent($this->request, $this->post));

            $this->form = $this->factory->create($this->formPostType, $this->post);
            $this->form->add($this->factory->create($this->formTopicType, $topic, $topicOptions));
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\PostInterface $post
     */
    protected function onSuccess(PostInterface $post)
    {
        // get the current time, and compare to when the post was made.
        $now = new \DateTime();
        $interval = $now->diff($post->getCreatedDate());

        // if post is less than 15 minutes old, don't add that it was edited.
        if ($interval->format('%i') > 15) {
            $post->setEditedDate(new \DateTime());
            $post->setEditedBy($this->user);
        }

        $this->dispatcher->dispatch(ForumEvents::USER_POST_EDIT_SUCCESS, new UserPostEvent($this->request, $this->post));

        $this->postModel->updatePost($post);

        $this->dispatcher->dispatch(ForumEvents::USER_POST_EDIT_COMPLETE, new UserPostEvent($this->request, $this->post));
    }
}
