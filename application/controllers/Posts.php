<?php

	class Posts extends CI_Controller{
		public function index($offset = 0){	
			// Pagination Config	
			$pageconfig['base_url'] = base_url() . 'posts/index/';
			$pageconfig['total_rows'] = $this->db->count_all('posts');
			$pageconfig['per_page'] = 3;
			$pageconfig['uri_segment'] = 3;
			$pageconfig['attributes'] = array('class' => 'pagination-link');

			// Init Pagination
			$this->pagination->initialize($pageconfig);

			$data['title'] = 'All Posts';

			$data['posts'] = $this->post_model->get_posts(FALSE, $pageconfig['per_page'], $offset);

			$this->load->view('templates/header');
			$this->load->view('posts/index', $data);
			$this->load->view('templates/footer');
		}

		public function view($slug = NULL){
			$data['post'] = $this->post_model->get_posts($slug);
			$post_id = $data['post']['id'];
		
			if(empty($data['post'])){
				show_404();
			}
			$data['user_data'] = $this->user_model->get_firstname_lastname( $data['post']['user_id']);
			//print_r($data['user_data']); exit;
			$data['first_name'] = $data['user_data']['first_name'];
			$data['last_name'] = $data['user_data']['last_name'];
			
			$data['title'] = $data['post']['title'];
		
			$data['body'] = $data['post']['body'];
			$data['created_at'] = $data['post']['created_at'];
			$data['updated_at'] = $data['post']['updated_at'];
			$data['post_image'] = $data['post']['post_image'];
			$name = $data['post_image'];
			
		
			
			$this->load->view('templates/header');
			$this->load->view('posts/view', $data);
			$this->load->view('templates/footer');
		}

		public function create(){

			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}
			$foldername= $this->session->userdata('username').$this->session->userdata('user_id');

			$data['title'] = 'Create Post';

			$data['categories'] = $this->post_model->get_categories();

			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('body', 'Body', 'required');

			if($this->form_validation->run() === FALSE){
				$this->load->view('templates/header');
				$this->load->view('posts/create', $data);
				$this->load->view('templates/footer');
			} else {
				// Upload Image

				$fileconfig['upload_path'] = './files/images';
				$fileconfig['allowed_types'] = 'gif|jpg|png';
				$fileconfig['max_size'] = '2048';
				
				$this->load->library('upload', $fileconfig);

				$this->upload->initialize($fileconfig);
				if(! $this->upload->do_upload('userfile')){
					$errors = array('error' => $this->upload->display_errors());
			
					$this->load->view('posts/create', $errors);
			// Set message
				$this->session->set_flashdata('login_failed', 'File you uploaded exceeds the size limit');
					
			} else {

				require 'app/start.php';
			$data = array('upload_data' => $this->upload->data());
			$name = $_FILES['userfile']['name'];
			$tmp_file_path = "./files/images/{$name}";
			
						$s3->putObject([
							'Bucket' => $config['s3']['bucket'],
							'Key' => "{$foldername}/{$name}",
							'Body' => fopen($tmp_file_path, 'rb'),
							'ACL' => 'public-read'
							]);

						unlink($tmp_file_path);
						
					
					
					$post_image = $_FILES['userfile']['name'];
					//echo "hi there".$post_image; exit;
					$this->post_model->create_post($post_image);
				// Set message
				$this->session->set_flashdata('post_created', 'Your post has been created');

			 }

				
				redirect('posts');
			}
		}

		public function delete($id){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}
			$foldername= $this->session->userdata('username').$this->session->userdata('user_id');
			$post_name = $this->post_model->get_postnamebyid($id);
			//print_r($post_name); exit;
			require 'app/start.php';
			
			//print_r($result); exit;
		//	$this->post_model->delete_post($id);
			$result = $s3->deleteObject(array(
   			 'Bucket' => $config['s3']['bucket'],
   			 'Key'    => "{$foldername}/{$post_name}",
			));

			//print_r($result); exit;
			$this->post_model->delete_post($id);

			// Set message
			$this->session->set_flashdata('post_deleted', 'Your post has been deleted');

			redirect('posts');
		}

		public function edit($slug){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$data['post'] = $this->post_model->get_posts($slug);

			// Check user
			if($this->session->userdata('user_id') != $this->post_model->get_posts($slug)['user_id']){
				redirect('posts');

			}

			$data['categories'] = $this->post_model->get_categories();

			if(empty($data['post'])){
				show_404();
			}

			$data['title'] = 'Edit Post';

			$this->load->view('templates/header');
			$this->load->view('posts/edit', $data);
			$this->load->view('templates/footer');
		}

		public function update(){
			// Check login
			if(!$this->session->userdata('logged_in')){
				redirect('users/login');
			}

			$this->post_model->update_post();

			// Set message
			$this->session->set_flashdata('post_updated', 'Your post has been updated');

			redirect('posts');
		}
	}