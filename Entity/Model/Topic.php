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

namespace CCDNForum\ForumBundle\Entity\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Entity\Board as ConcreteBoard;
use CCDNForum\ForumBundle\Entity\Post as ConcretePost;
use CCDNForum\ForumBundle\Entity\Subscription as ConcreteSubscription;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Topic
 * @package CCDNForum\ForumBundle\Entity\Model
 * @ORM\MappedSuperclass()
 */
abstract class Topic
{
    /** @var Board $board */
    protected $board = null;

    /** @var UserInterface $closedBy */
    protected $closedBy = null;

    /** @var UserInterface $deletedBy */
    protected $deletedBy = null;

    /** @var UserInterface $stickiedBy */
    protected $stickiedBy = null;

    /** @var Post $firstPost */
    protected $firstPost = null;

    /** @var Post $lastPost */
    protected $lastPost = null;

    /** @var ArrayCollection $posts */
    protected $posts;

    /** @var ArrayCollection $subscriptions */
    protected $subscriptions;

    /**
     *
     * @var string $title
     */
    protected $title;

    /**
     *
     * @var integer $cachedViewCount
     */
    protected $cachedViewCount = 0;

    /**
     *
     * @var integer $cachedReplyCount
     */
    protected $cachedReplyCount = 0;

    /**
     *
     * @var Boolean $isClosed
     */
    protected $isClosed = false;

    /**
     *
     * @var \DateTime $closedDate
     */
    protected $closedDate;

    /**
     *
     * @var Boolean $isDeleted
     */
    protected $isDeleted = false;

    /**
     *
     * @var \DateTime $deletedDate
     */
    protected $deletedDate;

    /**
     *
     * @var \DateTime $stickiedDate
     */
    protected $stickiedDate;

    /**
     *
     * @var Boolean $isSticky
     */
    protected $isSticky = false;


    /**
     *
     * @access public
     */
    public function __construct()
    {
        // your own logic
        $this->posts = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
    }

    /**
     * Get board
     *
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set board
     *
     * @param  Board $board
     * @return Topic
     */
    public function setBoard(ConcreteBoard $board = null)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get closed_by
     *
     * @return UserInterface
     */
    public function getClosedBy()
    {
        return $this->closedBy;
    }

    /**
     * Set closed_by
     *
     * @param  UserInterface $closedBy
     * @return Topic
     */
    public function setClosedBy(UserInterface $closedBy = null)
    {
        $this->closedBy = $closedBy;

        return $this;
    }

    /**
     * Get deleted_by
     *
     * @return UserInterface
     */
    public function getDeletedBy()
    {
        return $this->deletedBy;
    }

    /**
     * Set deleted_by
     *
     * @param  UserInterface $deletedBy
     * @return Topic
     */
    public function setDeletedBy(UserInterface $deletedBy = null)
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    /**
     * Get stickiedBy
     *
     * @return UserInterface
     */
    public function getStickiedBy()
    {
        return $this->stickiedBy;
    }

    /**
     * Set stickiedBy
     *
     * @param  UserInterface $stickiedBy
     * @return Topic
     */
    public function setStickiedBy(UserInterface $stickiedBy = null)
    {
        $this->stickiedBy = $stickiedBy;

        return $this;
    }

    /**
     * Get first_post
     *
     * @return Post
     */
    public function getFirstPost()
    {
        return $this->firstPost;
    }

    /**
     * Set first_post
     *
     * @param  Post  $firstPost
     * @return Topic
     */
    public function setFirstPost(ConcretePost $firstPost = null)
    {
        $this->firstPost = $firstPost;

        return $this;
    }

    /**
     * Get last_post
     *
     * @return Post
     */
    public function getLastPost()
    {
        return $this->lastPost;
    }

    /**
     * Set last_post
     *
     * @param  Post  $lastPost
     * @return Topic
     */
    public function setLastPost(ConcretePost $lastPost = null)
    {
        $this->lastPost = $lastPost;

        return $this;
    }

