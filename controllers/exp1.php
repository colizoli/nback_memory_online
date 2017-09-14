<?php
class Exp1 extends CI_Controller 
{
    
    
   	
function index()
{
    $this->EE =& get_instance();
    
	$this->load->model('livetest/exp1/Exp1_model','',TRUE);
    
    $id = $this->EE->session->userdata('group_id');
 
    if ($id == 1 || $id == 5)
    {
	   $this->load->view('livetest/exp1/exp1_view');
    }
    else
    {
	   $this->load->view('livetest/exp1/please_login');
    }
    
}

function ned()
{
    $this->EE =& get_instance();
    
	$this->load->model('livetest/exp1/Exp1_model','',TRUE);
    
    $id = $this->EE->session->userdata('group_id');
 
    if ($id == 1 || $id == 5)
    {
	   $this->load->view('livetest/exp1/exp1_view_NED'); /////
    }
    else
    {
	   $this->load->view('livetest/exp1/please_login');///// need to change for 2 different paths: english and dutch
    }
    
}

////////////////////////////////////// LOADING VIEWS /////////////////////////////////////


function check_question_info()
{
    $this->EE =& get_instance();
    $subject_id = $this->EE->session->userdata('member_id');
    
	$this->load->model('livetest/exp1/Exp1_model','',TRUE);
	
    // Checks to see if questionaire data is already in the database
    $result = $this->Exp1_model->check_questions($subject_id);

    if ($result)
    {
		echo 'true';  // data already in DB	
    }
    else
    {
        echo 'false';
    }       
}
 
// Answers to questionnaire
function send_subject_info()
{
    $this->EE =& get_instance();
    $subject_id = $this->EE->session->userdata('member_id');

    $this->load->library('form_validation');            

    $age = $this->input->post('age',TRUE);
    $sex = $this->input->post('sex',TRUE);
	$race = $this->input->post('race',TRUE);
    $education = $this->input->post('education',TRUE);
    $glasses = $this->input->post('glasses',TRUE);
    $color_blind = $this->input->post('color_blind',TRUE);
    $colored_sequences = $this->input->post('colored_sequences',TRUE);
    $colored_sound = $this->input->post('colored_sound',TRUE);
    $spatial_form = $this->input->post('spatial_form',TRUE);
    $netherlands = $this->input->post('netherlands',TRUE);
    $participation = $this->input->post('participation',TRUE);
    $informed_consent = $this->input->post('informed_consent',TRUE);
	$language = $this->input->post('language',TRUE);
         
    //$rules['subject_id'] = "required|integer";
	$rules['language'] = "required";
	$rules['age'] = "required|integer|max_length[3]";
	$rules['sex'] = "required|integer|max_length[1]";
	$rules['race'] = "required|integer|max_length[1]";		
	$rules['education'] = "required|integer|max_length[1]";
	$rules['glasses'] = "required|integer|max_length[1]";
	$rules['color_blind'] = "required|integer|max_length[1]";
	$rules['colored_sequences'] = "required|integer|max_length[1]";
	$rules['colored_sound'] = "required|integer|max_length[1]";
	$rules['spatial_form'] = "required|integer|max_length[1]";
	$rules['netherlands'] = "required|integer|max_length[1]";
	$rules['participation'] = "required|integer|max_length[1]";
	$rules['informed_consent'] = "required|integer|max_length[1]";


    $this->form_validation->set_rules($rules);

	if ($this->form_validation->run() == FALSE)
	{
		echo $this->form_validation->error_string;
	}
	else
	{
        // Enter information
        $data = array(
            'subject_id' => $subject_id,
			'language' => $language,
			'age' => $age,
            'sex' => $sex,
			'race' => $race,
            'education' => $education,
            'glasses' => $glasses,
            'color_blind' => $color_blind,
            'colored_sequences' => $colored_sequences,
            'colored_sound' => $colored_sound,
            'spatial_form' => $spatial_form,
            'netherlands' => $netherlands,
            'participation' => $participation,
            'informed_consent' => $informed_consent
        );
    };     
	
	$this->load->model('livetest/exp1/Exp1_model','',TRUE);
    $this->Exp1_model->questionnaire($data);
 }


//TEST CHOOSER

