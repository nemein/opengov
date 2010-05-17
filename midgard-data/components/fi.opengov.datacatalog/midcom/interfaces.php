<?php
/**
 * @package fi.opengov.datacatalog
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * This is the interface class for fi.opengov.datacatalog
 * 
 * @package fi.opengov.datacatalog
 */
class fi_opengov_datacatalog_interface extends midcom_baseclasses_components_interface
{
    /**
     * Constructor, define component name
     */
    function __construct()
    {
        parent::__construct();
        $this->_component = 'fi.opengov.datacatalog';
    }

    /**
     * Permalink handler
     *
     * @param string $guid The permalink GUID that should be looked up.
     * @param midcom_baseclasses_database_topic $topic the Topic to look up.
     * @param midcom_helper_configuration $config The configuration used for the given topic.
     * @return string The local URL (without leading slashes) or null on failure.
     */
    function _on_resolve_permalink($topic, $config, $guid)
    {
        $_url = null;
        $_dataset = new fi_opengov_datacatalog_dataset_dba($guid);
        if (isset($_dataset->id))
        {
            $_url = 'view/' . $_dataset->id;
        }
        unset($_dataset);
        return $_url;
    }
}
?>
