<?php
/**
 * @package fi.opengov.datacatalog
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * fi.opengov.datacatalog dataset info dba
 *
 * @todo: docs
 *
 * @package fi.opengov.datacatalog
 */
class fi_opengov_datacatalog_info_dba extends __fi_opengov_datacatalog_info_dba
{
    /**
     * Finds and returns organization and license details
     * @param integer id of the info
     * @param string type can be organization, license, format
     * @return array storing the title and URL of the organization or the license
     */
    public function get_details($id = null, $type = '')
    {
        $details = array();
        if (   $id
            && $type != '')
        {
            $organization = array();
            $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
            $qb->add_constraint('id', '=', $id);
            $qb->add_constraint('type', '=', $type);
            $_res = $qb->execute();

            /* make sure we return only 1 organization and license */
            $details['title'] = $_res[0]->title;
            $details['url'] = $_res[0]->url;

            switch($type)
            {
                case 'organization':
                    $details['information'] = $_res[0]->get_parameter('fi.opengov.datacatalog', 'org_information');
                    $details['address'] = $_res[0]->get_parameter('fi.opengov.datacatalog', 'org_address');
                    $details['contact'] = $_res[0]->get_parameter('fi.opengov.datacatalog', 'org_contact');
                    break;
                case 'license':
                    $details['type'] = $_res[0]->get_parameter('fi.opengov.datacatalog', 'license_type');
                    break;
            }

            unset($_res);
            unset($qb);
        }        
        return $details;
    }

    /**
     * Finds and returns the info's guid
     * @param integer id of the info
     * @return string guid of the info object
     */
    public function get_guid($id = null)
    {
        $guid = null;
        if (isset($id))
        {
            $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
            $qb->add_constraint('id', '=', $id);
            $_res = $qb->execute();
            $guid = $_res[0]->guid;
            unset($_res);
            unset($qb);
        }
        return $guid;
    }
}
?>
