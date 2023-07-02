<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report
 *
 * @package         Displayfort_api
 * @subpackage      Model/api/auth
 * @category        common to all
 * @author          Barun Pandey
 * @date            10 September, 2019, 04:18:00 PM
 * @version         1.0.0
 */
class mRole extends CI_Model {

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
            $data = $this->db->get_where("user_access", ['user_id' => $id])->result_array();
        } else {
            $this->db->order_by('access', 'ASC');
            $data = $this->db->get("user_access")->result_array();
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
        $module = $input['module'];
        $data = array();
        foreach ($module as $key => $value) {
            $data[$key]['user_id'] = $input['user_id'];
            $data[$key]['module'] = $value['name'];
            $data[$key]['access'] = json_encode($value['access']);
            $data[$key]['is_active'] = (!empty($input['is_active'])) ? $input['is_active'] : '0';
        }
        $data = $this->db->insert_batch('user_access', $data);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('user_access', array('access_id' => $id));
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
        $module = $input['module'];
        $data = array();
        foreach ($module as $key => $value) {
            $data[$key]['user_id'] = $input['user_id'];
            $data[$key]['module'] = $value['name'];
            $data[$key]['access'] = json_encode($value['access']);
            $data[$key]['is_active'] = (!empty($input['is_active'])) ? $input['is_active'] : '0';
        }
        $data = $this->db->update('user_access', $data, array('user_id' => $id));
        echo $this->db->last_query(); die;
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
        $data = $this->db->delete('user_access', array('access_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
