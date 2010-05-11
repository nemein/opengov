
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
    var $datasets = array();
    
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
        $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();
        $qb->add_constraint('id', '=', $args[0]);
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
        $this->_request_data['schemadb_dataset'] = midcom_helper_datamanager2_schema::load_database($this->_config->get('schemadb_dataset'));
        $this->_schemadb =& $this->_request_data['schemadb_dataset'];
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
        $this->_request_data['controller']->callback_object =& $this;

        if ($type == 'simple')
        {
            $this->_request_data['controller']->set_storage($this->_object, 'default');
        }

        if (! $this->_request_data['controller']->initialize())
        {
            $_MIDCOM->generate_error(MIDCOM_ERRCRIT, "Failed to initialize a DM2 create controller.");
        }
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
            MIDCOM_NAV_NAME => $this->_i18n->get_string('view_datasets'),
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
            if ($this->_object->can_do('midgard:create'))
            {
                $this->_view_toolbar->add_item
                (
                    array
                    (
                        MIDCOM_TOOLBAR_URL => "/data/create/",
                        MIDCOM_TOOLBAR_LABEL => $this->_l10n_midcom->get('create_dataset'),
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
        $this->_object = new fi_opengov_datacatalog_dataset_dba();
        $this->_object->title = $_POST['title'];
        $this->_object->description = $_POST['description'];
        $this->_object->url = $_POST['url'];
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

        $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();

        switch($handler_id)
        {
            case 'view':
                $qb->add_constraint('id', '=', $args[0]);
                break;
        }

        $this->datasets = $qb->execute();

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

        if ($this->_request_data['controller'])
        {
            $this->_action = $this->_request_data['controller']->process_form();
            if ($this->_action == 'save')
            {
//var_dump($_POST);
//die;
                $this->_object->license = array_pop($_POST['fi_opengov_datacatalog_license_chooser_widget_selections']);
                // do something fancy
            }        
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
        midcom_show_style('dataset_create');
    }          

   /**
     * Displays the datasets page
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_read($handler_id, &$data)
    {
        if (isset($this->datasets))
        {
            if ($handler_id != 'view')
            {
                midcom_show_style('dataset_list_header');
            }
            $i = 0;
            foreach ($this->datasets as $dataset) 
            {
                $this->_request_data['dataset'] = $dataset;

                /* fetch organization info */
                $this->_request_data['organization'] = fi_opengov_datacatalog_dataset_dba::get_details($dataset->organization, 'organization');
                
                /* fetch license info */
                $this->_request_data['license'] = fi_opengov_datacatalog_dataset_dba::get_details($dataset->license, 'license');
                
                /* fetch formats info */
                $this->_request_data['formats'] = fi_opengov_datacatalog_dataset_dba::get_formats($dataset->id);

                /* show different page when viewing only 1 dataset */
                if ($handler_id == 'view')
                {
                    midcom_show_style('dataset_item_detailed_view');
                }
                else
                {
                    (++$i % 2) ? $this->_request_data['class'] = 'odd' : $this->_request_data['class'] = 'even';
                    midcom_show_style('dataset_item_view');
                }
            }
            if ($handler_id != 'view')
            {
                midcom_show_style('dataset_list_footer');
            }
        }
        else 
        {
            midcom_show_style('no_datasets');
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
                $_MIDCOM->relocate('view/' . $this->_object->id);
                break;
            default:
                midcom_show_style('dataset_edit');
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
        midcom_show_style('dataset_delete');
    }
}
