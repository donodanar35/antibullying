<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bio extends CI_Controller {
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

		$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
        $data['kategori'] = $this->MyModel->kategori();
        $jumlah_data = $this->MyModel->totalArtikelIndex();
        
		$identitas = $this->MyModel->getidentitas();
		if($identitas->TEMPLATE == 'standar'){
			$this->template('frontend/standar/bio.html',$data);
		}else if($identitas->TEMPLATE == 'meranda'){	
			$this->template('frontend/meranda/bio.html',$data);
		}else{
			$this->template('frontend/standar/bio.html',$data);
		}   
	}

	public function penulis($id=null){
		if(!empty($_SESSION['user_id'])){
			$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
			$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);	
		}else{
			$data['notifikasi'] = 0;
		}
		$data['judul'] = 'Penulis';
		$beranda = $this->MyModel->getidentitas();
		if(!is_null($id)){
			$bio = explode("-", $id);
			$is_bio = count($bio);
			if($is_bio==3){
				$pengguna = $this->MyModel->getPenggunaByNamaLengkapID($bio[2]);
				if(!empty($pengguna)){
					$data['pengguna'] = $pengguna;
					$data['total_pengikut'] = $this->total_pengikut($bio[2]);
					$data['total_mengikuti'] = $this->total_mengikuti($bio[2]);
					$data['total_artikel'] = $this->total_artikel($bio[2]);
					$data['artikel'] =  $this->MyModel->getArtikelByUsername($bio[2]);
					$data['identitas'] = array(
						'judul' => $beranda->NAMA_WEBSITE,
						'meta_deskripsi' => $beranda->META_DESKRIPSI,
						'keywords' => $beranda->META_KEYWORD,
						'author' => 'Danar Dono'
					);
					$data['tagar_populer'] = $this->MyModel->tagar_populer();
					$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
					$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
					$data['kategori'] = $this->MyModel->kategori();
					$jumlah_data = $this->MyModel->totalArtikelIndex();
					$identitas = $this->MyModel->getidentitas();
					if($identitas->TEMPLATE == 'standar'){
						$this->template('frontend/standar/bio.html',$data);
					}else if($identitas->TEMPLATE == 'meranda'){	
						$this->template('frontend/meranda/bio.html',$data);
					}else{
						$this->template('frontend/standar/bio.html',$data);
					}  
				}else{
					$data['identitas'] = array(
						'judul' => $beranda->NAMA_WEBSITE,
						'meta_deskripsi' => $beranda->META_DESKRIPSI,
						'keywords' => $beranda->META_KEYWORD,
						'author' => 'Danar Dono'
					);
					$data['tagar_populer'] = $this->MyModel->tagar_populer();
					$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
					$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
					$data['kategori'] = $this->MyModel->kategori();
					$jumlah_data = $this->MyModel->totalArtikelIndex();
					$identitas = $this->MyModel->getidentitas();
					if($identitas->TEMPLATE == 'standar'){
						$this->template('frontend/standar/blog-single-error.html',$data); 
					}else if($identitas->TEMPLATE == 'meranda'){	
						$this->template('frontend/meranda/blog-single-error.html',$data); 
					}else{
						$this->template('frontend/standar/blog-single-error.html',$data); 
					} 
				}
			}else{
				$data['identitas'] = array(
					'judul' => $beranda->NAMA_WEBSITE,
					'meta_deskripsi' => $beranda->META_DESKRIPSI,
					'keywords' => $beranda->META_KEYWORD,
					'author' => 'Danar Dono'
				);
				$data['tagar_populer'] = $this->MyModel->tagar_populer();
				$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
				$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
				$data['kategori'] = $this->MyModel->kategori();
				$jumlah_data = $this->MyModel->totalArtikelIndex();
				$identitas = $this->MyModel->getidentitas();
				if($identitas->TEMPLATE == 'standar'){
					$this->template('frontend/standar/blog-single-error.html',$data); 
				}else if($identitas->TEMPLATE == 'meranda'){	
					$this->template('frontend/meranda/blog-single-error.html',$data); 
				}else{
					$this->template('frontend/standar/blog-single-error.html',$data); 
				} 
			}
		}else{
			$data['identitas'] = array(
				'judul' => $beranda->NAMA_WEBSITE,
				'meta_deskripsi' => $beranda->META_DESKRIPSI,
				'keywords' => $beranda->META_KEYWORD,
				'author' => 'Danar Dono'
			);
			$data['tagar_populer'] = $this->MyModel->tagar_populer();
			$data['kategori_pilihan'] = $this->MyModel->kategori_pilihan();
			$data['artikel_populer'] = $this->MyModel->getArtikelByPopuler();
			$data['kategori'] = $this->MyModel->kategori();
			$jumlah_data = $this->MyModel->totalArtikelIndex();
			$identitas = $this->MyModel->getidentitas();
			if($identitas->TEMPLATE == 'standar'){
				$this->template('frontend/standar/blog-single-error.html',$data); 
			}else if($identitas->TEMPLATE == 'meranda'){	
				$this->template('frontend/meranda/blog-single-error.html',$data); 
			}else{
				$this->template('frontend/standar/blog-single-error.html',$data); 
			} 
		}  
	}

	public function total_pengikut($id){
		$total_pengikut = 0;
        $pengikut = $this->MyModel->getpengikut($id);
        if(empty($pengikut)){
            $total =0;
        }else{
            $total = $this->MyModel->total_pengikutByPengguna($pengikut->ID_PENGIKUT);
            if(empty($total) || !isset($total) || is_null($total)){
                $total = 0;
            }else{
                $total_pengikut = $total->PENGIKUT;
            }
		}
		return $total_pengikut;
	}
	
	public function total_mengikuti($id){
		$total_mengikuti = 0;
        $mengikuti = $this->MyModel->getmengikuti($id);
        if(empty($mengikuti)){
            $total_mengikuti = 0;
        }else{
            $total = $this->MyModel->total_mengikutiByPengguna($mengikuti->ID_MENGIKUTI);
            if(empty($total) || !isset($total) || is_null($total)){
                $total_mengikuti = 0;
            }else{
				$total_mengikuti = $total->MENGIKUTI; 
            }  
        }
        return $total_mengikuti;
	}
	
	public function total_artikel($id){
        $total_artikel = $this->MyModel->total_artikelByPengguna($id);
        return $total_artikel;
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

	public function cek_mengikuti(){
		//var_dump($_POST);
		if($_POST['id_user_mengikuti']>0){
			$pengguna = $this->MyModel->getPenggunaById($_POST['id_user_mengikuti']);
			if($pengguna->ID == $_POST['id_bio']){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'N'
				);
				echo json_encode($callback);
			}else{
				$mengikuti = $this->MyModel->getmengikuti($pengguna->ID);
				if(empty($mengikuti)){
					$callback = array(
						'status'=>'gagal',
						'pesan'=>'N'
					);
					echo json_encode($callback);
				}else{
					$is_mengikuti = $this->MyModel->getdetailmengikuti($_POST['id_bio'],$mengikuti->ID_MENGIKUTI);
					if(!empty($mengikuti)){
						$is_mengikuti = $this->MyModel->getdetailmengikuti($_POST['id_bio'],$mengikuti->ID_MENGIKUTI);
						//var_dump($is_mengikuti);
						if(empty($is_mengikuti)){
							$callback = array(
								'status'=>'gagal',
								'pesan'=>'N'
							);
							echo json_encode($callback);
						}else{
							if($is_mengikuti->AKTIF=='Y'){
								$callback = array(
									'status'=>'sukses',
									'pesan'=>'Y'
								);
								echo json_encode($callback);
							}else{
								$callback = array(
									'status'=>'sukses',
									'pesan'=>'N'
								);
								echo json_encode($callback);
							}
						}
					}else{
						$callback = array(
							'status'=>'gagal',
							'pesan'=>'N'
						);
						echo json_encode($callback);
					}
				}	
			}
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'N'
			);
			echo json_encode($callback);
		}
	}
	
	public function aksi(){
		$mengikuti = $this->mengikuti();
		$diikuti = $this->diikuti();
		if($mengikuti['status']=='sukses' && $diikuti['status']=='sukses'){
			echo json_encode($mengikuti);
		}else if($mengikuti['status']=='gagal' && $diikuti['status']=='gagal'){
			echo json_encode($mengikuti);
		}else if($mengikuti['status']=='gagal'){
			echo json_encode($mengikuti);
		}else if($diikuti['status']=='gagal'){
			echo json_encode($diikuti);
		}
	}

	public function mengikuti(){
		$callback = array(
			'status'=>'gagal',
			'pesan'=>'Anda belum login!'
		);
		
		if($_POST['id_user_mengikuti']==0){
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Anda belum login!'
			);
			//echo json_encode($callback);
		}else{
			//cek apakah pengguna = bio
			$pengguna = $this->MyModel->getPenggunaById($_POST['id_user_mengikuti']);
			if($_POST['id_bio']==$pengguna->ID){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Anda adalah penulisnya!'
				);
			}else{
				//cek apakah pengguna tersedia
				if($pengguna->ID>0){
					//cek apakah pengguna sudah punya akun mengikuti
					$mengikuti = $this->MyModel->getmengikuti($pengguna->ID);
					if(empty($mengikuti)){
						//jika pengguna belum memiliki akun mengikuti, buat akun mengikuti
						$this->MyModel->buat_mengikuti($pengguna->ID);
						$akun_mengikuti = $this->MyModel->getmengikuti($pengguna->ID);
						$this->MyModel->tambah_detailmengikuti($_POST['id_bio'], $akun_mengikuti->ID_MENGIKUTI);
						$callback = array(
							'status'=>'sukses',
							'pesan'=>'Berhasil mengikuti!'
						);
						//echo json_encode($callback);
					}else{
						//jika pengguna sudah punya akun mengikuti, cek akun detal_mengikuti apakah sudah ada bio 
						$is_detailmengikuti = $this->MyModel->getdetailmengikuti($_POST['id_bio'], $mengikuti->ID_MENGIKUTI);
						if(!empty($is_detailmengikuti)){
							//jika bio tersedia, perbarui mengikuti bio
							if($is_detailmengikuti->AKTIF=="Y"){
								//batal mengikuti bio
								if($this->MyModel->perbarui_detailmengikuti($is_detailmengikuti->ID_DETAILMENGIKUTI,"Y")>0){
									$total = $mengikuti->TOTAL_MENGIKUTI - 1;
									$this->MyModel->perbarui_total_mengikuti($total, $mengikuti->ID_MENGIKUTI);
									$callback = array(
										'status'=>'sukses',
										'pesan'=>'Batal mengikuti!'
									);
									//echo json_encode($callback);
								}
							}else{
								//mengikuti bio kembali
								if($this->MyModel->perbarui_detailmengikuti($is_detailmengikuti->ID_DETAILMENGIKUTI,"N")>0){
									$total = $mengikuti->TOTAL_MENGIKUTI + 1;
									$this->MyModel->perbarui_total_mengikuti($total, $mengikuti->ID_MENGIKUTI);
									$callback = array(
										'status'=>'sukses',
										'pesan'=>'Mengikuti kembali!'
									);
									//echo json_encode($callback);
								}
							}
						}else{
							//jika bio belum tersedia, buat mengikuti bio
							$this->MyModel->tambah_detailmengikuti($_POST['id_bio'], $mengikuti->ID_MENGIKUTI);
							$total = $mengikuti->TOTAL_MENGIKUTI + 1;
							$this->MyModel->perbarui_total_mengikuti($total, $mengikuti->ID_MENGIKUTI);
							$callback = array(
								'status'=>'sukses',
								'pesan'=>'Berhasil mengikutinya!'
							);
							//echo json_encode($callback);
						}
					}
				}else{
					//jika pengguna tidak tersedia, berarti terjadi kesalahan
					$callback = array(
						'status'=>'gagal',
						'pesan'=>'Terjadi kesalahan! Pengguna tidak terdaftar!'
					);
					//echo json_encode($callback);
				}
			}
		}
		return $callback;
	}

	public function diikuti(){
		$callback = array(
			'status'=>'gagal',
			'pesan'=>'Anda belum login!'
		);

		if($_POST['id_user_mengikuti']==0){
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Anda belum login!'
			);
			//echo json_encode($callback);
		}else{
			//cek apakah pengguna = bio
			$pengguna = $this->MyModel->getPenggunaById($_POST['id_user_mengikuti']);
			if($_POST['id_bio']==$pengguna->ID){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Anda adalah penulisnya!'
				);
			}else{
				if($_POST['id_bio']>0){
					//cek apakah pengguna sudah punya akun [pengikut]
					$diikuti = $this->MyModel->getdiikuti($_POST['id_bio']);
					if(empty($diikuti)){
						//jika pengguna belum memiliki akun [pengikut], buat akun [pengikut]
						$this->MyModel->buat_diikuti($_POST['id_bio']);
						$akun_diikuti = $this->MyModel->getdiikuti($_POST['id_bio']);
						$pengguna = $this->MyModel->getPenggunaById($_POST['id_user_mengikuti']);
						$this->MyModel->tambah_detaildiikuti($akun_diikuti->ID_PENGIKUT, $pengguna->ID);
						$callback = array(
							'status'=>'sukses',
							'pesan'=>'Berhasil diikuti!'
						);
						//echo json_encode($callback);
					}else{
						//jika bio sudah punya akun [pengikut], cek akun [detail_pengikut] apakah sudah ada pengguna
						$pengguna = $this->MyModel->getPenggunaById($_POST['id_user_mengikuti']); 
						$is_detailpengikut = $this->MyModel->getdetailpengikut($diikuti->ID_PENGIKUT,$pengguna->ID);	
						if(empty($is_detailpengikut->ID_DETAILPENGIKUT)){
							//jika pengguna belum tersedia, buat diikuti pengguna
							$akun_diikuti = $this->MyModel->getdiikuti($_POST['id_bio']);
							$pengguna = $this->MyModel->getPenggunaById($_POST['id_user_mengikuti']);
							$this->MyModel->tambah_detaildiikuti($akun_diikuti->ID_PENGIKUT, $pengguna->ID);
							$total = $diikuti->TOTAL_PENGIKUT + 1;
							$this->MyModel->perbarui_total_pengikut($total, $diikuti->ID_PENGIKUT);
							$callback = array(
								'status'=>'sukses',
								'pesan'=>'Berhasil diikutinya!'
							);
							//echo json_encode($callback);

							$timezone = new DateTimeZone('Asia/Jakarta');
							$tgl = new DateTime();
							$tgl->setTimeZone($timezone);
							$url_photo = "";
							if($pengguna->PHOTO_PROFIL<>""){
								$url_photo = base_url($pengguna->PHOTO_PROFIL);
							}else{
								$url_photo = base_url('asset/logo/user.png');
							}
							$first_name = explode(' ', $pengguna->FIRST_NAME); 
							$first_name2 = implode('_', $first_name);
							$last_name = explode(' ', $pengguna->LAST_NAME); 
							$last_name2 = implode('_', $last_name);							
							$url = base_url('bio/penulis/') . $first_name2 . '-' . $last_name2 .'-'. $balas['ID'];

							$notifikasi = array(
								"ID" => $_POST['id_bio'],
								"TANGGAL" => $tgl->format('Y-m-d H:i:s'),
								"NOTIFIKASI" => '
									<h5><a href="'. $url .'">'. $pengguna->FIRST_NAME . ' ' . $pengguna->LAST_NAME .' telah mengikuti Anda!</a></h5>
									<div class="media">
										<img width="3%" height="3%" class="d-flex mr-3 rounded-circle" src="'. $url_photo .'" alt="">
										'. $tgl->format('Y-m-d H:i:s') .' | '. $pengguna->FIRST_NAME . ' ' . $pengguna->LAST_NAME .' telah mengikuti Anda.
									</div>
								',
								"AKTIF" => 'Y',
								"DIBACA" => "N"
							);
							$this->MyModel->notifikasi_buat($notifikasi);

						}else{
							//jika bio punya akun [pengikut] tersedia, perbarui [pengikut] bio
							if($is_detailpengikut->AKTIF=="Y"){
								//batal diikuti bio
								if($this->MyModel->perbarui_detaildiikuti($is_detailpengikut->ID_DETAILPENGIKUT,"Y")>0){
									$total = $diikuti->TOTAL_PENGIKUT - 1;
									$this->MyModel->perbarui_total_pengikut($total, $diikuti->ID_PENGIKUT);
									$callback = array(
										'status'=>'sukses',
										'pesan'=>'Batal diikuti!'
									);
									//echo json_encode($callback);
								}
							}else{
								//diikuti bio kembali
								if($this->MyModel->perbarui_detaildiikuti($is_detailpengikut->ID_DETAILPENGIKUT,"N")>0){
									$total = $diikuti->TOTAL_PENGIKUT + 1;
									$this->MyModel->perbarui_total_pengikut($total, $diikuti->ID_PENGIKUT);
									$callback = array(
										'status'=>'sukses',
										'pesan'=>'Diikuti kembali!'
									);
									//echo json_encode($callback);
								}
							}
	
						}
					}
				}else{
					//jika pengguna tidak tersedia, berarti terjadi kesalahan
					$callback = array(
						'status'=>'gagal',
						'pesan'=>'Terjadi kesalahan! Pengguna tidak terdaftar!'
					);
					//echo json_encode($callback);
				}
			}
		}
		return $callback;
	}

	public function tes(){
		/*$kalimat ="kamu jahat sekali sih";
		$first_name = explode(' ', $kalimat);
		$first_name = implode('', $first_name);
		echo $first_name;*/
		echo 0/0;
	}

}