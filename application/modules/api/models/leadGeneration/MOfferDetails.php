<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         ventilia_api
 * @subpackage      Model/api/mOfferDetails
 * @category        common to all
 * @author          Barun Pandey
 * @date            3 June, 2023, 02:22:00 PM
 * @version         1.0.0
 */
class mOfferDetails extends CI_Model
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
        if (!empty($id)) {
            $data = $this->db->get_where("offer_details", ['lead_id' => $id, 'is_active' => 0])->result();
        } else {
            $this->db->order_by('offer_id', 'DESC');
            $data = $this->db->get("offer_details")->result();
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
     * Get All Data from this method.
     *
     * @return Response
     */
    public function insertData($input)
    {
        if (isset($input['lead_id'])) {
            $this->db->update('offer_details', array('is_active' => 0), array('lead_id' => $input['lead_id']));
        }
        $data = $this->db->insert('offer_details', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('offer_details', array('offer_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        if (isset($input['lead_id'])) {
            $this->db->select("COUNT(*) as count");
            $this->db->from('offer_details');
            $this->db->where('lead_id', $input['lead_id']);
            $offerData = $this->db->get()->row_array();
            if ($offerData['count'] == 1) {
                $this->db->update('lead', array('lead_progress' => 3), array('lead_id' => $input['lead_id']));
            } else if ($offerData['count'] > 1) {
                $this->db->update('lead', array('lead_progress' => 4), array('lead_id' => $input['lead_id']));
            }
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
        $data = $this->db->update('offer_details', $input, array('offer_id' => $id));
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
        $data = $this->db->delete('offer_details', array('offer_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
    public function offerLetterData($id){
        $this->db->select('o.*,q.*');
        $this->db->from('offer_details o', NULL, FALSE);
        $this->db->join('`quotation_assets` `q`', 'o.lead_id=q.lead_id', NULL, FALSE);
        $this->db->where('o.is_active', '1');
        $this->db->where('q.is_active', '1');
        $this->db->where('o.lead_id', $id);

        $data = $this->db->get()->result();
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'No record found';
        }
        return $response;
    }
}
