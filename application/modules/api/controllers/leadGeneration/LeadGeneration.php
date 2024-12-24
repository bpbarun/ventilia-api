<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');

require APPPATH . 'libraries/REST_Controller.php';
/**
 * This class is used for Crud operation over leadGeneration
 *
 * @package         Ventilia_api
 * @subpackage      Controllers/Api/Lead
 * @category        Api
 * @author          Barun Pandey
 * @date            14 May, 2023, 2:25:00 PM
 * @version         1.0.0
 */
class LeadGeneration extends REST_Controller
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
        $this->load->model('leadGeneration/mLeadGeneration');
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
            if (!empty($id)) {
                $response = $this->mLeadGeneration->getData($id);
            } else {
                $data = explode(":", $id);
                $data['created_by'] = $responseData['data']['id'];
                $response = $this->mLeadGeneration->getData($data);
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
            $input['created_by'] = $responseData['data']['id'];
            if (isset($input['comment'])) {
                $commentData = $input['comment'];
                unset($input['comment']);
            }
            $response = $this->mLeadGeneration->insertData($input);
            if ($commentData) {
                $commentInput = array('comment' => $commentData,
                 'lead_id' => $response['data']->lead_id,
                'created_by'=>$responseData['data']['id']);
                $this->load->model('leadGeneration/mComment');
                $this->mComment->insertData($commentInput);
            }
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
            $response = $this->mLeadGeneration->updateData($id, $input);
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
            $response = $this->mLeadGeneration->deleteData($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * Upload the files from this method
     * 
     * @return Response
     */
    public function upload_post()
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
                    echo "error";
                    //     $subject  = 'Attachment Issue';
                    //     $this->notificationSend->sendIssueMail($subject, str_replace("\n", '<br>', $mailBody));
                    //     $response = array('status' => FALSE, 'error' => $subject);
                    //     $this->response($response, REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
                    //     return;
                }
                $input['asset_name'] = $mediaName;
                $input['asset_type'] = $_FILES["file"]["type"];
                $response = $this->mLeadGeneration->insertAssetsData($input);
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
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getQuotationLead_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (!empty($id)) {
                $response = $this->mLeadGeneration->getQuotationLead($id);
            } else {
                $data = explode(":", $id);
                $data['created_by'] = $responseData['data']['id'];
                $response = $this->mLeadGeneration->getQuotationLead($data);
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * Get All Opportunity Data from this method.
     *
     * @return Response
     */
    public function getOpportunity_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (!empty($id)) {
                $response = $this->mLeadGeneration->getOpportunity($id);
            } else {
                $data = explode(":", $id);
                $data['created_by'] = $responseData['data']['id'];
                $response = $this->mLeadGeneration->getOpportunity($data);
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /* Get All Data for reports.
    *
    * @return Response
    */
    public function getReport_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (!empty($id)) {
                $response = $this->mLeadGeneration->getReportData($id);
            } else {
                $data = explode(":", $id);
                $response = $this->mLeadGeneration->getReportData($data);
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getUseLeadData_get($id)
    {

        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            if (!empty($id)) {
                $response = $this->mLeadGeneration->getUseLeadData($id);
            } else {
                $response = array('status' => false, 'error' => 'Please send userId');
            }
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getGrapgData_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mLeadGeneration->getGrapgData($id);

            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    public function getMyGraphData_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mLeadGeneration->getMyGraphData($id);

            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getCancelLead_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mLeadGeneration->getCancelLead($id);

            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getCompletedLead_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mLeadGeneration->getCompletedLead($id);

            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
     /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getLeadAssets_get($id = 0)
    {
        $headerData = $this->input->request_headers();
        $responseData = $this->common->authCheck($headerData);
        if (empty($responseData['error'])) {
            $response = $this->mLeadGeneration->getLeadAssets($id);
            $this->response($response, REST_Controller::HTTP_OK);
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}
