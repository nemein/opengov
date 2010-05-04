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
        // Handle /data
        $this->_request_switch['data'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_dataset', 'read'),
        );
        // Handle /data/create
        $this->_request_switch['create'] = array
        (
            'handler' => array('fi_opengov_datacatalog_handler_dataset', 'create'),
            'fixed_args' => array('create'),
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
     * The handle callback populates the toolbars.
     */
    function _on_handle($handler, $args)
    {
        $this->_populate_node_toolbar();
        $this->_load_styles();
        return true;
    }

    /**
     * Loads styles used in basecamp
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
