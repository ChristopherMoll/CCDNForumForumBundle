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

namespace CCDNForum\ForumBundle\Entity;

use Symfony\Component\Security\Core\SecurityContextInterface;

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
interface BoardInterface
{
    public function isAuthorisedToReplyToTopic(SecurityContextInterface $securityContext);
    public function hasTopicReplyAuthorisedRole($role);
    public function setTopicReplyAuthorisedRoles(array $roles = null);
    public function getTopicReplyAuthorisedRoles();
    public function isAuthorisedToCreateTopic(SecurityContextInterface $securityContext);
    public function hasTopicCreateAuthorisedRole($role);
    public function setTopicCreateAuthorisedRoles(array $roles = null);
    public function getTopicCreateAuthorisedRoles();
    public function isAuthorisedToRead(SecurityContextInterface $securityContext);
    public function hasReadAuthorisedRole($role);
    public function getReadAuthorisedRoles();
    public function setReadAuthorisedRoles(array $roles = null);
    public function setCachedPostCount($cachedPostCount);
    public function getCachedPostCount();
    public function setCachedTopicCount($cachedTopicCount);
    public function getCachedTopicCount();
    public function setListOrderPriority($listOrderPriority);
    public function getListOrderPriority();
    public function setDescription($description);
    public function getDescription();
    public function setName($name);
    public function getName();
    public function getId();
}
