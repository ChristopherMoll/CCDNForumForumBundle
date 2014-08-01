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

namespace CCDNForum\ForumBundle\Model\Component\Manager;

use CCDNForum\ForumBundle\Model\Component\Gateway\GatewayInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use CCDNForum\ForumBundle\Model\Component\Manager\ManagerInterface;
use CCDNForum\ForumBundle\Model\Component\Manager\BaseManager;

use CCDNForum\ForumBundle\Entity\SubscriptionInterface;
use CCDNForum\ForumBundle\Entity\TopicInterface;

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
class SubscriptionManager extends BaseManager implements ManagerInterface
{
    protected $subscriptionClass;

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Gateway\GatewayInterface|\CCDNForum\ForumBundle\Model\Component\Gateway\GatewayInterface $gateway
     * @param $subscriptionClass
     */
    public function __construct(GatewayInterface $gateway, $subscriptionClass)
    {
        $this->gateway = $gateway;
        $this->subscriptionClass = $subscriptionClass;
    }

    /**
     *
     * @access public
     * @return \CCDNForum\ForumBundle\Entity\Subscription
     */
    public function createSubscription()
    {
        return $this->gateway->createSubscription();
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\TopicInterface $topic
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function subscribe(TopicInterface $topic, UserInterface $user)
    {
        $subscription = $this->model->findOneSubscriptionForTopicByIdAndUserById($topic->getId(), $user->getId());

        if (! $subscription) {
            $subscriptionClass = $this->subscriptionClass;
            $subscription = new $subscriptionClass();
        }

        if (! $subscription->isSubscribed()) {
            $subscription->setSubscribed(true);
            $subscription->setOwnedBy($user);
            $subscription->setTopic($topic);
            $subscription->setRead(true);
            $subscription->setForum($topic->getBoard()->getCategory()->getForum());

            $this->gateway->saveSubscription($subscription);
        }

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\TopicInterface $topic
     * @param  int $userId
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function unsubscribe(TopicInterface $topic, $userId)
    {
        $subscription = $this->model->findOneSubscriptionForTopicByIdAndUserById($topic->getId(), $userId);

        if (! $subscription) {
            return $this;
        }

        $subscription->setSubscribed(false);
        $subscription->setRead(true);

        $this->gateway->saveSubscription($subscription);

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\SubscriptionInterface $subscription
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function markAsRead(SubscriptionInterface $subscription)
    {
        $subscription->setRead(true);

        $this->persist($subscription)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\SubscriptionInterface $subscription
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function markAsUnread(SubscriptionInterface $subscription)
    {
        $subscription->setRead(false);

        $this->persist($subscription)->flush();

        return $this;
    }

    /**
     *
     * @access public
     * @param  \Doctrine\Common\Collections\ArrayCollection        $subscriptions
     * @param  \Symfony\Component\Security\Core\User\UserInterface $exceptUser
     * @return \CCDNForum\ForumBundle\Manager\ManagerInterface
     */
    public function markTheseAsUnread($subscriptions, UserInterface $exceptUser)
    {
        foreach ($subscriptions as $subscription) {
            if ($subscription->getOwnedBy()) {
                if ($subscription->getOwnedBy()->getId() != $exceptUser->getId()) {
                    $subscription->setRead(false);

                    $this->persist($subscription);
                }
            }
        }

        $this->flush();
    }
}
