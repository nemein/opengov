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
     * Checks the license type of the dataset
     * @param integer id of the dataset
     * @param string type; can be free or non-free
     * @return boolean true, if the dataset license type macthes the given criteria
     */
    public function matching_license_type($dataset_id, $type)
    {
        $retval = false;

        $qb = fi_opengov_datacatalog_dataset_dba::new_query_builder();
        $qb->add_constraint('id', '=', $dataset_id);
        $res = $qb->execute();

        if (count($res) == 1)
        {            
            $qb = fi_opengov_datacatalog_info_dba::new_query_builder();
            $qb->add_constraint('id', '=', $res[0]->license);
            $qb->add_constraint('type', '=', 'license');
            $res = $qb->execute();
            if (count($res) == 1)
            {
                if ($res[0]->get_parameter('fi.opengov.datacatalog', 'license_type') == $type)
                {
                    $retval = true;
                }
            }
        }

        unset($qb);
        unset($res);

        return $retval;
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

    /**
     * Returns an array of datasets tagged with 'tags'
     * @return array all tags
     */
    public function get_all_tags()
    {
        return net_nemein_tag_handler::get_tags_by_class('fi_opengov_datacatalog_dataset_dba');
    }
}
?>
