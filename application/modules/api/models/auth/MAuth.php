<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");


/**
 * This class is used for Crud operation over report 
 *
 * @package         Ventelia
 * @subpackage      Model/api/auth
 * @category        common to all
 * @author          Barun Pandey
 * @date            03 june, 2023, 09:45:00 AM
 * @version         1.0.0
 */
class mAuth extends CI_Model {

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
            $data = $this->db->get_where("auth_token", ['token_code' => $id])->row_array();
        } else {
            $this->db->order_by('token_id', 'ASC');
            $data = $this->db->get("auth_token")->result();
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
        $data = $this->db->insert('auth_token', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('auth_token', array('token_id' => $id));
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
    public function updateData($id, $input) {
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
        if (!empty($input['oldPassword'])) {
            $passworddata = $this->db->get_where("auth_user", ['user_id' => $id, 'password' => $input['oldPassword']])->row_array();
            if (!empty($passworddata)) {
                unset($input['oldPassword']);
                $data = $this->db->update('auth_user', $input, array('user_id' => $id));
                if ($this->db->affected_rows() > 0) {
                    $response['status'] = TRUE;
                    $response['data'] = 'Record updated successfully';
                } else {
                    $response['error'] = 'No any update in database';
                }
            } else {
                $response['error'] = 'Old password does not match in our database';
            }
        } else {
            $response['error'] = 'Please enter the old password';
        }

        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id) {
        $data = $this->db->delete('auth_token', array('token_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
