<?php
/**
 * @package fi.opengov.dataset
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * This is the class that defines which URLs should be handled by this module.
 *
 * @package fi.opengov.dataset
 */
class fi_opengov_datacatalog_viewer extends midcom_baseclasses_components_request
{
    function __construct($topic, $config)
    {
        parent::__construct($topic, $config);
    }

    /**
     * Initialize the request switch and the content topic.
     *
     * @access protected
     */
    function _on_initialize()
    {
        /**
         * Prepare the request switch, which contains URL handlers for the component
         */

        // Handle /config
        $this->_request_switch['config'] = array
        (
            'handler' => array('midcom_core_handler_configdm2', 'config'),
            'schemadb' => 'file:/fi/opengov/datacatalog/config/config_schemadb.inc',
            'schema' => 'config',
            'fixed_args' => array('config'),
        );
        // Handle /
        $this->_request_switch[''] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_dataset', 'read'),
        );
        // Handle /id
        $this->_request_switch['id'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_dataset', 'read'),
            'fixed_args' => array('id'),
            'variable_args' => 1,
        );
        // Handle /create
        $this->_request_switch['create'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_dataset', 'create'),
            'fixed_args' => array('create'),
        );
        // Handle /edit
        $this->_request_switch['edit'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_dataset', 'update'),
            'fixed_args' => array('edit'),
            'variable_args' => 1,
        );
        // Handle /delete
        $this->_request_switch['delete'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_dataset', 'delete'),
            'fixed_args' => array('delete'),
            'variable_args' => 1,
        );
        // Handle /organization/id
        $this->_request_switch['organization_id'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'read'),
            'fixed_args' => array('organization', 'id'),
            'variable_args' => 1,
        );
        // Handle /organization/create
        $this->_request_switch['organization_create'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'create'),
            'fixed_args' => array('organization', 'create'),
        );
        // Handle /organization/create/chooser
        $this->_request_switch['organization_create_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'create'),
            'fixed_args' => array('organization', 'create', 'chooser'),
        );
        // Handle /organization/edit
        $this->_request_switch['organization_edit'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'update'),
            'fixed_args' => array('organization', 'edit'),
            'variable_args' => 1,
        );
        // Handle /organization/edit/chooser
        $this->_request_switch['organization_edit_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'update'),
            'fixed_args' => array('organization', 'edit', 'chooser'),
            'variable_args' => 1,
        );
        // Handle /organization/delete
        $this->_request_switch['organization_delete'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'delete'),
            'fixed_args' => array('organization', 'delete'),
            'variable_args' => 1,
        );
        // Handle /organization/delete/chooser
        $this->_request_switch['organization_delete_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'delete'),
            'fixed_args' => array('organization', 'delete', 'chooser'),
            'variable_args' => 1,
        );
        // Handle /license/id
        $this->_request_switch['license_id'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'read'),
            'fixed_args' => array('license', 'id'),
            'variable_args' => 1,
        );
        // Handle /license/create
        $this->_request_switch['license_create'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'create'),
            'fixed_args' => array('license', 'create'),
        );
        // Handle /license/create/chooser
        $this->_request_switch['license_create_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'create'),
            'fixed_args' => array('license', 'create', 'chooser'),
        );
        // Handle /license/edit
        $this->_request_switch['license_edit'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'update'),
            'fixed_args' => array('license', 'edit'),
            'variable_args' => 1,
        );
        // Handle /license/edit/chooser
        $this->_request_switch['license_edit_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'update'),
            'fixed_args' => array('license', 'edit', 'chooser'),
            'variable_args' => 1,
        );
        // Handle /license/delete
        $this->_request_switch['license_delete'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'delete'),
            'fixed_args' => array('license', 'delete'),
            'variable_args' => 1,
        );
        // Handle /license/delete/chooser
        $this->_request_switch['license_delete_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'delete'),
            'fixed_args' => array('license', 'delete', 'chooser'),
            'variable_args' => 1,
        );
        // Handle /format/id
        $this->_request_switch['format_id'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'read'),
            'fixed_args' => array('format', 'id'),
            'variable_args' => 1,
        );
        // Handle /format/create
        $this->_request_switch['format_create'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'create'),
            'fixed_args' => array('format', 'create'),
        );
        // Handle /format/create/chooser
        $this->_request_switch['format_create_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'create'),
            'fixed_args' => array('format', 'create', 'chooser'),
        );
        // Handle /format/edit
        $this->_request_switch['format_edit'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'update'),
            'fixed_args' => array('format', 'edit'),
            'variable_args' => 1,
        );
        // Handle /format/edit/chooser
        $this->_request_switch['format_edit_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'update'),
            'fixed_args' => array('format', 'edit', 'chooser'),
            'variable_args' => 1,
        );
        // Handle /format/delete
        $this->_request_switch['format_delete'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'delete'),
            'fixed_args' => array('format', 'delete'),
            'variable_args' => 1,
        );
        // Handle /format/delete/chooser
        $this->_request_switch['format_delete_chooser'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_info', 'delete'),
            'fixed_args' => array('format', 'delete', 'chooser'),
            'variable_args' => 1,
        );
   }

    /**
     * Indexes an article.
     *
     * This function is usually called statically from various handlers.
     *
     * @param midcom_helper_datamanager2_datamanager &$dm The Datamanager encapsulating the event.
     * @param midcom_services_indexer &$indexer The indexer instance to use.
     * @param midcom_db_topic The topic which we are bound to. If this is not an object, the code
     *     tries to load a new topic instance from the database identified by this parameter.
     */
    function index(&$dm, &$indexer, $topic)
    {
        if (! is_object($topic))
        {
            $tmp = new midcom_db_topic($topic);
            if (   ! $tmp
                || ! $tmp->guid)
            {
                $_MIDCOM->generate_error(MIDCOM_ERRCRIT,
                    "Failed to load the topic referenced by {$topic} for indexing, this is fatal.");
                // This will exit.
            }
            $topic = $tmp;
        }

        // Don't index directly, that would loose a reference due to limitations
        // of the index() method. Needs fixes there.

        $nav = new midcom_helper_nav();
        $node = $nav->get_node($topic->id);
        $author = $_MIDCOM->auth->get_user($dm->storage->object->creator);

        $document = $indexer->new_document($dm);
        $document->topic_guid = $topic->guid;
        $document->component = $topic->component;
        $document->topic_url = $node[MIDCOM_NAV_FULLURL];
        $document->read_metadata_from_object($dm->storage->object);
        $indexer->index($document);
    }

    /**
     * Populates the node toolbar depending on the user's rights.
     *
     * @access protected
     */
    function _populate_node_toolbar()
    {
        if (   $this->_topic->can_do('midgard:update')
            && $this->_topic->can_do('midcom:component_config'))
        {
            $this->_node_toolbar->add_item
            (
                array
                (
                    MIDCOM_TOOLBAR_URL => 'config/',
                    MIDCOM_TOOLBAR_LABEL => $this->_l10n_midcom->get('component configuration'),
                    MIDCOM_TOOLBAR_HELPTEXT => $this->_l10n_midcom->get('component configuration helptext'),
                    MIDCOM_TOOLBAR_ICON => 'stock-icons/16x16/stock_folder-properties.png',
                )
            );
        }
        if ($this->_topic->can_do('midgard:create'))
        {
            $this->_node_toolbar->add_item
            (
                array
                (
                    MIDCOM_TOOLBAR_URL => "create/",
                    MIDCOM_TOOLBAR_LABEL => $this->_i18n->get_string('create_dataset'),
                    MIDCOM_TOOLBAR_ICON => $this->_config->get('default_new_icon'),
                )
            );
        }
    }

    /**
     * The handle callback populates the toolbars
     */
    function _on_handle($handler, $args)
    {
        $this->_populate_node_toolbar();
        $this->_load_styles();
        return true;
    }

    /**
     * Loads styles used in datacatalog
     *
     * @access protected
     */
    function _load_styles()
    {
        if ($this->_config->get('use_default_style'))
        {
            $_MIDCOM->add_link_head(array('rel' => 'stylesheet',  'type' => 'text/css', 'href' => MIDCOM_STATIC_URL . '/fi.opengov.datacatalog/datacatalog.css', 'media' => 'all'));
        }
    }
}
?>
