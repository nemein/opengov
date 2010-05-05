<?php
/**
 * @package fi.opengov.datacatalog
 * @author The Midgard Project, http://www.midgard-project.org
 * @copyright The Midgard Project, http://www.midgard-project.org
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 */

/**
 * fi.opengov.datacatalog dataset dba
 *
 * @todo: docs
 *
 * @package fi.opengov.datacatalog
 */
class fi_opengov_datacatalog_dataset_dba extends __fi_opengov_datacatalog_dataset_dba
{
    /*
     * Finds and returns organization and license details
     * @param integer the info's ID 
     * @param string type ca nbe organization, license, format
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

            unset($_res);
            unset($qb);
        }        
        return $details;
    }

    /*
     * Finds and returns formats of a dataset
     * @param integer ID of the dataset
     * @return array storing the title and URL of the format's
     */
    public function get_formats($id = null)
    {
        $type = 'format';
        $details = array();
        if (   $id
            && $type != '')
        {
            $organization = array();
            $qb = fi_opengov_datacatalog_dataset_info_dba::new_query_builder();
            $qb->add_constraint('dataset', '=', $id);
            $_res = $qb->execute();

            foreach ($_res as $info)
            {
                $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
                $qb->add_constraint('id', '=', $info->info);
                $qb->add_constraint('type', '=', $type);
                $formats = $qb->execute();
            }
            unset($_res);
            unset($qb);
        }        
        return $formats;
    }

}
?>
