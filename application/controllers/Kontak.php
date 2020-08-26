<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontak extends CI_Controller {
    public function __Contruct(){
		parent::__Contruct();
        $this->load->helper('url','form'); 
		$this->load->library(array('ion_auth', 'form_validation'));
        $this->load->model(array('myModel','ion_auth_model'));        
    }

    public function index(){
        redirect('admin/kontak');
    }

    public function simpan(){
        $this->form_validation->set_rules('namakontak', 'Nama Kontak', 'required');
        $this->form_validation->set_rules('deskripsikontak', 'Deskrispi Kontak', 'required');

        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
            $callback = array(
				'status'=>'sukses',
				'pesan'=>'Berhasil disimpan!'
            );
            $this->MyModel->kontak_ubah($_POST);
            echo json_encode($callback);
        }
    }

}