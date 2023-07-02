<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over token 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/api/token
 * @category        common to all
 * @author          Barun Pandey
 * @date            10 June, 2019, 05:19:00 PM
 * @version         1.0.0
 */
class mSubcounter extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    public function getData($id) {
        if (!empty($id)) {
//            $data = $this->db->get_where("token_subcounter", ['subcounter_id' => $id])->row_array();
            if (is_numeric($id)) {
                $data = $this->db->get_where("token_subcounter", ['subcounter_id' => $id])->row_array();
            } else {
                $col = (!empty($id[0])) ? $id[0] : 'subcounter_id';
                $this->db->select('s.*,c.counter_name', NULL, FALSE);
                $this->db->from('token_subcounter s', NULL, FALSE);
                $this->db->where('s.' . $col, $id[1]);
                $this->db->join('token_counter c', 'c.counter_id = s.counter_id', 'left', NULL, FALSE);
                $this->db->order_by('s.subcounter_id');
                $data = $this->db->get()->result();
            }
        } else {
            $this->db->select('s.*,c.counter_name', NULL, FALSE);
            $this->db->from('token_subcounter s', NULL, FALSE);
            $this->db->join('token_counter c', 'c.counter_id = s.counter_id', 'left', NULL, FALSE);
            $this->db->order_by('s.subcounter_id');
            $data = $this->db->get()->result();
        }
//        print_r($data);
        if (!empty($data)) {
            foreach ($data as $key => $subCounter) {
                if (!empty($subCounter->subcounter_id)) {
                    $this->db->select('token_display_name,is_active', NULL, FALSE);
                    $this->db->from('token_detail', NULL, FALSE);
                    $this->db->where('subcounter_id', $subCounter->subcounter_id);
                    $this->db->where('is_active', 1);
                    $pendingTokenData = $this->db->get()->result_array();
                    if (!empty($pendingTokenData)) {
                        $tokenCountData = count($pendingTokenData);
//                        foreach ($tokenData as $k => $tData) {
//                            if ($tData['is_active'] == 2) {
//                                $data[$key]->running = $tData['token_display_name'];
//                                $tokenCountData = $tokenCountData - 1;
//                            }
//                        }
                        $data[$key]->pending = $tokenCountData;
                    } else {
                        $data[$key]->pending = 0;
                    }
                    $this->db->select('token_display_name,is_active', NULL, FALSE);
                    $this->db->from('token_detail', NULL, FALSE);
                    $this->db->where('subcounter_id', $subCounter->subcounter_id);
                    $this->db->where('is_active', 2);
                    $runningTokenData = $this->db->get()->result_array();
                    if (!empty($runningTokenData)) {
                        $data[$key]->running = $runningTokenData[0]['token_display_name'];
                    } else {

                        $data[$key]->running = 0;
                    }
//                    if (empty($data[$key]->running)) {
//                        $data[$key]->running = 0;
//                    }
                }
            }

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
        $data = $this->db->insert('token_subcounter', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('token_subcounter', array('subcounter_id' => $id));
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
        $data = $this->db->update('token_subcounter', $input, array('subcounter_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['error'] = 'No recerd updated in database';
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id) {
        $data = $this->db->delete('token_subcounter', array('subcounter_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
