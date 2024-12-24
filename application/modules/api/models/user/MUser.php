<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Barun Pandey(api)
 * @subpackage      Model/api/album
 * @category        common to all
 * @author          Barun Pandey
 * @date            19 AUgust, 2019, 02:22:00 PM
 * @version         1.0.0
 */
class mUser extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $response = array('status' => FALSE, 'error' => '', 'data' => array(), 'response_tag' => 220);
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function getData($id)
    {
        if (!empty($id)) {
            $data = $this->db->get_where("auth_user", ['user_id' => $id])->row_array();
        } else {
            $this->db->order_by('user_id', 'ASC');
            $data = $this->db->get("auth_user")->result();
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
    public function insertData($input)
    {
        $data = $this->db->insert('auth_user', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('auth_user', array('user_id' => $id));
            $response['data'] = $q->row();
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input)
    {
        $data = $this->db->update('auth_user', $input, array('user_id' => $id));
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
    public function deleteData($id)
    {
        $data = $this->db->delete('auth_user', array('user_id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function technicalsidebar($id)
    {
        $qry =  "SELECT u.user_name,u.user_id from auth_user as u 
        JOIN technial_salesman_relation as r on find_in_set(u.user_id,r.sealesman_id) 
        where r.technical_id = $id";
        $query = $this->db->query($qry);
        $data2  = $query->result();

        $qry1 =  "SELECT u.user_name,u.user_id from auth_user as u 
        JOIN teamlead_technical_relation as t on u.user_id=t.teamlead_id
        where find_in_set($id,t.technical_id)";
        $query1 = $this->db->query($qry1);
        $data1  = $query1->result();
        $data = array_merge($data2, $data1);
        if ($data) {

            foreach ($data as $key) {
                $sql1 = "SELECT COUNT(`created_by`) as no_of_lead from `lead` where `created_by`=$key->user_id and `lead_progress` =1";
                $query1 = $this->db->query($sql1);
                $record  = $query1->result();
                $key->no_of_lead = $record[0]->no_of_lead;
            }
        }
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        return $response;
    }
    public function getfranchises($id){
            $qry =  "SELECT au.user_name as label,au.user_id as value, t.is_active from auth_user as au
            JOIN teamlead_technical_relation as t on find_in_set(au.user_id,t.technical_id) 
            where t.teamlead_id = $id";
            $query = $this->db->query($qry);
            $data  = $query->result();
        if (!empty($data)) {
            $response['status'] = TRUE;
            $response['data'] = $data;
        } else {
            $response['error'] = 'No record found';
        }
        return $response;
    }
    public function savefranchises($id,$input){
        $data = $this->db->update('teamlead_technical_relation', $input, array('teamlead_id' => $id));
        if ($this->db->affected_rows() > 0) {
            $response['status'] = TRUE;
            $response['data'] = 'Record updated successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
}
