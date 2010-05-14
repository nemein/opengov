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
class fi_opengov_datacatalog_handler_comment extends midcom_baseclasses_components_handler
{
    /* navigation object */
    var $_navi = null;

    /* the last n comments */
    var $_comments = array();
    
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
    public function _get_last_blog_comments($number)
    {
        $qb = net_nehmer_comments_comment::new_query_builder();
        $qb->add_constraint('status', '=', 6);
        $_res = $qb->execute();

        foreach($_res as $comment)
        {
            $qb2 = midcom_db_article::new_query_builder();
            $qb2->add_constraint('guid', '=', $comment->objectguid);
            $qb2->add_constraint('topic', '=', $this->_config->get('blog_topic_id'));
            $_res2 = $qb2->execute();
            if (count($_res2))
            {
                $this->_comments[$comment->metadata->created] = $comment;
                krsort(&$this->_comments);
            }
        }

        if (count($this->_comments) > $number)
        {
            $this->_comments = array_splice($this->_comments, 0, $number);
        }
    }

    /**
     * Get latest 'n' comments that are posted to blogs
     * @param string GUID of the blog topic
     * @param integer the last 'n' number of comments to be fetched 
     */
    public function _get_last_dataset_comments($number)
    {
        $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();
        $_res = $qb->execute();

        foreach($_res as $dataset)
        {
            $qb2 = net_nehmer_comments_comment::new_query_builder();
            $qb2->add_constraint('status', '=', 6);
            $qb2->add_constraint('objectguid', '=', $dataset->guid);
            $_res2 = $qb2->execute();
            if (count($_res2))
            {
                foreach($_res2 as $comment)
                {
                    $this->_comments[$comment->metadata->created] = $comment;
                }
                krsort(&$this->_comments);
            }
        }

        if (count($this->_comments) > $number)
        {
            $this->_comments = array_splice($this->_comments, 0, $number);
        }
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
        $this->_get_last_dataset_comments(10);

        if (count($this->_comments))
        {
            midcom_show_style('comment_list_header');
            foreach($this->_comments as $comment)
            {
                $this->_request_data['comment'] = $comment;
                midcom_show_style('comment_list_item');
            }
            midcom_show_style('comment_list_footer');
        }
    }         
}
