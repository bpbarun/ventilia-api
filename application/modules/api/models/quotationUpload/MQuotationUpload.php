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
class mQuotationUpload extends CI_Model
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
            $this->db->order_by('is_active', 'DESC');
            $data = $this->db->get_where("quotation_assets", ['lead_id' => $id])->result();
        } else {
            $this->db->order_by('quotation_asset_id', 'DESC');
            $data = $this->db->get("quotation_assets")->result();
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
        $data = $this->db->insert('quotation_assets', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('quotation_assets', array('quotation_asset_id' => $id));
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
        if (isset($input['set_opportunity'])) {
            $this->db->update('quotation_assets', array('set_opportunity' => 0, 'is_active' => 0), array('lead_id' => $input['lead_id']));
        }
        $data = $this->db->update('quotation_assets', $input, array('quotation_asset_id' => $id));
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
        $data = $this->db->delete('quotation_assets', array('quotation_asset_id' => $id));
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
            $this->db->update('quotation_assets', array('is_active' => 0), array('lead_id' => $input['lead_id']));
        }
        $data = $this->db->insert('quotation_assets', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('quotation_assets', array('quotation_asset_id' => $id));
            $response['data'] = $q->row();
            $this->db->update('lead', array('lead_progress'=>2), array('lead_id' => $input['lead_id']));
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
}
