<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kritik_Saran extends CI_Controller {
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
		$data['judul'] = 'Kritik dan SAran';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );

		$data['tagar_populer'] = $this->MyModel->tagar_populer();
		$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
		$data['kontak'] = "Kritik dan Saran";
		$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $data['kategori'] = $this->MyModel->kategori();
		
		$this->load->helper('captcha');
		$random = rand(1000,9999);
		$vals = array(
			'word'          => $random,
			'img_path'      => './captcha/',
			'img_url'       => base_url('captcha'),
			'img_width'     => '205',
			'img_height'    => 50,
			'expiration'    => 3600,
			'word_length'   => 8,
			'font_size'     => 30,
	
			// White background and border, black text and red grid
			'colors'        => array(
					'background' => array(255, 255, 255),
					'border' => array(155, 155, 155),
					'text' => array(0, 0, 0),
					'grid' => array(255, 255, 40)
			)
		);
		
		$cap = create_captcha($vals);
		$data['captcha_kode'] = $random;
		$data['captcha'] = $cap['image'];
		$data['kuesioner'] = $this->get_kuesioner();
		
		$identitas = $this->MyModel->getidentitas();
		if($identitas->TEMPLATE == 'standar'){
            $this->template('frontend/standar/kritik-saran.html', $data);
        }else if($identitas->TEMPLATE == 'meranda'){	
            $this->template('frontend/meranda/kritik-saran.html', $data);
        }else{
            $this->template('frontend/standar/kritik-saran.html', $data);
        }
	}

	public function terima_kasih(){
		$data['judul'] = 'Kritik dan SAran';
        $beranda = $this->MyModel->getidentitas();

		$data['identitas'] = array(
            'judul' => $beranda->NAMA_WEBSITE,
            'meta_deskripsi' => $beranda->META_DESKRIPSI,
            'keywords' => $beranda->META_KEYWORD,
            'author' => 'Danar Dono'
        );

		$data['tagar_populer'] = $this->MyModel->tagar_populer();
		$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
		$data['kontak'] = "Kritik dan Saran";
		$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $data['kategori'] = $this->MyModel->kategori();
		$identitas = $this->MyModel->getidentitas();

		if($identitas->TEMPLATE == 'standar'){
            $this->template('frontend/standar/terima-kasih.html', $data);
        }else if($identitas->TEMPLATE == 'meranda'){	
            $this->template('frontend/meranda/terima-kasih.html', $data);
        }else{
            $this->template('frontend/standar/terima-kasih.html', $data);
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

	function buat_captcha(){
		$this->load->helper('captcha');
		$random = rand(1000,9999);
		$vals = array(
			'word'          => $random,
			'img_path'      => './captcha/',
			'img_url'       => base_url('captcha'),
			'img_width'     => '205',
			'img_height'    => 50,
			'expiration'    => 7200,
			'word_length'   => 8,
			'font_size'     => 30,
	
			// White background and border, black text and red grid
			'colors'        => array(
					'background' => array(255, 255, 255),
					'border' => array(155, 155, 155),
					'text' => array(0, 0, 0),
					'grid' => array(255, 255, 40)
			)
		);
		
		$cap = create_captcha($vals);
		$callback = array(
			'captcha_kode' => $random,
			'captcha' => $cap['image']
		);
		echo json_encode($callback);
	}
		
	public function get_kuesioner(){
		$kuesioner = $this->MyModel->get_kuesioner();
		$listpertanyaan = '';
		$pertanyaan = '';
		$listjawab = '';
		$jawab = '';
		$index_kuesioner = '0';
        foreach($kuesioner->result_array() as $tanya){
			$index_kuesioner = $index_kuesioner .'-'. $tanya['ID_KUESIONER'];
			$idkuesioner = $tanya['ID_KUESIONER'];
            $pertanyaan ='
			<hr/><fieldset class="form-group">
			<div class="row">
			  <legend class="col-form-label col-sm-9 pt-0">'. $tanya['PERTANYAAN'] .'</legend>			  
			  <div class="col-sm-3">';
            $listjawab = $this->list_nilai($tanya['ID_KUESIONER']);
			$listpertanyaan = $listpertanyaan . $pertanyaan . $listjawab . '</div></div>';
		}
		$index = '<input type="hidden" name="index" value="'. $index_kuesioner .'">';
		$listpertanyaan = $listpertanyaan . '</fieldset>' . $index;
        return $listpertanyaan;
	}

	public function list_nilai($id)
	{
		$list = '';
		$nilai = $this->MyModel->get_nilai($id);
		foreach($nilai->result_array() as $value){
			$li = '
				<div class="form-check">
					<input class="form-check-input pertanyaan" name="pertanyaan-'. $value['ID_KUESIONER'] .'" type="radio" value="'. $value['ID_KUESIONER'] .'-'. $value['NILAI'] .'-'. $value['PREDIKAT'].'" required>
					<label class="form-check-label" for="gridRadios1">
						'. $value['PREDIKAT'] .'
					</label>
				</div>
			';
		$list = $list . $li;
		}
		return $list;
	}

	public function jawaban(){
		//var_dump($_POST);
		if(!empty($_POST)){
			$kode = "";
			$cek_penjawab = $this->MyModel->cek_penjawab($_POST['email']);
			//var_dump($cek_penjawab);
			if(!empty($cek_penjawab)){
				if($cek_penjawab->EMAIL_PENILAI == $_POST['email']){
					$kode = $cek_penjawab->KODE;
				}
			}else{
				$kode = $this->MyModel->buat_penilai($_POST['nama'], $_POST['email'], $_POST['gender'], $_POST['profesi']);
			}

			$penilai = $this->MyModel->get_penilai($kode);
			$this->MyModel->buat_kritiksaran($penilai->ID_PENILAI, $_POST['kritiksaran']);
			if(!empty($penilai)){
				$index = array();
				$indeks = $_POST['index'];
				$index = explode('-',$indeks);
				unset($index[0]);
				for($i=1;$i<=count($index)-1;$i++){
					$pertanyaan = 'pertanyaan-' . $index[$i];
					$jawaban = $_POST[$pertanyaan];
					$jawab = explode('-',$jawaban);
					$this->MyModel->buat_jawaban($penilai->ID_PENILAI, $jawab[0], $jawab[1], $jawab[2]);
				}
			}
			$admin = $this->MyModel->get_admin();
			$timezone = new DateTimeZone('Asia/Jakarta');
            $tgl = new DateTime();
            $tgl->setTimeZone($timezone);
			foreach($admin->result() as $adm){
				$pengguna = $this->MyModel->getPenggunaById($adm->user_id);
				$notifikasi = array(
					"ID" => $pengguna->ID,
					"TANGGAL" => $tgl->format('Y-m-d H:i:s'),
					"NOTIFIKASI" => '
						<h5><a href="'. base_url('admin/evaluasi') .'"> Seseorang bernama '. $penilai->NAMA_PENILAI .' telah menjawab kuesioner Anda!</a></h5>
						<div class="media">
							<img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. base_url('asset/logo/user.png') .'" alt="">
							'. $tgl->format('Y-m-d H:i:s') .' | '. $penilai->NAMA_PENILAI .' telah telah menjawab kuesioner Anda.
						</div>
					',
					"AKTIF" => 'Y',
					"DIBACA" => "N"
				);
				$this->MyModel->notifikasi_buat($notifikasi);
			}
			redirect(base_url('kritik_saran/terima_kasih'));
		}else{
			redirect(base_url('kritik_saran'));
		}
		
	}

	public function tambah_pertanyaan(){
		$this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
		$this->form_validation->set_rules('variabel', 'Variabel', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
            $callback = array(
				'status'=>'sukses',
				'pesan'=>'Berhasil disimpan!'
            );
            $this->MyModel->tambah_kuesioner($_POST);
            echo json_encode($callback);
        }
	}

	public function hapus_pertanyaan(){
		$this->form_validation->set_rules('id', 'ID', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if(($this->MyModel->hapus_kuesioner($_POST))>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'Berhasil dihapus!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Terjadi kesalahan!'
				);
				echo json_encode($callback);
			}
        }
	}

	public function hapus_responden(){
		//var_dump($_POST);
		$this->form_validation->set_rules('id', 'ID', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if(($this->MyModel->hapus_responden($_POST))>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'Berhasil dihapus!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Terjadi kesalahan!'
				);
				echo json_encode($callback);
			}
        }
	}

	public function ubah_pertanyaan(){
		$this->form_validation->set_rules('pertanyaan', 'Pertanyaan', 'required');
		$this->form_validation->set_rules('variabel', 'Variabel', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if($this->MyModel->ubah_kuesioner($_POST)>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'Berhasil disimpan!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Terjadi kesalahan!'
				);
				echo json_encode($callback);
			}
        }
	}

	public function hapus_jawaban(){
		$this->form_validation->set_rules('id', 'ID', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if(($this->MyModel->hapus_jawaban($_POST))>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'Berhasil dihapus!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Terjadi kesalahan!'
				);
				echo json_encode($callback);
			}
        }
	}

	public function hapus_kritik(){
		//var_dump($_POST);
		if(($this->MyModel->hapus_kritik($_POST))>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Terjadi kesalahan!'
			);
			echo json_encode($callback);
		}
	}
	
	public function tambah_skala(){
		$this->form_validation->set_rules('nilai', 'Nilai', 'required|integer');
		$this->form_validation->set_rules('predikat', 'Predikat', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
            $callback = array(
				'status'=>'sukses',
				'pesan'=>'Berhasil disimpan!'
            );
            $this->MyModel->tambah_skala($_POST);
            echo json_encode($callback);
        }
	}

	public function hapus_skala(){
		$this->form_validation->set_rules('id', 'ID', 'required|integer');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if($this->MyModel->hapus_skala($_POST)>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'Berhasil disimpan!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Terjadi kesalahan!'
				);
				echo json_encode($callback);
			}
        }
	}

	public function edit_skala(){
		$this->form_validation->set_rules('id', 'ID', 'required|integer');
		$this->form_validation->set_rules('nilai', 'Nilai', 'required|integer');
		$this->form_validation->set_rules('predikat', 'predikat', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if($this->MyModel->edit_skala($_POST)>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'Berhasil disimpan!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Terjadi kesalahan!'
				);
				echo json_encode($callback);
			}
        }
	}

	public function laporan_jawaban(){
		//var_dump($this->MyModel->statistik_jawaban());
		$laporan = $this->MyModel->statistik_jawaban();
		$variabel = array();
		$nilai = array();
        $judul = "Statistik Jawaban";
        foreach($laporan as $report){
            $variabel[] = $report->VARIABEL;
            $nilai[] = $report->NILAI;
        }
        $callback = array(
            "sukses" => "sukses",
            "judul" => $judul,
            "variabel" => $variabel,
            "nilai" => $nilai
        );
        echo json_encode($callback);
    }

}