<?php

header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");

/**
 * This class is used for Crud operation over report 
 *
 * @package         Barun Pandey(api)
 * @subpackage      Model/api/mTechnicalSalesman
 * @category        common to all
 * @author          Barun Pandey
 * @date            19 AUgust, 2026, 02:22:00 PM
 * @version         1.0.0
 */

class mTechnicalSalesman extends CI_Model
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
            $data = $this->db->get_where("technial_salesman_relation", ['technical_id' => $id])->row_array();
        } else {
            $this->db->order_by('id', 'ASC');
            $data = $this->db->get("technial_salesman_relation")->result();
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
        $data = $this->db->insert('technial_salesman_relation', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('technial_salesman_relation', array('id' => $id));
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
        $data = $this->db->update('technial_salesman_relation', $input, array('technical_id' => $id));
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
        $data = $this->db->delete('technial_salesman_relation', array('technical_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
}
