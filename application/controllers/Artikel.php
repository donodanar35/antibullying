<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel extends CI_Controller {
	public function __Contruct(){
		parent::__Contruct();
        $this->load->helper('url');
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
		$data['judul'] = 'Rahmatika.com - Menebarkan kebaikan kepada sesama';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );

        $data['tagar_populer'] = $this->MyModel->tagar_populer();
        $data['kategori'] = $this->MyModel->kategori();
        $data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $jumlah_data = $this->MyModel->totalArtikelIndex();
        
		$this->load->library('pagination');
		
		$config['per_page'] = 9;
		$offset = $this->uri->segment(3);

		$config["uri_segment"] = 3;
		$config["num_links"] = 5;
		$config['base_url'] = base_url().'artikel/index';
		$config['total_rows'] = $jumlah_data;

        $data['artikel'] = $this->MyModel->getArtikelIndex($config['per_page'],$offset);
        $data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
		$data['artikel_trending'] = $this->MyModel->getArtikelByTrending();

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
			$this->template('frontend/standar/kategori.html', $data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->template('frontend/meranda/categories.html', $data);
		}else{
			$this->template('frontend/standar/kategori.html', $data);
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

    function getIP() {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif(isset($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
        else $ip = "0";
        return $ip;
    }

    public function komentar(){
        //var_dump($_POST);
        if($_POST['idpengguna'] == 0){
            $callback = array(
                'status' => 'gagal',
                'pesan' => 'Harap login untuk memberikan komentar!',
                'id_posting' => $_POST['idposting']
            );
        }else{
            $this->form_validation->set_rules('komentar', 'Komentar', 'required');
            if($this->form_validation->run()==false){
                $callback = array(
                    'status' => 'gagal',
                    'pesan' => "Harap mengisi komentar",
                    'id_posting' => $_POST['idposting']
                );
            }else{
                $this->MyModel->komentar($_POST['idpengguna'], $_POST['idposting'],$_POST['komentar']);
                $artikel = $this->MyModel->get_artikelById($_POST['idposting']);
                $pengguna = $this->MyModel->getPengguna($_POST['idpengguna']);
                $timezone = new DateTimeZone('Asia/Jakarta');
                $tgl = new DateTime();
                $tgl->setTimeZone($timezone);
                $url_photo = "";
                if($pengguna->PHOTO_PROFIL<>""){
                    $url_photo = base_url($pengguna->PHOTO_PROFIL);
                }else{
                    $url_photo = base_url('asset/logo/user.png');
                }
                if($artikel->ID<>$_POST['idpengguna']){
                    $notifikasi = array(
                        "ID" => $artikel->ID,
                        "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                        "NOTIFIKASI" => '
                            <h5><a href="'.base_url('admin/artikel_ubah/') . $_POST['idposting'] .'">'. $artikel->JUDUL .'</a></h5>
                            <div class="media">
                                <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                                '. $tgl->format('Y-m-d H:i:s') .' | '. $pengguna->FIRST_NAME . ' ' . $pengguna->LAST_NAME .' berkomentar: <br/> '. $_POST['komentar'] .'
                            </div>
                        ',
                        "AKTIF" => 'Y',
                        "DIBACA" => "N"
                    );
                    $this->MyModel->notifikasi_buat($notifikasi);
                }
                $callback = array(
                    'status' => 'sukses',
                    'pesan' => 'Terima kasih telah memberikan tanggapan!',
                    'id_posting' => $_POST['idposting']
                );
            }
        }
        echo json_encode($callback);
    }

    public function laporkan_komentar(){
        $this->form_validation->set_rules('pelanggaran', 'Pelanggaran', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
                'status' => 'gagal',
                'pesan' => "Harap pilih jenis pelanggaran!"
            );
        }else{
            $lapor = $this->MyModel->laporkan_komentar($_POST);
            $callback = array(
                'status' => 'sukses',
                'pesan' => "Terima kasih telah membantu kami dalam meningkatkan kualitas konten!"
            );

                    $komentar = $this->MyModel->getkomentar($_POST['idkomentar']);
                    $artikel = $this->MyModel->get_artikelById($_POST['idposting']);
                    $timezone = new DateTimeZone('Asia/Jakarta');
                    $tgl = new DateTime();
                    $tgl->setTimeZone($timezone);
                    $url_photo = base_url('asset/logo/user.png');
                    $notifikasi = array(
                        "ID" => $artikel->ID,
                        "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                        "NOTIFIKASI" => '
                            <h5><a href="'.base_url('admin/artikel_ubah') . '/' . $artikel->ID_POSTING .'"> Anda mendapatkan laporan pelanggaran '. $_POST['pelanggaran'] .'</a></h5>
                            <div class="media">
                                    <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                                    '. $tgl->format('Y-m-d H:i:s') .' | Seseorang telah melaporkan terjadinya pelanggaran '. $_POST['pelanggaran'] .' pada komentar: &nbsp; <br/> '. $komentar->KOMENTAR .'
                            </div>
                        ',
                        "AKTIF" => 'Y',
                        "DIBACA" => "N"
                    );
                    $this->MyModel->notifikasi_buat($notifikasi);    

        }
        echo json_encode($callback);
    }

    public function laporkan_komentar_balasan(){
        $this->form_validation->set_rules('pelanggaran', 'Pelanggaran', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
                'status' => 'gagal',
                'pesan' => "Harap pilih jenis pelanggaran!"
            );
        }else{
            $lapor = $this->MyModel->laporkan_komentar_balasan($_POST);
            $callback = array(
                'status' => 'sukses',
                'pesan' => "Terima kasih telah membantu kami dalam meningkatkan kualitas konten!"
            );

            $komen = $this->MyModel->getkomentarbalasan($_POST['idbalasan']);
            $artikel = $this->MyModel->get_artikelById($_POST['idposting']);
            $timezone = new DateTimeZone('Asia/Jakarta');
            $tgl = new DateTime();
            $tgl->setTimeZone($timezone);
            $url_photo = base_url('asset/logo/user.png');
            $notifikasi = array(
                "ID" => $artikel->ID,
                "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                "NOTIFIKASI" => '
                    <h5><a href="'.base_url('admin/artikel_komentar') . '/' . $artikel->ID_POSTING .'/'. $komen->ID_KOMENTAR .'"> Anda mendapatkan laporan pelanggaran '. $_POST['pelanggaran'] .'</a></h5>
                    <div class="media">
                            <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                            '. $tgl->format('Y-m-d H:i:s') .' | Seseorang telah melaporkan terjadinya pelanggaran '. $_POST['pelanggaran'] .' pada komentar: &nbsp; <br/> '. $komen->KOMENTAR .' &nbsp;
                    </div>
                ',
                "AKTIF" => 'Y',
                "DIBACA" => "N"
            );
            $this->MyModel->notifikasi_buat($notifikasi);

        }
        echo json_encode($callback);
    }

    public function balas_komentar(){
        if($_POST['idpengguna'] == 0){
            $callback = array(
                'status' => 'gagal',
                'pesan' => 'Harap login untuk memberikan komentar!',
                'id_posting' => $_POST['idkomentar']
            );
        }else{
            $this->form_validation->set_rules('komentar', 'Komentar', 'required');
            if($this->form_validation->run()==false){
                $callback = array(
                    'status' => 'gagal',
                    'pesan' => "Harap isi balasan komentar",
                    'id_posting' => $_POST['idkomentar']
                );
            }else{
                $this->MyModel->balas_komentar($_POST['idpengguna'], $_POST['idkomentar'],$_POST['komentar']);
                $callback = array(
                    'status' => 'sukses',
                    'pesan' => 'Terima kasih telah memberikan balasan tanggapan!',
                    'id_posting' => $_POST['idkomentar']
                );
            }
        }
        echo json_encode($callback);
    }

    public function kirim_balasan(){
        $id = 0;
        if(empty($_SESSION['user_id'])){
            $id = 0;
        }else{
            $id = $_SESSION['user_id'];
        }
        
        if($id==0){
            $callback = array(
                "status" => 'gagal',
                "pesan" => "Anda belum logi, harap login terlebih dulu!"
            );
        }else{
            $pengguna = $this->MyModel->getPenggunaById($id);
            if($_POST['komentar']==""){
                $callback = array(
                    "status" => 'gagal',
                    "pesan" => "Harap isi komentar balasan!"
                );
            }else{
                $url = "";
                $komentar = "";
                if(empty($_POST['namabelakang'] )||empty($_POST['namadepan'])||empty($_POST['namaid'])){
                    $komentar = $_POST['komentar'];
                }else{
                    $first_name = explode(' ', $_POST['namadepan']); 
                    $first_name2 = implode('_', $first_name); 
                    $last_name = explode(' ', $_POST['namabelakang']); 
                    $last_name2 = implode('_', $last_name);

                    $url = $first_name2 .'-'. $last_name2 .'-'. $_POST['namaid'];
                    $komentar = '<a target="_blank" href="'. base_url('bio/penulis/') . $url .'">'. $_POST['namadepan'] .' '. $_POST['namabelakang']. '</a> &nbsp;'. $_POST['komentar'];    
                }
                $this->MyModel->balasan_komentar($pengguna->ID, $_POST['idbalasan'], $komentar, $_POST['idposting']);
                $callback = array(
                    "status" => 'sukses',
                    "pesan" => "Komentar balasan berhasil dikirim!"
                );

                $penggunax = $this->MyModel->getPengguna($pengguna->ID);
                $balasan = $this->MyModel->getkomentarbalasan($_POST['idbalasanku']);

                if($balasan->ID<>$pengguna->ID){
                    $timezone = new DateTimeZone('Asia/Jakarta');
                    $tgl = new DateTime();
                    $tgl->setTimeZone($timezone);
                    $url_photo = "";
                    if($penggunax->PHOTO_PROFIL<>""){
                        $url_photo = base_url($penggunax->PHOTO_PROFIL);
                    }else{
                        $url_photo = base_url('asset/logo/user.png');
                    }
    
                    $notifikasi = array(
                        "ID" => $balasan->ID,
                        "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                        "NOTIFIKASI" => '
                            <h5><a href="'.base_url('artikel/detail/') . $balasan->JUDUL_URL .'">'. $balasan->JUDUL .'</a></h5>
                            <div class="media">
                                    <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                                    '. $tgl->format('Y-m-d H:i:s') .' | '. $penggunax->FIRST_NAME . ' ' . $penggunax->LAST_NAME .' membalas komentar: <br/> '. $_POST['komentar'] .'
                            </div>
                        ',
                        "AKTIF" => 'Y',
                        "DIBACA" => "N"
                    );
                    $this->MyModel->notifikasi_buat($notifikasi);
                }

            }
        }
        echo json_encode($callback);
    }

    public function kirim_balas(){
        $id = 0;
        if(empty($_SESSION['user_id'])){
            $id = 0;
        }else{
            $id = $_SESSION['user_id'];
        }
        if($id==0){
            $callback = array(
                "status" => 'gagal',
                "pesan" => "Anda belum logi, harap login terlebih dulu!"
            );
        }else{
            $pengguna = $this->MyModel->getPenggunaById($id); 
            if($_POST['komentar']==""){
                $callback = array(
                    "status" => 'gagal',
                    "pesan" => "Harap isi komentar balasan!"
                );
            }else{
                $url = "";
                $komentar = "";
                if(empty($_POST['namabelakang'] )||empty($_POST['namadepan'])||empty($_POST['namaid'])){
                    $komentar = $_POST['komentar'];
                }else{
                    $first_name = explode(' ', $_POST['namadepan']); 
                    $first_name2 = implode('_', $first_name); 
                    $last_name = explode(' ', $_POST['namabelakang']); 
                    $last_name2 = implode('_', $last_name);

                    $url = $first_name2 .'-'. $last_name2 .'-'. $_POST['namaid'];
                    $komentar = '<a target="_blank" href="'. base_url('bio/penulis/') . $url .'">'. $_POST['namadepan'] .' '. $_POST['namabelakang']. '</a> &nbsp;'. $_POST['komentar'];    
                }
                $this->MyModel->balas_komentar($pengguna->ID, $_POST['idbalas'], $komentar);
                $callback = array(
                    "status" => 'sukses',
                    "pesan" => "Komentar balasan berhasil dikirim!"
                );

                $artikel = $this->MyModel->getkomentar($_POST['idbalas']);
                $penggunax = $this->MyModel->getPengguna($pengguna->ID);
                if($artikel->ID<>$pengguna->ID){
                    $timezone = new DateTimeZone('Asia/Jakarta');
                    $tgl = new DateTime();
                    $tgl->setTimeZone($timezone);
                    $url_photo = "";
                    if($penggunax->PHOTO_PROFIL<>""){
                        $url_photo = base_url($penggunax->PHOTO_PROFIL);
                    }else{
                        $url_photo = base_url('asset/logo/user.png');
                    }
                    $notifikasi = array(
                        "ID" => $artikel->ID,
                        "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                        "NOTIFIKASI" => '
                            <h5><a href="'.base_url('artikel/detail/') . $artikel->JUDUL_URL .'">'. $artikel->JUDUL .'</a></h5>
                            <div class="media">
                                <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                                    '. $tgl->format('Y-m-d H:i:s') .' | '. $penggunax->FIRST_NAME . ' ' . $penggunax->LAST_NAME .' membalas komentar: <br/> '. $komentar .'
                            </div>
                        ',
                        "AKTIF" => 'Y',
                        "DIBACA" => "N"
                    );
                    $this->MyModel->notifikasi_buat($notifikasi);     
                }

            }
        }
        echo json_encode($callback);
    }

    public function kirim_balas_admin(){
        $id = 0;
        if(empty($_SESSION['user_id'])){
            $id = 0;
        }else{
            $id = $_SESSION['user_id'];
        }
        if($id==0){
            $callback = array(
                "status" => 'gagal',
                "pesan" => "Anda belum logi, harap login terlebih dulu!"
            );
        }else{
            $pengguna = $this->MyModel->getPenggunaById($id); 
            if($_POST['komentar']==""){
                $callback = array(
                    "status" => 'gagal',
                    "pesan" => "Harap isi komentar balasan!"
                );
            }else{
                $this->MyModel->balas_komentar($pengguna->ID, $_POST['idkomentar'], $_POST['komentar']);
                $callback = array(
                    "status" => 'sukses',
                    "pesan" => "Komentar balasan berhasil dikirim!"
                );
            }
        }
        echo json_encode($callback);
    }

    public function kirim_balasan_admin(){
        $id = 0;
        if(empty($_SESSION['user_id'])){
            $id = 0;
        }else{
            $id = $_SESSION['user_id'];
        }
        if($id==0){
            $callback = array(
                "status" => 'gagal',
                "pesan" => "Anda belum logi, harap login terlebih dulu!"
            );
        }else{
            $pengguna = $this->MyModel->getPenggunaById($id); 
            if($_POST['komentar']==""){
                $callback = array(
                    "status" => 'gagal',
                    "pesan" => "Harap isi komentar balasan!"
                );
            }else{
                $komentar = '<a target="_blank" href="'. $_POST['url'] .'">'. $_POST['nama_lengkap'] .'</a> &nbsp;' . $_POST['komentar'];
                $this->MyModel->balas_komentar($pengguna->ID, $_POST['idkomentar'], $komentar);
                $callback = array(
                    "status" => 'sukses',
                    "pesan" => "Komentar balasan berhasil dikirim!"
                );
            }
        }
        echo json_encode($callback);
    }
     

    public function get_balasan(){
        //pengguna.ID AS ID, pengguna.FIRST_NAME, pengguna.LAST_NAME, balasan)komentar.KOMENTAR AS KOMENTAR, balasan_komentar.TANGGAL_KOMENTAR AS TANGGAL, balasan_komentar.SENTIMEN AS SENTIMEN, balasan_komentar.LAPORKAN AS DILAPORKAN, balasan_komentar.PELANGGARAN AS PELANGGARAN, balasan_komentar.KETERANGAN AS KETERANGAN, balasan_komentar.TANGGAL_PELAPORAN AS TANGGAL_PELAPORAN, balasan_komentar.ID_BALASAN AS ID_BALASAN'
		$list = $this->MyModel->get_balasankomentarArtikelById(13);
		foreach($list as $li){
			$row = array();
			$row[] = $li->ID;
			$row[] = $li->FIRST_NAME;
			$row[] = $li->LAST_NAME;
			$row[] = '<a href="#" class="btn btn-info konfir-balasan" data-toggle="modal" data-target="#ok" data-id="1">'. $li->KOMENTAR .'</a>';
			$row[] = $li->TANGGAL;
			$row[] = $li->SENTIMEN;
			$row[] = $li->DILAPORKAN;
			$row[] = $li->PELANGGARAN;
			$row[] = $li->KETERANGAN;
            $row[] = $li->TANGGAL_PELAPORAN;
            $row[] = $li->ID_BALASAN;
			$data[] = $row;
		}	
		$output = array(
			"draw" => 1,
			"recordsTotal" => 0,
			"recordsFiltered" =>  0,
			"data" => $data
		);
		echo json_encode($output);
	}

    public function get_komentarByArtikel($id, $url=""){
        $komentar = $this->MyModel->komentarByArtikel($id);
        $listKomentar = '';
        $listBalasan = '';
        $n_balas = 1;
        $m_balas = 1;
        
        $id = 0;
        if(empty($_SESSION['user_id'])){
            $id = 0;
        }else{
            $id = $_SESSION['user_id'];
        }
        foreach($komentar->result_array() as $komen){
            $pengguna = $this->MyModel->getPenggunaById($id);
            $url_profil = base_url('asset/logo/user.png');
            if(!empty($komen['PHOTO_PROFIL'])){
                $url_profil = base_url($komen['PHOTO_PROFIL']);
            }
            $hapus = "";
            if($id<>0){
                if(($pengguna->ID) == $komen['ID']){
                    $hapus = '<input class="btn btn-outline-danger btn-sm fa fa-exclamation hapus" value="Hapus" type="button" data-toggle="modal" data-id="'. $komen['ID_KOMENTAR'] .'" data-target="#formHapus"/>';
                }
            }
            $first_name = explode(' ', $komen['FIRST_NAME']); 
            $first_name2 = implode('_', $first_name);
            $last_name = explode(' ', $komen['LAST_NAME']); 
            $last_name2 = implode('_', $last_name);

            $url_komen = base_url('bio/penulis/') . $first_name2 . '-' . $last_name2 .'-'. $komen['ID'];
            $list ='
                <div class="media mb-4">
                    <img width="5%" height="5%" class="d-flex mr-3 rounded-circle" src="'. $url_profil . '" alt="">
                    <div class="media-body">
                    <h5 class="mt-0"><a target="_blank" href="'. $url_komen .'">'. $komen['FIRST_NAME'] . ' ' . $komen['LAST_NAME'] .' </a>| '. $komen['TANGGAL_KOMENTAR'] .'</h5>
                    <p>'. $komen['KOMENTAR'] .'</p>
                    <button class="btn btn-primary btn-sm fa fa-reply balas" type="button" data-toggle="collapse" data-target="#balas'. $n_balas .'"  data-id="'. $komen['ID_KOMENTAR'] .'" data-idnamadepan="'. $komen['FIRST_NAME'] .'" data-idnamabelakang="'. $komen['LAST_NAME'] .'" data-idnamaid="'. $komen['ID'] .'" aria-expanded="false" aria-controls="collapseExample"> Balas </button>
                    <input class="btn btn-outline-warning btn-sm fa fa-exclamation laporkan" value="Laporkan" type="button" data-toggle="modal" data-id="'. $komen['ID_KOMENTAR'] .'" data-target="#formLaporkan"/> '. $hapus .'
                            <form method="POST" action="'. base_url('artikel/kirim_balasan') .'">
                                <div class="collapse" id="balas'. $n_balas .'">
                                <div id="hasil"></div>
                                    <div class="card card-body">
                                        <input class="idposting" name="idposting" type="hidden" value="'. $komen['ID_POSTING'] .'">
                                        <input class="url" name="url" type="hidden" value="'. $url .'">
                                        <input class="nama-depan" name="namadepan" type="hidden" value="'. $komen['FIRST_NAME'] .'">
                                        <input class="nama-belakang" name="namabelakang" type="hidden" value="'. $komen['LAST_NAME'] .'">
                                        <input class="nama-id" name="namaid" type="hidden" value="'. $komen['ID'] .'">
                                        <input class="idkomentar" name="idkomentar" type="hidden" value="'. $komen['ID_KOMENTAR'] .'">
                                        <textarea name="komentarbalasan" class="form-control komentarbalasan"></textarea>  
                                    </div>
                                    <button  class="btn btn-primary fa fa-paper-plane btn-kirim-balas" type="button" data-toggle="collapse"> Kirim </button>
                                </div>
                            </form>
                    ';
            $balasan = $this->MyModel->balasanKomentarByArtikel($komen['ID_KOMENTAR']);
            $listBalasan = '';
            foreach($balasan->result_array() as $balas){
                $hapus_balas = "";
                if($id<>0){
                    if(($pengguna->ID) == $balas['ID']){
                        $hapus_balas = '<input class="btn btn-outline-danger fa fa-exclamation btn-sm hapus-balasan" value="Hapus" type="button" data-toggle="modal" data-id="'. $balas['ID_BALASAN'] .'" data-target="#formHapusBalasan"/>';
                    }
                }

                $url_profil2 = base_url('asset/logo/user.png');
                if(!empty($balas['PHOTO_PROFIL'])){
                    $url_profil2 = base_url($balas['PHOTO_PROFIL']);
                }

                if(!empty($balas)){
                    if($balas['ID_KOMENTAR'] == $komen['ID_KOMENTAR']){
                        $first_name = explode(' ', $balas['FIRST_NAME']); 
                        $first_name2 = implode('_', $first_name);
                        $last_name = explode(' ', $balas['LAST_NAME']); 
                        $last_name2 = implode('_', $last_name);
                        
                        $url_balas = base_url('bio/penulis/') . $first_name2 . '-' . $last_name2 .'-'. $balas['ID'];
                        $bal = '
                        <div class="media mt-4">
                            <img width="5%" height="5%" class="d-flex mr-3 rounded-circle" src="'. $url_profil2 . '" alt="">
                            <div class="media-body">
                            <h5 class="mt-0"><a target="_blank" href="'.$url_balas.'">'. $balas['FIRST_NAME'] . ' ' . $balas['LAST_NAME'] .'</a> | '. $balas['TANGGAL_KOMENTAR'] .'</h5>
                            '. $balas['KOMENTAR'] .'

                            <button class="btn btn-primary btn-sm fa fa-reply balasan" type="button" data-toggle="collapse"  data-target="#balasan'. $m_balas .'" data-id="'. $komen['ID_KOMENTAR'] .'" data-ida="'. $balas['FIRST_NAME'] .'" data-idb="'. $balas['LAST_NAME'] .'" data-idc="'. $balas['ID'] .'" data-idd="'. $komen['ID_POSTING'] .'" data-ide="'. $balas['ID_BALASAN'] .'" aria-expanded="false" aria-controls="collapseExample"> Balas </button>
                            <input class="btn btn-outline-warning btn-sm fa fa-exclamation-triangle laporkan-balasan" value="Laporkan" type="button" data-toggle="modal" data-id="'. $balas['ID_BALASAN'] .'" data-target="#formLaporkanBalasan"/> 
                            '. $hapus_balas .' 
                            <form id="formKomentarBalasan '. $m_balas .'" class="formKomentarBalasan" method="POST" action="'. base_url('artikel/kirim_balasan') .'">
                                <div class="collapse" id="balasan'. $m_balas .'">
                                <div id="hasil"></div>
                                    <div class="card card-body">
                                        <input class="url" name="url" type="hidden" value="'. $url .'">
                                        <input class="idbalasan" name="idbalasan" type="hidden" value="'. $balas['ID_BALASAN'] .'">
                                        <textarea name="komentarbalasan" class="form-control komentarbalasan"></textarea>  
                                    </div>
                                    <button  class="btn btn-primary fa fa-paper-plane btn-kirim-detailbalasan" type="button" data-toggle="collapse"> Kirim </button>
                                </div>
                            </form>

                            </div>
                        </div>
                        ';
                    }
                }
                $m_balas = $m_balas+1;
                $listBalasan = $listBalasan . $bal;  
            }
            $listKomentar = $listKomentar. $list . $listBalasan .'<hr/>'. '</div></div>';  
            $n_balas = $n_balas+1;
        }
        return $listKomentar;
    }

    public function komentarByArtikel(){
        $komentar = $this->MyModel->komentarByArtikel($_POST['idposting']);
        $listKomentar = '';
        $listBalasan = '';
        $n_balas = 1;

        foreach($komentar->result_array() as $komen){
            $list ='
                <div class="media mb-4">
                    <img width="5%" height="5%" class="d-flex mr-3 rounded-circle" src="'. base_url($komen['PHOTO_PROFIL']) . '" alt="">
                    <div class="media-body">
                    <h5 class="mt-0"> '. $komen['FIRST_NAME'] . ' ' . $komen['LAST_NAME'] .' | '. $komen['TANGGAL_KOMENTAR'] .'</h5>
                    <p>'. $komen['KOMENTAR'] .'</p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#balas'. $n_balas .'" aria-expanded="false" aria-controls="collapseExample">Balas</button>
                            <form method="POST" action="'. base_url('artikel/kirim_balasan') .'">
                                <div class="collapse" id="balas'. $n_balas .'">
                                <div id="hasil"></div>
                                    <div class="card card-body">
                                        <input class="url" name="url" type="hidden" value="'. $url="" .'">
                                        <input class="idkomentar" name="idkomentar" type="hidden" value="'. $komen['ID_KOMENTAR'] .'">
                                        <textarea name="komentarbalasan" class="form-control komentarbalasan"></textarea>  
                                    </div>
                                    <button  class="btn btn-primary btn-kirim-balasan" type="submit" data-toggle="collapse">Kirim</button>
                                </div>
                            </form>
                    ';
            $balasan = $this->MyModel->balasanKomentarByArtikel($komen['ID_KOMENTAR']);
            $listBalasan = '';
            foreach($balasan->result_array() as $balas){
                if(!empty($balas)){
                    if($balas['ID_KOMENTAR'] == $komen['ID_KOMENTAR']){
                        $bal = '
                        <div class="media mt-4">
                            <img width="5%" height="5%" class="d-flex mr-3 rounded-circle" src="'. base_url($balas['PHOTO_PROFIL']) . '" alt="">
                            <div class="media-body">
                            <h5 class="mt-0">'. $balas['FIRST_NAME'] . ' ' . $balas['LAST_NAME'] .' | '. $balas['TANGGAL_KOMENTAR'] .'</h5>
                            '. $balas['KOMENTAR'] .'
                            </div>
                        </div>
                        ';
                    }
                }
                $listBalasan = $listBalasan . $bal;  
            }
            $listKomentar = $listKomentar. $list . $listBalasan . '</div></div>';  
            $n_balas = $n_balas+1;
        }
        $callback = array(
            'status' => 'sukses',
            'pesan' => $listKomentar
        );
        echo json_encode($callback);
    }

    public function get_komentar()
    {
        echo json_encode($this->MyModel->get_komentar($_POST['idx']));
    }

    public function rating()
    {
        if($_POST['idpenulis'] == $_POST['idpengguna']){
            $callback = array(
                'status' => 'gagal',
                'pesan' => 'Penulis tidak boleh memberikan rating terhadap artikelnya sendiri!'
            );
        }else{
            $cek = $this->MyModel->RatingBy($_POST['idpengguna'], $_POST['idposting']);        
            $iscek = 0;
            if(! empty($cek) && (! is_null($cek))){
                foreach($cek->result() as $c){
                    if(($c->ID) == $_POST['idpengguna'] && ($c->ID_POSTING) == $_POST['idposting']){
                        $iscek = 1;
                    }
                }
            }

            if($iscek == 1){
                $callback = array(
                    'status' => 'gagal',
                    'pesan' => 'Anda telah memberikan rating!'
                );
            }else{
                $this->MyModel->rating($_POST['idpengguna'], $_POST['idposting'],$_POST['rating']);
                $callback = array(
                    'status' => 'sukses',
                    'pesan' => 'Terima kasih telah memberikan rating!'
                );

                $artikel = $this->MyModel->get_artikelById($_POST['idposting']);
                $penggunax = $this->MyModel->getPengguna($_POST['idpengguna']);
                $timezone = new DateTimeZone('Asia/Jakarta');
                $tgl = new DateTime();
                $tgl->setTimeZone($timezone);
                $url_photo = "";
                if($penggunax->PHOTO_PROFIL<>""){
                    $url_photo = base_url($penggunax->PHOTO_PROFIL);
                }else{
                    $url_photo = base_url('asset/logo/user.png');
                }
                $notifikasi = array(
                    "ID" => $artikel->ID,
                    "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                    "NOTIFIKASI" => '
                        <h5><a href="'.base_url('admin/artikel_ubah/') . $artikel->ID .'">'. $artikel->JUDUL .'</a></h5>
                        <div class="media">
                                <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                                '. $tgl->format('Y-m-d H:i:s') .' | '. $penggunax->FIRST_NAME . ' ' . $penggunax->LAST_NAME .' memberi rating: &nbsp;<b>'. $_POST['rating'] .' </b>
                        </div>
                    ',
                    "AKTIF" => 'Y',
                    "DIBACA" => "N"
                );
                $this->MyModel->notifikasi_buat($notifikasi);    

            }
        }
        echo json_encode($callback);
    }

    public function hapus_komentar(){
        if($this->MyModel->hapus_komentarArtikel($_POST['id'])>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data komentar berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data komentar gagal dihapus!'
			);
			echo json_encode($callback);
		}
    }

    public function hapus_komentar_balasan(){
        if($this->MyModel->hapus_komentarBalasan($_POST['id'])>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data komentar berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data komentar gagal dihapus!'
			);
			echo json_encode($callback);
		}
    }
    
    public function hapus_komentarbully(){
        //var_dump($_POST);
        if($this->MyModel->hapus_komentarbully($_POST['id'])>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data komentar berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data komentar gagal dihapus!'
			);
			echo json_encode($callback);
		}
    }

    public function hapus_balasanbully(){
        //var_dump($_POST);
        if($this->MyModel->hapus_balasanbully($_POST['id'])>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data komentar berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data komentar gagal dihapus!'
			);
			echo json_encode($callback);
		}
    }

    public function hapus_tagar(){
        if($this->MyModel->hapus_tagar($_POST['id'])>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data tagar berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data tagar gagal dihapus!'
			);
			echo json_encode($callback);
		}
    }

    public function ubah_tagar(){
        $this->form_validation->set_rules('katakunci', 'Tagar (Kata Kunci Artikel)', 'required');
        $this->form_validation->set_rules('total', 'Diakses (Total Diakses)', 'required|integer');
        if($this->form_validation->run()==false){
            $callback = array(
                'status'=>'gagal',
                'pesan'=> validation_errors()
            );
            echo json_encode($callback);
        }else{
            if($this->MyModel->ubah_tagar($_POST)>0){
                $callback = array(
                    'status'=>'sukses',
                    'pesan'=>'Data tagar berhasil diubah!'
                );
                echo json_encode($callback);
            }else{
                $callback = array(
                    'status'=>'gagal',
                    'pesan'=>'Data tagar gagal diubah!'
                );
                echo json_encode($callback);
            }
        }
    }

    public function tambah_tagar(){
        $this->form_validation->set_rules('katakunci', 'Tagar (Kata Kunci Artikel)', 'required');
        $this->form_validation->set_rules('total', 'Diakses (Total Diakses)', 'required|integer');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
			);
			echo json_encode($callback);
		}else{
            $this->MyModel->tambah_tagar($_POST);
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data tagar berhasil diutambahkan!'
			);
			echo json_encode($callback);
        }
    }

    public function hapus_pelaporan(){
        if($this->MyModel->hapus_pelaporan($_POST)>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data komentar berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data komentar gagal dihapus!'
			);
			echo json_encode($callback);
        }
    }
    
    public function hapus_pelaporan_balasan(){
        if($this->MyModel->hapus_pelaporan_balasan($_POST)>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data komentar berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data komentar gagal dihapus!'
			);
			echo json_encode($callback);
        }
	}

    public function tagar($tagar=null){
        if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
        if(!empty($_POST)){
            $tagar = preg_replace("/[^a-zA-Z0-9]/", "", $_POST['tagar']);
            //$tagar = $_POST['tagar'];
        }else{
            $tagar = preg_replace("/[^a-zA-Z0-9]/", "", $tagar);
            //$tagar = $tagar;
        }
        if($tagar != null || $tagar !="" || !empty($tagar)){
            $this->MyModel->tagar($tagar);
        }
		$data['judul'] = 'Rahmatika.com - Menebarkan kebaikan kepada sesama';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );
        $data['bytagar'] = $tagar;

        $data['tagar_populer'] = $this->MyModel->tagar_populer();
        $data['kategori'] = $this->MyModel->kategori();
        $data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $jumlah_data = $this->MyModel->totalArtikelByTagar($tagar);
        
		$this->load->library('pagination');

        $config['per_page'] = 9;
        $offset = $this->uri->segment(4);
        
		$config["uri_segment"] = 4;
		$config["num_links"] = 5;
		$config['base_url'] = base_url()."artikel/tagar/$tagar" ;
		$config['total_rows'] = $jumlah_data;

        $data['artikel'] = $this->MyModel->artikel_byTagar($tagar,$config['per_page'],$offset);
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
			$this->template('frontend/standar/bytagar.html', $data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->template('frontend/meranda/bytagar.html', $data);
		}else{
			$this->template('frontend/standar/bytagar.html', $data);
		}
    }

    public function cari(){
        if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
        $cari = '';
        if(empty($_POST)){
			$cari = '';
		}else{
            $cari = $_POST['cari'];
		}

		$data['judul'] = 'Rahmatika.com - Menebarkan kebaikan kepada sesama';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );
        $data['bycari'] = $cari;

        $data['tagar_populer'] = $this->MyModel->tagar_populer();
        $data['kategori'] = $this->MyModel->kategori();
        $data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $jumlah_data = $this->MyModel->totalArtikelByTagar($cari);
        
		$this->load->library('pagination');

        $config['per_page'] = 9;
        $offset = $this->uri->segment(3);
        
		$config["uri_segment"] = 3;
		$config["num_links"] = 5;
		$config['base_url'] = base_url()."artikel/cari" ;
		$config['total_rows'] = $jumlah_data;

        $data['artikel'] = $this->MyModel->artikel_byTagar($cari,$config['per_page'],$offset);
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
			$this->template('frontend/standar/bypencarian.html', $data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->template('frontend/meranda/bypencarian.html', $data);
		}else{
			$this->template('frontend/standar/bypencarian.html', $data);
		}
    }

    public function detail($artikel = null){
        if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
		if(isset($artikel)){
            $data['judul'] = 'Rahmatika.com - Menebarkan kebaikan kepada sesama';
            $artikel = $this->MyModel->getArtikel($artikel);
            $data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();

            if($artikel =="" || empty($artikel) || is_null($artikel)){
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
                $data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();

                $identitas = $this->MyModel->getidentitas();
                if($identitas->TEMPLATE == 'standar'){
                    $this->template('frontend/standar/blog-single-error.html', $data);
                }else if($identitas->TEMPLATE == 'meranda'){	
                    $this->template('frontend/meranda/blog-single-error.html', $data);
                }else{
                    $this->template('frontend/standar/blog-single-error.html', $data);
                }
            }else{
                $posting = $artikel->ID_POSTING;
                $pengguna = $artikel->ID;
                $data['populer'] = $this->MyModel->getArtikelPopulerByPengguna($pengguna);
                $this->MyModel->view($posting, $pengguna);

                $total = $this->MyModel->total_tayanganByPengguna($artikel->ID);
                $pengguna = $this->MyModel->getPengguna($artikel->ID);
                $timezone = new DateTimeZone('Asia/Jakarta');
                $tgl = new DateTime();
                $tgl->setTimeZone($timezone);
                $url_photo = "";
                if($pengguna->PHOTO_PROFIL<>""){
                    $url_photo = base_url($pengguna->PHOTO_PROFIL);
                }else{
                    $url_photo = base_url('asset/logo/user.png');
                }
                if($total % 1000 == 0){
                    $notifikasi = array(
                        "ID" => $pengguna->ID,
                        "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                        "NOTIFIKASI" => '
                            <h5><a href="'. base_url('admin') .'">Selamat tayangan Anda telah mencapai <b>+'. $total .'</b>!</a></h5>
                            <div class="media">
                                <i class="fas fa-award"></i>
                                <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                                '. $tgl->format('Y-m-d H:i:s') .' | '. $pengguna->FIRST_NAME . ' ' . $pengguna->LAST_NAME .' telah menayangkan &nbsp;<b>'. $total .'</b>&nbsp;tayangan artikel sepanjang masa.
                            </div>
                        ',
                        "AKTIF" => 'Y',
                        "DIBACA" => "N"
                    );
                    $this->MyModel->notifikasi_buat($notifikasi);
                }

                
                $total_view = $this->MyModel->total_view($posting);
                $total_rating = $this->MyModel->total_rating($posting);
                $count_rating = $this->MyModel->count_rating($posting);
                $count_komentar = $this->MyModel->count_komentar($posting);
                $count_balasan_komentar = $this->MyModel->count_balasan_komentar($posting);
                $total_komentar = $count_komentar->KOMENTAR + $count_balasan_komentar->KOMENTAR;
                $this->MyModel->perbarui_statistik_artikel($posting, $total_rating->RATING, $count_rating->ID, $total_view->VIEW, $total_komentar);
                $komentar = $this->get_komentarByArtikel($artikel->ID_POSTING, $artikel->JUDUL_URL);
    
                $data['identitas'] = array(
                    'judul' => $artikel->JUDUL,
                    'meta_deskripsi' => $artikel->DESKRIPSI_ARTIKEL,
                    'keywords' => $artikel->KATA_KUNCI,
                    'author' => $artikel->FIRST_NAME . ' ' . $artikel->LAST_NAME,
                    'view' => $total_view->VIEW,
                    'rating' => $total_rating->RATING,
                    'jumlah_rating' => $count_rating->ID,
                    'komentar' => $total_komentar
                );
                
                $data['komentar'] = $komentar;
                $data['artikel'] = $artikel;
                $data['kategori'] = $this->MyModel->kategori();
                
                $data['tagar_populer'] = $this->MyModel->tagar_populer();
                $data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
                $data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
                
                $identitas = $this->MyModel->getidentitas();
                if($identitas->TEMPLATE == 'standar'){
                    $this->template('frontend/standar/artikel.html', $data);
                }else if($identitas->TEMPLATE == 'meranda'){	
                    $this->template('frontend/meranda/blog-single.html', $data);
                }else{
                    $this->template('frontend/standar/artikel.html', $data);
                }
            }
        }else{
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
            $data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
            $identitas = $this->MyModel->getidentitas();
            if($identitas->TEMPLATE == 'standar'){
                $this->template('frontend/standar/blog-single-error.html', $data);
            }else if($identitas->TEMPLATE == 'meranda'){	
                $this->template('frontend/meranda/blog-single-error.html', $data);
            }else{
                $this->template('frontend/standar/blog-single-error.html', $data);
            }
        }
        
	}

	public function getartikel(){
		$hasil = $this->MyModel->postingById($_POST['idx']);
		echo json_encode($hasil);	
    }

    public function statistikPengunjuang()
    {
        var_dump($_SERVER);
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
           $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
            echo  "Nama Komputer=".$hostname . '<br />';
            echo  "IP Address=".$ip;
    }

	public function artikel_hapus(){
        if($this->MyModel->posting_hapus($_POST)>0){
            header('Location: ' . base_url() . 'admin/artikel_kelola');
        }else{
            header('Location: ' . base_url() . 'admin/artikel_kelola');
        }
    }

	public function artikel_ubah()
	{
		$this->form_validation->set_rules('judul', 'Judul', 'required');
		$this->form_validation->set_rules('deskripsiartikel', 'Deskripsi Artikel', 'required|min_length[5]|max_length[500]');
        $this->form_validation->set_rules('katakunci', 'Kata Kunci', 'required|min_length[5]|max_length[300]');
        $this->form_validation->set_rules('isiartikel', 'Isi Artikel', 'required|min_length[160]');
		
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
            $this->MyModel->posting_ubah($_POST);
            echo json_encode($callback);
        }
    }
    
    public function zona_waktu()
	{
		/*$timezone = new DateTimeZone('Asia/Jakarta');
		$date = new DateTime();
        $date->setTimeZone($timezone);
        echo $date->format('Y-m-d m:i:s'); //date('Y-m-d m:i:s')
        echo '<br />';
        echo $date->format('d-m-Y H:i:s');
        echo '<br />';
        echo $date->format('Y-m-d H:i:s');*/
        //var_dump($_REQUEST);
        /*if( !$_REQUEST["waktu_client"] ) {
            echo "<script type=\"text/javascript\">";
            echo "localtime = new Date();";            
            echo "document.location.href = '$PHP_SELF?waktu_client=' + localtime.getTime();";
            echo "</script>";
        }
        else{    
            // Proses selanjutnya menggunakan variabel $waktu_client
        }*/
	}

    public function uji()
    {
        echo str_replace(' ','-',$_POST['judul']);
    }

	public function artikel_buat(){
		$this->form_validation->set_rules('judul', 'Judul', 'required|min_length[5]|max_length[160]');
		$this->form_validation->set_rules('deskripsiartikel', 'Deskripsi Artikel', 'required|min_length[5]|max_length[500]');
        $this->form_validation->set_rules('katakunci', 'Kata Kunci', 'required|min_length[5]|max_length[300]');
        $this->form_validation->set_rules('isiartikel', 'Isi Artikel', 'required|min_length[160]');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
            $return = $this->MyModel->artikel_buat($_POST);
            $callback = array(
				'status'=>'sukses',
                'pesan'=>'berhasil disimpan',
                'judul_url' => $return['JUDUL_URL']
            );
            
            $timezone = new DateTimeZone('Asia/Jakarta');
            $tgl = new DateTime();
            $tgl->setTimeZone($timezone);
            $pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
            $akunpengikut = $this->MyModel->getpengikut($pengguna->ID);
            $pengikutku = $this->MyModel->get_listpengikut($akunpengikut->ID_PENGIKUT);
            $url_photo = "";
                if($pengguna->PHOTO_PROFIL<>""){
                    $url_photo = base_url($pengguna->PHOTO_PROFIL);
                }else{
                    $url_photo = base_url('asset/logo/user.png');
                }
            if(!empty($pengikutku)){
                foreach($pengikutku->result() as $follower){
                    $notifikasi2 = array(
                        "ID" => $follower->ID,
                        "TANGGAL" => $tgl->format('Y-m-d H:i:s'),
                        "NOTIFIKASI" => '
                            <h5><a href="'. base_url('artikel/detail/') . $return['JUDUL_URL'] .'">'. $return['JUDUL'] .'!</a></h5>
                            <div class="media">
                                <img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
                                '. $tgl->format('Y-m-d H:i:s') .' | '. $pengguna->FIRST_NAME . ' ' . $pengguna->LAST_NAME .' telah mempublikasikan artikel berjudul: '. $return['JUDUL'] .'.
                            </div>
                        ',
                        "AKTIF" => 'Y',
                        "DIBACA" => "N"
                    );
                    $this->MyModel->notifikasi_buat($notifikasi2);
                }
            }

            echo json_encode($callback);
        }
	}

	function upload_file() {
        //upload file
        $config['upload_path'] = './images/galeri/';
        $config['allowed_types'] = 'png|jpg|jpeg|gif';
        //$config['max_filename'] = '255';
        //$config['encrypt_name'] = FALSE;
        $config['max_size'] = '2048'; //2 MB

        if (isset($_FILES['file']['name'])) {
            if (0 < $_FILES['file']['error']) {
                //echo 'Error during file upload' . $_FILES['file']['error'];
                $callback = array(
                    "status" => "gagal",
                    "pesan" => 'Error during file upload' . $_FILES['file']['error']
                );
                echo json_encode($callback);
            }else{
                if(file_exists('images/galeri/' . $_FILES['file']['name'])) {
                    //echo 'File already exists: ' . $_FILES['file']['name'];
                    $callback = array(
                        "status" => "gagal",
                        "pesan" => 'File ' . $_FILES['file']['name'] .' sudah ada!'
                    );
                    echo json_encode($callback);
                }else{
                    if($_FILES['file']['size']>2048000){
                        //echo 'Ukuran gambar tidak boleh lebih dari 1MB';
                        $callback = array(
                            "status" => "gagal",
                            "pesan" => 'Ukuran gambar tidak boleh lebih dari 1MB'
                        );
                        echo json_encode($callback);
                    }else{
                        $this->load->library('upload', $config);
                        if(!$this->upload->do_upload('file')) {
                            //echo $this->upload->display_errors();
                            $callback = array(
                                "status" => "gagal",
                                "pesan" => $this->upload->display_errors()
                            );
                            echo json_encode($callback);
                        }else{
                            //echo 'images/galeri/' . $_FILES['file']['name'];
                            $callback = array(
                                "status" => "sukses",
                                "pesan" => 'images/galeri/' . $_FILES['file']['name']
                            );
                            echo json_encode($callback);
                        }
                    }
                
                }
            }
        }else{
            echo 'Please choose a file';
        }
    }

    public function total_artikel(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $total = $this->MyModel->total_artikelByPengguna($pengguna->ID);
        $callback = array(
            "sukses" => "sukses",
            "total" => $total
        );
        echo json_encode($callback);
    }

    public function total_tayangan(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $total = $this->MyModel->total_tayanganByPengguna($pengguna->ID);
        $callback = array(
            "sukses" => "sukses",
            "total" => $total
        );
        echo json_encode($callback);
    }

    public function total_rating(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $total = $this->MyModel->total_ratingByPengguna($pengguna->ID);
        $callback = array(
            "sukses" => "sukses",
            "total" => round($total->RATING,3)
        );
        echo json_encode($callback);
    }

    public function total_pengikut(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $pengikut = $this->MyModel->getpengikut($pengguna->ID);
        if(empty($pengikut)){
            $callback = array(
                "sukses" => "gagal",
                "total" => 0
            );
        }else{
            $total = $this->MyModel->total_pengikutByPengguna($pengikut->ID_PENGIKUT);
            if(empty($total) || !isset($total) || is_null($total)){
                $callback = array(
                    "sukses" => "gagal",
                    "total" => 0
                );
            }else{
                $callback = array(
                    "sukses" => "sukses",
                    "total" => $total->PENGIKUT
                );
            }
        }
        echo json_encode($callback);
    }

    public function total_mengikuti(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $mengikuti = $this->MyModel->getmengikuti($pengguna->ID);
        if(empty($mengikuti)){
            $callback = array(
                "sukses" => "sukses",
                "total" => 0
            );
        }else{
            $total = $this->MyModel->total_mengikutiByPengguna($mengikuti->ID_MENGIKUTI);
            if(empty($total) || !isset($total) || is_null($total)){
                $callback = array(
                    "sukses" => "gagal",
                    "total" => 0
                );
            }else{
                $callback = array(
                    "sukses" => "sukses",
                    "total" => $total->MENGIKUTI
                );
            }  
        }
        echo json_encode($callback);
    }

    public function total_komentar(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $total = $this->MyModel->total_komentarByPengguna($pengguna->ID);
        $callback = array(
            "sukses" => "sukses",
            "total" => $total->KOMENTAR
        );
        echo json_encode($callback);
    }

    public function laporan_rating(){
        $laporan = $this->MyModel->laporan_rating($_POST['idx']);
        $tulisan = $this->MyModel->get_artikelById($_POST['idx']);
        $tanggal = array();
        $rating = array();
        $judul = "";
        foreach($laporan->result() as $report){
            $tanggal[] = $report->TANGGAL;
            $rating[] = $report->RATING;
        }
        $callback = array(
            "sukses" => "sukses",
            "judul" => $tulisan->JUDUL,
            "tanggal" => $tanggal,
            "rating" => $rating
        );
        echo json_encode($callback);
    }

    public function laporan_artikel(){
        $laporan = $this->MyModel->laporan_artikel($_POST['idx']);
        $tulisan = $this->MyModel->get_artikelById($_POST['idx']);
        $tanggal = array();
        $view = array();
        $judul = "";
        foreach($laporan->result() as $report){
            $tanggal[] = $report->TANGGAL;
            $view[] = $report->VIEW;
        }
        $callback = array(
            "sukses" => "sukses",
            "judul" => $tulisan->JUDUL,
            "tanggal" => $tanggal,
            "view" => $view
        );
        echo json_encode($callback);
    }

    public function laporan_tayangan(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $laporan = $this->MyModel->laporan_tayangan($pengguna->ID);
        $tanggal = array();
        $view = array();
        $judul = "Perkembangan Tayangan Sepanjang Masa";
        foreach($laporan->result() as $report){
            $tanggal[] = $report->TANGGAL;
            $view[] = $report->VIEW;
        }
        $callback = array(
            "sukses" => "sukses",
            "judul" => $judul,
            "tanggal" => $tanggal,
            "view" => $view
        );
        echo json_encode($callback);
    }

    public function laporan_top_artikel(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $laporan = $this->MyModel->getTopArtikel($pengguna->ID);
        $judul_artikel = array();
        $view = array();
        $judul = "Perbandingan Artikel Terpopuler";
        //var_dump($laporan);
        foreach($laporan as $report){
            $judul_artikel[] = $report->JUDUL;
            $view[] = $report->TOTAL;
        }
        $callback = array(
            "sukses" => "sukses",
            "judul" => $judul,
            "artikel" => $judul_artikel,
            "view" => $view
        );
        echo json_encode($callback);
    }

    public function laporan_top_rating(){
        $pengguna = $this->MyModel->getPenggunaById($_POST['idx']);
        $laporan = $this->MyModel->getTopRating($pengguna->ID);
        $judul_artikel = array();
        $rating = array();
        $judul = "Perbandingan Top Rating Artikel";
        //var_dump($laporan);
        foreach($laporan as $report){
            $judul_artikel[] = $report->JUDUL;
            $rating[] = $report->RATING;
        }
        $callback = array(
            "sukses" => "sukses",
            "judul" => $judul,
            "artikel" => $judul_artikel,
            "rating" => $rating
        );
        echo json_encode($callback);
    }

    public function tes(){
        //$pengikut = $this->MyModel->get_listpengikut($id);
        $akunpengikut = $this->MyModel->getpengikut(11);
        echo $akunpengikut->ID_PENGIKUT . ' ';
    }

}