    /**
     *
     * Get posts
     *
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     *
     * Set posts
     *
     * @param  ArrayCollection $posts
     * @return Topic
     */
    public function setPosts(ArrayCollection $posts = null)
    {
        $this->posts = $posts;

        return $this;
    }

    /**
     *
     * Add post
     *
     * @param  Post  $post
     * @return Topic
     */
    public function addPost(ConcretePost $post)
    {
        $this->posts->add($post);

        return $this;
    }

    /**
     *
     * @param  Post  $post
     * @return Topic
     */
    public function removePost(ConcretePost $post)
    {
        $this->posts->removeElement($post);

        return $this;
    }

    /**
     *
     * Get subscriptions
     *
     * @return ArrayCollection
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     *
     * @param  ArrayCollection $subscriptions
     * @return Topic
     */
    public function setSubscriptions(ArrayCollection $subscriptions = null)
    {
        $this->subscriptions = $subscriptions;

        return $this;
    }

    /**
     * Add subscription
     *
     * @param  Subscription $subscription
     * @return Topic
     */
    public function addSubscription(ConcreteSubscription $subscription)
    {
        $this->subscriptions->add($subscription);

        return $this;
    }

    /**
     *
     * @param Subscription $subscription
     * @return $this
     */
    public function removeSubscription(ConcreteSubscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Topic
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get cachedViewCount
     *
     * @return integer
     */
    public function getCachedViewCount()
    {
        return $this->cachedViewCount;
    }

    /**
     * Set cachedViewCount
     *
     * @param  integer $cachedViewCount
     * @return Topic
     */
    public function setCachedViewCount($cachedViewCount)
    {
        $this->cachedViewCount = $cachedViewCount;

        return $this;
    }

    /**
     * Get cachedReplyCount
     *
     * @return integer
     */
    public function getCachedReplyCount()
    {
        return $this->cachedReplyCount;
    }

    /**
     * Set cachedReplyCount
     *
     * @param  integer $cachedReplyCount
     * @return Topic
     */
    public function setCachedReplyCount($cachedReplyCount)
    {
        $this->cachedReplyCount = $cachedReplyCount;

        return $this;
    }

    /**
     * Get isClosed
     *
     * @return boolean
     */
    public function isClosed()
    {
        return $this->isClosed;
    }

    /**
     * Set isClosed
     *
     * @param  boolean $isClosed
     * @return Topic
     */
    public function setClosed($isClosed)
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    /**
     * Get closedDate
     *
     * @return \datetime
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }

    /**
     * Set closedDate
     *
     * @param  \datetime $closedDate
     * @return Topic
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set isDeleted
     *
     * @param  boolean $isDeleted
     * @return Topic
     */
    public function setDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get deletedDate
     *
     * @return \datetime
     */
    public function getDeletedDate()
    {
        return $this->deletedDate;
    }

    /**
     * Set deletedDate
     *
     * @param  \datetime $deletedDate
     * @return Topic
     */
    public function setDeletedDate($deletedDate)
    {
        $this->deletedDate = $deletedDate;

        return $this;
    }

    /**
     * Get isSticky
     *
     * @return boolean
     */
    public function isSticky()
    {
        return $this->isSticky;
    }

    /**
     * Set isSticky
     *
     * @param  boolean $isSticky
     * @return Topic
     */
    public function setSticky($isSticky)
    {
        $this->isSticky = $isSticky;

        return $this;
    }

    /**
     * Get stickiedDate
     *
     * @return \datetime
     */
    public function getStickiedDate()
    {
        return $this->stickiedDate;
    }

    /**
     * Set stickiedDate
     *
     * @param  \datetime $stickiedDate
     * @return Topic
     */
    public function setStickiedDate($stickiedDate)
    {
        $this->stickiedDate = $stickiedDate;

        return $this;
    }

}
