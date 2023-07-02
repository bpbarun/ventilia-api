<?php

header('Access-Control-Allow-Origin: *');
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This class is used for Crud operation over album
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/Api/user
 * @category        Api
 * @author          Barun Pandey
 * @date            19 September, 2019, 12:22:00 PM
 * @version         1.0.0
 */
class ThirdParty extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('auth/mAuth');
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    public function index() {
        echo "called index function";
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function index_get($id = 0) {

        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (is_numeric($id)) {
                $response = $this->mAuth->getData($id);
            } else {
                $data = explode(":", $id);
                $response = $this->mAuth->getData($data);
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function index_post() {
        $headerData = $this->input->request_headers();
        $input = $this->post();
        $responseData = $this->common->checkLogin($input);
//        print_r($responseData);
        if (empty($responseData['error'])) {
            $responseConfigData = $this->common->getThirdPartyConfigData($responseData['data']['user_id']);
            $inputData['token_code'] = substr(md5(rand()), 0, 16);
            $inputData['created_on'] = date("Y-m-d H:i:s");
            $inputData['expire_on'] = date("Y-m-d H:i:s", strtotime('+1 year'));
            $inputData['user_id'] = $responseData['data']['user_id'];
            $inputData['ip'] = $_SERVER['REMOTE_ADDR'];
            $response = $this->mAuth->insertData($inputData);
            if (empty($responseConfigData['error'])) {
                $response['data']->user_name = $responseData['data']['user_name'];
                $response['data']->logo = (!empty($responseConfigData['data']['logo'])) ? $responseConfigData['data']['logo'] : '';
                $response['data']->bg_image = (!empty($responseConfigData['data']['bg_image'])) ? $responseConfigData['data']['bg_image'] : '';
                $response['data']->company_name = (!empty($responseConfigData['data']['company_name'])) ? $responseConfigData['data']['company_name'] : '';
                $response['data']->header_text = (!empty($responseConfigData['data']['header_text'])) ? $responseConfigData['data']['header_text'] : '';
                $response['data']->sub_header_text = (!empty($responseConfigData['data']['sub_header_text'])) ? $responseConfigData['data']['header_text'] : '';
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update Data from this method.
     *
     * @return Response
     */
    public function index_put($id) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->put();
            $response = $this->mAuth->updateData($id, $input);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function index_delete($id) {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->put();
            $response = $this->mAuth->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}
