
<?php
/**
 * @package fi.opengov.datacatalog
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * Dataset suggestion handler
 *
 * @package fi.open.datacatalog
 */
class fi_opengov_datacatalog_handler_suggestion extends midcom_baseclasses_components_handler_crud
{
    /* the action that is set when a form is submitted */
    var $_action = null;

    /* all datasets */
    var $suggestions = array();
    
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
        $qb = fi_opengov_datacatalog_dataset_suggestion_dba::new_query_builder();
        $qb->add_constraint('guid', '=', $args[0]);
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
                'Failed to read dataset object (handler: ' . $handler_id . '/' . $args[0] . ')');
            //this will result in HTTP error 404
        }
    }

    /**
     * Load the schmadb used in forms
     */
    public function _load_schemadb()
    {
        $this->_request_data['schemadb_suggestion'] = midcom_helper_datamanager2_schema::load_database($this->_config->get('schemadb_suggestion'));

        if (!  $_MIDCOM->auth->user
            && $this->_config->get('use_captcha'))
        {
            $this->_request_data['schemadb_suggestion']['default']->append_field
            (
                'captcha',
                array
                (
                    'title' => $this->_l10n_midcom->get('captcha field title'),
                    'storage' => null,
                    'type' => 'captcha',
                    'widget' => 'captcha',
                    'widget_config' => $this->_config->get('captcha_config'),
                )
            );
        }

        $this->_schemadb =& $this->_request_data['schemadb_suggestion'];
    }

    /**
     * Internal helper, loads the controller. Any error triggers a 500.
     * @param type of the controller (e.g. simple, create)
     * @access private
     */
    function _load_controller($type)
    {
        $this->_request_data['controller'] =& midcom_helper_datamanager2_controller::create($type);
        $this->_request_data['controller']->schemadb =& $this->_schemadb;
        $this->_request_data['controller']->schema = 'default';
        $this->_request_data['controller']->callback_object =& $this;

        if ($type == 'simple')
        {
            $this->_request_data['controller']->set_storage($this->_object, 'default');
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
            MIDCOM_NAV_NAME => $this->_i18n->get_string($handler_id),
        );
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
     */
    public function _populate_toolbar($handler_id)
    {
        if (! $this->_object)
        {
            return;
        }
        else
        {
            if ($this->_object->can_do('midgard:admin'))
            {
                $this->_view_toolbar->add_item
                (
                    array
                    (
                        MIDCOM_TOOLBAR_URL => 'suggestion/edit/' . $this->_object->guid,
                        MIDCOM_TOOLBAR_LABEL => sprintf($this->_l10n_midcom->get('edit %s'), 'suggestion'),
                        MIDCOM_TOOLBAR_ICON => $this->_config->get('default_edit_icon'),
                    )
                );
                $this->_view_toolbar->add_item
                (
                    array
                    (
                        MIDCOM_TOOLBAR_URL => 'suggestion/delete/' . $this->_object->guid,
                        MIDCOM_TOOLBAR_LABEL => sprintf($this->_l10n_midcom->get('delete %s'), 'suggestion'),
                        MIDCOM_TOOLBAR_ICON => $this->_config->get('default_trash_icon'),
                    )
                );
            }
            if ($this->_object->can_do('midgard:create'))
            {
                $url = "create?";
                $defaults = array();
                $defaults['suggestion'] = $this->_object->guid;
                $defaults['title'] = $this->_object->title;
                $defaults['description'] = $this->_object->description;
                $defaults['organization'] = $this->_object->organization;
                $defaults['url'] = $this->_object->url;
                $defaults['tags'] = '';
                $tags = net_nemein_tag_handler::get_tags_by_guid($this->_object->guid);

                if (is_array($tags))
                {
                    $defaults['tags'] = implode(" ", array_keys($tags));
                }

                foreach($defaults as $key => $value)
                {
                    $url .= "defaults[$key]=$value&";
                }
                $url = substr($url, 0, -1);
                
                $this->_view_toolbar->add_item
                (
                    array
                    (
                        MIDCOM_TOOLBAR_URL => $url,
                        MIDCOM_TOOLBAR_LABEL => sprintf($this->_l10n_midcom->get('create %s'), 'dataset from suggestion'),
                        MIDCOM_TOOLBAR_ICON => $this->_config->get('default_new_icon'),
                    )
                );
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
        $this->_object = new fi_opengov_datacatalog_dataset_suggestion_dba();

        if ($_MIDCOM->auth->request_sudo('fi.opengov.datacatalog'))
        {
            if (! $this->_object->create())
            {
                debug_push_class(__CLASS__, __FUNCTION__);
                debug_print_r('We operated on this object:', $this->_object);
                debug_pop();
                $_MIDCOM->generate_error(MIDCOM_ERRNOTFOUND,
                    'Failed to create a new dataset suggestion, cannot continue. Last Midgard error was: '. midcom_application::get_error_string());
                // This will exit.
            }
        }

        $_MIDCOM->auth->drop_sudo();

        return $this->_object;
    }

    /**
     * @param mixed $handler_id The ID of the handler.
     * @param Array $args The argument list.
     * @param Array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_create($handler_id, $args, &$data)
    {
        if (   ($_MIDCOM->auth->user
            || $this->_config->get('allow_anonymous'))
            && $_MIDCOM->auth->request_sudo('fi.opengov.datacatalog'))
        {
            parent::_handler_create($handler_id, $args, &$data);
            $_MIDCOM->auth->drop_sudo();
            return true;
        }
        else
        {
            return parent::_handler_create($handler_id, $args, &$data);
        }            
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
        $this->_request_data['topic']->require_do('midgard:admin');

        $_MIDCOM->enable_jquery();

        $qb = fi_opengov_datacatalog_dataset_suggestion_dba::new_query_builder();

        switch($handler_id)
        {
            case 'suggestion_view':
                if (isset($args[0]))
                {
                    if ($args[0] != 'all')
                    {
                        $qb->add_constraint('guid', '=', $args[0]);
                    }
                }
                break;
        }

        $this->suggestions = $qb->execute();
    
        if (count($this->suggestions) == 1)
        {
            $this->_object = $this->suggestions[0];
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
        $this->_request_data['topic']->require_do('midgard:update');
        $this->_mode = 'update';

        $this->_load_object($handler_id, $args, $data);
        $this->_load_schemadb();
        $this->_load_controller('simple');

        if ($this->_request_data['controller'])
        {
            $this->_action = $this->_request_data['controller']->process_form();
        }

        $this->_update_breadcrumb($handler_id);
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
//        $this->_action = 'delete';
        return parent::_handler_delete($handler_id, $args, &$data);
    }
    
   /**
     * Displays the datasets create form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_create($handler_id, &$data)
    {
        midcom_show_style('dataset_suggestion_create');
    }          

   /**
     * Displays the datasets page
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_read($handler_id, &$data)
    {
        if (isset($this->suggestions))
        {
            if ($handler_id != 'view')
            {
                midcom_show_style('dataset_suggestion_list_header');
            }
            $i = 0;
            foreach ($this->suggestions as $suggestion) 
            {
                $this->_request_data['suggestion'] = $suggestion;

                /* show different page when viewing only 1 dataset */
                if ($handler_id == 'view')
                {
                    midcom_show_style('dataset_suggestion_item_detailed_view');
                }
                else
                {
                    (++$i % 2) ? $this->_request_data['class'] = 'odd' : $this->_request_data['class'] = 'even';
                    midcom_show_style('dataset_suggestion_item_view');
                }
            }
            if ($handler_id != 'view')
            {
                midcom_show_style('dataset_suggestion_list_footer');
            }
        }
        else 
        {
            midcom_show_style('no_dataset_suggestion');
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
        switch ($this->_action)
        {
            case 'save':
            case 'cancel':
                $_MIDCOM->relocate('suggestion/view/' . $this->_object->guid);
                break;
            default:
                midcom_show_style('dataset_suggestion_edit');
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
                $_MIDCOM->relocate('suggestion/view/' . $this->_object->guid);
                break;
            case 'delete':
                $_MIDCOM->relocate('');
                break;
            default:
                $this->_request_data['suggestion'] = $this->_object;
                midcom_show_style('dataset_suggestion_delete');
        }
    }
}
