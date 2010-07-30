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
        $this->_component_id = 'fi_opengov_datacatalog';
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
        if (isset($_dataset->guid))
        {
            $_url = 'view/' . $_dataset->guid;
        }
        unset($_dataset);
        return $_url;
    }

    function _on_watched_dba_update($object)
    {
        // Note: the API key has to be defined in /etc/midgard/midcom.conf
        $apikey = $GLOBALS['midcom_config']['qaiku_apikey'];

        $_MIDCOM->load_library('org_openpsa_httplib');
        $message = array
        (
            'channel' => 'opendata',
            'source'  => 'opengov.fi',
            'lang'    => 'fi',
            'status'  => '',
            'external_url' => '',
        );

        if ($object->get_parameter('fi.opengov.datacatalog', 'qaiku_id'))
        {
            // This is already on Qaiku, skip
            return;
        }

        if ($object instanceof midcom_baseclasses_database_article)
        {
            // Check that the article is a visible one
            $topic = new midcom_db_topic($object->topic);
            if ($topic->component != 'net.nehmer.blog')
            {
                return;
            }
            $message['status'] = "[blog] {$object->title}";
        }
        elseif ($object instanceof fi_opengov_datacatalog_dataset_dba)
        {
            // Check that the dataset is a published one
            if (!fi_opengov_datacatalog_dataset_dba::matching_license_type($object->guid, 'free'))
            {
                // We don't publicize closed datasets
                return;
            }
            $message['status'] = "[dataset] {$object->title}";
        }
        else
        {
            return;
        }

        $message['external_url'] = $_MIDCOM->permalinks->resolve_permalink($object->guid);

        $http = new org_openpsa_httplib();
        $json = $http->post("http://www.qaiku.com/api/statuses/update.json?apikey={$apikey}", $message);
        $qaiku = json_decode($json);
        $object->set_parameter('fi.opengov.datacatalog', 'qaiku_id', $qaiku['id']);
    }
}
?>
