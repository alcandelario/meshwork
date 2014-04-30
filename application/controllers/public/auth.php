<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
     protected $crop_width = 150;
     protected $crop_height = 150; 
     
    //Class-wide variable to store user object in.
     protected $the_user;
	
        function __construct()
	{
                parent::__construct();
            
                $this->load->library('form_validation');
		$this->load->helper('url');

		// Load MongoDB library instead of native db driver if required
		// $this->config->item('use_mongodb', 'ion_auth') ?
		// $this->load->library('mongo_db') :

		$this->load->database();
		$this->lang->load('auth');
		$this->load->helper('language');
                
            if( $this->ion_auth->logged_in() ) 
            {
                                  $the_user = $this->ion_auth->user()->row();
                    $this->data['the_user'] = $the_user->first_name.' '.$the_user->last_name;
                          $this->data['id'] = $the_user->id;
               @$this->data['profilephoto'] = $the_user->profilephoto_filepath;

                if($this->ion_auth->in_group("admin"))
                {
                   $this->data['is_admin'] = TRUE;
                }

                if($this->ion_auth->in_group("superuser"))
                {
                    $this->data['is_super'] = TRUE;
                }

                $this->load->vars($this->data);
            }
                        
            $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        }

	//redirect if needed, otherwise display the user list
	function index()
	{
		if (!$this->ion_auth->is_admin())
		{
			//redirect them to the home page because they must be an administrator to view this
			redirect('/','refresh');
		}
		else
		{
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			//list the users
			$this->data['users'] = $this->ion_auth->users()->result();
                        
                        foreach ($this->data['users'] as $k => $user)
			{
				$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
			}
                      
                        // redirect('messages');
                      $this->_render_page('templates/header',$this->data);
	              $this->_render_page('auth/index', $this->data);
		}
	}
        
	//log the user in
	function login()
	{
		$this->data['title'] = "Login";

		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('default_controller', 'refresh');
			}
			else
			{
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				   'id' => 'identity',
				 'type' => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				  'id' => 'password',
				'type' => 'password',
			);
                        
                        $this->data['title'] = 'Meshwork';
                        $this->_render_page('templates/header', $this->data);
                        $this->_render_page('auth/login', $this->data);
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->ion_auth->logout();

		//redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('/', 'refresh');
	}

	//change password
	function change_password()
	{
		$this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
		$this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
		$this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login', 'refresh');
		}

		$user = $this->ion_auth->user()->row();

		if ($this->form_validation->run() == false)
		{
			//display the form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
			$this->data['old_password'] = array(
				'name' => 'old',
				'id'   => 'old',
				'type' => 'password',
			);
			$this->data['new_password'] = array(
				'name' => 'new',
				'id'   => 'new',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['new_password_confirm'] = array(
				'name' => 'new_confirm',
				'id'   => 'new_confirm',
				'type' => 'password',
				'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
			);
			$this->data['user_id'] = array(
				'name'  => 'user_id',
				'id'    => 'user_id',
				'type'  => 'hidden',
				'value' => $user->id,
			);

			//render
                        $this->data['title'] = 'Change Password - Meshwork';
                        $this->_render_page('templates/header',$this->data);
			$this->_render_page('auth/change_password', $this->data);
                        $this->_render_page('templates/footer',$this->data);
		}
		else
		{
			$identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

			$change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

			if ($change)
			{
				//if the password was successfully changed
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('/', 'refresh');
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('change_password', 'refresh');
			}
		}
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');
		if ($this->form_validation->run() == false)
		{
			//setup the input
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
			);

			if ( $this->config->item('identity', 'ion_auth') == 'username' ){
				$this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
			}
			else
			{
				$this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
			}

			//set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			
                        $this->data['title'] = 'Forgot Password - Meshwork';
                        $this->_render_page('templates/header',$this->data);
                        $this->_render_page('auth/forgot_password', $this->data);
		}
		else
		{
			// get identity for that email
			$config_tables = $this->config->item('tables', 'ion_auth');
			     $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();

			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

			if ($forgotten)
			{
				//if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect("forgot_password", 'refresh');
			}
		}
	}

	//reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			//if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

			if ($this->form_validation->run() == false)
			{
				//display the form

				//set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
				'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name' => 'new_confirm',
					'id'   => 'new_confirm',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				//render
                                $this->data['title'] = 'Reset Password - Meshwork';
                                $this->_render_page('templates/header',$this->data);
				$this->_render_page('auth/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{
					//something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);
					show_error($this->lang->line('error_csrf'));
				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						//if the password was successfully changed
						$this->session->set_flashdata('message', $this->ion_auth->messages());
						$this->logout();
					}
					else
					{
						$this->session->set_flashdata('message', $this->ion_auth->errors());
						redirect('reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			//if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("forgot_password", 'refresh');
		}
	}

	//activate the user
	function activate($id, $code=false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			//redirect them to the auth page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("admin_dashboard", 'refresh');
		}
		else
		{
			//redirect them to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
			redirect("forgot_password", 'refresh');
		}
	}

	//deactivate the user
	function deactivate($id = NULL)
	{
		$id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
		$this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

		if ($this->form_validation->run() == FALSE)
		{
			// insert csrf check
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$this->data['user'] = $this->ion_auth->user($id)->row();
                        $this->_render_page('templates/header',$this->data);
			$this->_render_page('auth/deactivate_user', $this->data);
		}
		else
		{
			// do we really want to deactivate?
			if ($this->input->post('confirm') == 'yes')
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
				{
					show_error($this->lang->line('error_csrf'));
				}

				// do we have the right userlevel?
				if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect('admin_dashboard', 'refresh');
		}
	}

	//create a new user
	function create_user()
	{
		$this->data['title'] = "Create User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('/', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
		$this->form_validation->set_rules('providerID', 'Provider', 'required|xss_clean');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower(trim($this->input->post('first_name'))) . ' ' . strtolower(trim($this->input->post('last_name')) );
			$email    = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
                                     'title' => $this->input->post('title'),
				'first_name' => $this->input->post('first_name'),
				 'last_name' => $this->input->post('last_name'),
		          'providerID_users' => $this->input->post('providerID'),
                                    'street' => $this->input->post('street'),
                                      'city' => $this->input->post('city'),
                                     'state' => $this->input->post('state'),
                                   'zipcode' => $this->input->post('zipcode'),
                                     'phone' => $this->input->post('phone'),
                                   'has_car' => $this->input->post('has_car'),
                                  'about_me' => $this->input->post('about_me')
			);
                        
                        //if their is a profile photo being uploaded, validate and return the uploaded filepath
                        $additional_data = $additional_data + $this->processPhoto();
                        
                        $admin = $this->input->post('make_admin');
                        if(isset($admin) && $admin !== FALSE && !is_bool($admin))
                        {
                            // make this user an admin, They'll be made a general member by default
                            $group = array('1','2');
                        }
                        else 
                        {
                            $group = array('2');
                        }
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data,$group))
		{
                        //check to see if we are creating the user
			//redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			redirect("users", 'refresh');
		}
		else
		{
			//display the create user form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['last_name'] = array(
				'name'  => 'last_name',
				'id'    => 'last_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('last_name'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
                        $this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
                        
                        $this->load->model('providers_model');
                        $this->data['provider_select'] = $this->providers_model->get_providerNameAndIDArray();
                        
                        // get all groups
                        // $groups = $this->ion_auth->groups()->result();
                        // $data['groups'] = $this->remove_protectedUserGroups($groups);
                        // $this->data['select_groups'] = $this->_render_page('auth/select_groups',$data,true);
                        
                        $this->data['title'] = 'Create New User - Meshwork';
                        $this->_render_page('templates/header',$this->data);
			$this->_render_page('auth/create_user', $this->data);
                        $this->_render_page('templates/footer');
                }
	}
        
        //edit a user
	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() ) //|| !$this->ion_auth->is_admin())
		{
			redirect('/', 'refresh');
		}

                                $user = $this->ion_auth->user($id)->row();
                              $groups = $this->ion_auth->groups()->result_array();
                       $currentGroups = $this->ion_auth->get_users_groups($id)->result();
                $this->data['member'] = (array) $user;

		//validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
		$this->form_validation->set_rules('providerID', 'Provider', 'required|xss_clean');
		
		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}
                        
                        $username = strtolower(trim($this->input->post('first_name'))) . ' ' . strtolower(trim($this->input->post('last_name')) );
			
			$data = array(
                                  'username' => $username,
				     'title' => $this->input->post('title'),
			        'first_name' => $this->input->post('first_name'),
				 'last_name' => $this->input->post('last_name'),
                                     'email' => $this->input->post('email'),
		          'providerID_users' => $this->input->post('providerID'),
                                    'street' => $this->input->post('street'),
                                      'city' => $this->input->post('city'),
                                     'state' => $this->input->post('state'),
                                   'zipcode' => $this->input->post('zipcode'),
                                     'phone' => $this->input->post('phone'),
                                   'has_car' => $this->input->post('has_car'),
                                  'about_me' => $this->input->post('about_me')
			);
                        
                        //if their is a profile photo being uploaded, validate and return the uploaded filepath
                        $data = $data + $this->processPhoto($user);

			//Update the groups user belongs to
                        $admin = $this->input->post('make_admin');
                        if(isset($admin) && $admin !== FALSE && !is_bool($admin))
                        {
                            // make this user an admin, They'll be made a general member by default
                            $this->ion_auth->add_to_group('1',$id);
                        }
                        else
                        {
                            // make them a standard user
                            $this->ion_auth->remove_from_group(array('1'), $id);
                            $this->ion_auth->add_to_group('2', $id);
                            
                        }
                      
                        // not being used in this application