 function block_tracker()
{
    $this->EE =& get_instance();
	
	//First check the language
	$this->load->library('form_validation');            
    $language = $this->input->post('language',TRUE);
	$rules['language'] = "required";
	
	$this->form_validation->set_rules($rules);

	if ($this->form_validation->run() == FALSE)
	{
		echo $this->form_validation->error_string;
	}
	else
	{
		if($language == 'nederlands')
		{
			$dbtable = 'nback_blocks_exp1_ned'; 
		}
		else if($language == 'english')
		{
			$dbtable = 'nback_blocks_exp1_eng';
		}
  
		$pick_4 = Array(); // The test ids to be completed in this block

		$subject_id = $this->EE->session->userdata('member_id');

		// Get the entire list of tests
		$this->load->model('livetest/exp1/Exp1_model','',TRUE);
		// select nback_id from nback_blocks_exp1
		$all_tests = $this->Exp1_model->get_all_tests($dbtable);

		if($all_tests == FALSE)
		{ 
			echo "Sorry, there has been an internal error.  Please come back to the site later";
		} 
		else
		{
			$all_tests = $all_tests->result_array();
			//The result array stores each data point as an array within a 2D array
			//Must break the ids out of this array and put them in a 1D array

			$count_all = count($all_tests); 

			// echo "count_all: "; print_r($count_all);

			$ids_all = Array();

			for ($i = 0; $i < $count_all; $i++)
			{
				array_push($ids_all, $all_tests[$i]['nback_id']);
			}
			
			//print_r($ids_all);
			
			// Find out if subject has completed any tests
			$this->load->model('livetest/exp1/Exp1_model','',TRUE);

			// select nback_id from nback_trials where subject_id = $subject_id and completed == 1
			$completed = $this->Exp1_model->check_tests($subject_id);

			if($completed == FALSE) //FIRST TIME, CHOOSE RANDOM
			{                  
				$pick_4_rand = array_rand($ids_all, 4);
				//echo "rand: "; print_r($pick_4_rand);

				$pick_4_rand = array_values($pick_4_rand); //resets the keys in order
								
				$pick_4[0] = $ids_all[$pick_4_rand[0]];
				$pick_4[1] = $ids_all[$pick_4_rand[1]];
				$pick_4[2] = $ids_all[$pick_4_rand[2]];
				$pick_4[3] = $ids_all[$pick_4_rand[3]];

				//echo "4: "; print_r($pick_4);

				$this->load->model('livetest/exp1/Exp1_model','',TRUE);
				$data['stimuli'] = $this->Exp1_model->nback_stimuli4($pick_4, $dbtable);
								
				$testjson['nbacks'] = $data['stimuli'] -> result_array();
												
				echo json_encode($testjson);
			}
			else // Subject has already completed at least 1 test
			{							
				$completed = $completed->result_array(); //the sql result array
				$count_completed = count($completed); // the number of nback ids in that array
				$completed_all = Array();

				for ($i = 0; $i < $count_completed; $i++)
				{
					array_push($completed_all, $completed[$i]['nback_id']);
				}

				// do not count the same test twice!!!, get rid of duplicates!
				$completed_unique = array_unique($completed_all);
				$count_completed = count($completed_unique);
										  
				// echo " completed unique: "; print_r($completed_unique);
				// echo " count_completed: "; print_r($count_completed);
							  
				if ($count_completed == $count_all) // FINISHED ALL TESTS
				//Here need to compare the arrays of completed to all, and if the difference is 0, then finished
				{
					$testjson['nbacks'] = "finished";										
					echo json_encode($testjson);    
					
					// Send email to experimenter 
					//$to = "kitty.reemst@student.uva.nl";
					//$subject = "2-back series completed (all 32 tests)";
					//$txt = $subject_id;
					//$headers = "From: uvatest.info";

					//mail($to,$subject,$txt,$headers);					
				}
				else // Still tests to finish
				{
					$ids_completed = Array();
					for ($i = 0; $i < $count_completed; $i++)
					{
						array_push($ids_completed, $completed[$i]['nback_id']);
					}

					// print_r($ids_completed);

					// compare $all_tests to $completed
					// $difference = the tests that have not yet been taken
					$difference = array_diff($ids_all, $completed_unique);

					// echo " difference(ids_all, completed_unique): "; print_r($difference);

					$num_leftover = count($difference);

					// echo " #leftover ";print_r($num_leftover);

					if($num_leftover < 4)
					{
						// $pick_4 = $difference;
						$difference = array_values($difference); //restarts numbering of keys
						//print_r($difference);
						
						if($num_leftover == 3)
						{
							$this->load->model('livetest/exp1/Exp1_model','',TRUE);
							$data['stimuli'] = $this->Exp1_model->nback_stimuli3($difference, $dbtable);
						}
						elseif($num_leftover == 2)
						{
							$this->load->model('livetest/exp1/Exp1_model','',TRUE);
							$data['stimuli'] = $this->Exp1_model->nback_stimuli2($difference, $dbtable);
						}
						elseif($num_leftover == 1)
						{
							$this->load->model('livetest/exp1/Exp1_model','',TRUE);
							$data['stimuli'] = $this->Exp1_model->nback_stimuli1($difference, $dbtable);
						}

						$testjson['nbacks'] = $data['stimuli'] -> result_array();
												
						echo json_encode($testjson);
					}
					else
					{
						$pick4_keys = array_rand($difference, 4);
						$pick4_keys = array_values($pick4_keys);

						//print_r($pick4_keys);

						$pick_4[0] = $difference[$pick4_keys[0]];
						$pick_4[1] = $difference[$pick4_keys[1]];
						$pick_4[2] = $difference[$pick4_keys[2]];
						$pick_4[3] = $difference[$pick4_keys[3]];

						//echo " pick-4: ";print_r($pick_4);

						$this->load->model('livetest/exp1/Exp1_model','',TRUE);
						$data['stimuli'] = $this->Exp1_model->nback_stimuli4($pick_4, $dbtable);
																						
						$testjson['nbacks'] = $data['stimuli'] -> result_array();
												
						echo json_encode($testjson);

						//$this->load->model('livetest/exp1/Exp1_model','',TRUE);
						//$this->load->view('exp1/general_instructions', $data);
					}
										   
				}
			}
		}
        //fun: select 4 where at least one is fun > 3
        //how many have been done of each test
        // working memory
	}
}

//Page with a list of all tests
function subject_scores()
{
    $this->EE =& get_instance();
    $subject_id = $this->EE->session->userdata('member_id');

    $this->load->model('livetest/exp1/Exp1_model','',TRUE);
    $completed = $this->Exp1_model->check_test_scores($subject_id);
    //Gives the unique id from the trial_id

    $completed = $completed->result_array(); //the sql result array
    $count_completed = count($completed); // the number of nback ids in that array
    $completed_all = Array();

    for ($i = 0; $i < $count_completed; $i++)
    {
        array_push($completed_all, $completed[$i]['trial_id']);
    }

    // do not count the same test twice!!!, get rid of duplicates!
    $completed_unique = array_unique($completed_all);
    $count_completed = count($completed_unique);
                        
    //print_r($completed_unique); //returns the trial_id
    //print_r($count_completed);

    //$data['scores']
        
    $keep_scores = Array();
    $keep_ids = Array();

    //want the score and nback_id from nback_trials, where trial_id == $trial_id

    foreach($completed_unique as $trial_id)
    {
		$this->load->model('livetest/exp1/Exp1_model','',TRUE);
        $score = $this->Exp1_model->get_scores($trial_id);
        //print_r($score);
			
        $score = $score->result_array();
        //print_r($score);
        array_push($keep_scores, $score[0]['score']);
        array_push($keep_ids, $score[0]['nback_id']);
    }
      
    //print_r($keep_scores);
    //print_r($keep_ids);
        
    $data = Array();
        
    //$data['nback_ids'] = $keep_ids;
    //$data['scores'] = $keep_scores;
		
	$testjson['ids'] = $keep_ids;
	$testjson['scores'] = $keep_scores;
											
	echo json_encode($testjson);

	//$this->load->model('livetest/exp1/Exp1_model','',TRUE);
	//$this->load->view('exp1/subject_scores', $data);
}

// TAKE ONE -> Take nback test as single, by nback_id
	
function take_one_nback()
{ 
	$nback = $this->input->post('nback',TRUE);
	$language = $this->input->post('language',TRUE);
	//echo $nback;
 
    $rules['nback'] = "required";
	$rules['language'] = "required";

	$this->load->library('form_validation');            
    $this->form_validation->set_rules($rules);

    if ($this->form_validation->run() == FALSE)
	{
        echo $this->form_validation->error_string;
	}
	else
	{
		if($language == 0)
		{ 
			$table = 'nback_blocks_exp1_eng';
		}
		else if ($language == 1)
		{ 
			$table = 'nback_blocks_exp1_ned';
		}
			
		$this->load->model('livetest/exp1/Exp1_model','',TRUE);
		$query['stimuli'] = $this->Exp1_model->nback_stimuli_takeone($nback, $table); 
			
		$testjson['nbacks'] = $query['stimuli'] -> result_array();
		echo json_encode($testjson);
	}
}
	
////////////////////////////////////// SENDING DATA /////////////////////////////////////

function send_nback_data()
{
    $this->EE =& get_instance();
    $subject_id = $this->EE->session->userdata('member_id');

	$nback_id = $this->input->post('nback_id',TRUE); 
	$start_time = $this->input->post('start_time',TRUE); 
	$completed = $this->input->post('completed',TRUE);
	
	$targets = $this->input->post('targets',TRUE); 
	$responses = $this->input->post('responses',TRUE); 
	$hits = $this->input->post('hits',TRUE); 
	$misses = $this->input->post('misses',TRUE); 
	$incorrect = $this->input->post('incorrect',TRUE);
	$score = $this->input->post('score',TRUE);
	$rt_correct = $this->input->post('rt_correct',TRUE); 
	$rt_incorrect = $this->input->post('rt_incorrect',TRUE);
	$sequence = $this->input->post('sequence',TRUE);
	$image_order = $this->input->post('image_order',TRUE);
	$meta_data = $this->input->post('meta_data',TRUE); 
	$trial_id = $this->input->post('trial_id');
	
	// form_validation
	$rules['nback_id'] = "required|integer";
	$rules['start_time'] = "required";
	$rules['completed'] = "required|integer|max_length[1]";
	
	$rules['targets'] = "required|integer|max_length[3]";
	$rules['responses'] = "integer|max_length[3]";
	$rules['hits'] = "integer|max_length[3]";
	$rules['misses'] = "integer|max_length[3]";
	$rules['incorrect'] = "integer|max_length[3]";
	$rules['rt_correct'] = "integer";
	$rules['rt_incorrect'] = "integer";
	$rules['sequence'] = "required";
	$rules['image_order'] = "required";
	$rules['meta_data'] = "required";

    $rules['trial_id'] = "required";
		
	$this->load->library('form_validation');            
	$this->form_validation->set_rules($rules);
	
	if ($this->form_validation->run() == FALSE)
	{
		echo $this->form_validation->error_string;
	}
	else
	{
		$trial_data = array(
			'subject_id' => $subject_id,
			'nback_id' => $nback_id,
			'completed' => $completed,
			'targets' => $targets,
			'responses' => $responses,
			'hits' => $hits,
			'misses' => $misses,
			'incorrect' => $incorrect,
			'score' => $score,
			'rt_correct' => $rt_correct,
			'rt_incorrect' => $rt_incorrect,
			'sequence' => $sequence,
			'image_order' => $image_order,
			'meta_data' => $meta_data,
			'start_time' => $start_time
			);
	
		$this->load->model('livetest/exp1/Exp1_model','',TRUE);
		
		if($trial_id == 0)
		{
			$trial_id = $this->Exp1_model->trials($trial_data, $trial_id);
		}
		else
		{
			$this->Exp1_model->trials($trial_data, $trial_id);
		}
		
		//echo 'trial_id= '; echo $trial_id; echo ';'; 
		
		$testjson['trial_id'] = $trial_id;
											
		echo json_encode($testjson);
				
		//echo json_encode(array('trial_id'=>$trial_id,'trial_id'=>$trial_id));

	}
}	

	
} // This } is for the whole controller

?>