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
class fi_opengov_datacatalog_handler_dataset extends midcom_baseclasses_components_handler_crud
{
    /* the action that is set when a form is submitted */
    var $_action = null;

    /* all datasets */
    var $_datasets = array();

    /* a flag indicating whether a list or a detailed view is shown */
    var $_show_list = true;

    /* the filter (open, close) */
    var $_filter = '';
    
    /**
     * Simple default constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Loads some data
     */
    public function _load_object($handler_id, $args, &$data)
    {
        $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();
        switch($handler_id)
        {
            case 'view':
            case 'edit':
            case 'delete':
                if (isset($args[0]))
                {
                    $this->_show_list = false;
                    $qb->add_constraint('guid', '=', $args[0]);
                }
                break;
        }

        $this->_datasets = $qb->execute();

        switch($handler_id)
        {
            case 'open':
                $this->_filter_datasets('open');
                break;
            case 'closed':
                $this->_filter_datasets('closed');
                break;
        }

        if (count($this->_datasets))
        {
            if (   isset($args[0])
                && ($handler_id == 'view'
                || $handler_id == 'edit'
                || $handler_id == 'delete'))
            {
                /* set _object if we want to work on a particular dataset */
                $this->_object = $this->_datasets[0];
            }
        }
        else
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $arg = '';
            if (isset($args[0]))
            {
                $arg = $args[0];
            }

            $this->_populate_toolbar($handler_id);

            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to read dataset object (handler: ' . $handler_id . '/' . $arg . ')');
            //this will result in HTTP error 404
        }
    }

    /**
     * Load the schmadb used in forms
     */
    public function _load_schemadb()
    {
        $this->_request_data['schemadb_dataset'] = midcom_helper_datamanager2_schema::load_database($this->_config->get('schemadb_dataset'));
        $this->_schemadb =& $this->_request_data['schemadb_dataset'];
    }

    /**
     * Internal helper, loads the controller. Any error triggers a 500.
     * @param type of the controller (e.g. simple, create)
     * @access private
     */
    function _load_controller($type = null)
    {
        $this->_request_data['type'] = 'dataset';

        $this->_controller =& midcom_helper_datamanager2_controller::create($type);
        $this->_controller->schemadb =& $this->_schemadb;
        $this->_controller->callback_object =& $this;
        $this->_controller->schemaname = 'default';
        $this->_controller->defaults = $this->_defaults;

        $this->_datamanager =& $this->_controller->datamanager;

        if ($type == 'simple')
        {
            $this->_controller->set_storage($this->_object, 'default');
        }

        if (! $this->_controller->initialize())
        {
            $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to initialize a DM2 create controller.");
        }
    }


 
    /**
     * Loads default values for the creation controller.
     */
    public function _load_defaults()
    {
        // Allow setting defaults from query string, useful for things like "create event for today" and chooser
        if (   isset($_GET['defaults'])
            && is_array($_GET['defaults']))
        {
            foreach ($_GET['defaults'] as $key => $value)
            {
                if ( ! isset($this->_schemadb['default']->fields[$key])
                    && $key != 'suggestion')
                {
                    // No such field in schema
                    continue;
                }
                $this->_defaults[$key] = $value;
            }
        }
    }

    /**
     * Helper, updates the context so that we get a complete breadcrumb line towards the current
     * location.
     *
     */
    public function _update_breadcrumb($handler_id)
    {
        if ($handler_id != '')
        {
            $tmp[] = Array
            (
                MIDCOM_NAV_URL => "/",
                MIDCOM_NAV_NAME => sprintf($this->_i18n->get_string($handler_id . ' %s'), 'dataset'),
            );
        }
        else
        {
            $tmp[] = Array
            (
                MIDCOM_NAV_URL => "/",
                MIDCOM_NAV_NAME => $this->_i18n->get_string('dataset_list'),
            );
        }
        $_MIDCOM->set_custom_context_data('midcom.helper.nav.breadcrumb', $tmp);
    }

    /**
     * Sets the page title
     */
    public function _update_title($handler_id)
    {        
        $_MIDCOM->set_pagetitle($this->_i18n->get_string('view_datasets'));
    }

    /**
     * Add menu items to toolbar
     *
     * @param mixed $handler_id The ID of the handler.
     *
     */
    public function _populate_toolbar($handler_id)
    {
        parent::_populate_toolbar($handler_id);
        
        if ($this->_topic->can_do('midgard:admin'))
        {
            $this->_node_toolbar->add_item
            (
                array
                (
                    MIDCOM_TOOLBAR_URL => "create",
                    MIDCOM_TOOLBAR_LABEL => sprintf(    $this->_i18n->get_string('create %s'), 'dataset'),
                    MIDCOM_TOOLBAR_ICON => $this->_config->get('default_new_icon'),
                )
            );
            $this->_node_toolbar->add_item
            (
                array
                (
                    MIDCOM_TOOLBAR_URL => "suggestion/view/all",
                    MIDCOM_TOOLBAR_LABEL => sprintf($this->_i18n->get_string('view %s'), 'all suggestions'),
                    MIDCOM_TOOLBAR_ICON => $this->_config->get('default_list_icon'),
                )
            );

            if (isset($this->_object->license))
            {
                $license_guid = fi_opengov_datacatalog_info_dba::get_guid($this->_object->license);
                if ($license_guid)
                {
                    $this->_view_toolbar->add_item
                    (
                        array
                        (
                            MIDCOM_TOOLBAR_URL => "license/view/" . $license_guid,
                            MIDCOM_TOOLBAR_LABEL => sprintf($this->_i18n->get_string('view %s'), 'license'),
                            MIDCOM_TOOLBAR_ICON => $this->_config->get('default_list_icon'),
                        )
                    );
                }
            }
            
            if (isset($this->_object->id))
            {
                $formats = fi_opengov_datacatalog_dataset_dba::get_formats($this->_object->id);
                foreach ($formats as $format)
                {
                    $this->_view_toolbar->add_item
                    (
                        array
                        (
                            MIDCOM_TOOLBAR_URL => "format/view/" . $format->guid,
                            MIDCOM_TOOLBAR_LABEL => sprintf($this->_i18n->get_string('view %s'), 'format: ' . $format->title),
                            MIDCOM_TOOLBAR_ICON => $this->_config->get('default_list_icon'),
                        )
                    );
                }
            }
        }
    }
    
    /**
     * Object create callback
     *
     * @param mixed $handler_id The ID of the handler.
     */
    function &dm2_create_callback(&$controller)
    {
        $this->_object = new fi_opengov_datacatalog_dataset_dba();

        $this->_object->organization = array_pop($_POST['fi_opengov_datacatalog_organization_chooser_widget_selections']);
        $this->_object->license = array_pop($_POST['fi_opengov_datacatalog_license_chooser_widget_selections']);

        if (! $this->_object->create())
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_print_r('We operated on this object:', $this->_object);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new dataset, cannot continue. Last Midgard error was: '. midcom_application::get_error_string());
            // This will exit.
        }

        /* if the dataset is created based on a suggestion then delete the suggestion */
        if (isset($this->_defaults['suggestion']))
        {
            $suggestion = new fi_opengov_datacatalog_dataset_suggestion_dba($this->_defaults['suggestion']);
            $suggestion->delete();
        }

        return $this->_object;
    }


    /**
     * Filters the dataset based on criteria
     * @param strin criteria (currently: open, closed)
     */
    private function _filter_datasets($criteria)
    {
        $_type = '';
        $_filtered = array();

        switch ($criteria)
        {
            case 'open':
                $_type = 'free';
                break;
            case 'closed':
                $_type = 'non-free';
                break;
        }
        
        if ($_type != '')
        {
            $this->_filter = $criteria;
            
            if (count($this->_datasets))
            {
                $i = 0;
                foreach ($this->_datasets as $dataset)
                {
                    if (fi_opengov_datacatalog_dataset_dba::matching_license_type($dataset->guid, $_type))
                    {
                        $_filtered[] = $dataset;
                    }
                    ++$i;
                }
            }
        }
        $this->_datasets = $_filtered;

        unset($_type);
        unset($_filtered);
    }

    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_create($handler_id, $args, &$data)
    {
        $this->_mode = 'create';
        return parent::_handler_create($handler_id, $args, &$data);
    }

    /**
     * The handler for listing datasets
     *
     * @param mixed $handler_id the array key from the request array
     * @param array $args the arguments given to the handler
     * @param array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_read($handler_id, $args, &$data)
    {
        $_MIDCOM->enable_jquery();

        if ($handler_id == 'topic')
        {
            $this->_datasets = fi_opengov_datacatalog_dataset_dba::get_dataset_by_tags($args[0]);
            $this->_request_data['tags'] = $args[0];
        }
        else
        {
            $this->_load_object($handler_id, $args, &$data);
        }
        
        $this->_update_breadcrumb($handler_id);
        $this->_populate_toolbar($handler_id);

        return true;
    }

    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_update($handler_id, $args, &$data)
    {    
        $this->_mode = 'update';
        $this->_request_data['topic']->require_do('midgard:update');

        $this->_load_object($handler_id, $args, $data);
        $this->_load_schemadb();
        $this->_load_controller('simple');

        if ($this->_controller)
        {
            $this->_action = $this->_controller->process_form();
        }

        $this->_request_data['object'] =& $this->_object;
        $this->_request_data['controller'] = $this->_controller;

        return true;
    }

    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_delete($handler_id, $args, &$data)
    {
        $this->_request_data['topic']->require_do('midgard:delete');
        $this->_request_data['type'] = preg_replace('/_.*/', '', $handler_id);

        $this->_load_object($handler_id, $args, $data);
        $this->_load_schemadb();

        if (array_key_exists('crud_delete', $_POST))
        {
            if ($this->_object->delete())
            {
                $this->_action = 'delete';
            }
        }
        
        if (array_key_exists('crud_cancel', $_POST))
        {
            $this->_action = 'cancel';
        }
        
        return true;
    }
    
    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_tagcloud($handler_id, $args, &$data)
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
    public function _show_create($handler_id, &$data)
    {
        $this->_request_data['type'] = 'dataset';
        midcom_show_style('create');
    }          

   /**
     * Displays the datasets page
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_read($handler_id, &$data)
    {
        if (isset($this->_datasets))
        {
            $this->_request_data['handler_id'] = $handler_id;

            if ($this->_show_list)
            {
                $this->_request_data['filter'] = $this->_filter;
                midcom_show_style('dataset_list_intro');
                midcom_show_style('dataset_list_header');
            }
            $i = 0;

            foreach ($this->_datasets as $dataset) 
            {
                $this->_request_data['dataset'] = $dataset;
                $this->_request_data['permalink'] = $_MIDCOM->permalinks->create_permalink($dataset->guid);
                $this->_request_data['organization'] = fi_opengov_datacatalog_info_dba::get_details($dataset->organization, 'organization');
                $this->_request_data['license'] = fi_opengov_datacatalog_info_dba::get_details($dataset->license, 'license');
                $this->_request_data['formats'] = fi_opengov_datacatalog_dataset_dba::get_formats($dataset->id);

                /* show different page when viewing only 1 dataset */
                if ($handler_id == 'view' && ! $this->_show_list)
                {
                    /* fetch and populate tags */
                    $this->_request_data['tags'] = net_nemein_tag_handler::get_tags_by_guid($dataset->guid);
                    /* gather blog posts about this dataset */
                    $this->_request_data['blogposts'] = $this->_seek_blogposts();                    
                    /* load the comments if enabled */
                    if ($this->_config->get('allow_comments'))
                    {
                        $comments_node = $this->_seek_comments();
                        if ($comments_node)
                        {
                            $this->_request_data['comments_url'] = $comments_node[MIDCOM_NAV_RELATIVEURL] . "comment/{$dataset->guid}";
                        }
                    }
                    midcom_show_style('dataset_item_detailed_view');
                }
                else
                {
                    (++$i % 2) ? $this->_request_data['class'] = 'odd' : $this->_request_data['class'] = 'even';
                    midcom_show_style('dataset_item_view');
                }
            }
            
            if ($this->_show_list)
            {
                midcom_show_style('dataset_list_footer');
            }
        }
        else 
        {
            midcom_show_style('no_dataset');
        }
    }

   /**
     * Displays the datasets delete form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_update($handler_id, &$data)
    {
        if (   $this->_action == 'save'
            || $this->_action == 'cancel')
        {
            $_MIDCOM->cache->invalidate($this->_object->guid);
            $_MIDCOM->relocate('view/' . $this->_object->guid);
        }
        else
        {
            $this->_request_data['type'] = 'dataset';
            midcom_show_style('edit');
        }
    }            

   /**
     * Displays the datasets delete form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_delete($handler_id, &$data)
    {
        switch($this->_action)
        {
            case 'cancel':
                $_MIDCOM->relocate('view/' . $this->_object->guid);
                break;
            case 'delete':
                $_MIDCOM->relocate('');
                break;
            default:
                $this->_request_data['type'] = 'dataset';
                $this->_request_data['dataset'] = $this->_object;
                $this->_request_data['permalink'] = $_MIDCOM->permalinks->create_permalink($this->_object->guid);
                $this->_request_data['organization'] = fi_opengov_datacatalog_info_dba::get_details($this->_object->organization, 'organization');
                $this->_request_data['license'] = fi_opengov_datacatalog_info_dba::get_details($this->_object->license, 'license');
                $this->_request_data['formats'] = fi_opengov_datacatalog_dataset_dba::get_formats($this->_object->id);
                $this->_request_data['class'] = 'odd';
                midcom_show_style('delete');
        }
    }

   /**
     * Displays the dataset tag cloud
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_tagcloud($handler_id, &$data)
    {
        $_tags = fi_opengov_datacatalog_dataset_dba::get_all_tags();
        if (count($_tags))
        {
            midcom_show_style('dataset_tagcloud_header');
            foreach($_tags as $tag => $value)
            {
                $this->_request_data['tag'] = $tag;
                $this->_request_data['url'] = $_MIDCOM->get_context_data(MIDCOM_CONTEXT_ANCHORPREFIX). 'topic/' . $tag;
                midcom_show_style('dataset_tagcloud_item');
            }
            midcom_show_style('dataset_tagcloud_footer');
        }
        else
        {
            midcom_show_style('no_tags');
        }        
        unset($_tags);
    }

    /**
     * Try to find the comments node (cache results)
     * @return object node which has the dataset comments
     * @access private
     */
    function _seek_comments()
    {
        $comments_node = false;
        if ($this->_config->get('comments_topic_id'))
        {
            $nap = new midcom_helper_nav();
            $comments_node = $nap->get_node($this->_config->get('comments_topic_id'));
        }
        else
        {
            // No comments topic specified, autoprobe
            $comments_node = midcom_helper_find_node_by_component('net.nehmer.comments');
        }

        return $comments_node;
    }

    /**
     * Searches for related blog posts
     * @return array of blog articles
     * @access private
     */
    function _seek_blogposts()
    {                
        $blogposts = array();
        $qb = new midgard_query_builder('midgard_parameter');
        $qb->add_constraint('domain', '=', 'net.nehmer.blog');
        $qb->add_constraint('name', '=', 'dataset');
        $qb->add_constraint('value', '=', $this->_object->guid);        
        $params = $qb->execute();
        foreach ($params as $param)
        {
            $article = new midcom_db_article($param->parentguid);
            $blogposts[] = $article;
        }
        return $blogposts;
    }
}
