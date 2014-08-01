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

use CCDNForum\ForumBundle\Entity\Board;
use CCDNForum\ForumBundle\Form\Handler\Admin\Board\BoardCreateFormHandler;
use CCDNForum\ForumBundle\Form\Handler\Admin\Board\BoardDeleteFormHandler;
use CCDNForum\ForumBundle\Form\Handler\Admin\Board\BoardUpdateFormHandler;

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
class AdminBoardBaseController extends BaseController
{
    protected $boardCreateFormHandler;
    protected $boardUpdateFormHandler;
    protected $boardDeleteFormHandler;


    public function __construct(BoardCreateFormHandler $boardCreateFormHandler, BoardUpdateFormHandler $boardUpdateFormHandler, BoardDeleteFormHandler $boardDeleteFormHandler)
    {
        $this->boardCreateFormHandler = $boardCreateFormHandler;
        $this->boardUpdateFormHandler = $boardUpdateFormHandler;
        $this->boardDeleteFormHandler = $boardDeleteFormHandler;
    }

    /**
     *
     * @access protected
     * @param null $categoryFilter
     * @return \CCDNForum\ForumBundle\Form\Handler\BoardCreateFormHandler
     */
    protected function getFormHandlerToCreateBoard($categoryFilter = null)
    {
        $formHandler = $this->boardCreateFormHandler;

        $formHandler->setRequest($this->getRequest());

        if ($categoryFilter) {
            $category = $this->getCategoryModel()->findOneCategoryById($categoryFilter);

            if ($category) {
                $formHandler->setDefaultCategory($category);
            }
        }

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     * @return \CCDNForum\ForumBundle\Form\Handler\BoardUpdateFormHandler
     */
    protected function getFormHandlerToUpdateBoard(Board $board)
    {
        $formHandler = $this->boardUpdateFormHandler;

        $formHandler->setRequest($this->getRequest());

        $formHandler->setBoard($board);

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\Board $board
     * @return \CCDNForum\ForumBundle\Form\Handler\BoardDeleteFormHandler
     */
    protected function getFormHandlerToDeleteBoard(Board $board)
    {
        $formHandler = $this->boardDeleteFormHandler;

        $formHandler->setRequest($this->getRequest());

        $formHandler->setBoard($board);

        return $formHandler;
    }

    /**
     *
     * @access protected
     * @param  \CCDNForum\ForumBundle\Entity\Board $board
     * @return array
     */
    protected function getFilterQueryStrings(Board $board)
    {
        $params = array();

        if ($board->getCategory()) {
            $params['category_filter'] = $board->getCategory()->getId();

            if ($board->getCategory()->getForum()) {
                $params['forum_filter'] = $board->getCategory()->getForum()->getId();
            }
        }

        return $params;
    }

    /**
     *
     * @access protected
     * @return array
     */
    protected function getNormalisedCategoryAndForumFilters()
    {
        $forumFilter = $this->getQuery('forum_filter', null);
        $categoryFilter = $this->getQuery('category_filter', null);

        if ($categoryFilter) { // Corrective Measure incase forum/category filters fall out of sync.
            if ($category = $this->getCategoryModel()->findOneCategoryById($categoryFilter)) {
                if ($category->getForum()) {
                    $forumFilter = $category->getForum()->getId();
                } else {
                    $forumFilter = null; // Force it to be blank so 'unassigned' is highlighted.
                }
            } else {
                $forumFilter = null;
            }
        }

        return array(
            'forum_filter' => $forumFilter,
            'category_filter' => $categoryFilter
        );
    }
}