//			$groupData = $this->input->post('groups');
//
//			if (isset($groupData) && !empty($groupData)) {
//
//				$this->ion_auth->remove_from_group('', $id);
//
//				foreach ($groupData as $grp) {
//					$this->ion_auth->add_to_group($grp, $id);
//				}
//
//			}

			//update the password if it was posted
			if ($this->input->post('password'))
			{
                            $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                            $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                                    $data['password'] = $this->input->post('password');
                            $data['password_confirm'] = $this->input->post('password_confirm');
			}

			if ($this->form_validation->run() === TRUE)
			{
                            $this->ion_auth->update($user->id, $data);

                            //check to see if we are creating the user
                            //redirect them back to the admin page
                            $this->session->set_flashdata('message', "User Saved");
                            redirect("users/view/".$id,'refresh');
			}
		}

		//display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);
                $this->load->model('providers_model');
                $this->data['provider_select'] = $this->providers_model->get_providerNameAndIDArray();
                
                $this->data['title'] = 'Update '.$user->first_name.' '.$user->last_name.'\'s Profile - Meshwork';
                $this->_render_page('templates/header',$this->data);
		$this->_render_page('auth/edit_user', $this->data);
                $this->_render_page('templates/footer');
                
        }

        
        private function processPhoto($user = NULL)
        {
           $data['new_user'] = TRUE;
           
                    $results = $this->validate_photo($data,$user);
                      $photo = $results['photo'];
             $photo_filename = $results['filename'];
                
                if($photo === TRUE){
                    return array('profilephoto_filepath' => $photo_filename);
                }
                else{
                    return array();
                }
        }
        
        private function validate_photo($data,$user = NULL)
        {
            /*
             * Note: PROFILEPHOTO_BASEPATH defined in /config/constants.php
             */
            // grab basic user name data if they aren't new
            if(isset($user))
            {
                     $first_name = preg_replace('/\s+/', '', $user->first_name);
                      $last_name = preg_replace('/\s+/', '', $user->last_name);
                 $existing_photo = $user->profilephoto_filepath;
                       $new_user = FALSE;
            }
            else 
            {
                $first_name = preg_replace('/\s+/', '', $_POST['first_name']);
                 $last_name = preg_replace('/\s+/', '', $_POST['last_name']);
                  $new_user = TRUE;
            }
            
            // grab info on the file the user just uploaded
            $filename = trim($_FILES['profile_photo']['name']);
            if(!empty($filename))
            {           
                // grab the uploaded file's file extension before renaming it
                $ext = explode(".",$_FILES['profile_photo']['name']);
                $ext = $ext[1];
            }
            else 
            {
                 $ext = FALSE;
            }
            // set the new filename for the uploaded file
            $this->load->helper('string');
            $rand = random_string('alnum',16);
            if($ext !== FALSE)
            {
               $photo_filename = $first_name.$last_name."_".$rand.".".$ext;
            }
            else {
                $photo_filename = NULL;
            }
            
            if(!empty($photo_filename)){
                // overwrite the file hame so we don't upload  to the photo directory
                $_FILES['profile_photo']['name'] = $photo_filename;

                // try to copy the new photo to the profile photos directory and delete the old one if applicable
                if($new_user !== FALSE || empty($existing_photo))
                {
                    $delete = NULL;
                }
                else
                {
                    $delete = $existing_photo;
                }
                $photo = $this->do_upload("profile_photo",$photo_filename,$new_user,$delete);
                
                if(isset($photo) && $photo['upload_data']['is_image'] !== FALSE){
                    $fname_crop = $photo['upload_data']['file_name'];
                    $fpath_crop = $photo['upload_data']['file_path'];
                    $photo = TRUE;
                }
                else
                {
                    // photo didn't upload correctly for some reason
                    $photo = FALSE;
                }
            }
            else {
                // no photo was uploaded
                $photo = "NULL";
            }
            
            // crop the photo if necessary
            if(isset($photo) && $photo !== FALSE && $photo !== "NULL")
            {
                $this->crop_photo($fname_crop,$fpath_crop);
            }
                // return the successfully 
                return array('photo' => $photo, 'filename' => $photo_filename);
        }
        
        private function do_upload($userfile = '',$new_photo,$new_user = FALSE,$orig_photo = NULL)
	{
             $this->load->helper('file');
		
                  $config['upload_path'] = ROOTDIR.PROFILEPHOTO_BASEPATH;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		     $config['max_size'] = '0';
		   $config['max_width']  = '0';
		  $config['max_height']  = '0';

		$this->load->library('upload', $config);
               
                $result = $this->upload->do_upload($userfile);
		if ( ! $result)
		{
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
                    // grab the upload data
                    $upload = $this->upload->data();
                    
                    if(!empty($orig_photo))
                    {
                        // delete the old file
                        $filepath = ROOTDIR.PROFILEPHOTO_BASEPATH;
                        $original = $filepath.$orig_photo;
                        @unlink($original);
                        // just in case, rename the new file to whatever the old one was. In some cases it may be redundant
//                        $old = explode(".",$orig_photo);
//                        $new = explode(".",$new_photo);
//                        $old_name = $filepath.$upload['file_name'];
//                        //build the new filename such as oldfilename.newfileextension
//                        $new_name = $filepath.$old[0].".".$new[1];
//                        @rename($old_name,$new_name);
//                        $upload['file_name'] = $old[0].".".$new[1];
                    }
                    
                    return array('upload_data' => $upload);               
		}
	}
        
        private function crop_photo($filename,$filepath)
        {
              $size = getimagesize($filepath.$filename);
             $width = $size[0];
            $height = $size[1];
            
             $crop_width = $this->crop_width;
            $crop_height = $this->crop_height;
            
            // we want a square aspect ratio no matter what
            if($height < $this->crop_height)
            {
                  $crop_width = $height;
                 $crop_height = $height; 
            }
            elseif($width < $this->crop_width)
            {
                 $crop_width = $width;
                $crop_height = $width;
            }
            else
            {
                $resize = TRUE;
            }
            // load the image library to resize pics that are too big
            $this->load->library('image_lib');
            
            //config the image manipulation library as needed
            $config['image_library'] = 'gd2';
            
            // $config['library_path'] = '/usr/X11R6/bin/';
             $config['source_image'] = $filepath.$filename;
                    $config['width'] = $crop_width;
                   $config['height'] = $crop_height;
                   $config['x_axis'] = 0;
                   $config['y_axis'] = 0;
                   
            // do we need to resize first before cropping?
            if(isset($resize))
            {
                // we need to resize our image based on the smaller of the 
                // two dimensions
                if($height > $width)
                {
                    // our photo must be a portrait so lets resize based on image width
                    $config['master_dim'] = 'width';
                }
                elseif($width > $height)
                {
                    // our photo must be a landscape so lets resize based on image height
                      $config['master_dim'] = 'height';
                }
                  $this->image_lib->initialize($config);
                $resize = $this->image_lib->resize();

            }
            
            // now set up for cropping
            $config['maintain_ratio'] = FALSE;
            $this->image_lib->initialize($config);
            $success = $this->image_lib->crop();
        }
        
        private function remove_protectedUserGroups($groups)
        {
            foreach($groups as $key => $group)
            {
                if(stripos($group->name, "superuser") !== FALSE)
                {
                    unset($groups[$key]);
                }
            }
            
            return $groups;
        }

	// create a new group
	function create_group()
	{
		$this->data['title'] = $this->lang->line('create_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('admin_dashboard', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

		if ($this->form_validation->run() == TRUE)
		{
			$new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
			if($new_group_id)
			{
				// check to see if we are creating the group
				// redirect them back to the admin page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("admin_dashboard", 'refresh');
			}
		}
		else
		{
			//display the create group form
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['group_name'] = array(
				'name'  => 'group_name',
				'id'    => 'group_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('group_name'),
			);
			$this->data['description'] = array(
				'name'  => 'description',
				'id'    => 'description',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('description'),
			);

                        $this->_render_page('templates/header',$this->data);
			$this->_render_page('auth/create_group', $this->data);
		}
	}

	//edit a group
	function edit_group($id)
	{
		// bail if no group id given
		if(!$id || empty($id))
		{
			redirect('admin_dashboard', 'refresh');
		}

		$this->data['title'] = $this->lang->line('edit_group_title');

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
		{
			redirect('admin_dashboard', 'refresh');
		}

		$group = $this->ion_auth->group($id)->row();

		//validate form input
		$this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');
		$this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

		if (isset($_POST) && !empty($_POST))
		{
			if ($this->form_validation->run() === TRUE)
			{
				$group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

				if($group_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}
				redirect("admin_dashboard", 'refresh');
			}
		}

		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		//pass the user to the view
		$this->data['group'] = $group;

		$this->data['group_name'] = array(
			'name'  => 'group_name',
			'id'    => 'group_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_name', $group->name),
		);
		$this->data['group_description'] = array(
			'name'  => 'group_description',
			'id'    => 'group_description',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('group_description', $group->description),
		);
                $this->_render_page('templates/header',$this->data);
		$this->_render_page('auth/edit_group', $this->data);
	}


	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $render=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $render);

		if ($render !== FALSE) 
                {
                    return $view_html;
                }
        }

}

