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
 * @date            03 june, 2023, 10:43:00 PM
 * @version         1.0.0
 */
class QuotationUpload extends REST_Controller
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
        $this->load->model('quotationUpload/mQuotationUpload');
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
                $response = $this->mQuotationUpload->getData($id);
            } else {
                $data = explode(":", $id);
                $response = $this->mQuotationUpload->getData($data);
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
            try {
                $mediaDirectory = '/var/www/html/lead/';
                $mediaName = $_FILES["file"]["name"];
                // $mediaName = $this->security->sanitize_filename($_FILES["file"]["name"]);
                $indexOFF  = strrpos($mediaName, '.');
                // $nameFile  = substr($mediaName, 0, $indexOFF) . '-' . $input['upload_meta_id'];
                $nameFile  = substr($mediaName, 0, $indexOFF);
                $extension = substr($mediaName, $indexOFF);
                $clean     = preg_replace("([^\w\s\d\-_])", "", $nameFile);
                $mediaName  = str_replace(' ', '', $clean) . $extension;
                if (empty($_FILES["file"]["type"]) || !move_uploaded_file($_FILES["file"]["tmp_name"], $mediaDirectory . '/' . $mediaName)) {
                    //     $subject  = 'Attachment Issue';
                    //     $this->notificationSend->sendIssueMail($subject, str_replace("\n", '<br>', $mailBody));
                    //     $response = array('status' => FALSE, 'error' => $subject);
                    //     $this->response($response, REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
                    //     return;
                }
                // unset($input['upload_meta_id']);
                $input['asset_name'] = $mediaName;
                $input['asset_type'] = $_FILES["file"]["type"];
                $response = $this->mQuotationUpload->insertAssetsData($input);
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
            $response = $this->mQuotationUpload->updateData($id, $input);
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
            $response = $this->mQuotationUpload->deleteData($id);
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
                // $config = json_decode(file_get_contents(FRONT_URL . "/login/config.json"));
                // if (isset($config->{'media'}->{'directory'}) && isset($_FILES["file"])) {
                // $mediaDirectory = '/var/www/demo/university-media' . '/' . $input['activity_id'];
                // $mediaDirectory = $config->{'media'}->{'directory'} . '/' . $input['activity_id'];
                $mediaDirectory = '/var/www/html/lead/';
                print_r($input);
                // if (!is_dir($mediaDirectory)) {
                //    exec(mkdir($mediaDirectory));
                //     exec(chmod($mediaDirectory, 0777));
                // }
                print_r($_FILES);
                $mediaName = $_FILES["file"]["name"];
                // $mediaName = $this->security->sanitize_filename($_FILES["file"]["name"]);
                $indexOFF  = strrpos($mediaName, '.');
                // $nameFile  = substr($mediaName, 0, $indexOFF) . '-' . $input['upload_meta_id'];
                $nameFile  = substr($mediaName, 0, $indexOFF);
                $extension = substr($mediaName, $indexOFF);
                $clean     = preg_replace("([^\w\s\d\-_])", "", $nameFile);
                $mediaName  = str_replace(' ', '', $clean) . $extension;
                // move_uploaded_file($_FILES["file"]["tmp_name"], $mediaDirectory . '/' . $mediaName);
                // $mailBody = "UPLOAD FILE \n:\n " . print_r($input, true) . " \n::\n " . print_r($_FILES["file"], true) . " \n::\n " . print_r(scandir(str_replace(basename($_FILES["file"]["tmp_name"]), '', $_FILES["file"]["tmp_name"])), 1);
                // error_log($mailBody);
                if (empty($_FILES["file"]["type"]) || !move_uploaded_file($_FILES["file"]["tmp_name"], $mediaDirectory . '/' . $mediaName)) {
                    //     $subject  = 'Attachment Issue';
                    //     $this->notificationSend->sendIssueMail($subject, str_replace("\n", '<br>', $mailBody));
                    //     $response = array('status' => FALSE, 'error' => $subject);
                    //     $this->response($response, REST_Controller::HTTP_UNPROCESSABLE_ENTITY);
                    //     return;
                }
                // unset($input['upload_meta_id']);
                $input['asset_name'] = $mediaName;
                $input['asset_type'] = $_FILES["file"]["type"];
                $response = $this->mQuotationUpload->insertAssetsData($input);
                $this->response($response, REST_Controller::HTTP_OK);
            } catch (Exception $e) {
                $response = $e->getMessage();
                $this->response($response, REST_Controller::HTTP_OK);
            }
        } else {
            $this->response($responseData, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}
