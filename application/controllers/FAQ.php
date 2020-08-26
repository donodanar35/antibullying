<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends CI_Controller {
	public function __Contruct(){
		parent::__Contruct();
        $this->load->helper(array('url','captcha'));
        $this->load->library(array('ion_auth', 'form_validation','pagination'));
        $this->load->model(array('myModel','ion_auth_model'));
	}
    
	public function index(){
		if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
		$data['judul'] = 'FAQ';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );

		$data['tagar_populer'] = $this->MyModel->tagar_populer();
		$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
		$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
		$data['kategori'] = $this->MyModel->kategori();
		$data['faq'] = $this->MyModel->allfaq(); 
		
		$identitas = $this->MyModel->getidentitas();
		if($identitas->TEMPLATE == 'standar'){
            $this->template('frontend/standar/faq.html', $data);
        }else if($identitas->TEMPLATE == 'meranda'){	
            $this->template('frontend/meranda/faq.html', $data);
        }else{
            $this->template('frontend/standar/faq.html', $data);
        }
	}

	public function template($content, $data=null){
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

	public function buat_faq(){
		$this->form_validation->set_rules('jawaban', 'Jawaban', 'required');
		$this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
                'status' => 'gagal',
                'pesan' => validation_errors()
			);
			echo json_encode($callback);
        }else{
			$this->MyModel->buat_faq($_POST);
			$callback = array(
				'status' => 'sukses',
				'pesan' => "FAQ berhasil dibuat!"
			);
			echo json_encode($callback);
        }
	}

	public function ubah_faq(){
		$this->form_validation->set_rules('jawaban', 'Jawaban', 'required');
		$this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
                'status' => 'gagal',
                'pesan' => validation_errors()
			);
			echo json_encode($callback);
        }else{
			if(($this->MyModel->ubah_faq($_POST))>0){
				$callback = array(
					'status' => 'sukses',
					'pesan' => "FAQ berhasil disunting!"
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status' => 'gagal',
					'pesan' => "Terjadi kesalahan!"
				);
				echo json_encode($callback);
			}
        }
	}

	public function hapus_faq(){
		if(($this->MyModel->hapus_faq($_POST))>0){
			$callback = array(
				'status' => 'sukses',
				'pesan' => "FAQ berhasil dihapus!"
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status' => 'gagal',
				'pesan' => "Terjadi kesalahan!"
			);
			echo json_encode($callback);
		}
	}
	
}