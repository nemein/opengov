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
class eu_urho_accommodation_handler_service_delete extends eu_urho_accommodation_handler_service_main
{
    /**
     * handlers that wish to have to embed the create form "inline" should set this to true
     */
    var $_inline_edit = false;

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
    function _can_handle_delete($handler_id, $args, &$data)
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
            debug_add("Service {$args[0]} not found, " . midgard_connection::get_error_string());
            debug_pop();
            return false;
        }
    }
    
    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_delete($handler_id, $args, &$data)
    {
        $data['origin'] = $this->_origin;
        switch ($this->_origin)
        {
            case 'accommodation':
                $qb = eu_urho_accommodation_accommodation_service_dba::new_query_builder();
                $url = 'accommodation/' . $this->session->get('accommodation_guid') . '/#services';
                break;
            case 'room':
                $qb = eu_urho_accommodation_room_service_dba::new_query_builder();
                $url = 'accommodation/' . $this->session->get('accommodation_guid') . '/#rooms';
                break;
        }

        $qb->add_constraint('service', '=', $args[0]);
        $qb->set_limit(1);
        $service_members = $qb->execute();

        if (! count($service_members))
        {
            return false;
        }
        $_object = $service_members[0];
        $_object->require_do('midgard:delete');              

        $data['service'] = new eu_urho_accommodation_service_dba($args[0]);

        if (array_key_exists('eu_urho_accommodation_service_delete_ok', $_POST))
        {
            $servicetitle = $data['service']->title;
            if ($_object->delete())
            {
                $data['deleted'] = true;
                $_MIDCOM->uimessages->add($this->_request_data['l10n']->get('eu.urho.accommodation'), sprintf($this->_request_data['l10n']->get('accommodation service %s deleted'), $servicetitle), 'ok');

                // Update the index
                $indexer =& $_MIDCOM->get_service('indexer');
                $indexer->delete($_object->guid);
                if (! $this->_inline_edit)
                {
                    $_MIDCOM->relocate($url);
                }
            }
            else
            {
                $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to delete service, reason " . midcom_application::get_error_string());
            }
        }
        elseif (array_key_exists('eu_urho_accommodation_service_delete_cancel', $_POST))
        {
            $data['cancelled'] = true;
            if (! $this->_inline_edit)
            {
                $_MIDCOM->relocate($url);
            }
        }

        if ($this->_inline_edit)
        {
           $_MIDCOM->skip_page_style = true;
        }

        return true;
    }

    /**
     * @todo: docs
     */
    public function _show_delete($handler_id, &$data)
    {
        if ((isset($data['cancelled']) && $data['cancelled']) ||
            (isset($data['deleted']) && $data['deleted'])) 
        {
            midcom_show_style('service_admin_delete_after');
        } else {        
            midcom_show_style('service_admin_delete');
        }
    }
}
?>
