<?php

header('Access-Control-Allow-Origin: *');

/**
 * This class is used for Crud operation over report 
 *
 * @package         Barun Pandey(api)
 * @subpackage      Model/api/MFollowUp
 * @category        ventilia_api
 * @author          Barun Pandey
 * @date            28 Feb, 2026, 03:10:00 PM
 * @version         1.0.0
 */
class mFollowUp extends CI_Model
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
    public function getData($filter)
    {
        $this->db->where('assigned_to', $filter['assigned_to']);
        // $this->db->where('is_delete', 0);
        if (!empty($filter['type'])) {
            if ($filter['type'] == 'today') {
                $this->db->where('DATE(followup_date)', date('Y-m-d'));
            }
            elseif ($filter['type'] == 'upcoming') {
                $this->db->where('DATE(followup_date) >', date('Y-m-d'));
                $this->db->where('status', 'pending');
            }
            elseif ($filter['type'] == 'missed') {
                $this->db->where('DATE(followup_date) <', date('Y-m-d'));
                $this->db->where('status', 'pending');
            }
            elseif ($filter['type'] == 'all') {
                // no extra filter
            }
        }
    
        $this->db->order_by('followup_date', 'ASC');
    
        $query = $this->db->get('follow_ups');
    
        return [
            'status' => true,
            'data'   => $query->result()
        ];
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function insertData($input)
    {
        $input['created_at'] = date('Y-m-d H:i:s');
        $input['status'] = 'pending';
        $this->db->insert('follow_ups', $input);
        if ($this->db->affected_rows() > 0) {

            $id = $this->db->insert_id();
            $q = $this->db->get_where('follow_ups', ['followup_id' => $id]);

            return [
                'status' => TRUE,
                'data' => $q->row()
            ];
        } else {
            return [
                'status' => FALSE,
                'error' => 'Error while inserting followup'
            ];
        }
    }

    /**
     * Insert Given Data from this method.
     *
     * @return Response
     */
    public function updateData($id, $input)
    {
        $input['updated_at'] = date('Y-m-d H:i:s');
        $this->db->update('follow_ups', $input, ['followup_id' => $id]);
        if ($this->db->affected_rows() > 0) {
            return [
                'status' => TRUE,
                'data' => 'Record updated successfully'
            ];
        } else {
            return [
                'status' => FALSE,
                'error' => 'No changes made'
            ];
        }
    }

    /**
     * Delete given Record from this method.
     *
     * @return Response
     */
    public function deleteData($id)
    {
        $this->db->delete('follow_ups', ['followup_id' => $id]);
        if ($this->db->affected_rows() > 0) {
            return [
                'status' => TRUE,
                'data' => 'Record deleted successfully'
            ];
        } else {
            return [
                'status' => FALSE,
                'error' => 'Unable to delete'
            ];
        }
    }
      /**
     * Mark Complete
     */
    public function markComplete($id)
    {
        $this->db->update(
            'follow_ups',
            ['status' => 'completed'],
            ['followup_id' => $id]
        );
        if ($this->db->affected_rows() > 0) {
            return [
                'status' => TRUE,
                'data' => 'Follow-up marked as completed'
            ];
        } else {
            return [
                'status' => FALSE,
                'error' => 'Unable to update'
            ];
        }
    }
    public function getTodayFollowups($id)
    {
        date_default_timezone_set('Asia/Kolkata');
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd   = date('Y-m-d 23:59:59');
        $this->db->where('followup_date >=', $todayStart);
        $this->db->where('followup_date <=', $todayEnd);
        $this->db->where('assigned_to',$id);
        $this->db->where('status !=','completed');
        $result = $this->db->get('follow_ups')->result();
        return [
            'status' => true,
            'data'   => $result
        ];
    }
    
}
