<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

require APPPATH . 'libraries/REST_Controller.php';
/**
 * This class is used for Crud operation over leadGeneration
 *
 * @package         Ventilia_api
 * @subpackage      Controllers/Api/FollowUp
 * @category        Api
 * @author          Barun Pandey
 * @date            28 Feb, 2026, 3:05:00 PM
 * @version         1.0.0
 */
class FollowUp extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('leadGeneration/mFollowUp');
        $this->load->model('common');
    }

    /**
     * GET: /followup?type=today
     */
    public function index_get()
    {
        $headerData = $this->input->request_headers();
        $auth = $this->common->authCheck($headerData);

        if (!empty($auth['error'])) {
            return $this->response($auth, REST_Controller::HTTP_UNAUTHORIZED);
        }

        $userId = $auth['data']['id'];
        $type   = $this->get('type'); // today/upcoming/missed
        $leadId = $this->get('lead_id');

        $filter = [
            'assigned_to' => $userId,
            'type'        => $type,
            'lead_id'     => $leadId
        ];

        $response = $this->mFollowUp->getData($filter);

        return $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * POST: Create FollowUp
     */
    public function index_post()
    {
        $headerData = $this->input->request_headers();
        $auth = $this->common->authCheck($headerData);

        if (!empty($auth['error'])) {
            return $this->response($auth, REST_Controller::HTTP_UNAUTHORIZED);
        }

        $input = $this->post();
        $input['created_by'] = $auth['data']['id'];
        $input['assigned_to'] = $auth['data']['id'];

        $response = $this->mFollowUp->insertData($input);

        return $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * PUT: Update FollowUp
     */
    public function index_put($id)
    {
        $headerData = $this->input->request_headers();
        $auth = $this->common->authCheck($headerData);

        if (!empty($auth['error'])) {
            return $this->response($auth, REST_Controller::HTTP_UNAUTHORIZED);
        }

        $input = $this->put();

        $response = $this->mFollowUp->updateData($id, $input);

        return $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * DELETE: Delete FollowUp
     */
    public function index_delete($id)
    {
        $headerData = $this->input->request_headers();
        $auth = $this->common->authCheck($headerData);

        if (!empty($auth['error'])) {
            return $this->response($auth, REST_Controller::HTTP_UNAUTHORIZED);
        }

        $response = $this->mFollowUp->deleteData($id);

        return $this->response($response, REST_Controller::HTTP_OK);
    }

    /**
     * PUT: Mark Complete
     * URL: /followup/complete/{id}
     */
    public function complete_put($id)
    {
        $headerData = $this->input->request_headers();
        $auth = $this->common->authCheck($headerData);

        if (!empty($auth['error'])) {
            return $this->response($auth, REST_Controller::HTTP_UNAUTHORIZED);
        }

        $response = $this->mFollowUp->markComplete($id);

        return $this->response($response, REST_Controller::HTTP_OK);
    }
    public function aiParse_post()
    {
        $currentDateTime = date('Y-m-d H:i:s');
        $currentDate = date('Y-m-d');
        $headerData = $this->input->request_headers();
        $auth = $this->common->authCheck($headerData);

        if (!empty($auth['error'])) {
            return $this->response($auth, REST_Controller::HTTP_UNAUTHORIZED);
        }

        $input = $this->post();
        $text  = $input['text'];
        $apiKey = getenv('OPENAI_API_KEY');
        $data = [
            "model" => "gpt-4o-mini",
            "messages" => [
                    [
                        "role" => "system",
                        "content" => "You are a strict JSON generator. Only return valid JSON."
                    ],
                    [
                        "role" => "user",
                        "content" => "
                        Current server date and time is: $currentDateTime

                        Extract structured follow-up details from:
                        \"$text\"

                        If user says 'tomorrow', calculate based on current date.
                        If user says weekday (Monday, Friday etc), calculate nearest future date.

                        Return strictly in JSON:
                        {
                        \"lead_name\": string,
                        \"followup_date\": \"YYYY-MM-DD HH:MM:SS\",
                        \"followup_type\": \"Call|Visit|Meeting|WhatsApp\",
                        \"remarks\": string
                        }"
                    ]
                ],
            "temperature" => 0
        ];
        $ch = curl_init("https://api.openai.com/v1/chat/completions");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        curl_close($ch);
        $decoded = json_decode($result, true);
        $content = $decoded['choices'][0]['message']['content'];
        preg_match('/\{.*\}/s', $content, $matches);
        if (!empty($matches[0])) {
            $json = json_decode($matches[0], true);
        } else {
            $json = null;
        }
        return $this->response([
            "status" => true,
            "data"   => $json
        ], REST_Controller::HTTP_OK);
    }

    public function getTodayFollowups_get($id)
    {
        $headerData = $this->input->request_headers();
        $auth = $this->common->authCheck($headerData);
        if (!empty($auth['error'])) {
            return $this->response($auth, REST_Controller::HTTP_UNAUTHORIZED);
        }
        $response = $this->mFollowUp->getTodayFollowups($id);

        return $this->response($response, REST_Controller::HTTP_OK);
    }
}