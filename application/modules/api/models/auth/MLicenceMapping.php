<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/auth
 * @category        common to all
 * @author          Barun Pandey
 * @date            24th October, 2019, 05:23:00 PM
 * @version         1.0.0
 */
class mLicenceMapping extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getData($id) {
        if (!empty($id)) {
            $data = $this->db->get_where("licence_mapping", ['mapping_id' => $id])->row_array();
        } else {
            $this->db->order_by('mapping_id', 'ASC');
            $data = $this->db->get("licence_mapping")->result_array();
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
    public function insertData($input) {
        $response = array('status' => FALSE, 'error' => '', 'data' => array());
        if (!empty($input)) {
            $data = $this->db->insert('licence_mapping', $input);
            if (!empty($data)) {
                $response['status'] = TRUE;
                $id = $this->db->insert_id();
                $q = $this->db->get_where('licence_mapping', array('mapping_id' => $id));
                $response['data'] = $q->row();
            } else {
                $response['error'] = 'Getting error please try after some time';
            }
        } else {
            $response['error'] = 'Input data is not proper';
        }

        return $response;
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function selectR($input) {
        $response = array('status' => FALSE, 'error' => '', 'data' => array());
        if (!empty($input)) {
            if (!empty($input['licence_code']) && !empty($input['unique_id'])) {
                $data = $this->db->get_where("licence_register", ['licence_code' => $input['licence_code'], 'unique_id' => $input['unique_id']])->row_array();
            } else {
                $response['error'] = 'Please Enter the proper detail';
            }
            if (!empty($data)) {
                $response['status'] = TRUE;
                $response['data'] = $data;
            } else {
                $response['error'] = 'No record found';
            }
        } else {
            $response['error'] = 'No record found';
        }

        return $response;
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input) {
        $data = $this->db->update('licence_register', $input, array('licence_id' => $id));
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
    public function deleteData($id) {
        $data = $this->db->delete('licence_register', array('licence_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
