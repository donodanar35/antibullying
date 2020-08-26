<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {
    var $order = array('id_kategori' => 'desc');
    var $table = 'kategori';
    var $idq = 'id_kategori';
    var $column_order = array('nama_kategori',null);
    var $column_search = array('nama_kategori');

    public function __Contruct(){
		parent::__Contruct();
		$this->load->helper('url');
		$this->load->library(array('ion_auth', 'form_validation'));
        $this->load->model(array('myModel','ion_auth_model'));        
	}
	

    public function index(){
        if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
        $kategori="";
        $data['judul'] = 'Rahmatika.com - Menebarkan kebaikan kepada sesama';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );
        $data['bykategori'] = $kategori;

        $data['tagar_populer'] = $this->MyModel->tagar_populer();
        $data['kategori'] = $this->MyModel->kategori();
        $data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $jumlah_data = $this->MyModel->totalArtikelByKategori($kategori);
        
		$this->load->library('pagination');

        $config['per_page'] = 9;
        $offset = $this->uri->segment(4);
        
		$config["uri_segment"] = 4;
		$config["num_links"] = 5;
		$config['base_url'] = base_url()."kategori/cari/$kategori" ;
		$config['total_rows'] = $jumlah_data;

        $data['artikel'] = $this->MyModel->artikel_byKategori($kategori,$config['per_page'],$offset);
        $data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();

		// Membuat Style pagination untuk BootStrap v4
		$config['first_link']       = 'Terawal';
        $config['last_link']        = 'Terakhir';
        $config['next_link']        = 'Berikutnya';
        $config['prev_link']        = 'Sebelumnya';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';

		$this->pagination->initialize($config);
        
        $identitas = $this->MyModel->getidentitas();
		if($identitas->TEMPLATE == 'standar'){
			$this->template('frontend/standar/bykategori.html', $data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->template('frontend/meranda/bycategories.html', $data);
		}else{
			$this->template('frontend/standar/bykategori.html', $data);
		}
    }

    public function cari($kategori=null){
        if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
		$data['judul'] = 'Rahmatika.com - Menebarkan kebaikan kepada sesama';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );
        $data['bykategori'] = $kategori;

        $data['tagar_populer'] = $this->MyModel->tagar_populer();
        $data['kategori'] = $this->MyModel->kategori();
        $data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $jumlah_data = $this->MyModel->totalArtikelByKategori($kategori);
        
		$this->load->library('pagination');

        $config['per_page'] = 9;
        $offset = $this->uri->segment(4);
        
		$config["uri_segment"] = 4;
		$config["num_links"] = 5;
		$config['base_url'] = base_url()."kategori/cari/$kategori" ;
		$config['total_rows'] = $jumlah_data;

        $data['artikel'] = $this->MyModel->artikel_byKategori($kategori,$config['per_page'],$offset);
        $data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();

		// Membuat Style pagination untuk BootStrap v4
		$config['first_link']       = 'Terawal';
        $config['last_link']        = 'Terakhir';
        $config['next_link']        = 'Berikutnya';
        $config['prev_link']        = 'Sebelumnya';
        $config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav></div>';
        $config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']    = '</span></li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
        $config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['prev_tagl_close']  = '</span>Next</li>';
        $config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['first_tagl_close'] = '</span></li>';
        $config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';

		$this->pagination->initialize($config);
        
        $identitas = $this->MyModel->getidentitas();
		if($identitas->TEMPLATE == 'standar'){
			$this->template('frontend/standar/bykategori.html', $data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->template('frontend/meranda/bycategories.html', $data);
		}else{
			$this->template('frontend/standar/bykategori.html', $data);
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

    public function getkategori(){
        echo json_encode($this->MyModel->kategoriById($_POST['idx']));
    }

    public function kategori_buat(){
        $this->form_validation->set_rules('namakategori', 'Nama Kategori', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
            $cek = 0;
            $cek = $this->MyModel->cek_kategori();
            if($cek==0){
                $callback = array(
                    'status'=>'sukses',
                    'pesan'=>'berhasil disimpan'
                );
                $this->MyModel->kategori_buat();
                echo json_encode($callback);
            }else{
                $callback = array(
                    'status'=>'gagal',
                    'pesan'=>'kategori sudah ada!'
                );
                echo json_encode($callback);
            }
        }
    }
    
    public function tes()
    {
        //$this->MyModel->artikel_byKategori($kategori,$config['per_page'],$offset);
        var_dump($this->uri->segment(3));
    }

    public function kategori_ubah(){
        $this->form_validation->set_rules('namakategori', 'Kategori', 'required');
        $this->form_validation->set_rules('featured', 'FEATURED', 'required'); 
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
            $callback = array(
				'status'=>'sukses',
				'pesan'=>'berhasil disimpan'
            );
            $this->MyModel->kategori_ubah($_POST);
            echo json_encode($callback);
        }
    }

    public function kategori_hapus(){
        if($this->MyModel->kategori_hapus($_POST)>0){
            header('Location: ' . base_url() . 'admin/kategori');
        }else{
            header('Location: ' . base_url() . 'admin/kategori');
        }
    }
  
}
