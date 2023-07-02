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
class Licence extends REST_Controller {

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct() {
        parent::__construct();
        $this->load->database(); 
        $this->load->model('auth/mLicence');
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
        if (is_numeric($id)) {
            $response = $this->mLicence->getData($id);
        } else {
            $data = explode(":", $id);
            $response = $this->mLicence->getData($data);
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
        $responseData = $this->mLicence->insertData($input);
        if (empty($responseData['error'])) {
            $this->response($responseData, REST_Controller::HTTP_OK);
        } else {
            unset($responseData['data']);
            $this->response($responseData, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function selectR_post() {
        $input = $this->post();
        $response = $this->mLicence->selectR($input);
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
            $response = $this->mLicence->updateData($id, $input);
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
            $response = $this->mLicence->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}
