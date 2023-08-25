<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         ventilia_api
 * @subpackage      Model/api/lead
 * @category        common to all
 * @author          Barun Pandey
 * @date            3 June, 2023, 02:22:00 PM
 * @version         1.0.0
 */
class mLeadGeneration extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getData($id)
    {
        if (is_numeric($id)) {
            $data = $this->db->get_where("lead", ['lead_id' => $id])->row_array();
        } else {
            $this->db->order_by('lead_id', 'DESC');
            $this->db->where('is_active', 1);
            $this->db->where('created_by', $id['created_by']);
            $data = $this->db->get("lead")->result();
            $leadIDs = array();
            foreach ($data as $key) {
                array_push($leadIDs, $key->lead_id);
            }
            $this->db->where_in('lead_id', $leadIDs);
            $this->db->where('is_active', 1);
            $data1 = $this->db->get("quotation_assets")->result();
            foreach ($data as $key) {
                foreach ($data1 as $key1) {
                    if ($key->lead_id === $key1->lead_id) {
                        $key->asset_name = $key1->asset_name;
                        $key->is_active = $key1->is_active;
                        $key->asset_type = $key1->asset_type;
                        $key->total_area = $key1->total_area;
                        $key->total_unit = $key1->total_unit;
                        $key->average_price = $key1->average_price;
                    }
                }
            }
            $this->db->where_in('lead_id', $leadIDs);
            $this->db->where('is_active', 1);
            $data1 = $this->db->get("offer_details")->result();
            foreach ($data as $key) {
                foreach ($data1 as $key1) {
                    if ($key->lead_id === $key1->lead_id) {
                        $key->offer_price = $key1->offer_price;
                        $key->gst = $key1->gst;
                        $key->freight = $key1->freight;
                        $key->close_date = $key1->close_date;
                    }
                }
            }
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        return $response;
    }
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getOpportunity($id)
    {
        if (is_numeric($id)) {
            $data = $this->db->get_where("lead", ['lead_id' => $id])->row_array();
        } else {
            $this->db->select('l.*,qa.set_opportunity');
            $this->db->from('lead l', NULL, FALSE);
            $this->db->join('quotation_assets qa', 'l.lead_id=qa.lead_id', NULL, FALSE);
            $this->db->order_by('lead_id', 'DESC');
            $this->db->where('qa.set_opportunity', 1);
            $data = $this->db->get()->result();
            $leadIDs = array();
            foreach ($data as $key) {
                array_push($leadIDs, $key->lead_id);
            }
            $this->db->where_in('lead_id', $leadIDs);
            $this->db->where('is_active', 1);
            $this->db->where('set_opportunity', 1);
            $data1 = $this->db->get("quotation_assets")->result();
            foreach ($data as $key) {
                foreach ($data1 as $key1) {
                    if ($key->lead_id === $key1->lead_id) {
                        $key->asset_name = $key1->asset_name;
                        $key->is_active = $key1->is_active;
                        $key->asset_type = $key1->asset_type;
                        $key->total_area = $key1->total_area;
                        $key->total_unit = $key1->total_unit;
                        $key->average_price = $key1->average_price;
                    }
                }
            }
            $this->db->where_in('lead_id', $leadIDs);
            $this->db->where('is_active', 1);
            $data1 = $this->db->get("offer_details")->result();
            foreach ($data as $key) {
                foreach ($data1 as $key1) {
                    if ($key->lead_id === $key1->lead_id) {
                        $key->offer_price = $key1->offer_price;
                        $key->gst = $key1->gst;
                        $key->freight = $key1->freight;
                        $key->close_date = $key1->close_date;
                    }
                }
            }
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        return $response;
    }
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function insertData($input)
    {
        $data = $this->db->insert('lead', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('lead', array('lead_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input)
    {
        $data = $this->db->update('lead', $input, array('lead_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id)
    {
        $data = $this->db->delete('lead', array('lead_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
    /**
     * Insert the data in assets table
     */
    public function insertAssetsData($input)
    {
        if (isset($input['lead_id'])) {
            $this->db->update('lead_assets', array('is_active' => 0), array('lead_id' => $input['lead_id']));
        }
        $data = $this->db->insert('lead_assets', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('lead_assets', array('asset_id' => $id));
            $response['data'] = $q->row();
            $this->db->update('lead', array('lead_progress' => 1), array('lead_id' => $input['lead_id']));
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getQuotationLead($id)
    {
        if (is_numeric($id)) {
            $this->db->select('l.*,a.asset_name,a.asset_id,a.asset_type,a.is_active');
            $this->db->from('lead l', NULL, FALSE);
            $this->db->join('lead_assets a', 'l.lead_id=a.lead_id', NULL, FALSE);
            $this->db->where('a.is_active', 1);
            $this->db->where_in('l.created_by', $id);
            $this->db->where_in('l.lead_progress', 1);
            $this->db->order_by('l.lead_id', 'ASC');
            $data = $this->db->get()->result();
        } else {
            if (!empty($id['created_by'])) {
                $this->db->select('sealesman_id');
                $this->db->from('technial_salesman_relation', NULL, FALSE);
                $this->db->where('technical_id', $id['created_by']);
                $sealsmanId = $this->db->get()->row();
                $sealsmanId = explode(',', $sealsmanId->sealesman_id);
            }
            $this->db->select('l.*,a.asset_name,a.asset_id,a.asset_type,a.is_active');
            $this->db->from('lead l', NULL, FALSE);
            $this->db->join('lead_assets a', 'l.lead_id=a.lead_id', NULL, FALSE);
            $this->db->where('a.is_active', 1);
            $this->db->where_in('l.created_by', $sealsmanId);
            $this->db->order_by('l.lead_id', 'ASC');
            $data = $this->db->get()->result();
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'No record found';
        }
        return $response;
    }

    /**
     * Get Reports Data from this method.
     *
     * @return Response
     */
    public function getReportData($id)
    {

        // if (is_numeric($id)) {
        //     $data = $this->db->get_where("lead", ['lead_id' => $id])->row_array();
        // } 
        // else {
        //     $this->db->order_by('lead_id', 'DESC');
        //     $this->db->where('created_by', $id);
        //     $data = $this->db->get("lead")->result();
        //     $leadIDs = array();
        //     foreach ($data as $key) {
        //         array_push($leadIDs, $key->lead_id);
        //     }
        //     $this->db->where_in('lead_id', $leadIDs);
        //     $this->db->where('is_active', 1);
        //     $data1 = $this->db->get("quotation_assets")->result();
        //     foreach ($data as $key) {
        //         foreach ($data1 as $key1) {
        //             if ($key->lead_id === $key1->lead_id) {
        //                 $key->asset_name = $key1->asset_name;
        //                 $key->is_active = $key1->is_active;
        //                 $key->asset_type = $key1->asset_type;
        //                 $key->total_area = $key1->total_area;
        //                 $key->total_unit = $key1->total_unit;
        //                 $key->average_price = $key1->average_price;
        //             }
        //         }
        //     }
        //     $this->db->where_in('lead_id', $leadIDs);
        //     $this->db->select('COUNT(lead_id) as offer_count,lead_id,offer_price,gst,freight');
        //     $data1 = $this->db->get("offer_details")->result();
        //     foreach ($data as $key) {
        //         foreach ($data1 as $key1) {
        //             if ($key->lead_id === $key1->lead_id) {
        //                 $key->offer_price = $key1->offer_price;
        //                 $key->gst = $key1->gst;
        //                 $key->freight = $key1->freight;
        //                 $key->offer_count = $key1->offer_count;
        //             }
        //         }
        //     }
        // // }
        // if (!empty($data)) {
        //     $response['status'] = TRUE;
        //     $response['data'] = $data;
        // } else {
        //     $response['error'] = 'No record found';
        // }
        // return $response;

        $this->db->select('au.user_name,l.lead_id,l.is_active');
        $this->db->from('auth_user au', null, false);
        $this->db->join('lead l', 'au.user_id=l.created_by', null, false);
        $this->db->where('au.user_role', 'sealseman');
        $data = $this->db->get()->result();
        $k = 0;
        $n = '';

        $newData = array();
        for ($i = 0; $i < count($data); $i++) {
            if ($n != $data[$i]->user_name) {
                $n = $data[$i]->user_name;
                $k = 0;
                $l = 0;
                for ($j = 0; $j < count($data); $j++) {
                    if ($n == $data[$j]->user_name) {
                        $k = $k + 1;
                    }
                    if ($n == $data[$j]->user_name && $data[$j]->is_active == 1) {
                        $l = $l + 1;
                    }
                }
                $newData[] = array('user_name' => $n, 'total_lead' => $k, 'active_lead' => $l);
            }
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $newData;
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'No record found';
        }
        return $response;
    }
}
