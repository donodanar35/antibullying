<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include('./vendor/sastrawi/sastrawi/src/Sastrawi/Stemmer/StemmerFactory.php');
class Home extends CI_Controller {
	public function __Contruct(){
		parent::__Contruct();
		$this->load->helper('url','form'); 
		$this->load->library(array('ion_auth', 'form_validation','upload'));
		$this->load->library('StemmerFactory');
        $this->load->model(array('myModel','ion_auth_model'));
	}
	
	public function index(){
		if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
		$beranda = $this->MyModel->getidentitas();
		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
		);
		
		$data['kategori'] = $this->MyModel->kategori();
		$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
		$data['tagar_populer'] = $this->MyModel->tagar_populer();
		$data['artikel'] = $this->MyModel->getArtikelIndex(25,0);
		$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();

		$identitas = $this->MyModel->getidentitas();
		if($identitas->TEMPLATE == 'standar'){
			$this->template('frontend/standar/index.html', $data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->template('frontend/meranda/index.html', $data);
		}else{
			$this->template('frontend/standar/index.html', $data);
		}
	}

	private function template($content, $data=null){
		$identitas = $this->MyModel->getidentitas();
		$data['content'] = $this->load->view($content,$data,true);
		if($identitas->TEMPLATE == 'standar'){
			$this->load->view('frontend/standar/template',$data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->load->view('frontend/meranda/template',$data);
		}else{
			$this->load->view('frontend/standar/template',$data);
		}
	}

	public function tes(){
		$tes = new \Sastrawi\Stemmer1\StemmerFactory();
		$tes->tes();
	}
}
