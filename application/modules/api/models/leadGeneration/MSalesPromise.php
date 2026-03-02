<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Barun Pandey(api)
 * @subpackage      Model/api/salesPromise
 * @category        ventilia_api
 * @author          Barun Pandey
 * @date            28 Feb, 2026, 03:10:00 PM
 * @version         1.0.0
 */
class mSalesPromise extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    public function getWeeklyData($userId, $from, $to)
    {
        $this->db->where('created_by', $userId);
        $this->db->where('DATE(created_on) >=', $from);
        $this->db->where('DATE(created_on) <=', $to);
    
        $query = $this->db->get('sales_promise');
    
        if ($query->num_rows() > 0) {
            return [
                'status' => TRUE,
                'data' => $query->result()
            ];
        } else {
            return [
                'status' => FALSE,
                'data' => []
            ];
        }
    }
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getData($id)
    {
        if (!empty($id)) {
            $data = $this->db->get_where("sales_promise", ['id' => $id])->row_array();
        } else {
            $this->db->order_by('id', 'ASC');
            $data = $this->db->get("sales_promise")->result();
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
    public function insertData($id,$input)
    {
        // $data = $this->db->insert('sales_promise', $input);
        // if (!empty($data)) {
        //     $response['status'] = TRUE;
        //     $id = $this->db->insert_id();
        //     $q = $this->db->get_where('sales_promise', array('id' => $id));
        //     $response['data'] = $q->row();
        // } else {
        //     $response['error'] = 'Getting error please try after some time';
        // }
        // return $response;
        // Get logged-in user ID (make sure this is already available)
        $input['created_by'] = $id;
        $today = date('Y-m-d');

        // Check if record already exists for today
        $this->db->where('created_by', $id);
        $this->db->where('DATE(created_on)', $today);
        $existing = $this->db->get('sales_promise')->row();

        if ($existing) {

            // -------- UPDATE --------
            $this->db->where('id', $existing->id);
            $update = $this->db->update('sales_promise', $input);

            if ($update) {
                $response['status'] = TRUE;
                $q = $this->db->get_where('sales_promise', array('id' => $existing->id));
                $response['data'] = $q->row();
            } else {
                $response['error'] = 'Update failed. Please try again.';
            }

        } else {

            // -------- INSERT --------
            $input['created_on'] = date('Y-m-d H:i:s');
            $insert = $this->db->insert('sales_promise', $input);

            if ($insert) {
                $response['status'] = TRUE;
                $id = $this->db->insert_id();
                $q = $this->db->get_where('sales_promise', array('id' => $id));
                $response['data'] = $q->row();
            } else {
                $response['error'] = 'Insert failed. Please try again.';
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
        $data = $this->db->update('sales_promise', $input, array('id' => $id));
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
        $data = $this->db->delete('sales_promise', array('id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
    
}
