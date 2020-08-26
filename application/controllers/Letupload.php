<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Letupload extends CI_Controller {
    var $gallery_path;
    var $gallery_path_url;

    public function __Contruct(){
		parent::__Contruct();
        $this->load->helper(array('url','form','file','html')); 
		$this->load->library(array('ion_auth', 'form_validation','upload'));
        $this->load->model(array('myModel','ion_auth_model'));
        $this->gallery_path = realpath(APPATH . '../images');
        $this->gallery_path_url = base_url('asset/images/profil');
    }

    public function index(){
        $this->load->view('admin/tes/letupload.html');
    }

    function upload_file() {
        //upload file
        $config['upload_path'] = './images/';
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
                        echo base_url() .'images/' . $_FILES['file']['name'];
                    }
                }
            }
        } else {
            echo 'Please choose a file';
        }
    }

}