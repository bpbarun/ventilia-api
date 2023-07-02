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
 * @date            24 September, 2019, 05:14:00 PM
 * @version         1.0.0
 */
class LicenceMapping extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('auth/mLicenceMapping');
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
//        echo "called"; die;

        if (is_numeric($id)) {
            $response = $this->mLicenceMapping->getData($id);
        } else {
            $data = explode(":", $id);
            $response = $this->mLicenceMapping->getData($data);
        }
        $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function index_post() {
        $input = $this->post();
        $response = $this->mLicenceMapping->insertData($input);
        $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function selectR_post() {
        $input = $this->post();
        $response = $this->mLicenceMapping->selectR($input);
        $this->response($response, REST_Controller::HTTP_OK);
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
            $response = $this->mLicenceMapping->updateData($id, $input);
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
            $response = $this->mLicenceMapping->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}
