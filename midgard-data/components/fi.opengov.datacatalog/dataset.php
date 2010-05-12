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
                $_formats = $qb->execute();
                $formats[] = $_formats[0];
            }
            unset($_formats);
            unset($_res);
            unset($qb);
        }        
        return $formats;
    }

    /**
     * Returns the number of datasets (free or non-free)
     * @param type string: free | non-free
     * @return integer the number of datasets
     */
    public function get_number_of_datasets($type = 'free')
    {
        $_retval = 0;
        $_res = array();
        if ($type != '')
        {
            $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();
            $_res = $qb->execute();
            foreach ($_res as $dataset)
            {
                $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
                $qb->add_constraint('id', '=', $dataset->license);
                $qb->add_constraint('type', '=', 'license');
                $_info = $qb->execute();
                if (count($_info) == 1)
                {
                    if ($_info[0]->get_parameter('fi.opengov.datacatalog', 'license_type') == $type)
                    {
                        $_retval++;
                    }
                }
            }
            unset($_info);
            unset($_res);
            unset($qb);        
        }

        return $_retval;
    }

    /**
     * Returns an array of datasets tagged with 'tags'
     * @param tags string
     * @return array all matching dataset objects
     */
    public function get_dataset_by_tags($tags)
    {
        $_tags = explode(' ', $tags);
        $_classes[] = 'fi_opengov_datacatalog_dataset_dba';
        return net_nemein_tag_handler::get_objects_with_tags($_tags, $_classes);
    }
}
?>
