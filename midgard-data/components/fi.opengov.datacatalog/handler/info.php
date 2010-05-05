<?php
/**
 * @package fi.opengov.datacatalog
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * Datacatalog info handler
 *
 * @package fi.open.datacatalog
 */
class fi_opengov_datacatalog_handler_info extends midcom_baseclasses_components_handler_crud
{
    /* the schema db that contain all schemas */
    var $_schemadb = null;

    /**
     * Simple default constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * _on_initialize is called by midcom on creation of the handler
     */
    function _on_initialize()
    {
        /* @todo: what to do here */
    }

    /**
     * Loads some data
     */
    public function _load_object($handler_id, $args, &$data)
    {
        switch ($handler_id)
        {
            case 'organization':
            case 'license':
            case 'format':
                $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
                $qb->add_constraint('id', '=', $args[0]);
                $qb->add_constraint('type', '=', $handler_id);
                $_res = $qb->execute();
                if (count($_res))
                {
                    $this->_object = $_res[0];
                }
                break;
        }
        if (! $this->_object)
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to read info object ('. $handler_id . '/' . $args[0] . ')');
            //this will result in HTTP error 500
        }
    }

    /**
     * Load the schmadb used in forms
     */
    public function _load_schemadb()
    {
        $this->_request_data['schemadb'] = midcom_helper_datamanager2_schema::load_database($this->_config->get('schemadb_info'));
        $this->_schemadb =& $this->_request_data['schemadb'];
    }

    /**
     * Helper, updates the context so that we get a complete breadcrumb line towards the current
     * location.
     *
     */
    public function _update_breadcrumb($handler_id)
    {
        $tmp[] = Array
        (
            MIDCOM_NAV_URL => "/",
            MIDCOM_NAV_NAME => $this->_i18n->get_string('view_info'),
        );
        $_MIDCOM->set_custom_context_data('midcom.helper.nav.breadcrumb', $tmp);
    }

    /**
     * Sets the page title
     */
    public function _update_title($handler_id)
    {        
        $_MIDCOM->set_pagetitle($this->_i18n->get_string('view_info'));
    }

    /**
     * Add menu items to toolbar
     *
     * @param mixed $handler_id The ID of the handler.
     */
    public function _populate_toolbar($handler_id)
    {
        if (! $this->_object)
        {
            return;
        }
    }

    /**
     * Object create callback
     *
     * @param mixed $handler_id The ID of the handler.
     */
    function &dm2_create_callback(&$controller)
    {
        $this->_object = new fi_opengov_datacatalog_info_dba();
        if (! $this->_object->create())
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_print_r('We operated on this object:', $this->_object);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new info, cannot continue. Last Midgard error was: '. midcom_application::get_error_string());
            // This will exit.
        }

        return $this->_object;
    }

    /**
     * Displays an accommodation service creation view.
     *
     * The form can be manipulated using query strings like the following:
     *
     * ?defaults[title]=Kaljakellunta&defaults[start]=20070911T123001&defaults[categories]=|foo|
     *
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_create($handler_id, $args, &$data)
    {
        $this->_request_data['topic']->require_do('midgard:create');

echo "handler_id: " . $handler_id;
var_dump($args);
die;
        $type = '';
        switch($handler_id)
        {
            case 'organization_create':
                $type = 'organization';
                break;
            case 'license_create':
                $type = 'license';
                break;
            case 'format_create':
                $type = 'format';
                break;
        }

        if ($type != '')
        {
            $this->_load_controller('create');
            $data['schema'] = $type;
        }
        else
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new info object. No type specified: (handler: ' . $handler_id . ')');
            //this will result in HTTP error 500
        }

        if ( ! array_key_exists($type, $this->_schemadb) )
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new info object (handler: ' . $handler_id . ', type: ' . $type . ')');            
            //this will result in HTTP error 500
        }

        $data['defaults'] = Array();

        // Allow setting defaults from query string, useful for things like "create event for today" and chooser
        if (   isset($_GET['defaults'])
            && is_array($_GET['defaults']))
        {
            foreach ($_GET['defaults'] as $key => $value)
            {
                if (! isset($this->_schemadb[$type]->fields[$key]))
                {
                    // No such field in schema
                    continue;
                }

                $data['defaults'][$key] = $value;
            }
        }

        switch ($this->_controller->process_form())
        {
            case 'save':
                break;
            case 'cancel':
                break;
        }

        $_MIDCOM->skip_page_style = true;

        return true;
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
        $this->_request_data['topic']->require_do('midgard:read');

        $this->_load_object($handler_id, $args, $data);

        if (! $this->_object)
        {
            $this->_no_data($handler_id);
        }

        $this->_load_schemadb();


        //$this->_load_controller();

        if ( ! array_key_exists($handler_id, $this->_schemadb) )
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to read info object (handler: ' . $handler_id . ')');
            //this will result in HTTP error 500
        }

        $this->_load_datamanager();

        $this->_datamanager->set_schema($handler_id);

        $this->_prepare_request_data();
        
        if ($this->_controller)
        {
            // For AJAX handling it is the controller that renders everything
            $this->_request_data['object_view'] = $this->_controller->get_content_html();
        }
        else
        {
            $this->_request_data['object_view'] = $this->_datamanager->get_content_html();
        }

        $this->_update_breadcrumb($handler_id);
        $this->_populate_toolbar($handler_id);

        $_MIDCOM->skip_page_style = true;

        return true;
    }

    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_edit($handler_id, $args, &$data)
    {
        $this->_request_data['topic']->require_do('midgard:update');

        $this->_load_object($handler_id, $args, $data);

        if (! $this->_object)
        {
            $this->_no_data($handler_id);
        }

        $this->_load_schemadb();

        if ( ! array_key_exists($handler_id, $this->_schemadb) )
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to update info object (handler: ' . $handler_id . ')');            
            //this will result in HTTP error 500
        }

        /* @todo: do something */
        $this->_load_datamanager();

        $this->_datamanager->set_schema($handler_id);

        $this->_prepare_request_data();
        
        if ($this->_controller)
        {
            // For AJAX handling it is the controller that renders everything
            $this->_request_data['object_view'] = $this->_controller->get_content_html();
        }
        else
        {
            $this->_request_data['object_view'] = $this->_datamanager->get_content_html();
        }
      
        switch ($this->_controller->process_form())
        {
            case 'save':
                break;
            case 'cancel':
                break;
        }
        
        $_MIDCOM->skip_page_style = true;

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

