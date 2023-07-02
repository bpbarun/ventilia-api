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
class mToken extends CI_Model {

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
    public function getData($inputData) {
        if (!empty($inputData)) {
            if (is_numeric($inputData)) {
                $this->db->select('t.*,c.counter_name,s.subcounter_name,s.counter_id', NULL, FALSE);
                $this->db->from('token_detail t', NULL, FALSE);
                $this->db->where('t.token_id', $inputData);
                $this->db->join('token_subcounter s', 's.subcounter_id = t.subcounter_id', 'left', NULL, FALSE);
                $this->db->join('token_counter c', 's.counter_id = c.counter_id', 'left', NULL, FALSE);
                $this->db->order_by('t.token_id', 'ASC');
                $data = $this->db->get()->row_array();
            } else {
                $this->db->select('t.*,c.counter_name,s.subcounter_name,s.counter_id', NULL, FALSE);
                $this->db->from('token_detail t', NULL, FALSE);
                $alies = (in_array('counter_id', $inputData)) ? 'c' : 't';
                if (in_array('counter_id', $inputData)) {
                    $this->db->where('t.is_active', 2);
                }
                for ($i = 0; $i < COUNT($inputData); $i++) {
                    $this->db->where($alies . '.' . $inputData[$i], $inputData[++$i]);
                }
                $this->db->join('token_subcounter s', 's.subcounter_id = t.subcounter_id', 'left', NULL, FALSE);
                $this->db->join('token_counter c', 's.counter_id = c.counter_id', 'left', NULL, FALSE);
                $this->db->order_by('t.token_id');
                if (in_array('counter_id', $inputData)) {
                    $this->db->limit(8);
                } else {
                    $this->db->limit(1);
                }
                $data = $this->db->get()->result();
                $selectRunningToken = $this->db->select('token_id');
                for ($i = 0; $i < COUNT($inputData); $i++) {
                    if (in_array('subcounter_id', $inputData)) {
                        $runningToken = $this->db->get_where('token_detail', ['subcounter_id' => $inputData[1], 'is_active' => 2])->row();
                    }
                    if (!empty($runningToken->is_active)) {
                        if ($runningToken->is_active == 2) {
                            $this->db->update('token_detail', ['is_active' => 4], array('subcounter_id' => $runningToken->subcounter_id, 'is_active' => 2));
                        }
                    }
                }
                if (!empty($data)) {
                    $this->db->update('token_detail', ['is_active' => 2], array('token_id' => $data[0]->token_id));
                }
            }
        } else {
            $this->db->select('t.*,c.counter_name,s.subcounter_name,s.counter_id', NULL, FALSE);
            $this->db->from('token_detail t', NULL, FALSE);
            $this->db->join('token_subcounter s', 's.subcounter_id = t.subcounter_id', 'left', NULL, FALSE);
            $this->db->join('token_counter c', 's.counter_id = c.counter_id', 'left', NULL, FALSE);
            $this->db->order_by('t.token_id', 'ASC');
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
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function insertData($input) {
        /*         * **************** */
        if (isset($input['subcounter_id']) && !empty($input['subcounter_id'])) {
//            $this->db->select('token_display_name');
//            $this->db->where('subcounter_id', $input['subcounter_id']);

            $sql = "SELECT `token_display_name` FROM `token_detail` WHERE token_id = (SELECT MAX(`token_id`) from token_detail WHERE `subcounter_id` = '" . $input['subcounter_id'] . "')";
            $query = $this->db->query($sql);
            $lastId = $query->row();
//            $lastId = $this->db->get('token_detail')->row();
        }
        $displayName = (!empty($lastId->token_display_name)) ? ($lastId->token_display_name + 1) : 1;
        $input['token_display_name'] = $displayName;

        /*         * ************* */
        $data = $this->db->insert('token_detail', $input);
//        echo $this->db->last_query();
//        die;
        if (!empty($data)) {

            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('token_detail', array('token_id' => $id));
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
    public function getActiveToken($id) {
        if (!empty($id)) {
            $this->db->select('subcounter_id');
            $this->db->from('token_subcounter');
            $this->db->where('counter_id', $id);
            $subCounterArray = array();
            $subcounterId = $this->db->get()->result_array();
            if (!empty($subcounterId)) {

                foreach ($subcounterId as $cData) {
                    array_push($subCounterArray, $cData['subcounter_id']);
                }
            }
        }
        $this->db->distinct('t.subcounter_id');
        $this->db->select('t.*,c.counter_name,s.subcounter_name,s.counter_id', NULL, FALSE);
        $this->db->from('token_detail t', NULL, FALSE);
        if (!empty($id)) {
            if (!empty($subCounterArray)) {
                $this->db->where('t.is_active', 2);
                $this->db->where_in('t.subcounter_id', $subCounterArray);
            } else {
                $response['status'] = FALSE;
                $response['error'] = 'No record found';
                return $response;
            }
        } else
            $this->db->where('t.is_active', 2);
        $this->db->join('token_subcounter s', 's.subcounter_id = t.subcounter_id', 'left', NULL, FALSE);
        $this->db->join('token_counter c', 's.counter_id = c.counter_id', 'left', NULL, FALSE);
//        $this->db->group_by('t.subcounter_id'); 
        $this->db->order_by('t.modify_on', 'ASC');
        $this->db->order_by('t.subcounter_id', 'ASC');

        $this->db->order_by('t.token_id', 'ASC');
//        $this->db->limit(12);
        $data = $this->db->get()->result();
//        echo $this->db->last_query();
//        die;
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
            $allData = $data;
            foreach ($allData as $key => $newData) {
                $this->db->select('token_display_name', NULL, FALSE);
                $this->db->from('token_detail', NULL, FALSE);
                $this->db->where('subcounter_id', $newData->subcounter_id);
                $this->db->where('is_active', 1);
                $this->db->limit(3);
                $this->db->order_by('token_id', 'ASC');
                $nextToken = $this->db->get()->result_array();
                $nData = array();
                foreach ($nextToken as $nexTokenData) {
                    array_push($nData, $nexTokenData['token_display_name']);
                }
                $nextToken = implode(",", $nData);
                $response['data'][$key]->next_token = $nextToken;
            }
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'No record found';
        }
        return $response;
    }

    /**
     * Update Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input) {
        if (!empty($input['subcounter_id'])) {
            $subcount = $input['subcounter_id'];
            unset($input['subcounter_id']);
        }
        $data = $this->db->update('token_detail', $input, array('token_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $getData = array('subcounter_id', $subcount, 'is_active', 1);
            $response = $this->getData($getData);
        } else {
            $response['status'] = FALSE;
            $response['error'] = 'No update in database';
        }
        return $response;
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id) {
        $data = $this->db->delete('token_detail', array('token_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

}
