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
class eu_urho_accommodation_handler_service_main extends midcom_baseclasses_components_handler_crud
{
    /**
     * @todo: docs
     */    
    var $_origin = null;
    var $_prefix = null;
    var $_controller = null;
    var $_session_domain = 'eu_urho_accommodation';

    /**
     * _on_initialize is called by midcom on creation of the handler.
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
        switch ($this->_origin) {
            case 'accommodation':
                $this->_prefix = '';
                break;            
            case 'room':
                $this->_prefix = '/' . $this->_origin;
                break;
            default:
                $this->_prefix = '';
        }
        $this->session = new midcom_service_session($this->_session_domain);
    }
    
     /**
     * Can-Handle check against the current service GUID. We have to do this explicitly
     * in can_handle already, otherwise we would hide all subtopics as the request switch
     * accepts all argument count matches unconditionally.
     *
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean True if the request can be handled, false otherwise.
     */
    function _can_handle_read($handler_id, $args, &$data)
    {
        debug_push_class(__CLASS__, __FUNCTION__);

        $this->_request_data['service'] = new eu_urho_accommodation_service_dba($args[0]);

        if ($this->_request_data['service'])
        {
            debug_pop();
            return true;
        }
        else
        {
            debug_add("Event {$args[0]} not found, ".midgard_connection::get_error_string());
            debug_pop();
            return false;
        }
    }

