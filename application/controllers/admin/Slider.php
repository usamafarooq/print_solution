<?php
		    class Slider extends MY_Controller{

		    	public function __construct()
	    {
	        parent::__construct();
	        $this->load->model('Slider_model');
	        $this->module = 'slider';
	        $this->user_type = $this->session->userdata('user_type');
	        $this->id = $this->session->userdata('user_id');
	        $this->permission = $this->get_permission($this->module,$this->user_type);
	    }public function index()
		{
			if ( $this->permission['view'] == '0' && $this->permission['view_all'] == '0' ) 
			{
				redirect('admin/home');
			}
			$this->data['title'] = 'Slider';
			if ( $this->permission['view_all'] == '1'){$this->data['slider'] = $this->Slider_model->all_rows('slider');}
			elseif ($this->permission['view'] == '1') {$this->data['slider'] = $this->Slider_modelget_rows('slider',array('user_id'=>$this->id));}
			$this->data['permission'] = $this->permission;
			$this->load->template('admin/slider/index',$this->data);
		}public function create()
		{
			if ( $this->permission['created'] == '0') 
			{
				redirect('admin/home');
			}
			$this->data['title'] = 'Create Slider';$this->load->template('admin/slider/create',$this->data);
		}
		public function insert()
		{
			if ( $this->permission['created'] == '0') 
			{
				redirect('admin/home');
			}
			$data = $this->input->post();
			$data['user_id'] = $this->session->userdata('user_id');$config['upload_path']          = './uploads/';
					                $config['allowed_types']        = 'jpg|png|gif|mp4';
					                $config['max_size']             = 5000;
					                $config['max_width']            = 5024;
					                $config['max_height']           = 4768;

					                $this->load->library('upload', $config);

					                if ( $this->upload->do_upload('File'))
					                {
					                        $data['File'] = '/uploads/'.$this->upload->data('file_name');
					                }
					                else{
					                	
					                	print_r($this->upload->display_errors());die;
					                }
					                $id = $this->Slider_model->insert('slider',$data);
			if ($id) {
				redirect('admin/slider');
			}
		}public function edit($id)
		{
			if ($this->permission['edit'] == '0') 
			{
				redirect('admin/home');
			}
			$this->data['title'] = 'Edit Slider';
			$this->data['slider'] = $this->Slider_model->get_row_single('slider',array('id'=>$id));$this->load->template('admin/slider/edit',$this->data);
		}

		public function update()
		{
			if ( $this->permission['edit'] == '0') 
			{
				redirect('admin/home');
			}
			$data = $this->input->post();
			$id = $data['id'];
			unset($data['id']);$config['upload_path']          = './uploads/';
					                $config['allowed_types']        = 'jpg|png.gif|mp4';
					                $config['max_size']             = 5000;
					                $config['max_width']            = 5024;
					                $config['max_height']           = 4768;

					                $this->load->library('upload', $config);

					                if ( $this->upload->do_upload('File'))
					                {
					                        $data['File'] = '/uploads/'.$this->upload->data('file_name');
					                }
					                $id = $this->Slider_model->update('slider',$data,array('id'=>$id));
			if ($id) {
				redirect('admin/slider');
			}
		}public function delete($id)
		{
			if ( $this->permission['deleted'] == '0') 
			{
				redirect('admin/home');
			}
			$this->Slider_model->delete('slider',array('id'=>$id));
			redirect('admin/slider');
		}}