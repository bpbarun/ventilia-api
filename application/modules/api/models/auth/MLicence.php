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
class mLicence extends CI_Model {

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
            $data = $this->db->get_where("licence_register", ['locance_id' => $id])->row_array();
        } else {
            $this->db->order_by('licence_id', 'ASC');
            $data = $this->db->get("licence_register")->result_array();
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
            $this->db->select('config_value');
            $configData = $this->db->get_where("token_config", ['config_type' => 'licence_max_limit'])->row();
            $this->db->select('config_value');
            $licenceCode = $this->db->get_where("token_config", ['config_type' => 'licence_code', 'created_by' => 1])->row();
            $noRecord = $this->db->count_all('licence_register');
            if (!empty($input['licence_code']) && !empty($input['unique_id'])) {
                if ($licenceCode->config_value !== $input['licence_code']) {
                    $response['error'] = 'Your Licence code not matched from our record';
                } else {
                    $dataN['licence_code'] = $input['licence_code'];
                    $dataN['unique_id'] = $input['unique_id'];
                    $checkData = $this->selectR($dataN);
                    if (!empty($checkData['error'])) {
                        if ($noRecord >= $configData->config_value) {
                            $response['error'] = 'You have reached at max limit of device registration,please contact to administrator';
                        } else {
                            $data = $this->db->insert('licence_register', $input);
                            if (!empty($data)) {
                                $response['status'] = TRUE;
                                $id = $this->db->insert_id();
                                $q = $this->db->get_where('licence_register', array('licence_id' => $id));
                                $response['data'] = $q->row();
                            } else {
                                $response['error'] = 'Getting error please try after some time';
                            }
                        }
                    } else {
                        $response['status'] = TRUE;
                        $response['data'] = $checkData['data'];
                        /*                         * ************************************* */
                        if ($checkData['data']['module'] == 'token') {
                            $responseConfigData = $this->common->getThirdPartyConfigData(100);
                        } else {
                            $responseConfigData = $this->common->getThirdPartyConfigData($checkData['data']['sub_user_id']);
                        }
                        $inputData['token_code'] = substr(md5(rand()), 0, 16);
                        $inputData['created_on'] = date("Y-m-d H:i:s");
                        $inputData['expire_on'] = date("Y-m-d H:i:s", strtotime('+1 year'));
                        $inputData['user_id'] = '100';
                        $inputData['ip'] = $_SERVER['REMOTE_ADDR'];
                        $response1 = $this->mAuth->insertData($inputData);
                        $response['data']['token_code'] = (!empty($response1['data']->token_code)) ? $response1['data']->token_code : '';
                        $response['data']['logo'] = (!empty($responseConfigData['data']['logo'])) ? $responseConfigData['data']['logo'] : '';
                        $response['data']['bg_image'] = (!empty($responseConfigData['data']['bg_image'])) ? $responseConfigData['data']['bg_image'] : '';
                        $response['data']['icon'] = (!empty($responseConfigData['data']['icon'])) ? $responseConfigData['data']['icon'] : '';
                        $response['data']['company_name'] = (!empty($responseConfigData['data']['company_name'])) ? $responseConfigData['data']['company_name'] : '';
                        $response['data']['header_text'] = (!empty($responseConfigData['data']['header_text'])) ? $responseConfigData['data']['header_text'] : '';
                        $response['data']['sub_header_text'] = (!empty($responseConfigData['data']['sub_header_text'])) ? $responseConfigData['data']['header_text'] : '';

                        /*                         * ************************************* */
                    }
                }
            } else {
                $response['error'] = 'Input data is not proper';
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
                /*                 * ********** */
                $this->db->select('lr.*,lm.sub_user_id,lm.module', NULL, FALSE);
                $this->db->from('licence_register lr', NULL, FALSE);
                $this->db->where('lr.licence_code', $input['licence_code']);
                $this->db->where('lr.unique_id', $input['unique_id']);
                $this->db->join('licence_mapping lm', 'lr.licence_id = lm.licence_id', 'left', NULL, FALSE);
                $data = $this->db->get()->row_array();
            } else {
                $response['error'] = 'Please Enter the proper detail';
            }
            if (!empty($data)) {
                $response['status'] = TRUE;
                $data['sub_user_id'] = (!empty($data['sub_user_id'])) ? $data['sub_user_id'] : '';
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