    /**
     * @todo: docs
     */
    public function _load_object($handler_id, $args, &$data)
    {
        $qb = eu_urho_accommodation_service_dba::new_query_builder();
        if (isset($args[0])) 
        {
            $qb->add_constraint('guid', '=', $args[0]);
        }
        $qb->set_limit(1);
        $objects = $qb->execute();

        if (is_array($objects) && count($objects) > 0)
        {
            $this->_object = new eu_urho_accommodation_service_dba($objects[0]->guid);
        }
        else
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND, 'Failed to load service, cannot continue.');
        }
        return $this->_object;
    }

    /**    
     * @access private
     */
    function _load_controller()
    {
        $this->_load_schemadb();
        $this->_controller =& midcom_helper_datamanager2_controller::create('simple');
        $this->_controller->schemadb =& $this->_request_data['schemadb_service'];
        $this->_controller->set_storage($this->_request_data['service']);
        if (! $this->_controller->initialize())
        {
            $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to initialize a DM2 controller instance for service {$this->_object->title}.");
        }
        $this->_request_data['controller'] =& $this->_controller;
    }
    
    /**
     * @todo: docs
     */
    public function _load_schemadb()
    {
        $this->_request_data['schemadb_service'] = midcom_helper_datamanager2_schema::load_database($this->_config->get('schemadb_service'));
        $this->_schemadb =& $this->_request_data['schemadb_service'];
    }

    /**
     * Internal helper, loads the datamanager for the current article. Any error triggers a 500.
     *
     * @access private
     */
    function _load_datamanager()
    {
        if ( ! $this->_schemadb)
        {
            $this->_load_schemadb();
        }
        $this->_datamanager = new midcom_helper_datamanager2_datamanager($this->_schemadb);
        if (   ! $this->_datamanager
            || ! $this->_datamanager->autoset_storage($this->_object))
        {
            $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to create a DM2 instance for service {$this->_request_data['service']->title}");
        }
        $this->_request_data['datamanager'] =& $this->_datamanager;
    }

    /** 
     * Loads the parent object of the service. 
     * This can either be an accommodation or a room at the moment.
     * @param guid of the service
     */
    public function _load_parent($guid)
    {
        if (empty($guid)) 
        {
            $guid = $this->session->get('accommodation_guid');
        }

        switch ($this->_origin) {
            case 'accommodation':
                $this->_parent = new eu_urho_accommodation_accommodation_dba($guid);
                break;            
            case 'room':
                $this->_parent = new eu_urho_accommodation_room_dba($guid);
                break;
        }
    }

    /**
     * @todo: docs
     */
    public function _load_member($service_guid)
    {
        switch ($this->_origin) {
            case 'accommodation':
                $qb = eu_urho_accommodation_accommodation_service_dba::new_query_builder();
                break;            
            case 'room':
                $qb = eu_urho_accommodation_room_service_dba::new_query_builder();
                break;
        }
        if (isset($service_guid)) 
        {
            $qb->add_constraint('service', '=', $service_guid);
        }
        $qb->set_limit(1);
        $objects = $qb->execute();

        if (is_array($objects) && count($objects) > 0)
        {
            $this->_member = $objects[0];
        }
        else
        {
            debug_push_class(__CLASS__, __FUNCTION__);
            debug_pop();
            $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND, 'Failed to load service parent, cannot continue.');
        }
    }

    /**
     * @todo: docs
     */
    public function _get_object_url()
    {
        $prefix = $_MIDCOM->get_context_data(MIDCOM_CONTEXT_ANCHORPREFIX);
        if ($this->_object->can_do('midgard:create')) 
        {
            switch ($this->_origin) {
                case 'accommodation':
                    $url = 'accommodation/' . $this->_parent->guid . '/#services';
                    break;            
                case 'room':
                    $url = 'accommodation/' . $this->_parent->guid . '/#rooms';
                    break;
            }            
        }
        else 
        {
            $url = 'accommodation' . $this->_prefix . '/service/' . $this->_object->guid . '/';
        }
        return $prefix . $url;
    }

    /**
     * @todo: docs
     */
    public function _update_title($handler_id)
    {
        if (isset($this->_object->title))
        {
            $title = $this->_l10n_midcom->get('service') . " " . $this->_object->title;
        }
        else
        {
            $title = $this->_l10n_midcom->get('service');
        }
        $_MIDCOM->set_pagetitle("{$title}");
        return;
    }


    /**
     * @todo: docs
     */
    public function _populate_toolbar($handler_id)
    {
        if (! $this->_object)
        {
            return;
        }
        
        if ($this->_object->can_do('midgard:update'))
        {
            $this->_view_toolbar->add_item
            (
                array
                (
                    MIDCOM_TOOLBAR_URL => 'accommodation' . $this->_prefix . "/service/edit/{$this->_object->guid}/",
                    MIDCOM_TOOLBAR_LABEL => $this->_l10n_midcom->get('Edit service'),
                    MIDCOM_TOOLBAR_ICON => 'stock-icons/16x16/edit.png',
                )
            );
        }
        if ($this->_object->can_do('midgard:delete'))
        {
            $this->_view_toolbar->add_item
            (
                array
                (
                    MIDCOM_TOOLBAR_URL => 'accommodation' . $this->_prefix . "/service/delete/{$this->_object->guid}/",
                    MIDCOM_TOOLBAR_LABEL => $this->_l10n_midcom->get('Delete service'),
                    MIDCOM_TOOLBAR_ICON => 'stock-icons/16x16/trash.png',
                )
            );
        }
    }
    
    /**
     * @todo: docs
     */
    public function _update_breadcrumb($handler_id)
    {
        $tmp = array();
        if (! $this->_object)
        {
            return;
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

        if ($this->_origin == "room") 
        {
            $tmp[] = Array
            (
                MIDCOM_NAV_URL => 'accommodation' . $this->_prefix . '/' . $this->session->get('room_guid'),
                MIDCOM_NAV_NAME => $this->session->get('room_title'),
            );
        }
        
        $tmp[] = Array
        (
            MIDCOM_NAV_URL => 'accommodation' . $this->_prefix . '/service/' . $this->_object->guid,
            MIDCOM_NAV_NAME => $this->_object->title,
        );

        $_MIDCOM->set_custom_context_data('midcom.helper.nav.breadcrumb', $tmp);
    }

    /**
     * @todo: docs
     */
    public function _show_read($handler_id, &$data)
    {
        $data['service_priceplans'] = eu_urho_accommodation_priceplan_dba::get_priceplans($this->_object->guid);
        $data['accommodation_guid'] = $this->session->get('accommodation_guid');
        $data['accommodation_title'] = $this->session->get('accommodation_title');
        $data['service'] = $this->_object;
        midcom_show_style('service_view_header');
        midcom_show_style('service_view_item');
        midcom_show_style('service_view_footer');
    }
}
?>
