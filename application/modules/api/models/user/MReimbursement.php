<?php
header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Barun Pandey(api)
 * @subpackage      Model/api/Attendence
 * @category        common to all
 * @author          Barun Pandey
 * @date            23 May, s, 02:22:00 PM
 * @version         1.0.0
 */
class mReimbursement extends CI_Model
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
           // $data = $this->db->get_where("leave", ['id' => $id])->row_array();
            $this->db->select('au.user_name,l.*');
            $this->db->from('leave l', NULL, FALSE);
            $this->db->join('`auth_user` `au`', 'au.user_id=l.user_id', NULL, FALSE);
            $this->db->where('l.user_id', $id);
            $this->db->order_by('l.id', 'ASC');
            $data = $this->db->get()->result();
        } else {
            $this->db->select('au.user_name,l.*');
            $this->db->from('leave l', NULL, FALSE);
            $this->db->join('`auth_user` `au`', 'au.user_id=l.user_id', NULL, FALSE);
            $this->db->order_by('l.id', 'ASC');
            $data = $this->db->get()->result();
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
        $data = $this->db->insert('reimbusment', $input);
        if (!empty($data)) {
            $response['status'] = TRUE;
            $id = $this->db->insert_id();
            $q = $this->db->get_where('reimbusment', array('id' => $id));
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
        $data = $this->db->update('reimbusment', $input, array('id' => $id));
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
        $data = $this->db->delete('reimbusment', array('id' => $id));
        if ($this->db->affected_rows() > 0) { // need to find no. of record affected
            $response['status'] = TRUE;
            $response['data'] = 'Record deleted successfully';
        } else {
            $response['error'] = 'Getting error please try after some time';
        }
        return $response;
    }
    
}
