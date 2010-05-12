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
    /* the action that is set when a form is submitted */
    var $_action = null;

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
        $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
        $qb->add_constraint('id', '=', $args[0]);
        $qb->add_constraint('type', '=', $this->_request_data['type']);
        $_res = $qb->execute();
        
        if (count($_res))
        {
            $this->_object = $_res[0];
        }
        else
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to read info object (handler: ' . $handler_id . '/' . $args[0] . ')');
            //this will result in HTTP error 404
        }
    }

    /**
     * Load the schmadb used in forms
     */
    public function _load_schemadb()
    {
        $this->_request_data['schemadb'] = midcom_helper_datamanager2_schema::load_database($this->_config->get('schemadb_info'));
        $this->_schemadb =& $this->_request_data['schemadb'];

        if ( ! array_key_exists($this->_request_data['type'], $this->_schemadb) )
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to read info object (handler: ' . $handler_id . ')');
            //this will result in HTTP error 404
        }
    }

    /**
     * Internal helper, loads the controller. Any error triggers a 500.
     * @param handler_id
     * @access private
     */
    function _load_controller($handler_id)
    {
        if ($this->_mode == 'create')
        {
            $type = 'create';
        }
        else
        {
            $type = 'simple';
        }

        $this->_request_data['controller'] =& midcom_helper_datamanager2_controller::create($type);
        $this->_request_data['controller']->schemadb =& $this->_schemadb;
        $this->_request_data['controller']->schemaname = $this->_request_data['type'];
        $this->_request_data['controller']->callback_object =& $this;

        if ($type == 'simple')
        {
            $this->_request_data['controller']->set_storage($this->_object, $this->_request_data['type']);
        }

        if (! $this->_request_data['controller']->initialize())
        {
            $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to initialize a DM2 create controller.");
        }

        $this->_controller = $this->_request_data['controller'];
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
        $this->_object->type = $this->_request_data['type'];
        
        if (! $this->_object->create())
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_print_r('We operated on this object:', $this->_object);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new info [' . $this->_request_data['type'] .'], cannot continue. Last Midgard error was: '. midcom_application::get_error_string());
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
        $this->_mode = 'create';
        $this->_request_data['topic']->require_do('midgard:create');
        $this->_request_data['type'] = preg_replace('/_.*/', '', $handler_id);

        switch($handler_id)
        {
            case 'organization_create_chooser':
            case 'license_create_chooser':
            case 'format_create_chooser':
                $_MIDCOM->skip_page_style = true;
                break;
        }

        $this->_load_schemadb();

        $data['defaults'] = array();

        // Allow setting defaults from query string, useful for things like "create event for today" and chooser
        if (   isset($_GET['defaults'])
            && is_array($_GET['defaults']))
        {
            foreach ($_GET['defaults'] as $key => $value)
            {
                if (! isset($this->_schemadb[$this->_request_data['type']]->fields[$key]))
                {
                    // No such field in schema
                    continue;
                }

                $data['defaults'][$key] = $value;
            }
        }

        $this->_load_controller($handler_id);

        if ($this->_request_data['controller'])
        {
            $data['action'] = $this->_request_data['controller']->process_form();
        }
        
        return true;
    }

    /**
     * The handler for listing information
     *
     * @param mixed $handler_id the array key from the request array
     * @param array $args the arguments given to the handler
     * @param array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_read($handler_id, $args, &$data)
    {
        $this->_request_data['topic']->require_do('midgard:read');
        $this->_request_data['type'] = preg_replace('/_.*/', '', $handler_id);
        $this->_load_object($handler_id, $args, $data);
        $this->_load_schemadb();
        $this->_load_datamanager();
        $this->_datamanager->set_schema($this->_request_data['type']);
        $this->_load_controller($handler_id);
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
        $this->_request_data['topic']->require_do('midgard:update');
        $this->_request_data['type'] = preg_replace('/_.*/', '', $handler_id);

        switch($handler_id)
        {
            case 'organization_edit_chooser':
            case 'license_edit_chooser':
            case 'format_edit_chooser':
                $_MIDCOM->skip_page_style = true;
                break;
        }

        $this->_load_object($handler_id, $args, $data);
        $this->_load_schemadb();
//        $this->_load_datamanager();
//        $this->_datamanager->set_schema($this->_request_data['type']);
        $this->_load_controller($handler_id);

        if ($this->_request_data['controller'])
        {
            $this->_action = $this->_request_data['controller']->process_form();
        }

        $this->_request_data['object'] =& $this->_object;
              
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

        switch($handler_id)
        {
            case 'organization_delete_chooser':
            case 'license_delete_chooser':
            case 'format_delete_chooser':
                $_MIDCOM->skip_page_style = true;
                break;
        }

        $this->_load_object($handler_id, $args, $data);
        $this->_load_schemadb();
//        $this->_load_datamanager();
//        $this->_datamanager->set_schema($this->_request_data['type']);
              
        if (array_key_exists('fi_opengov_datacatalog_info_delete_ok', $_POST))
        {
            if ($this->_object->delete())
            {
                /* @todo: redirect somewhere */
                $_MIDCOM->relocate('');
            }
            $_MIDCOM->relocate('');            
            /* @todo: what happens at cancel? */
        }
        
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
        if ($this->_object)
        {
            switch ($this->_request_data['type'])
            {
                case 'organization':
                    $this->_request_data['organization_information'] = $this->_object->get_parameter('fi.opengov.datacatalog', 'org_information');
                    $this->_request_data['organization_address'] = $this->_object->get_parameter('fi.opengov.datacatalog', 'org_address');
                    $this->_request_data['organization_contact'] = $this->_object->get_parameter('fi.opengov.datacatalog', 'org_contact');
                    break;
                case 'license':
                    $this->_request_data['license_type'] = $this->_object->get_parameter('fi.opengov.datacatalog', 'license_type');
                    break;
                case 'format':
                    break;
            }
            $this->_request_data['class'] = 'odd';
            $this->_request_data['info'] = $this->_object;
            midcom_show_style('info_item_view');
        }
        else
        {
            midcom_show_style('no_info');
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
            $data['jsdata'] = $this->object_to_jsdata($this->_schemadb, $this->_object);
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
        switch ($this->_action)
        {
            case 'save': 
            case 'cancel':
                $_MIDCOM->relocate($this->_request_data['type'] . '/view/' . $this->_object->id);
                //midcom_show_style('info_edit_after');
                break;
            default:
                midcom_show_style('info_edit');
        }
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
    
        $hi_count = count($this->_schemadb[$this->_request_data['type']]->fields);
        $i = 1;

        foreach ($this->_schemadb[$this->_request_data['type']]->fields as $field => $field_data)
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
