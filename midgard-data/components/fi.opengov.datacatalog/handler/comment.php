<?php
/**
 * @package fi.opengov.datacatalog
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * Datacatalog dataset
 *
 * @package fi.open.datacatalog
 */
class fi_opengov_datacatalog_handler_comment extends net_nehmer_comments_handler_view
{
    /* navigation object */
    var $_navi = null;

    /* number of comments gathered by default */
    var $_num_of_comments = 0;
    
    /**
     * Simple default constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get latest 'n' comments that are posted to blogs
     * @param string GUID of the blog topic
     * @param integer the last 'n' number of comments to be fetched 
     */
    public function _get_last_blog_comments($number = -1)
    {
        $_comments = array();
        $qb = net_nehmer_comments_comment::new_query_builder();
        $qb->add_constraint('status', '>=', 4);
        $_res = $qb->execute();

        foreach($_res as $comment)
        {
            $qb2 = midcom_db_article::new_query_builder();
            $qb2->add_constraint('guid', '=', $comment->objectguid);
            $qb2->add_constraint('topic', '=', $this->_config->get('blog_topic_id'));
            $_res2 = $qb2->execute();
            if (count($_res2))
            {
                $_comments[$comment->metadata->created]['type'] = 'blog';
                $_comments[$comment->metadata->created]['object'] = $comment;
                krsort($_comments);
            }
        }
        if (   isset($number)
            && $number != -1
            && count($_comments) > $number)
        {
            $_comments = array_splice($_comments, 0, $number);
        }
        return $_comments;
    }

    /**
     * Get latest 'n' comments that are posted to blogs
     * @param string GUID of the blog topic
     * @param integer the last 'n' number of comments to be fetched 
     */
    public function _get_last_dataset_comments($number = -1)
    {
        $_comments = array();
        $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();
        $_res = $qb->execute();

        foreach($_res as $dataset)
        {
            $qb2 = net_nehmer_comments_comment::new_query_builder();
            $qb2->add_constraint('status', '>=', 4);
            $qb2->add_constraint('objectguid', '=', $dataset->guid);
            $_res2 = $qb2->execute();
            if (count($_res2))
            {
                foreach($_res2 as $comment)
                {
                    $_comments[$comment->metadata->created]['type'] = 'dataset';
                    $_comments[$comment->metadata->created]['object'] = $comment;
                }
                krsort($_comments);
            }
        }
        if (   isset($number)
            && $number != -1
            && count($_comments) > $number)
        {
            $_comments = array_splice($_comments, 0, $number);
        }
        return $_comments;
    }

    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_read($handler_id, $args, &$data)
    {
        $_MIDCOM->skip_page_style = true;
        if (isset($args[0])
            && (int)$args[0])
        {
            $this->_num_of_comments = (int)$args[0];
        }
        else
        {
            $this->_num_of_comments = (int)$this->_config->get('number_of_comments_on_frontpage');
        }
        return true;
    }

   /**
     * Displays the datasets create form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_read($handler_id, &$data)
    {
        /* union of the two arrays */
        $_comments = $this->_get_last_dataset_comments() +  $this->_get_last_blog_comments();

        if (count($_comments))
        {
            krsort($_comments);
            if (   isset($this->_num_of_comments)
                && count($_comments) > $this->_num_of_comments)
            {
                $_comments = array_splice($_comments, 0, $this->_num_of_comments);
            }
            midcom_show_style('comment_list_header');

            foreach($_comments as $comment)
            {
                $this->_request_data['comment'] = $comment['object'];
                switch($comment['type'])
                {
                    case 'dataset':
                        $_object = new fi_opengov_datacatalog_dataset_dba($comment['object']->objectguid);
                        break;
                    case 'blog':
                        $_object = new midcom_db_article($comment['object']->objectguid);
                        break;
                }
                $this->_request_data['object'] = $_object;
                $this->_request_data['object_permalink'] = $_MIDCOM->permalinks->create_permalink($_object->guid);
                midcom_show_style('comment_list_item');
            }
            midcom_show_style('comment_list_footer');
        }
        unset($_comments);
    }
}
