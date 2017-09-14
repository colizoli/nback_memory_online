
<?php
class Exp1_model extends CI_Model
{


// check to see if the subject has been here already
function email($email)
	{
        $this->db->where('email', $email);
		$this->db->select('subject_id');
		$this->db->from('nback_subject_info');
                //$this->db->group_by('subject_id');
		
		$r = $this->db->get();

		if($r->num_rows() == 0)
			{return false;}
		else 
			{return true;}
	}
	
// check to see if the subject has filled in the questionnaire
function check_questions($subject_id)
	{
        $this->db->where('subject_id', $subject_id);
		$this->db->select('age');
		$this->db->from('nback_subject_info');            				
		  //$this->db->limit(1);
        $r = $this->db->get();
		
		if($r->num_rows() > 0)
			{return $r;}
		else
			{return false;}			   
	}

// Get subject ID
function get_id($email)
	{
		$this->db->where('email', $email);
		$this->db->select('subject_id');
		$this->db->from('nback_subject_info');
                $this->db->limit(1);

               $r = $this->db->get();
               
               $row = $r->row(0);

               $r = $row->subject_id;

               return $r;       
	}

// Enter a new subject
function new_subject($data)
	{
		$this->db->insert('nback_subject_info', $data);
                 return $this->db->insert_id();
                 $this->db->limit(1);
	}

// Questionnaire Data
function questionnaire($data)
	{
		 $this->db->insert('nback_subject_info', $data);
         $this->db->limit(1);
	}

	
// Loads the actual nback test
function nback_stimuli4($nback_id, $dbtable)
	{
                        $this->db->select('nback_id, object_category, nback, stimuli_names, matching, instructions1, instructions2, example');
                        $this->db->from($dbtable);
                        $this->db->where('nback_id', $nback_id[0]);
                        $this->db->or_where('nback_id', $nback_id[1]);
                        $this->db->or_where('nback_id', $nback_id[2]);
                        $this->db->or_where('nback_id', $nback_id[3]);
                        $r = $this->db->get();
                        return $r;
    }

function nback_stimuli3($nback_id, $dbtable)
	{
                        $this->db->select('nback_id, object_category, nback, stimuli_names, matching, instructions1, instructions2, example');
                        $this->db->from($dbtable);
                        $this->db->where('nback_id', $nback_id[0]);
                        $this->db->or_where('nback_id', $nback_id[1]);
                        $this->db->or_where('nback_id', $nback_id[2]);
                        $r = $this->db->get();
                        return $r;
    }

function nback_stimuli2($nback_id, $dbtable)
	{
                        $this->db->select('nback_id, object_category, nback, stimuli_names, matching, instructions1, instructions2, example');
                        $this->db->from($dbtable);
                        $this->db->where('nback_id', $nback_id[0]);
                        $this->db->or_where('nback_id', $nback_id[1]);
                        $r = $this->db->get();
                        return $r;
        }

function nback_stimuli1($nback_id, $dbtable)
	{
                        $this->db->select('nback_id, object_category, nback, stimuli_names, matching, instructions1, instructions2, example');
                        $this->db->from($dbtable);
                        $this->db->where('nback_id', $nback_id[0]);
                        $r = $this->db->get();
                        return $r;
        }
		
function nback_stimuli_takeone($nback_id, $table)
	{
                        $this->db->select('nback_id, object_category, nback, stimuli_names, matching, instructions1, instructions2, example');
                        $this->db->from($table); //here variable
                        $this->db->where('nback_id', $nback_id);
                        $r = $this->db->get();
                        return $r;
        }

function trials($trial_data, $trial_id)
	{
		if($trial_id == 0)
		{
            $this->db->insert('nback_trials', $trial_data);
            return $this->db->insert_id();
		}
		else
		{
			$this->db->set($trial_data);
			$this->db->where('trial_id', $trial_id);
			$this->db->update('nback_trials', $trial_data);
			$this->db->limit(1);
            //return $this->db->insert_id();
		}	
	}	

function check_tests($subject_id)
        {
        $this->db->select('nback_id');
		$this->db->from('nback_trials');
		$this->db->where('subject_id', $subject_id);
        $this->db->where('completed', 1);
		$result = $this->db->get();

                if($result->num_rows() > 0)
                    {
                    return $result;
                    }
                else
                    {
                    return false;
                    }
        }

function check_test_scores($subject_id)
        {
        $this->db->select('trial_id');
		$this->db->from('nback_trials');
		$this->db->where('subject_id', $subject_id);
        $this->db->where('completed', 1);
		$result = $this->db->get();

                if($result->num_rows() > 0)
                    {
                    return $result;
                    }
                else
                    {
                    return false;
                    }
        }
        
function get_all_tests($dbtable)
        {
                $this->db->select('nback_id');
		$this->db->from($dbtable);
                // where language == 1
		$result = $this->db->get();

		if($result->num_rows() > 0)
                    {
                    return $result;
                    }
                else
                    {
                    return false;
                    }
        }

function get_scores($trial_id)
        {
            $this->db->select('nback_id, score');
            $this->db->from('nback_trials');
            $this->db->where('trial_id', $trial_id);
            //$this->db->group_by('nback_id');
            $result = $this->db->get();

               // return $result;

                
                if($result->num_rows() > 0)
                    {
                    return $result;
                    }
                else
                    {
                    return false;
                    }          
        }

function count_completed()
        {
            $this->db->select('nback_id');
            $this->db->from('nback_trials');
            $this->db->where('completed', 1);
           // $this->db->group_by('nback_id');
            $result = $this->db->get();

                if($result->num_rows() > 0)
                    {
                    return $result;
                    }
                else
                    {
                    return false;
                    }
        }

} // This } is for the whole model

?>
