
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
/*
    public function _load_object($handler_id, $args, &$data)
    {
        return $this->_object;
    }
*/

    /**
     * Load the schmadb used in forms
     */
    public function _load_schemadb()
    {
        $this->_request_data['schemadb_dataset'] = midcom_helper_datamanager2_schema::load_database($this->_config->get('schemadb_dataset'));
        $this->_schemadb =& $this->_request_data['schemadb_dataset'];
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
//        var_dump($_POST);
//        die;
        $this->_object->organization = $_POST['fi_opengov_datacatalog_organization_chooser_widget_selections'][1];
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
     * The handler for listing datasets
     *
     * @param mixed $handler_id the array key from the request array
     * @param array $args the arguments given to the handler
     * @param array &$data The local request data.
     * @return boolean Indicating success.
     */
    function _handler_read($handler_id, $args, &$data)
    {
        /* get all datasets */
        $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();
        $this->datasets = $qb->execute();

        $this->_update_breadcrumb($handler_id);
        $this->_populate_toolbar($handler_id);

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
        if (isset($this->datasets))
        {
            midcom_show_style('dataset_list_header');
            $i = 0;
            foreach ($this->datasets as $dataset) 
            {
                (++$i % 2) ? $this->_request_data['class'] = 'odd' : $this->_request_data['class'] = 'even';
                $this->_request_data['dataset'] = $dataset;

                /* fetch organization, license and format(s) info */
                $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
                $qb->add_constraint('id', '=', $dataset->organization);
                $qb->add_constraint('type', '=', 'organization');
                $_res = $qb->execute();
//var_dump($_res);
//die;

                if (count($_res) == 1)
                {
                    $this->_request_data['organization']['title'] = $_res[0]->title;
                    $this->_request_data['organization']['url'] = $_res[0]->url;
                }
                
                midcom_show_style('dataset_item_view');
            }
            midcom_show_style('dataset_list_footer');
        }
        else 
        {
            midcom_show_style('no_datasets');
        }
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
     * Displays the datasets delete form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_delete($handler_id, &$data)
    {
        midcom_show_style('dataset_delete');
    }

   /**
     * Displays the datasets delete form
     *
     * @param mixed $handler_id The ID of the handler.
     * @param mixed &$data The local request data.
     */
    public function _show_update($handler_id, &$data)
    {
        midcom_show_style('dataset_update');
    }            
}
