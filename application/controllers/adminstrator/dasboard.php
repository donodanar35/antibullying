<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dasboard extends CI_Controller {
    var $order = array('id_kategori' => 'desc');
    var $table = 'kategori';
    var $idq = 'id_kategori';
    var $column_order = array('nama_kategori',null);
    var $column_search = array('nama_kategori');

	public function __Contruct(){
		parent::__Contruct();
        $this->load->helper('url');
        $this->load->model('MyModel');
	}
    
    public function index(){
        echo 'dasboard';
    }

}
