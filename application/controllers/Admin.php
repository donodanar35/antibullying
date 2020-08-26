<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function __Contruct(){
		parent::__Contruct();
		$this->load->helper('url');
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->model(array('MyModel','ion_auth_model'));
	}
	
	public function index()
	{
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['daftar_artikel'] = $this->MyModel->getArtikelByUsername($pengguna->ID);

		$this->load->library('ion_auth');
		$data['judul'] = 'Admin | Dasbord';
		$data['identitas'] = $this->MyModel->getidentitas();
		$this->template('admin/layout/dasbord.html',$data);
	}

	private function template($content=null, $data=null){
		if (!$this->ion_auth->logged_in())
		{
			redirect('auth/login');
		}else{
			$data['content'] = $this->load->view($content,$data,true);
			$this->load->view('admin/layout/template',$data);
		}		
	}


	public function galeri_upload(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Galeri | Upload';
		$data['identitas'] = $this->MyModel->getidentitas();		
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['pengguna'] = $pengguna->ID;

		$this->template('admin/galeri/galeri_upload.html',$data);
	}

	public function galeri(){		
		$data['judul'] = 'Admin | Galeri';
		$data['identitas'] = $this->MyModel->getidentitas();
		
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$id = $pengguna->ID;

		$jumlah_data = $this->MyModel->total_gallery($id);
		$this->load->library('pagination');
		
		$config['per_page'] = 9;
		$offset = $this->uri->segment(3);

		$config["uri_segment"] = 3;
		$config["num_links"] = 3;
		$config['base_url'] = base_url().'admin/galeri/';
		$config['total_rows'] = $jumlah_data;

		$data['galeri'] = $this->MyModel->gallery($id,$config['per_page'],$offset);

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
		$this->template('admin/galeri/galeri.html',$data);
	}

	public function profil(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Profil';
		$data['identitas'] = $this->MyModel->getidentitas();
		$data['myprofil'] = $this->MyModel->getprofil($_SESSION['user_id']);
		$this->template('admin/profil/profil.html',$data);
	}

	public function kontak(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Tentang';
			$data['identitas'] = $this->MyModel->getidentitas();
			$data['kontak'] = $this->MyModel->getkontak();
			$this->template('admin/kontak/kontak.html',$data);
		}
	}

	public function identitas(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Kategori';
			$data['identitas'] = $this->MyModel->getidentitas();
			$this->template('admin/identitas/identitas.html',$data);
		}
	}

	public function kebijakan(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Kebijakan';
			$data['identitas'] = $this->MyModel->getidentitas();
			$data['kebijakan'] = $this->MyModel->getkebijakan();
			$this->template('admin/kebijakan/kebijakan.html',$data);
		}
	}

	public function pengikut_mengikuti(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Pengikut_Mengikuti';
		$data['identitas'] = $this->MyModel->getidentitas();
				$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$this->load->model('MyModel');
		if(isset($pengguna)){
			$data['pengikut'] = $this->MyModel->getpengikut($pengguna->ID);
		}
		$this->template('admin/antibullying/antibullying_kamusgaul.html',$data);
	}

	public function antibullying(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Dataset Artikel';
			$data['identitas'] = $this->MyModel->getidentitas();
					$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$this->load->model('MyModel');
			if(isset($pengguna)){
				$data['artikel'] = $this->MyModel->dataset_artikel();
			}
			$this->template('admin/antibullying/antibullying_artikel.html',$data);
		}
	}

	public function antibullying_kamusgaul(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Kamus Gaul';
			$data['identitas'] = $this->MyModel->getidentitas();
					$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$this->load->model('MyModel');
			if(isset($pengguna)){
				$data['kamus'] = $this->MyModel->kamus_bahasagaul();
			}
			$this->template('admin/antibullying/antibullying_kamusgaul.html',$data);
		}	
	}

	public function antibullying_katadasar(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Kamus Kata Dasar';
			$data['identitas'] = $this->MyModel->getidentitas();
					$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$this->load->model('MyModel');
			if(isset($pengguna)){
				$data['kamus'] = $this->MyModel->kamus_katadasar();
			}
			$this->template('admin/antibullying/antibullying_kamuskatadasar.html',$data);
		}	
	}

	public function antibullying_stopwordlist(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Kamus Kata Dasar';
			$data['identitas'] = $this->MyModel->getidentitas();
					$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$this->load->model('MyModel');
			if(isset($pengguna)){
				$data['kamus'] = $this->MyModel->kamus_stopwordlist();
			}
			$this->template('admin/antibullying/antibullying_stopwordlist.html',$data);
		}	
	}

	public function antibullying_kelaskata(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Kelas Kata';
			$data['identitas'] = $this->MyModel->getidentitas();
					$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$this->load->model('MyModel');
			if(isset($pengguna)){
				$data['kamus'] = $this->MyModel->kamus_bahasagaul();
			}
			$this->template('admin/antibullying/antibullying_kelaskata.html',$data);
		}
	}

	public function antibullying_pengujian(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Pengujian';
			$data['identitas'] = $this->MyModel->getidentitas();
					$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$this->load->model('MyModel');
			if(isset($pengguna)){
				$data['pengujian'] = $this->MyModel->get_allpengujian();
			}
			$this->template('admin/antibullying/antibullying_pengujian.html',$data);
		}
	}

	public function antibullying_komentar(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Dataset Komentar';
			$data['identitas'] = $this->MyModel->getidentitas();
			$data['komentar'] = $this->MyModel->dataset_allkomentar();
			$this->template('admin/antibullying/antibullying_komentar.html',$data);
		}	
	}

	public function antibullying_detail($id=null){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Detail Komentar';
			$data['identitas'] = $this->MyModel->getidentitas();		
			$data['artikel'] = $this->MyModel->dataset_artikelById($id);
			$data['komentar'] = $this->MyModel->dataset_komentar($id);
			$this->template('admin/antibullying/antibullying_detail.html',$data);
		}
	}

	public function antibullying_detail_pengujian($id=null){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Antibullying | Detail Pengujian';
			$data['identitas'] = $this->MyModel->getidentitas();		
			$data['detail'] = $this->MyModel->detail_pengujian($id);
			$data['pengujian'] = $this->MyModel->is_uji($id);
			$this->template('admin/antibullying/antibullying_detail_pengujian.html',$data);
		}
	}

	public function artikel_buat(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Artikel | Buat';
		$data['identitas'] = $this->MyModel->getidentitas();

		$this->load->model('MyModel');
		$data['kategori'] = $this->MyModel->kategori(); 
		
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['pengguna'] = $pengguna->ID;

		$this->template('admin/artikel/artikel_buat.html',$data);
	}

	public function artikel_ubah($id=null){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$artikelx = $this->uri->segment(2);
		$komentarx = $this->uri->segment(3);
		if(!is_null($artikelx) && !is_null($komentarx)){
			if(is_numeric($id)){
				$data['judul'] = 'Admin | Artikel | Ubah';
				$data['identitas'] = $this->MyModel->getidentitas();			
				$data['kategori'] = $this->MyModel->kategori(); 
				$posting = $this->MyModel->postingById($id);
				$data['artikel'] = $posting;

				if (!$this->ion_auth->is_admin()){
					if($posting->ID == $pengguna->ID){
						if(!empty($data['artikel'])){
							$data['rating'] = $this->MyModel->get_rating($id);
							$data['komentar'] = $this->MyModel->get_komentarArtikel($id);	
							$this->template('admin/artikel/artikel_ubah.html',$data);		
						}else{
							redirect('error');
						}
					}else{
						redirect('error');
					}
				}else{
					if(!empty($data['artikel'])){
						$data['rating'] = $this->MyModel->get_rating($id);
						$data['komentar'] = $this->MyModel->get_komentarArtikel($id);	
						$this->template('admin/artikel/artikel_ubah.html',$data);		
					}else{
						redirect('error');
					}
				} 

			}else{
				redirect('error');
			}
		}else{
			redirect('error');
		}
	}

	public function artikel_komentar($artikel=null,$komentar=null){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$artikelx = $this->uri->segment(3);
		$komentarx = $this->uri->segment(4);
		if(!is_null($artikelx) && !is_null($komentarx)){
			if(is_numeric($artikelx) && is_numeric($komentarx)){
				$data['judul'] = 'Admin | Artikel | Komentar';
				$data['identitas'] = $this->MyModel->getidentitas();				
				$data['kategori'] = $this->MyModel->kategori(); 
				$posting = $this->MyModel->get_artikelById($artikelx);
				$data['judul'] = $posting->JUDUL;
				$data['artikel'] = $artikelx;
				$data['komentar'] = $this->MyModel->get_komentarArtikelById($komentarx);				
				if(!empty($data['komentar'])){
					$data['balasan'] = $this->MyModel->get_balasankomentarArtikelById($komentarx);
					$this->template('admin/artikel/artikel_komentar.html',$data);
				}else{
					redirect('error');
				}
			}else{
				redirect('error');
			}
		}else{
			redirect('error');
		}
	}

	public function artikel_kelola(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Artikel | Kelola';
		$data['identitas'] = $this->MyModel->getidentitas();

		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$this->load->model('MyModel');
		if(isset($pengguna)){
			if (!$this->ion_auth->is_admin()){
				$data['artikel'] = $this->MyModel->artikel($pengguna->ID);
			}else{
				$data['artikel'] = $this->MyModel->allartikel();
			} 
		}else{
			$data['artikel'] = $this->MyModel->artikel(0);
		}

		$this->template('admin/artikel/artikel_kelola.html',$data);
	}

	public function artikel_cari(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		//var_dump($_POST['cari']);
		if(! empty($_POST) && (! is_null($_POST))){
			$cari = $_POST['cari'];
		}else{
			$cari = '';
		}
		$data['judul'] = 'Admin | Artikel | Kelola';
		$data['identitas'] = $this->MyModel->getidentitas();

		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		//$this->load->model('MyModel');
		//echo $pengguna->ID;
		$data['artikel'] = $this->MyModel->artikel_cari($pengguna->ID,$cari);
		$this->template('admin/artikel/artikel_kelola.html',$data);
	}

	public function kategori(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Kategori | Kelola';
			$data['identitas'] = $this->MyModel->getidentitas();
			$this->load->model('MyModel');
			$data['kategori'] = $this->MyModel->kategori(); 
			$this->template('admin/kategori/kategori.html',$data);
		}
	}

	public function kategori_buat(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Kategori | Buat';
			$data['identitas'] = $this->MyModel->getidentitas();
			$this->load->model('MyModel');
			$data['kategori'] = $this->MyModel->kategori(); 
			$this->template('admin/kategori/kategori_buat.html',$data);
		}
	}

	public function tagar(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | #Tagar';
			$data['identitas'] = $this->MyModel->getidentitas();
			$this->load->model('MyModel');
			$data['tagar'] = $this->MyModel->alltagar(); 
			$this->template('admin/tagar/tagar.html',$data);
		}
	}

	public function faq(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | FAQ';
		$data['identitas'] = $this->MyModel->getidentitas();
		$this->load->model('MyModel');
		$data['faq'] = $this->MyModel->allfaq(); 
		$this->template('admin/faq/faq.html',$data);
	}

	public function faq_buat(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | FAQ | Buat';
		$data['identitas'] = $this->MyModel->getidentitas();
		$this->load->model('MyModel');
		$this->template('admin/faq/faq_buat.html',$data);
	}

	public function faq_ubah($id=null){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | FAQ | Ubah';
		$data['identitas'] = $this->MyModel->getidentitas();
		$this->load->model('MyModel');
		$data['faq'] = $this->MyModel->get_faq($id); 
		$this->template('admin/faq/faq_ubah.html',$data);
	}

	public function evaluasi(){
		if(!$this->ion_auth->is_admin()){
			redirect('error');
		}else{
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
			$data['judul'] = 'Admin | Evaluasi';
			$data['identitas'] = $this->MyModel->getidentitas();
			$this->load->model('MyModel');
			$data['evaluasi'] = $this->MyModel->allevaluasi();
			$data['jawaban'] = $this->MyModel->alljawaban(); 
			$data['laporan'] = $this->MyModel->statistik_jawaban();
			$data['kritiksaran'] = $this->MyModel->allkritiksaran();
			$this->template('admin/evaluasi/evaluasi.html',$data);
		}
		
	}

	public function detail_kuesioner($id=null){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Evaluasi | Detal Pertanyaan';
		$data['identitas'] = $this->MyModel->getidentitas();
		$this->load->model('MyModel');
		$data['pertanyaan'] = $this->MyModel->get_kuesionerById($id);
		$data['rentang_nilai'] = $this->MyModel->get_nilaiById($id);
		$data['jawaban'] = $this->MyModel->getjawaban($id); 
		$this->template('admin/evaluasi/evaluasi_ubah.html',$data);
	}

	public function penjawab(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Evaluasi | Responden';
		$data['identitas'] = $this->MyModel->getidentitas();
		$this->load->model('MyModel');
		$data['penjawab'] = $this->MyModel->penjawab();
		$this->template('admin/evaluasi/evaluasi_penjawab.html',$data);
	}

	public function notifikasi(){
		$data['judul'] = 'Admin | Notifikasi';
		$data['identitas'] = $this->MyModel->getidentitas();
		
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$id = $pengguna->ID;
		$this->MyModel->tandai_allnotifikasi($id);

		$jumlah_data = $this->MyModel->total_notifikasi($id);
		$this->load->library('pagination');
		
		$config['per_page'] = 9;
		$offset = $this->uri->segment(3);

		$config["uri_segment"] = 3;
		$config["num_links"] = 3;
		$config['base_url'] = base_url().'admin/notifikasi/';
		$config['total_rows'] = $jumlah_data;

		$data['daftar_notifikasi'] = $this->MyModel->getnotifikasi($id,$config['per_page'],$offset);

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
		$this->template('admin/notifikasi/notifikasi.html',$data);
	}

	public function following(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Mengikuti';
		$data['identitas'] = $this->MyModel->getidentitas();
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$mengikuti = $this->MyModel->getmengikuti($pengguna->ID);
		$data['mengikuti'] = $this->MyModel->get_daftarmengikuti($mengikuti->ID_MENGIKUTI);
		$this->template('admin/follow/following.html',$data);
	}

	public function follower(){
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);
		$data['judul'] = 'Admin | Pengikut';
		$data['identitas'] = $this->MyModel->getidentitas();
		$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
		$pengikut = $this->MyModel->get_pengikut($pengguna->ID);
		if(empty($pengikut)){
			$data['pengikut'] = $this->MyModel->get_daftarpengikut(0);
		}else{
			$data['pengikut'] = $this->MyModel->get_daftarpengikut($pengikut->ID_PENGIKUT);
		}
		//var_dump($data['pengikut']);
		$this->template('admin/follow/follower.html',$data);
	}

	public function tes(){
		//var_dump($this->MyModel->getkomentarbalasan(213));
		$artikel = $this->MyModel->getkomentarbalasan(213);
		echo $artikel->ID;
	}

}
?>
