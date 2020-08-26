<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Identitas extends CI_Controller {
    public function __Contruct(){
		parent::__Contruct();
        $this->load->helper('url','form'); 
		$this->load->library(array('ion_auth', 'form_validation'));
        $this->load->model(array('myModel','ion_auth_model'));        
    }

    public function index(){
        echo 'identitas';
    }

    public function simpan(){
        $this->form_validation->set_rules('namawebsite', 'Nama Website', 'required');
        $this->form_validation->set_rules('alamatwebsite', 'Alamat Website', 'required');
        $this->form_validation->set_rules('metadeskripsi', 'Meta Deskripsi', 'required');
        $this->form_validation->set_rules('metakeyword', 'Meta Keyword', 'required');
        $this->form_validation->set_rules('template', 'Template', 'required');

        if($this->form_validation->run()==false){
            $callback = array(
                'status' => 'gagal',
                'pesan' => validation_errors()
            );
            echo json_encode($callback);
        }else{
            $callback = array(
                'status' => 'sukses',
                'pesan' => 'Berhasil disimpan!'
            );
            /*if(isset($_POST['tombol'])){
                $tes = $this->MyModel->identitas_ubah($_POST);
                var_dump($tes);
            }*/
            //var_dump($_POST);
            $this->MyModel->identitas_ubah($_POST);
            echo json_encode($callback);
        }
    }
}