<?php
/**
 * @package eu.urho.accommodation
 * @author Ferenc Szekely <ferenc.szekely@urho.eu>
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */


/**
 * This is a URL handler class for eu.urho.accommodation
 *
 * The midcom_baseclasses_components_handler class defines a bunch of helper vars
 *
 * @see midcom_baseclasses_components_handler
 * @see: http://www.midgard-project.org/api-docs/midcom/dev/midcom.baseclasses/midcom_baseclasses_components_handler/
 * 
 * @service eu.urho.accommodation
 */
class eu_urho_accommodation_handler_service_create extends eu_urho_accommodation_handler_service_main
{
    /**
     * handlers that wish to have to embed the create form "inline" should set this to true
     */
    var $_inline_edit = false;
    
    /**
     * originator (accommodation or room) where the srevice should belong to
     */
    var $_origin = null;
        
    /**
     * url prefix depending on the _origin (see below)
     */
    var $_prefix = null;

    /**
     * Simple default constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * _on_initialize is called by midcom on creation of the handler.
     */
    function _on_initialize()
    {
        parent::_on_initialize();
    }
    

    /**
     * loads the parent object where the service will belong to
     */
    
     /**
     * Can-Handle check against the current event GUID. We have to do this explicitly
     * in can_handle already, otherwise we would hide all subtopics as the request switch
     * accepts all argument count matches unconditionally.
     *
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean True if the request can be handled, false otherwise.
     */
    function _can_handle_create($handler_id, $args, &$data)
    {
        debug_push_class(__CLASS__, __FUNCTION__);

        $guid = '';
        if (isset($args[0]))
        {
            $guid = $args[0];
        }
        $this->_load_parent($guid);

        $this->_request_data['service'] = new eu_urho_accommodation_service_dba();

        if ($this->_request_data['service'])
        {
            debug_pop();
            return true;
        }
        else
        {
            debug_add("Event {$args[0]} not found, " . midgard_connection::get_error_string());
            debug_pop();
            return false;
        }
    }

    /**
     * Internal helper, loads the controller for the current article. Any error triggers a 500.
     *
     * @access private
     */
    function _load_controller()
    {        
        $this->_controller =& midcom_helper_datamanager2_controller::create('create');
        $this->_controller->schemadb =& $this->_request_data['schemadb_service'];
        $this->_controller->schemaname = $this->_request_data['schema'];
        $this->_controller->defaults = $this->_request_data['defaults'];
        $this->_controller->callback_object =& $this;
        if (! $this->_controller->initialize())
        {
            $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to initialize a DM2 create controller.");
        }
    }
        
    /**
     * @todo: docs
     */
    function &dm2_create_callback(&$controller)
    {
        if (! $this->session->get('accommodation_guid')) 
        {
            $_MIDCOM->relocate('/midcom-login-');
        }

        if (! $this->_origin)         
        {
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new service: origin of the service is not specified.');
        }

        $this->_object = new eu_urho_accommodation_service_dba();

        if (! $this->_object->create())
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_print_r('We operated on this object:', $this->_object);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new service: ' . midcom_application::get_error_string());
        }

        // create the member entry depending on the originator
        switch ($this->_origin) 
        {
            case 'accommodation':
                $member = new eu_urho_accommodation_accommodation_service_dba();
                $member->accommodation = $this->session->get('accommodation_guid');
                $member->service = $this->_object->guid;
                break;
            case 'room':
                $member = new eu_urho_accommodation_room_service_dba();
                $member->room = $this->_parent->guid;
                $member->service = $this->_object->guid;
                break;
            default: 
                //bail out
                $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                    'Failed to create a new service: origin of the service is not specified.');
        }

        if (! $member->create())
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_print_r('We operated on this object:', $member);
            debug_pop();
            // exit
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                'Failed to create a new ' . $this->_origin . ' service member: ' . midcom_application::get_error_string());
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
        $data['content_topic']->require_do('midgard:create');
        $data['saved'] = false;
        $data['cancelled'] = false;
        $data['service'] = null;
        $data['inline_edit'] = $this->_inline_edit;
        $data['parent_guid'] = $this->_parent->guid;
        
        switch ($this->_origin) 
        {
            case 'accommodation':
                $data['parent_title'] = $this->_parent->title;
                break;
            case 'room':
                $data['parent_title'] = $this->_parent->title;
                break;
        }                      

        if ( ! $this->session->get('accommodation_guid')) {      
            $_MIDCOM->relocate('/midcom-login-');
        }

        $this->_load_schemadb();

        $data['schema'] = "default";

        if ( ! array_key_exists($data['schema'], $this->_schemadb) )
        {
            return false;
            //this will result in HTTP error 500
        }

        $data['defaults'] = Array();

        // Allow setting defaults from query string, useful for things like "create event for today" and chooser
        if (isset($_GET['defaults'])
            && is_array($_GET['defaults']))
        {
            foreach ($_GET['defaults'] as $key => $value)
            {
                if (!isset($this->_schemadb[$data['schema']]->fields[$key]))
                {
                    // No such field in schema
                    continue;
                }

                $data['defaults'][$key] = $value;
            }
        }

        $this->_load_controller();
        
        switch ($this->_controller->process_form())
        {
            case 'save':
                $data['saved'] = true;
                break;
            case 'cancel':
                $data['cancelled'] = true;
                if ($handler_id != $this->_chooser_handler_id && ! $this->_inline_edit)
                {
                    $_MIDCOM->relocate('accommodation/' . $this->session->get('accommodation_guid') . '/');
                }
                break;
        }

        $title = $this->_l10n_midcom->get('create service');
        $_MIDCOM->set_pagetitle("{$this->_topic->extra}: {$title}");

        if ($handler_id == $this->_chooser_handler_id || $this->_inline_edit)
        {
           $_MIDCOM->skip_page_style = true;
        }

        $tmp[] = Array
        (
            MIDCOM_NAV_URL => "/",
            MIDCOM_NAV_NAME => $this->_l10n_midcom->get('Hotels'),
        );

        $tmp[] = Array
        (
            MIDCOM_NAV_URL => "accommodation/" . $this->session->get('accommodation_guid'),
            MIDCOM_NAV_NAME => $this->session->get('accommodation_title'),
        );     
        
        $tmp[] = Array
        (
            MIDCOM_NAV_URL => "",
            MIDCOM_NAV_NAME => sprintf($data['l10n']->get('service create %s'), $this->session->get('accommodation_title')),
        );
        $_MIDCOM->set_custom_context_data('midcom.helper.nav.breadcrumb', $tmp);

        return true;
    }    

    /**
     * @todo: docs
     */
    public function _show_create($handler_id, &$data)
    {
        $data['origin'] = $this->_origin;
        $data['controller'] =& $this->_controller;
        if ($handler_id == $this->_chooser_handler_id || $data['inline_edit'])
        {
            if ($this->_object)
            {
                $data['jsdata'] = $this->object_to_jsdata('schemadb_service', $this->_object);
                midcom_show_style('service_admin_create_after');
            } else
            {  
                if (isset($data['cancelled']) && $data['cancelled']) 
                {
                    midcom_show_style('service_admin_create_after');
                } 
                else 
                {
                    midcom_show_style('popup_header');
                    midcom_show_style('service_admin_create');
                    midcom_show_style('popup_footer');
                }
            }
            return;
        }
        midcom_show_style('service_admin_create');
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
?>
