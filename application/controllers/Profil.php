<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {
    var $gallery_path;
    var $gallery_path_url;

    public function __Contruct(){
		parent::__Contruct();
        $this->load->helper('url','form'); 
		$this->load->library(array('ion_auth', 'form_validation','upload'));
        $this->load->model(array('myModel','ion_auth_model'));
    }

    public function index(){
        echo 'profil';
    }

    function upload_file() {
        //upload file
        $config['upload_path'] = './images/profil/';
        $config['allowed_types'] = '*';
        $config['max_filename'] = '255';
        $config['encrypt_name'] = FALSE;
        $config['max_size'] = '1024'; //1 MB

        if (isset($_FILES['file']['name'])) {
            if (0 < $_FILES['file']['error']) {
                echo 'Error during file upload' . $_FILES['file']['error'];
            } else {
                if (file_exists('uploads/' . $_FILES['file']['name'])) {
                    echo 'File already exists : uploads/' . $_FILES['file']['name'];
                } else {
                    $this->load->library('upload', $config);
                    if (!$this->upload->do_upload('file')) {
                        echo $this->upload->display_errors();
                    } else {
                        //echo 'File successfully uploaded : uploads/' . $_FILES['file']['name'];
                        echo 'images/profil/' . $_FILES['file']['name'];
                    }
                }
            }
        } else {
            echo 'Please choose a file';
        }
    }
    
    public function simpan(){
        $this->form_validation->set_rules('firstname', 'Nama depan', 'required');
        $this->form_validation->set_rules('lastname', 'Nama belakang', 'required');
        $this->form_validation->set_rules('company', 'Perusahaan', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('confirmpassword', 'Confirm password', 'required');
        $this->form_validation->set_rules('phone', 'No telp', 'required');
        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('aboutme', 'About me', 'required');

        if($_POST['password'] == $_POST['confirmpassword']){
            $config['upload_path'] = './images/galeri/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2000;
            $config['max_width'] = 1500;
            $config['max_height'] = 1500;
 
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
                $this->MyModel->pengguna_ubah($_POST);
                $this->MyModel->users_ubah($_POST);
                echo json_encode($callback);
            }
        }else{
            $callback = array(
                'status' => 'gagal',
                'pesan' => 'Password tidak valid!'
            );
            echo json_encode($callback);
        }
    }
}