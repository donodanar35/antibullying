<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __Contruct(){
		parent::__Contruct();
        $this->load->helper('url');
        $this->load->library(array('ion_auth', 'form_validation','pagination'));
        $this->load->model(array('myModel','ion_auth_model'));
	}
    
	public function index()
	{
		$data['judul'] = 'Rahmatika.com - Menebarkan kebaikan kepada sesama';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );

        $data['kategori'] = $this->MyModel->kategori();
        $jumlah_data = $this->MyModel->totalArtikelIndex();
        
		$this->template('frontend/standar/bio.html',$data);   
	}

	public function tes(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		//var_dump($pengguna);
		$tes = $this->MyModel->getpengikut($pengguna->ID);
		//var_dump($tes);
		$detail = $this->MyModel->get_detailpengikut($tes->ID_PENGIKUT);
		foreach($detail->result() as $detil ){
			echo $detil->ARTIKEL + '<br/>';
			echo $detil->FIRST_NAME + '<br/>';
			echo $detil->LAST_NAME + '<br/>';
			echo $detil->ID + '<br/>';
		}
		//var_dump($detail);
	}

}