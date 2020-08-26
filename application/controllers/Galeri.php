<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galeri extends CI_Controller {
	public function __Contruct(){
		parent::__Contruct();
        $this->load->helper('url');
		$this->load->library(array('ion_auth', 'form_validation'));
        $this->load->model(array('myModel','ion_auth_model'));
	}
	
	public function index()
	{
		echo 'galeri';
	}

	public function detail()
    {
		echo json_encode($this->MyModel->getGaleriById($_POST['id']));
		//var_dump(json_encode($this->MyModel->getGaleriById($_POST['id'])));
	}
	
	public function hapus()
    {
		if($this->MyModel->galeri_hapus($_POST)>0){
            header('Location: ' . base_url() . 'admin/galeri');
        }else{
            header('Location: ' . base_url() . 'admin/galeri');
		}
	}

	public function galeri_buat(){
		$this->form_validation->set_rules('judul', 'Judul', 'required|min_length[5]|max_length[160]');
		$this->form_validation->set_rules('deskripsigambar', 'Deskripsi Gambar', 'required|min_length[5]|max_length[500]');
		$this->form_validation->set_rules('pathfoto', 'Path Foto', 'required|min_length[5]|max_length[500]');
		if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
            $return = $this->MyModel->gallery_upload($_POST);
            $callback = array(
				'status'=>'sukses',
                'pesan'=>'Gambar berhasil disimpan'
            );
            echo json_encode($callback);
        }
	}
	
}