<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
require APPPATH . 'libraries/REST_Controller.php';
/**
 * This class is used for Crud operation over album
 *
 * @package         Barun
 * @subpackage      Controllers/Api/user
 * @category        Api
 * @author          Barun Pandey
 * @date            19 August, 2019, 7:00:00 PM
 * @version         1.0.0
 */
class Reimbursement extends REST_Controller
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
        $this->load->model('user/mReimbursement');
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
                $response = $this->mReimbursement->getData($id);
            } else {
                $data = explode(":", $id);
                $response = $this->mReimbursement->getData($data);
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
    // public function index_post()
    // {
    //     $headerData = $this->input->request_headers();
    //     $responseData = $this->common->authCheck($headerData);
    //     if (empty($responseData['error'])) {
    //         $input = $this->post();
    //         $response = $this->mReimbursement->insertData($input);
    //         $this->response($response, REST_Controller::HTTP_OK);
    //     } else {
    //         $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
    //     }
    // }
    public function index_post()
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $input = $this->post();
            try {
                $mediaDirectory = '/var/www/html/lead/';
                $mediaName = $_FILES["file"]["name"];
                $indexOFF  = strrpos($mediaName, '.');
                $nameFile  = substr($mediaName, 0, $indexOFF);
                $extension = substr($mediaName, $indexOFF);
                $clean     = preg_replace("([^\w\s\d\-_])", "", $nameFile);
                $mediaName  = str_replace(' ', '', $clean) . $extension;
                if (empty($_FILES["file"]["type"]) || !move_uploaded_file($_FILES["file"]["tmp_name"], $mediaDirectory . '/' . $mediaName)) {
                  
                }
                 unset($input['file']);
                // $input['asset_name'] = $mediaName;
                // $input['asset_type'] = $_FILES["file"]["type"];
                $response = $this->mReimbursement->insertData($input);
                $this->response($response, REST_Controller::HTTP_OK);
            } catch (Exception $e) {
                $response = $e->getMessage();
                $this->response($response, REST_Controller::HTTP_OK);
            }
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
            $response = $this->mReimbursement->updateData($id, $input);
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
            $response = $this->mReimbursement->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}