echo "handler_id: " . $handler_id;
var_dump($args);
die;

        $this->_load_object($handler_id, $args, $data);

        if (! $this->_object)
        {
            $this->_no_data($handler_id);
        }

        $this->_load_schemadb();

        if ( ! array_key_exists($type, $this->_schemadb) )
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to delete info object (handler: ' . $handler_id . ', type: ' . $type . ')');            
            //this will result in HTTP error 500
        }

        $this->_load_datamanager();

        $this->_datamanager->set_schema($handler_id);

        $this->_prepare_request_data();
        
        if ($this->_controller)
        {
            // For AJAX handling it is the controller that renders everything
            $this->_request_data['object_view'] = $this->_controller->get_content_html();
        }
        else
        {
            $this->_request_data['object_view'] = $this->_datamanager->get_content_html();
        }
      
        if (array_key_exists('fi_opengov_datacatalog_info_delete_ok', $_POST))
        {
            if ($this->_object->delete())
            {
                /* @todo */
            }
        }
        
        $_MIDCOM->skip_page_style = true;
        
        return true;
    }

   /**
     * Displays the datasets page
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_read($handler_id, &$data)
    {        
        $this->_request_data['type'] = $handler_id;
        $this->_request_data['class'] = 'odd';
        
        switch ($handler_id)
        {
            case 'organization':
            case 'license':
            case 'format':
                if ($this->_object)
                {
                    $this->_request_data['info'] = $this->_object;
                    midcom_show_style('info_item_view');
                }
                else
                {
                    midcom_show_style('no_info');
                }
                break;
/*
            case 'format':                
                $formats = array();
                if (count($formats))
                {
                    midcom_show_style('info_list_header');
                    foreach ($formats as $format)
                    {
                        $i = 0;
                        (++$i % 2) ? $this->_request_data['class'] = 'odd' : $this->_request_data['class'] = 'even';
                        midcom_show_style('info_item_view');
                    }
                    midcom_show_style('info_list_footer');
                }
                else
                {
                    midcom_show_style('no_info');
                }
                break;
*/
        }
    }

   /**
     * Displays the info create form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_create($handler_id, &$data)
    {
        if ($this->_object)
        {
            $data['jsdata'] = $this->object_to_jsdata($this->schemadb, $this->_object);
            midcom_show_style('info_create_after');
        } else
        {  
            midcom_show_style('info_create');
        }
    }          

   /**
     * Displays the info delete form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_delete($handler_id, &$data)
    {
        midcom_show_style('info_delete');
    }

   /**
     * Displays the datasets delete form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_update($handler_id, &$data)
    {
        midcom_show_style('info_update');
    }                

   /**
     * Displays the no data page
     *
     * @param mixed $handler_id The ID of the handler.
     */
    public function _no_data($handler_id)
    {
        $this->_request_data['type'] = $handler_id;
        midcom_show_style('no_info');
    }
    
    /**
     * @todo: docs
     */
    function object_to_jsdata($schemadb, &$object)
    {
        $id = @$object->id;
        $guid = @$object->guid;
    
        $jsdata = "{";
    
        $jsdata .= "id: '{$id}',";
        $jsdata .= "guid: '{$guid}',";
        $jsdata .= "pre_selected: true,";
    
        $hi_count = count($this->_request_data[$schemadb][$this->_request_data['schema']]->fields);
        $i = 1;
        foreach ($this->_request_data[$schemadb][$this->_request_data['schema']]->fields as $field => $field_data)
        {
            $value = @$object->$field;
            $value = rawurlencode($value);
            $jsdata .= "{$field}: '{$value}'";
    
            if ($i < $hi_count)
            {
                $jsdata .= ", ";
            }
    
            $i++;
        }
    
        $jsdata .= "}";
    
        return $jsdata;
    }
}
