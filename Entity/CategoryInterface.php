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
interface CategoryInterface
{
    public function forumName();
    public function getId();
    public function getName();
    public function setName($name);
    public function getListOrderPriority();
    public function setListOrderPriority($listOrderPriority);
    public function getReadAuthorisedRoles();
    public function setReadAuthorisedRoles(array $roles = null);
    public function hasReadAuthorisedRole($role);
    public function isAuthorisedToRead(SecurityContextInterface $securityContext);
}
