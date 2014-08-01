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
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicEvent;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\UserTopicFloodEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
use CCDNForum\ForumBundle\Entity\ForumInterface;
use CCDNForum\ForumBundle\Entity\BoardInterface;
use CCDNForum\ForumBundle\Entity\PostInterface;
use CCDNForum\ForumBundle\Component\FloodControl;

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
class TopicCreateFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\User\Topic\TopicCreateFormType $formType
     */
    protected $formTopicType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\User\Post\PostCreateFormType $formType
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
     * @var \CCDNForum\ForumBundle\Model\FrontModel\BoardModel $boardModel
     */
    protected $boardModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Board $board
     */
    protected $board;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Forum $forum
     */
    protected $forum;

    /**
     *
     * @access private
     * @var \CCDNForum\ForumBundle\Component\FloodControl $floodControl
     */
    private $floodControl;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\Form\FormFactory $factory
     * @param \CCDNForum\ForumBundle\Form\Type\User\Topic\TopicCreateFormType $formTopicType
     * @param $formPostType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ModelInterface $topicModel
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ModelInterface $postModel
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ModelInterface $boardModel
     * @param \CCDNForum\ForumBundle\Component\FloodControl $floodControl
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $formTopicType,
     $formPostType, ModelInterface $topicModel, ModelInterface $postModel, ModelInterface $boardModel, FloodControl $floodControl)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->formTopicType = $formTopicType;
        $this->formPostType = $formPostType;
        $this->topicModel = $topicModel;
        $this->postModel = $postModel;
        $this->boardModel = $boardModel;
        $this->floodControl = $floodControl;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\ForumInterface $forum
     * @return \CCDNForum\ForumBundle\Form\Handler\User\Topic\TopicCreateFormHandler
     */
    public function setForum(ForumInterface $forum)
    {
        $this->forum = $forum;

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\BoardInterface $board
     * @return \CCDNForum\ForumBundle\Form\Handler\User\Topic\TopicCreateFormHandler
     */
    public function setBoard(BoardInterface $board)
    {
        $this->board = $board;

        return $this;
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function process()
    {
        $this->getForm();

        if ($this->floodControl->isFlooded()) {
            $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_CREATE_FLOODED, new UserTopicFloodEvent($this->request));

            return false;
        }

        $this->floodControl->incrementCounter();

        if ($this->request->getMethod() == 'POST') {
            $this->form->bind($this->request);

            // Validate
            if ($this->form->isValid()) {
                if ($this->getSubmitAction() == 'post') {
                    $formData = $this->form->getData();

                    $this->onSuccess($formData);

                    return true;
                }
            }
        }

        return false;
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
            if (! is_object($this->board) || ! ($this->board instanceof BoardInterface)) {
                throw new \Exception('Board must be specified to be create a Topic in TopicCreateFormHandler');
            }

            $filteredBoards = $this->boardModel->findAllBoardsForForumById($this->forum->getId());
            $topicOptions = array(
                'boards' => $filteredBoards,
                'auto_initialize' => false,
            );

            $topic = $this->topicModel->createTopic();
            $topic->setBoard($this->board);

            $post = $this->postModel->createPost();
            $post->setTopic($topic);
            $post->setCreatedBy($this->user);

            $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_CREATE_INITIALISE, new UserTopicEvent($this->request, $post->getTopic()));

            $this->form = $this->factory->create($this->formPostType, $post);
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
        $post->setCreatedDate(new \DateTime());
        $post->setCreatedBy($this->user);
        $post->setDeleted(false);

        $topic = $post->getTopic();
        $topic->setCachedViewCount(0);
        $topic->setCachedReplyCount(0);
        $topic->setClosed(false);
        $topic->setDeleted(false);
        $topic->setSticky(false);

        $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_CREATE_SUCCESS, new UserTopicEvent($this->request, $topic));

        $this->postModel->savePost($post);
        $topic->setFirstPost($post);
        $topic->setLastPost($post);
        $this->topicModel->saveTopic($topic);

        $this->dispatcher->dispatch(ForumEvents::USER_TOPIC_CREATE_COMPLETE, new UserTopicEvent($this->request, $topic, $this->didAuthorSubscribe()));
    }

    /**
     *
     * @access public
     * @return bool
     */
    public function didAuthorSubscribe()
    {
        return $this->form->get('subscribe')->getData();
    }
}
