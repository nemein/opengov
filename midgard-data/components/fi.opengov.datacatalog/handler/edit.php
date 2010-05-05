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
class eu_urho_accommodation_handler_service_edit extends eu_urho_accommodation_handler_service_main
{  
    var $_origin = null;

    /**
     * handlers that wish to have to embed the create form "inline" should set this to true
     */
    var $_inline_edit = false;
    
    /**
     * _on_initialize is called by midcom on creation of the handler.
     */
    function _on_initialize()
    {
        parent::_on_initialize();
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
    function _can_handle_edit($handler_id, $args, &$data)
    {
        debug_push_class(__CLASS__, __FUNCTION__);

        $data['service'] = new eu_urho_accommodation_service_dba($args[0]);

        if ($data['service'])
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
            $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to initialize a DM2 controller instance for article {$this->_article->id}.");
        }
        $this->_request_data['controller'] =& $this->_controller;
        $this->_object = $this->_request_data['service'];
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

        $data['origin'] = $this->_origin;
         
        $this->_load_controller();
      
        switch ($this->_controller->process_form())
        {
            case 'save':
                // Reindex the article
                //$indexer =& $_MIDCOM->get_service('indexer');
                //eu_urho_accommodation_viewer::index($this->_controller->datamanager, $indexer, $this->_topic);
                $data['saved'] = true;
                break;
            case 'cancel':
                $data['cancelled'] = true;
                if (! $this->_inline_edit)
                {
                    $_MIDCOM->relocate('accommodation/' . $this->session->get('accommodation_guid') . '/#services');
                }                
                break;
        }
        
        if ($this->_inline_edit)
        {
           $_MIDCOM->skip_page_style = true;
        }

        $this->_update_breadcrumb($handler_id);
        return true;
    }

    /**
     * @todo: docs
     */
    public function _show_edit($handler_id, &$data)
    {
        #$data['view_service'] = $this->_controller->datamanager->get_content_html();
        if ((isset($data['cancelled']) && $data['cancelled']) ||
            (isset($data['saved']) && $data['saved'])) 
        {
            midcom_show_style('service_admin_edit_after');
        } else {
            midcom_show_style('service_admin_edit');
        }
    }
}
?>
