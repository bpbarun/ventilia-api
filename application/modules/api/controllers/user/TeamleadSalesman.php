<?php

header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: *");

require APPPATH . 'libraries/REST_Controller.php';
/**
 * This class is used for Crud operation over album
 *
 * @package         Displayfort_api
 * @subpackage      Controllers/Api/user
 * @category        Api
 * @author          Barun Pandey
 * @date            19 March, 2026, 7:00:00 PM
 * @version         1.0.0
 */
class TeamleadSalesman extends REST_Controller
{

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('user/mTeamleadSalesman');
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    public function index()
    {
        echo "called index function";
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function index_get($id = 0)
    {

        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (is_numeric($id)) {
                $response = $this->mTeamleadSalesman->getData($id);
            } else {
                $data = explode(":", $id);
                $response = $this->mTeamleadSalesman->getData($data);
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
    public function index_post()
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->post();
            $response = $this->mTeamleadSalesman->insertData($input);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Update Data from this method.
     *
     * @return Response
     */
    public function index_put($id)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->put();
            $response = $this->mTeamleadSalesman->updateData($id, $input);
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
    public function index_delete($id)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->put();
            $response = $this->mTeamleadSalesman->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}
