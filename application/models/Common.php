<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

/**
 * This class is used for Crud operation over token 
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/modal
 * @category        common to all
 * @author          Barun Pandey
 * @date            10 June, 2019, 03:54:00 PM
 * @version         1.0.0
 */
class Common extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function authCheck($inputData) {
        $responseData['status'] = TRUE;
        $responseData['error'] = '';
        if (!empty($inputData['token_code'])) {
            $where_array = array(
                'token_code' => $inputData['token_code'],
                'expire_on >=' => date("Y-m-d H:i:s")
            );
            $data = $this->db->get_where("auth_token", $where_array)->row_array();
            if (empty($data)) {
                $responseData['status'] = FALSE;
                $responseData['error'] = "Invalid Authentication";
            } else {
                $responseData['data']['id'] = $data['user_id'];
                if ($data['user_id'] !== '100' && $data['user_id'] !== '999') {
//                    if ($data['user_id'] !== '100') {
                    $authIncress['expire_on'] = date("Y-m-d H:i:s", strtotime('+6 hours'));
                    $data = $this->db->update('auth_token', $authIncress, array('token_code' => $inputData['token_code']));
//                    }
                }
            }
        } else {
            $responseData['status'] = FALSE;
            $responseData['error'] = "Please enter proper Auth detail";
        }
        return $responseData;
    }

    /*
     * is use to check the login credentials are true or not
     */

    public function checkLogin($inputData) {
        $responseData['status'] = TRUE;
        $responseData['error'] = '';
        $responseData['data'] = array();
        if (!empty($inputData['user']) && !empty($inputData['password'])) {
            $where_array = array(
                'user_name' => $inputData['user'],
                'password' => $inputData['password']
            );
            $data = $this->db->get_where("auth_user", $where_array)->row_array();
            if (empty($data)) {
                $responseData['status'] = FALSE;
                $responseData['error'] = "Invalid Authentication";
            } else {
                $responseData['data'] = $data;
            }
        } else {
            $responseData['status'] = FALSE;
            $responseData['error'] = "Please enter proper Auth detail123";
        }
        return $responseData;
    }

    public function getThirdPartyConfigData($id, $lang = 'en') {
        $responseData['status'] = TRUE;
        $responseData['error'] = '';
        $responseData['data'] = array();
        $this->db->select("logo,bg_image,company_name,header_text,sub_header_text,icon");
        $this->db->where('created_by', $id);
        $this->db->where('lang', $lang);
        $data = $this->db->get("third_party_config")->row_array();
        if (!empty($data)) {
            $responseData['data'] = $data;
        }
        return $responseData;
    }

    public function getFeedbackConfigData($data) {
        $responseData['status'] = TRUE;
        $responseData['error'] = '';
        $responseData['data'] = array();
        $id = (!empty($data['id'])) ? $data['id'] : '';
        $col = (!empty($id[0])) ? $id[0] : 'feedback_questions_id';
        $userID = (!empty($data['created_by'])) ? $data['created_by'] : '';
        $this->db->select('fs.string_name,fs.string_key_id,fs.feedback_string_id,fc.config_type');
        $this->db->join('feedback_string fs', 'fs.string_key_id = fc.config_value', 'right');
        if($id[1] && $col !='feedback_type_id'){
            $this->db->where('fs.' . $col, $id[1]);
        }
        $this->db->where('fc.created_by', $userID);
        $data = $this->db->get("feedback_config fc")->result();
        if (!empty($data)) {
            $responseData['data'] = $data;
        }
        return $responseData;
    }

}
