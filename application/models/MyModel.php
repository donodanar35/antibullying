<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyModel extends CI_Model {
	public function __Contruct(){
		parent::__Contruct();
		$this->load->database();
		require_once(strtolower("libraries/src/Sastrawi/Stemmer/StemmerFactory.php"));
		require_once(strtolower("libraries/src/Sastrawi/StopWordRemover/StopWordRemoverFactory.php"));
	}

	//Laporan
	public function total_artikelByPengguna($id){
		$this->db->from('posting');
		$this->db->where('AKTIF',"Y");
		$this->db->where('ID',$id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function total_tayanganByPengguna($id){
		$this->db->from('view');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID',$id);
		$query = $this->db->get();
		return $query->num_rows();	
	}

	public function total_ratingByPengguna($id){
		$this->db->select('AVG(rating.RATING) AS RATING');
		$this->db->from('rating');
		$this->db->join('posting', 'posting.ID_POSTING = rating.ID_POSTING', 'left');
		$this->db->where('rating.AKTIF','Y');
		$this->db->where('posting.ID',$id);
		$query = $this->db->get();
        return $query->row();
	}

	public function total_komentarByPengguna($id){
		$this->db->select('COUNT(komentar.ID_KOMENTAR) AS KOMENTAR');
		$this->db->from('komentar');
		$this->db->join('posting', 'posting.ID_POSTING = komentar.ID_POSTING', 'left');
		$this->db->where('komentar.AKTIF','Y');
		$this->db->where('posting.ID',$id);
		$query = $this->db->get();
        return $query->row();
	}

	public function total_pengikutByPengguna($id){
		$this->db->select('COUNT(detail_pengikut.ID_PENGIKUT) AS PENGIKUT');
		$this->db->from('pengikut');
		$this->db->join('detail_pengikut', 'pengikut.ID_PENGIKUT = detail_pengikut.ID_PENGIKUT', 'left');
		$this->db->where('detail_pengikut.AKTIF','Y');
		$this->db->where('pengikut.ID_PENGIKUT',$id);
		$query = $this->db->get();
        return $query->row();
	}

	public function total_mengikutiByPengguna($id){
		$this->db->select('COUNT(detail_mengikuti.ID_mengikuti) AS MENGIKUTI');
		$this->db->from('mengikuti');
		$this->db->join('detail_mengikuti', 'mengikuti.ID_MENGIKUTI = detail_mengikuti.ID_MENGIKUTI', 'left');
		$this->db->where('detail_mengikuti.AKTIF','Y');
		$this->db->where('mengikuti.ID_MENGIKUTI',$id);
		$query = $this->db->get();
        return $query->row();
	}

	public function laporan_rating($id){
		$this->db->select('AVG(RATING) AS RATING, TANGGAL_RATING AS TANGGAL');
		$this->db->from('rating');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_POSTING',$id);
		$this->db->group_by('TANGGAL_RATING');
		$this->db->order_by('TANGGAL_RATING','ASC');
		$query = $this->db->get();
        return $query;
	}

	public function laporan_artikel($id){
		$this->db->select('COUNT(VIEW) AS VIEW, TANGGAL_VIEW AS TANGGAL');
		$this->db->from('view');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_POSTING',$id);
		$this->db->group_by('TANGGAL_VIEW');
		$this->db->order_by('TANGGAL_VIEW','ASC');
		$query = $this->db->get();
        return $query;
	}

	public function laporan_tayangan($id){
		$this->db->select('COUNT(VIEW) AS VIEW, TANGGAL_VIEW AS TANGGAL');
		$this->db->from('view');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID',$id);
		$this->db->group_by('TANGGAL_VIEW');
		$this->db->order_by('TANGGAL_VIEW','ASC');
		$query = $this->db->get();
        return $query;
	}

	public function laporan_pengujian(){
		$this->db->select('DATA_TES, AKURASI, ERROR_RATE, SPECIFICITY, RECALL, PRECISI, F_SCORE');
		$this->db->from('pengujian');
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query;
	}

	public function get_artikelById($id){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_POSTING',$id);
		$this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
	}

	public function count_notifikasi($id){
		$this->db->from('notifikasi');
		$this->db->where('AKTIF','Y');
		$this->db->where('DIBACA','N');
		$this->db->where('ID',$id);
        $query = $this->db->get();
        return $query->num_rows();
	}

	public function total_notifikasi($id){
		$this->db->from('notifikasi');
		$this->db->where('notifikasi.AKTIF','Y');
		$this->db->where('notifikasi.ID',$id);
		$query = $this->db->get();
        return $query->num_rows();
	}

	public function tandai_allnotifikasi($id){
		$dataUbah = array(
			'DIBACA' => 'Y'
		);
		$this->db->where('ID', $id);
		$this->db->update('notifikasi',$dataUbah);
		return $this->db->affected_rows();
	}

	public function getnotifikasi($id,$limit,$offset){
		$this->db->select('*'); 
		$this->db->from('notifikasi');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID',$id);
		$this->db->limit($limit,$offset);
		$this->db->order_by('ID_NOTIFIKASI','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function notifikasi_buat($data = null){
		$dataku = array(
			"ID" => $data['ID'],
			"TANGGAL_NOTIFIKASI" => $data['TANGGAL'],
			"NOTIFIKASI" => $data['NOTIFIKASI'],
			"AKTIF" => 'Y',
			"DIBACA" => $data['DIBACA']
		);
		$this->db->insert('notifikasi', $dataku);	
	}

	public function get_admin(){
		$this->db->from('users_groups');
		$this->db->where('group_id',1);
		$this->db->group_by('user_id');
		$query = $this->db->get();
        return $query;
	}

	public function getkomentar($id_komentar){
		$this->db->select('komentar.ID_KOMENTAR AS ID_KOMENTAR, komentar.KOMENTAR AS KOMENTAR, pengguna.FIRST_NAME AS DEPAN, pengguna.LAST_NAME AS BELAKANG, pengguna.ID AS ID, pengguna.PHOTO_PROFIL AS PHOTO_PROFIL, posting.JUDUL AS JUDUL, posting.ID_POSTING AS ID_POSTING, posting.JUDUL_URL AS JUDUL_URL'); 
		$this->db->from('komentar');
		$this->db->join('pengguna', 'komentar.ID = pengguna.ID' , 'left' );
		$this->db->join('posting', 'komentar.ID_POSTING = posting.ID_POSTING' , 'left' );
		$this->db->where('komentar.ID_KOMENTAR',$id_komentar);
		$this->db->where('komentar.AKTIF','Y');
        $query = $this->db->get();
        return $query->row();
	}

	public function getkomentarbalasan($id_komentar){
		$this->db->select('balasan_komentar.ID_KOMENTAR AS ID_KOMENTAR, balasan_komentar.KOMENTAR AS KOMENTAR, pengguna.FIRST_NAME AS DEPAN, pengguna.LAST_NAME AS BELAKANG, pengguna.ID AS ID, pengguna.PHOTO_PROFIL AS PHOTO_PROFIL, komentar.ID_KOMENTAR AS ID_KOMENTAR, posting.JUDUL AS JUDUL, posting.ID_POSTING AS ID_POSTING, posting.JUDUL_URL AS JUDUL_URL'); 
		$this->db->from('balasan_komentar');
		$this->db->join('komentar', 'komentar.ID_KOMENTAR = balasan_komentar.ID_KOMENTAR' , 'left' );
		$this->db->join('posting', 'komentar.ID_POSTING = posting.ID_POSTING' , 'left' );
		$this->db->join('pengguna', 'balasan_komentar.ID = pengguna.ID' , 'left' );
		$this->db->where('balasan_komentar.ID_BALASAN',$id_komentar);
		$this->db->where('balasan_komentar.AKTIF','Y');
        $query = $this->db->get();
        return $query->row();
	}

	public function getTopArtikel($id=0){
		$query = $this->db->query("
			SELECT posting.TANGGAL AS TANGGAL, pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, posting.JUDUL_URL, posting.JUDUL AS JUDUL, SUM(view.VIEW) AS TOTAL, kategori.NAMA_KATEGORI AS NAMA_KATEGORI FROM posting, view, pengguna, kategori WHERE posting.ID_POSTING = view.ID_POSTING AND posting.ID_KATEGORI=kategori.ID_KATEGORI AND posting.ID=pengguna.ID AND pengguna.ID='". $id ."' group by posting.ID_POSTING order by TOTAL DESC limit 5");
		return $query->result();
	}

	public function getTopRating($id=0){
		$query = $this->db->query("
			SELECT AVG(rating.RATING) AS RATING, posting.TANGGAL AS TANGGAL, pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, posting.JUDUL_URL, posting.JUDUL AS JUDUL, SUM(view.VIEW) AS TOTAL, kategori.NAMA_KATEGORI AS NAMA_KATEGORI FROM posting, view, pengguna, kategori, rating WHERE posting.ID_POSTING = rating.ID_POSTING AND posting.ID_POSTING = view.ID_POSTING AND posting.ID_KATEGORI=kategori.ID_KATEGORI AND posting.ID=pengguna.ID AND pengguna.ID='". $id ."' group by posting.ID_POSTING order by RATING DESC limit 5");
		return $query->result();
	}

	//galeri model
	public function total_gallery($id){
		$this->db->from('gallery');
		$this->db->where('gallery.AKTIF','Y');
		$this->db->where('gallery.ID',$id);
		$query = $this->db->get();
        return $query->num_rows();
	}

	public function gallery_buat($data = null){
		$dataku = array(
			"ID" => $data['ID'],
			"NAMA_GALLERY" => $data['JUDUL'],
			"KETERANGAN" => 'Foto Artikel ' . $data['JUDUL'],
			"AKTIF" => 'Y',
			"PHOTO" => $data['PHOTO']
		);
		$this->db->insert('gallery', $dataku);	
	}

	public function gallery_upload($data = null){
		$dataku = array(
			"ID" => $data['id'],
			"NAMA_GALLERY" => $data['judul'],
			"KETERANGAN" => $data['deskripsigambar'],
			"AKTIF" => 'Y',
			"PHOTO" => $data['pathfoto']
		);
		$this->db->insert('gallery', $dataku);	
	}

	public function gallery($id,$limit=null,$offset=null){
		$this->db->select('*'); 
		$this->db->from('gallery');
		$this->db->where('gallery.AKTIF','Y');
		$this->db->where('gallery.ID',$id);
		$this->db->order_by('ID_GALLERY','DESC');
		$this->db->limit($limit,$offset);
		$query = $this->db->get();
        return $query;
	}

	public function getGaleriById($id_galeri)
	{
		$this->db->select ('*'); 
		$this->db->from('gallery');
		$this->db->WHERE('gallery.AKTIF','Y');
		$this->db->WHERE('gallery.ID_GALLERY',$id_galeri);
		$query = $this->db->get();
		return $query->row();
	}

	public function galeri_hapus($data){
		$dataUbah = array(
			'AKTIF' => 'N'
		);
		$this->db->where('ID_GALLERY', $data['idgaleri']);
		$this->db->update('gallery',$dataUbah);
		return $this->db->affected_rows();
	}


	//posting model
	public function artikel_buat($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);

		$jumlah_data = $this->totalArtikelIndex();
		$artikelke = $jumlah_data + 1;
		$judulku = preg_replace("/[^a-zA-Z0-9]/", " ", $data['judul']);
		$judulku = str_replace("/", "-", $judulku);
		$judulUrl = str_replace(' ','-',$judulku) . '-' . $artikelke;

		if(! empty($data['pathfoto']) && (! is_null($data['pathfoto']))){
			$dataBuat = array(
				'ID' => $data['iduser'],
				'ID_KATEGORI' => $data['kategori'],
				'JUDUL' => $data['judul'],
				"JUDUL_URL" => $judulUrl,
				'ISI_ARTIKEL' => $data['isiartikel'],
				"PHOTO" => $data['pathfoto'],
				"DESKRIPSI_ARTIKEL" => $data['deskripsiartikel'],
				"KATA_KUNCI" => $data['katakunci'],
				"TANGGAL" => $tgl->format('Y-m-d H:i:s'),
				"AKTIF" => 'Y'
			);
			$this->gallery_buat($dataBuat);
		}else{			
			$dataBuat = array(
				'ID' => $data['iduser'],
				'ID_KATEGORI' => $data['kategori'],
				'JUDUL' => $data['judul'],
				"JUDUL_URL" => $judulUrl,
				'ISI_ARTIKEL' => $data['isiartikel'],
				"PHOTO" => 'images/standar.jpg',
				"DESKRIPSI_ARTIKEL" => $data['deskripsiartikel'],
				"KATA_KUNCI" => $data['katakunci'],
				"TANGGAL" => $tgl->format('Y-m-d H:i:s'),
				"AKTIF" => 'Y'
			);
		}
		$this->db->insert('posting', $dataBuat);
		$callback = array(
			"JUDUL" => $dataBuat['JUDUL'],
			"JUDUL_URL" => $dataBuat['JUDUL_URL']
		);
		return $callback;
	}

	public function artikel_cari($id, $cari){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join ('kategori', 'kategori.ID_KATEGORI = posting.id_kategori' , 'left' );
		$this->db->join ('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->where('posting.aktif','Y');
		$this->db->where('posting.ID',$id);
		$this->db->like('posting.JUDUL',$cari);
	    $query = $this->db->get();
        return $query;
	}

	

	public function totalArtikelByKategori($kategori){
		$this->db->from('posting');
		$this->db->join ('kategori', 'kategori.ID_KATEGORI = posting.ID_KATEGORI' , 'left' );
		$this->db->join ('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->order_by('ID_POSTING','DESC');
		$this->db->like('kategori.NAMA_KATEGORI',$kategori);
		$this->db->like('posting.AKTIF','Y');
	    $query = $this->db->get();
        return $query->num_rows();
	}

	public function artikel_byKategori($kategori,$limit,$offset){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join ('kategori', 'kategori.ID_KATEGORI = posting.id_kategori' , 'left');
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->like('posting.AKTIF','Y');
		$this->db->like('kategori.NAMA_KATEGORI',$kategori);
		$this->db->limit($limit, $offset);
		$this->db->order_by('ID_POSTING','DESC');
		$query = $this->db->get();
		return $query;
	}

	public function totalArtikelByTagar($tagar){
		$this->db->from('posting');
		$this->db->join('kategori', 'kategori.ID_KATEGORI = posting.ID_KATEGORI' , 'left' );
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->order_by('ID_POSTING','DESC');
		$this->db->like('posting.KATA_KUNCI',$tagar);
		$this->db->like('posting.AKTIF','Y');
	    $query = $this->db->get();
        return $query->num_rows();
	}

	public function artikel_byTagar($tagar,$limit,$offset){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join('kategori', 'kategori.ID_KATEGORI = posting.id_kategori' , 'left');
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->like('posting.AKTIF','Y');
		$this->db->like('posting.KATA_KUNCI',$tagar);
		$this->db->limit($limit, $offset);
		$this->db->order_by('ID_POSTING','DESC');
		$query = $this->db->get();
		return $query;
	}

	public function artikel_byPencarian($cari,$limit,$offset){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join('kategori', 'kategori.ID_KATEGORI = posting.id_kategori' , 'left');
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->like('posting.AKTIF','Y');
		$this->db->like('posting.JUDUL',$cari);
		$this->db->or_like('posting.KATA_KUNCI',$cari);
		$this->db->or_like('posting.DESKRIPSI_ARTIKEL',$cari);
		$this->db->or_like('kategori.NAMA_KATEGORI',$cari);
		$this->db->or_like('pengguna.FIRST_NAME',$cari);
		$this->db->or_like('pengguna.LAST_NAME',$cari);
		$this->db->limit($limit, $offset);
		$this->db->order_by('ID_POSTING','DESC');
		$query = $this->db->get();
		return $query;
	}
	//getArtikelIndex(

	public function artikel($id){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join('kategori', 'kategori.ID_KATEGORI = posting.id_kategori' , 'left' );
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->where('posting.aktif','Y');
		$this->db->where('posting.ID',$id);
		$this->db->order_by('ID_POSTING','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function allartikel(){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join('kategori', 'kategori.ID_KATEGORI = posting.id_kategori' , 'left' );
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->where('posting.aktif','Y');
		$this->db->order_by('ID_POSTING','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function getArtikelByUsername($username){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join ('kategori', 'kategori.ID_KATEGORI = posting.id_kategori' , 'left' );
		$this->db->join ('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->like('posting.aktif','Y');
		$this->db->like('posting.ID',$username);
        $query = $this->db->get();
        return $query;
	}

	public function dataset_artikelUbah($data){
		$dataUbah = array(
			'TOPIK_ARTIKEL' => $data['topikartikel'],
			'ISI_ARTIKEL' => $data['isiartikel'],
			'JUDUL_ARTIKEL' => $data['judulartikel'],
			'SUMBER_ARTIKEL' => $data['sumberartikel'],
			'TANGGAL_ARTIKEL' => $data['tanggalartikel']
        );

        $this->db->where('ID_ARTIKEL', $data['idartikel']);
        $this->db->update('dataset_artikel', $dataUbah);
        return $this->db->affected_rows();
	}

	//awal naive bayes model
	public function cek_kata($kata){
		$hasil = 0;
		$this->db->select('*');
		$this->db->from('kelas_kata');
		$this->db->where('AKTIF','Y');
		$this->db->where('KATA',$kata);
		$this->db->limit('1');
		$query = $this->db->get()->row();
		if(isset($query)){
			return $query;
		}else{
			return $hasil = 0;
		}
	}

	public function kalkuasi_sentimen($tes=""){
		$callback = array();
		$cyberbullying = 1;
		$noncyberbullying = 1;
		$cek = $this->case_folding($tes);
		$cek = $this->cleansing($cek);
		$cek = $this->gabungkan_kata($cek);
		$cek = $this->normalisasi_bahasagaul($cek);
		$cek = $this->stemmer($cek);
		$cek = $this->stopword($cek);
		$tokenizing = $this->tokenizer($cek);
		$cek = explode("<br/>", $tokenizing);
		$count = count($cek);

		for($i=0;$i<$count;$i++){
			$teks = $this->cek_kata($cek[$i]);
			if(!is_int($teks)){
				$cyberbullying = $cyberbullying * $teks->PROBABILITAS_CYBERBULLY;
				$noncyberbullying = $noncyberbullying * $teks->PROBABILITAS_NONCYBERBULLY;
			}else{
				$bully = $this->is_prior('dataset-komentar','cyberbullying');
				$nonbully = $this->is_prior('dataset-komentar','non-cyberbullying');
				$cyberbullying = $cyberbullying * (1 / ($bully->TOTAL_KELASKATA_PLUS_KATAUNIK));
				$noncyberbullying = $noncyberbullying * (1 / ($nonbully->TOTAL_KELASKATA_PLUS_KATAUNIK));
			}
		}

		$prior_bully = $this->is_prior('dataset-komentar','cyberbullying');
		$prior_nonbully = $this->is_prior('dataset-komentar','non-cyberbullying');
		$cyberbullying = $cyberbullying * $prior_bully->PROBABILITAS;
		$noncyberbullying = $noncyberbullying * $prior_nonbully->PROBABILITAS;
		//$cyberbullying = round($cyberbullying,1000000);
		//$noncyberbullying = round($noncyberbullying,1000000); 
		if($cyberbullying<=$noncyberbullying){
			$callback = array(
				'status' => 'sukses',
				'komentar' =>$tes,
				'pesan'=>'non-cyberbullying',
				'preprocessing'=>$tokenizing,
				'bobot_noncyberbullying' => $noncyberbullying,
				'bobot_cyberbullying' => $cyberbullying
			);
		}else{
			$callback = array(
				'status' => 'sukses',
				'komentar' =>$tes,
				'pesan'=>'cyberbullying',
				'preprocessing'=>$tokenizing,
				'bobot_noncyberbullying' => $noncyberbullying,
				'bobot_cyberbullying' => $cyberbullying
			);
		}
		return $callback;
	}

	public function count_alldataset($tabel){
		//$this->db->count_all($tabel);
		//$total_row = $this->db->where("AKTIF","Y");
		//return $total_row;
		$this->db->from($tabel);
		$this->db->where('AKTIF',"Y");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_alldataset_training($tabel){
		$this->db->from($tabel);
		$this->db->where('AKTIF',"Y");
		$this->db->where('MODE',"training");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_akhirdataset($tabel){
		$this->db->from($tabel);
		$this->db->where('AKTIF',"Y");
		$this->db->order_by('ID_DATASETKOMENTAR','DESC');
		$this->db->limit('1');
		$query = $this->db->get()->row();
		return $query;
	}

	public function count_segmen($tabel,$mode,$segmen='awal'){
		$this->db->from($tabel);
		$this->db->where('AKTIF',"Y");
		if($mode=='all'){
		}else{
			$this->db->where('MODE',$mode);
		}
		if($segmen=='awal'){
			$this->db->order_by('ID_DATASETKOMENTAR','ASC');
		}else{
			$this->db->order_by('ID_DATASETKOMENTAR','DESC');
		}
		$this->db->limit('1');
		$query = $this->db->get()->row();
		return $query;
	}

	public function count_akhirdataset_testing($tabel){
		$this->db->from($tabel);
		$this->db->where('AKTIF',"Y");
		$this->db->where('MODE',"TEST");
		$this->db->order_by('ID_DATASETKOMENTAR','DESC');
		$this->db->limit('1');
		$query = $this->db->get()->row();
		return $query;
	}

	public function count_kelasdataset($tabel,$kelas,$mode){
		$this->db->from($tabel);
		$this->db->where('SENTIMEN',$kelas);
		$this->db->where('AKTIF',"Y");
		if($mode=='all'){
		}else{
			$this->db->where('MODE',$mode);
		}
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function hapus_dataset_null(){
		$dataUbah = array(
			'AKTIF' => 'N',
        );
		$this->db->where('AKTIF', 'Y');
        $this->db->where('TOKENIZING', '');
        $this->db->update('dataset_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function resetmode_dataset(){
		$dataUbah = array(
			'MODE' => 'training',
        );
		$this->db->where('AKTIF', 'Y');		
        $this->db->update('dataset_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function split_dataset($data){
		$this->resetmode_dataset();
		
		$segmen_data = $this->count_segmen('dataset_komentar','all','akhir');
		$segmenakhir = $segmen_data->ID_DATASETKOMENTAR;
		$segmen = $segmenakhir * ($data['splitdatatesting'] / 100);
		//$segmenawal = ($segmenakhir - $segmen);

		$dataUbah = array(
			'MODE' => 'testing',
        );
		$this->db->where('AKTIF', 'Y');		
		//$this->db->where('ID_DATASETKOMENTAR >', $segmenawal);
		//$this->db->where('ID_DATASETKOMENTAR <=', $segmenakhir);
		$this->db->where('ID_DATASETKOMENTAR >', 0);
		$this->db->where('ID_DATASETKOMENTAR <=', $segmen);
        $this->db->update('dataset_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function totaldatasetnull(){
		$this->db->select('*');
		$this->db->from('dataset_komentar');
		$this->db->where('AKTIF','Y');
		$this->db->where('TOKENIZING','');
		$cek = $this->db->get()->num_rows();
		return $cek;
	}

	public function hapus_datadetailpengujian(){
		$this->db->query("DELETE FROM detail_pengujian");
		$this->db->query("ALTER TABLE detail_pengujian AUTO_INCREMENT=0");
		return 1;
	}

	public function hapus_datapengujian(){
		$this->db->query("DELETE FROM pengujian");
		$this->db->query("ALTER TABLE pengujian AUTO_INCREMENT=0");
		return 1;
	}

	public function hapus_datakelaskata(){
		$this->db->query("DELETE FROM kelas_kata");
		$this->db->query("ALTER TABLE kelas_kata AUTO_INCREMENT=0");
		return 1;
	}
	
	public function hapus_dataprior(){
		$this->db->query("DELETE FROM prior");
		$this->db->query("ALTER TABLE prior AUTO_INCREMENT=0");
		return 1;
	}

	public function reset_kelaskatamodel(){
		$this->db->trans_begin();
		$komentar = $this->dataset_allkelaskata();
		$result = 0;
		foreach($komentar->result_array() as $comment){
			$this->kelaskata_ubah_reset($comment['ID_KATA']);
		}

        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        	return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function klasifikasi_kata($tokenizing,$sentimen,$id)
	{
		$hasil = 0;
		$arr_kata = explode("<br/>", $tokenizing);
		$count = count($arr_kata);
		for($i=0;$i<$count;$i++){
			$cek = $this->is_kata($arr_kata[$i]);
			if(isset($cek->KATA)){
				$idkata = $cek->ID_KATA;
				$TOTAL_CYBERBULLY = ($cek->TOTAL_CYBERBULLY)+1;
				$TOTAL_NONCYBERBULLY = ($cek->TOTAL_NONCYBERBULLY)+1;
				if($sentimen=='cyberbullying'){
					$dataUbah = array(
						"TOTAL_CYBERBULLY" =>$TOTAL_CYBERBULLY,
					);
				}else{
					$dataUbah = array(
						"TOTAL_NONCYBERBULLY" =>$TOTAL_NONCYBERBULLY
					);
				}
				$this->kelaskata_ubah($dataUbah,$idkata,$sentimen);
			}else{
				if($sentimen=='cyberbullying'){
					$dataBuat = array(
						"KATA" => $arr_kata[$i],
						"TOTAL_CYBERBULLY" => 1,
						"TOTAL_NONCYBERBULLY" => 0,
						"PROBABILITAS_CYBERBULLY" => 0,
						"PROBABILITAS_NONCYBERBULLY" => 0
					);
				}else{
					$dataBuat = array(
						"KATA" => $arr_kata[$i],
						"TOTAL_CYBERBULLY" => 0,
						"TOTAL_NONCYBERBULLY" => 1,
						"PROBABILITAS_CYBERBULLY" => 0,
						"PROBABILITAS_NONCYBERBULLY" => 0
					);
				}
				$this->kelaskata_buat($dataBuat);
			}
			$hasil = 1;
		}
		$dataNull = array(
			"AKTIF" => "N"
		);
		$this->db->where('KATA',"");
		$this->db->update('kelas_kata', $dataNull);
		$this->db->affected_rows();
		return $hasil;
	}


	public function totalkata($kelas="")
	{
		$this->db->select('*');
		$this->db->from('kelas_kata');
		$this->db->where('AKTIF','Y');
		if($kelas=='cyberbullying'){
			$this->db->where('TOTAL_CYBERBULLY >',0);
		}else if($kelas=='non-cyberbullying'){	
			$this->db->where('TOTAL_NONCYBERBULLY >',0);
		}
		$cek = $this->db->get()->num_rows();
		return $cek;
	}

	public function probabilitas_kata(){
		$this->db->trans_begin();
		$kata = $this->allkata();
		foreach($kata->result() as $word){
			if($word->TOTAL_CYBERBULLY>0){
				$id = $word->ID_KATA;
				$P_CYBERBULLY = 0;
				$prior_bully = $this->is_prior('dataset-komentar','cyberbullying');
				$P_CYBERBULLY = ($word->TOTAL_CYBERBULLY + 1)/($prior_bully->TOTAL_KELASKATA_PLUS_KATAUNIK);
				$data = array(
					"PROBABILITAS_CYBERBULLY" =>$P_CYBERBULLY,
				);
				$this->kelaskata_ubah_probabilitas($data,$id,'cyberbullying');
			}else{
				$id = $word->ID_KATA;
				$P_CYBERBULLY = 0;
				$prior_bully = $this->is_prior('dataset-komentar','cyberbullying');
				$P_CYBERBULLY = (1)/($prior_bully->TOTAL_KELASKATA_PLUS_KATAUNIK);
				$data = array(
					"PROBABILITAS_CYBERBULLY" =>$P_CYBERBULLY,
				);
				$this->kelaskata_ubah_probabilitas($data,$id,'cyberbullying');
			}

			if($word->TOTAL_NONCYBERBULLY>0){
				$id = $word->ID_KATA;
				$P_NONCYBERBULLY = 0;
				$prior_nonbully = $this->is_prior('dataset-komentar','non-cyberbullying');
				$P_NONCYBERBULLY = ($word->TOTAL_NONCYBERBULLY + 1)/($prior_nonbully->TOTAL_KELASKATA_PLUS_KATAUNIK);
				$data = array(
					"PROBABILITAS_NONCYBERBULLY" =>$P_NONCYBERBULLY,
				);
				$this->kelaskata_ubah_probabilitas($data,$id,'non-cyberbullying');
			}else{
				$id = $word->ID_KATA;
				$P_NONCYBERBULLY = 0;
				$prior_nonbully = $this->is_prior('dataset-komentar','non-cyberbullying');
				$P_NONCYBERBULLY = (1)/($prior_nonbully->TOTAL_KELASKATA_PLUS_KATAUNIK);
				$data = array(
					"PROBABILITAS_NONCYBERBULLY" =>$P_NONCYBERBULLY,
				);
				$this->kelaskata_ubah_probabilitas($data,$id,'non-cyberbullying');
			}
		}
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        	return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function get_kelaskatamodel(){
		$this->db->trans_begin();
		$komentar = $this->dataset_allkomentar_training();
		//$komentar = $this->dataset_allkomentar();
		$result = 0;
		foreach($komentar->result_array() as $comment){
			if(isset($comment['TOKENIZING'])|| $comment['TOKENIZING']<>"" || !is_null($comment['TOKENIZING'])){
				$result= $this->klasifikasi_kata($comment['TOKENIZING'],$comment['SENTIMEN'],$comment['ID_DATASETKOMENTAR']);
			}
		}
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        	return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function get_priormodel(){
		$this->db->trans_begin();

		$cek_bullying = $this->is_prior('dataset-komentar','cyberbullying');
		if(isset($cek_bullying->ID_PRIOR)){
			$n_cyberbullying = $this->totalkata('cyberbullying');
			//$n_noncyberbullying = $this->totalkata('non-cyberbullying');
			//$totalkata = $n_noncyberbullying + $n_cyberbullying;
			$totalkata = $this->totalkata('all');

			$id = $cek_bullying->ID_PRIOR;
			$totalkelasdata = $this->count_kelasdataset('dataset_komentar','cyberbullying','training');
			$totalseluruhdata = $this->count_alldataset_training('dataset_komentar');
			$probabilitas = $totalkelasdata/$totalseluruhdata;
			$data = array(
				"kelas" => "cyberbullying",
				"jenis" => "dataset-komentar",
				"totalkelasdata" => $totalkelasdata,
				"totalseluruhdata" => $totalseluruhdata,
				"probabilitas" => $probabilitas,
				"total_kelaskata" => $n_cyberbullying,
				"total_seluruhkata" => $totalkata,
				"total_kata" => $totalkata + $n_cyberbullying,
				"aktif" => 'Y'
			);
			$this->prior_ubah($data,$id);
		}else{
			$n_cyberbullying = $this->totalkata('cyberbullying');
			//$n_noncyberbullying = $this->totalkata('non-cyberbullying');
			//$totalkata = $n_noncyberbullying + $n_cyberbullying;
			$totalkata = $this->totalkata('all');

			$totalkelasdata = $this->count_kelasdataset('dataset_komentar','cyberbullying','training');
			$totalseluruhdata = $this->count_alldataset_training('dataset_komentar');
			$probabilitas = $totalkelasdata/$totalseluruhdata;
			$data = array(
				"kelas" => "cyberbullying",
				"jenis" => "dataset-komentar",
				"totalkelasdata" => $totalkelasdata,
				"totalseluruhdata" => $totalseluruhdata,
				"probabilitas" => $probabilitas,
				"total_kelaskata" => $n_cyberbullying,
				"total_seluruhkata" => $totalkata,
				"total_kata" => $totalkata + $n_cyberbullying,
				"aktif" => 'Y'
			);
			$this->prior_buat($data);
		}
		
		$cek_nonbullying = $this->is_prior('dataset-komentar','non-cyberbullying');
		if(isset($cek_nonbullying->ID_PRIOR)){
			//$n_cyberbullying = $this->totalkata('cyberbullying');
			$n_noncyberbullying = $this->totalkata('non-cyberbullying');
			//$totalkata = $n_noncyberbullying + $n_cyberbullying;
			$totalkata = $this->totalkata('all');

			$id = $cek_nonbullying->ID_PRIOR;
			$totalkelasdata = $this->count_kelasdataset('dataset_komentar','non-cyberbullying','training');
			$totalseluruhdata = $this->count_alldataset_training('dataset_komentar');
			$probabilitas = $totalkelasdata/$totalseluruhdata;
			$data = array(
				"kelas" => "non-cyberbullying",
				"jenis" => "dataset-komentar",
				"totalkelasdata" => $totalkelasdata,
				"totalseluruhdata" => $totalseluruhdata,
				"probabilitas" => $probabilitas,
				"total_kelaskata" => $n_noncyberbullying,
				"total_seluruhkata" => $totalkata,
				"total_kata" => $totalkata + $n_noncyberbullying,
				"aktif" => 'Y'
			);
			$this->prior_ubah($data,$id);
		}else{
			//$n_cyberbullying = $this->totalkata('cyberbullying');
			$n_noncyberbullying = $this->totalkata('non-cyberbullying');
			//$totalkata = $n_noncyberbullying + $n_cyberbullying;
			$totalkata = $this->totalkata('all');

			$totalkelasdata = $this->count_kelasdataset('dataset_komentar','non-cyberbullying','training');
			$totalseluruhdata = $this->count_alldataset_training('dataset_komentar');
			$probabilitas = $totalkelasdata/$totalseluruhdata;
			$data = array(
				"kelas" => "non-cyberbullying",
				"jenis" => "dataset-komentar",
				"totalkelasdata" => $totalkelasdata,
				"totalseluruhdata" => $totalseluruhdata,
				"probabilitas" => $probabilitas,
				"total_kelaskata" => $n_noncyberbullying,
				"total_seluruhkata" => $totalkata,
				"total_kata" => $totalkata + $n_noncyberbullying,
				"aktif" => 'Y'
			);
			$this->prior_buat($data);
		}

        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function get_info_datasetkomentar_training(){
		$segmenawal = $this->count_segmen('dataset_komentar','training','awal');
		$segmenawal = $segmenawal->ID_DATASETKOMENTAR;
		$segmenakhir = $this->count_segmen('dataset_komentar','training','akhir');
		$segmenakhir = $segmenakhir->ID_DATASETKOMENTAR;
		$cyberbullying = $this->count_kelasdataset('dataset_komentar','cyberbullying','training');
		$noncyberbullying = $this->count_kelasdataset('dataset_komentar','non-cyberbullying','training');
		$hasil = array(
			"segmenawal" => $segmenawal,
			"segmenakhir" => $segmenakhir,
			"cyberbullying" => $cyberbullying,
			"noncyberbullying" => $noncyberbullying,
			"totaldata" => $cyberbullying + $noncyberbullying
		);
		return $hasil;
	}

	public function get_info_datasetkomentar(){
		$cyberbullying = $this->count_kelasdataset('dataset_komentar','cyberbullying','all');
		$noncyberbullying = $this->count_kelasdataset('dataset_komentar','non-cyberbullying','all');
		$hasil = array(
			"cyberbullying" => $cyberbullying,
			"noncyberbullying" => $noncyberbullying,
			"totaldata" => $cyberbullying + $noncyberbullying
		);
		return $hasil;
	}

	public function get_info_datasetkomentar_testing(){
		$segmenawal = $this->count_segmen('dataset_komentar','testing','awal');
		$segmenawal = $segmenawal->ID_DATASETKOMENTAR;
		$segmenakhir = $this->count_segmen('dataset_komentar','testing','akhir');
		$segmenakhir = $segmenakhir->ID_DATASETKOMENTAR;
		$cyberbullying = $this->count_kelasdataset('dataset_komentar','cyberbullying','testing');
		$noncyberbullying = $this->count_kelasdataset('dataset_komentar','non-cyberbullying','testing');
		$hasil = array(
			"segmenawal" => $segmenawal,
			"segmenakhir" => $segmenakhir,
			"cyberbullying" => $cyberbullying,
			"noncyberbullying" => $noncyberbullying,
			"totaldata" => $cyberbullying + $noncyberbullying
		);
		return $hasil;
	}

	public function hapus_pengujian($id){
		$dataUbah = array(
            'AKTIF' => 'N'
        );
        $this->db->where('ID_UJI', $id);
        $this->db->update('pengujian', $dataUbah);
        return $this->db->affected_rows();
	}

	public function detail_pengujian($id=0){
		$this->db->select('*'); 
		$this->db->from('detail_pengujian');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_UJI',$id);
		$query = $this->db->get();
		return $query;
	}

	public function tambah_pengujian($data)
	{
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);

		$dataBuat = array(
			"TOTAL_DATA" => $data['totaldata'],
			"SEGMEN_AWAL" => $data['segmenawalparam'],
			"SEGMEN_AKHIR" => $data['segmenakhirparam'],
			"JML_CYBERBULLYING" => $data['cyberbullying'],
			"JML_NONCYBERBULLYING" => $data['noncyberbullying'],
			"TP" => 0,
			"FP" => 0,
			"FN" => 0,
			"TN" => 0,
			"AKURASI" => 0,
			"ERROR_RATE" => 0,
			"SPECIFICITY" => 0,
			"RECALL" => 0,
			"PRECISI" => 0,
			"F_SCORE" => 0,
			"TGL_UJI" =>$tgl->format('Y-m-d'),
			"MODE" =>$data['mode'],
			"AKTIF" => 'Y'
		);
		$this->db->insert('pengujian', $dataBuat);
	}

	public function pengujian($id){
		$this->db->trans_begin();
		$pengujian = $this->is_uji($id);
		$segmentasi = $this->segmentasi($id,$pengujian->SEGMEN_AWAL,$pengujian->SEGMEN_AKHIR);
		$uji = array();
		$total_data = $pengujian->TOTAL_DATA;
		$tp = 0;
		$fp = 0;
		$fn = 0;
		$tn = 0;
		$i = 0;
		$n_tes = 0;
		$totalcyberbullying = 0;
		$totalnoncyberbullying = 0;
		$totaldata = 0;

		foreach($segmentasi->result_array() as $segmen){
			if($segmen['SENTIMEN']=='cyberbullying'){
				$totalcyberbullying = $totalcyberbullying + 1; 
			}
			if($segmen['SENTIMEN']=='non-cyberbullying'){
				$totalnoncyberbullying = $totalnoncyberbullying + 1;	
			}
			$uji = $this->MyModel->kalkuasi_sentimen($segmen['ISI_KOMENTAR']);
			$dataBuat = array(
				"ID_UJI" => $pengujian->ID_UJI,
				"ID_KOMENTAR" => $segmen['ID_DATASETKOMENTAR'],
				"KOMENTAR" => $segmen['ISI_KOMENTAR'],
				"HASIL_PREPROCESSING" => $uji['preprocessing'],
				"BOBOT_CYBERBULLYING" => $uji['bobot_cyberbullying'],
				"BOBOT_NONCYBERBULLYING" =>$uji['bobot_noncyberbullying'], 
				"AKTUAL" => $segmen['SENTIMEN'],
				"PREDIKSI" => $uji['pesan'],
				"AKTIF" => 'Y'
			);
			$this->db->insert('detail_pengujian', $dataBuat);
			if($segmen['SENTIMEN']=='cyberbullying' && $uji['pesan']=='cyberbullying'){
				$tp = $tp+1;
			}else if($segmen['SENTIMEN']=='non-cyberbullying' && $uji['pesan']=='non-cyberbullying'){
				$tn = $tn+1;
			}else if($segmen['SENTIMEN']=='cyberbullying' && $uji['pesan']=='non-cyberbullying'){
				$fn = $fn+1;
			}else if($segmen['SENTIMEN']=='non-cyberbullying' && $uji['pesan']=='cyberbullying'){
				$fp = $fp+1;
			}
			$n_tes = $n_tes + 1;
		}
		
		$totaldata = $totalnoncyberbullying + $totalcyberbullying;
						
		$akurasi = 0;
		$error = 0;
		$recall = 0;
		$specificity = 0;
		$precisi = 0;
		$fscore = 0;

		if(($tp+$fn+$fp+$tn)==0){
			$akurasi = 0;
			$error = 0;
		}else{
			$akurasi = ($tp+$tn)/($tp+$fn+$fp+$tn);
			$error = ($fp+$fn)/($tp+$fn+$fp+$tn);
		}
		if(($tp+$fn)==0){
			$recall = 0;
		}else{
			$recall = ($tp)/($tp+$fn);
		}
		if(($fp+$tn)==0){
			$specificity = 0;
		}else{
			$specificity = ($tn)/($fp+$tn);
		}
		if(($tp+$fp)==0){
			$precisi = 0;
		}else{
			$precisi = ($tp)/($tp+$fp);
		}
		if(($precisi+$recall)==0){
			$fscore = 0;
		}else{
			$fscore = (2*$precisi*$recall)/($precisi+$recall);
		}
		$evaluasi = array(
			"tp" => $tp,
			"fp" => $fp,
			"fn" => $fn,
			"tn" => $tn,
			"akurasi" => $akurasi,
			"error" => $error,
			"recall" => $recall,
			"specificity" => $specificity,
			"precisi" => $precisi,
			"fscore" => $fscore,
			"data_tes" => $n_tes,
			"jml_cyberbullying" => $totalcyberbullying,
			"jml_noncyberbullying" => $totalnoncyberbullying
		);
		$this->pengujian_ubah($evaluasi,$pengujian->ID_UJI);
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        }else{
            $this->db->trans_commit();
            return 1;
        }
	}

	public function pengujian_ubah($data,$id){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		
		$dataUbah = array(
			"DATA_TES" => $data['data_tes'],
			"JML_CYBERBULLYING" => $data['jml_cyberbullying'],
			"JML_NONCYBERBULLYING" => $data['jml_noncyberbullying'],
			"TP" => $data['tp'],
			"FP" => $data['fp'],
			"FN" => $data['fn'],
			"TN" => $data['tn'],
			"AKURASI" => $data['akurasi'],
			"ERROR_RATE" => $data['error'],
			"SPECIFICITY" => $data['specificity'],
			"RECALL" => $data['recall'],
			"PRECISI" => $data['precisi'],
			"F_SCORE" => $data['fscore'],
			"TGL_UJI" =>$tgl->format('Y-m-d'),
			"AKTIF" => 'Y'
		);

        $this->db->where('ID_UJI', $id);
        $this->db->update('pengujian', $dataUbah);
        return $this->db->affected_rows();
	}

	public function get_allpengujian(){
		$this->db->select ('*'); 
		$this->db->from('pengujian');
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query;
	}

	public function segmentasi($id,$awal,$akhir){
		$this->db->select('*'); 
		$this->db->from('dataset_komentar');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_DATASETKOMENTAR >=',$awal);
		$this->db->where('ID_DATASETKOMENTAR <=',$akhir);
		$query = $this->db->get();
        return $query;
	}

	public function is_uji($id){
		$this->db->select('*'); 
		$this->db->from('pengujian');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_UJI',$id);
		$this->db->limit('1');
		$query = $this->db->get()->row();
        return $query;
	}

	public function get_datasetkomentar($id = 0){
		$this->db->select ('*'); 
		$this->db->from('dataset_komentar');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_DATASETKOMENTAR',$id);
		$query = $this->db->get();
        return $query;
	}

	public function get_datasetkomentarById($id = 0){
		$this->db->select('*'); 
		$this->db->from('dataset_komentar');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_DATASETKOMENTAR',$id);
		$query = $this->db->get();
        return $query->row();
	}

	public function allkata(){
		$this->db->select ('*'); 
		$this->db->from('kelas_kata');
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query;
	}

	public function is_kata($kata){
		$this->db->select ('*'); 
		$this->db->from('kelas_kata');
		$this->db->where('AKTIF','Y');
		$this->db->where('KATA',$kata);
		$this->db->limit('1');
		$query = $this->db->get()->row();
        return $query;
	}

	public function is_prior($jenis,$kelas){
		$this->db->select('*'); 
		$this->db->from('prior');
		$this->db->where('AKTIF','Y');
		$this->db->where('JENIS',$jenis);
		$this->db->where('KELAS',$kelas);
		$this->db->limit('1');
		$query = $this->db->get()->row();
        return $query;
	}

	public function prior_ubah($data,$id){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		
		$dataUbah = array(
			"KELAS" => $data['kelas'],
			"JENIS" => $data['jenis'],
			"TOTAL_KELASDATA" => $data['totalkelasdata'],
			"TOTAL_SELURUHDATA" => $data['totalseluruhdata'],
			"PROBABILITAS" => $data['probabilitas'],
			"TOTAL_KELASKATA" => $data['total_kelaskata'],
			"TOTAL_SELURUHKATAUNIK" => $data['total_seluruhkata'],
			"TOTAL_KELASKATA_PLUS_KATAUNIK" => $data['total_kata'],
			"TGL_DIPERBARUI" =>$tgl->format('Y-m-d'),
			"AKTIF" => 'Y'
		);

        $this->db->where('ID_PRIOR', $id);
        $this->db->update('prior', $dataUbah);
        return $this->db->affected_rows();
	}

	public function prior_buat($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);

		$dataBuat = array(
			"KELAS" => $data['kelas'],
			"JENIS" => $data['jenis'],
			"TOTAL_KELASDATA" => $data['totalkelasdata'],
			"TOTAL_SELURUHDATA" => $data['totalseluruhdata'],
			"PROBABILITAS" => $data['probabilitas'],
			"TOTAL_KELASKATA" => $data['total_kelaskata'],
			"TOTAL_SELURUHKATAUNIK" => $data['total_seluruhkata'],
			"TOTAL_KELASKATA_PLUS_KATAUNIK" => $data['total_kata'],
			"TGL_DIPERBARUI" =>$tgl->format('Y-m-d'),
			"AKTIF" => 'Y'
		);
		$this->db->insert('prior', $dataBuat);
	}

	public function kelaskata_ubah_reset($id){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		
		$dataUbah = array(
			"TOTAL_CYBERBULLY" => 0,
			"TOTAL_NONCYBERBULLY" => 0,
			"PROBABILITAS_CYBERBULLY" => 0,
			"PROBABILITAS_NONCYBERBULLY" => 0,
			"TGL_PERBARUI" =>$tgl->format('Y-m-d'),
			"AKTIF" => 'Y'
		);

        $this->db->where('ID_KATA', $id);
        $this->db->update('kelas_kata', $dataUbah);
        return $this->db->affected_rows();
	}

	public function kelaskata_ubah_probabilitas($data,$id,$sentimen){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		
		if($sentimen=='cyberbullying'){
			$dataUbah = array(
				"PROBABILITAS_CYBERBULLY" => $data['PROBABILITAS_CYBERBULLY'],
				"TGL_PERBARUI" =>$tgl->format('Y-m-d'),
				"AKTIF" => 'Y'
			);
		}else{
			$dataUbah = array(
				"PROBABILITAS_NONCYBERBULLY" => $data['PROBABILITAS_NONCYBERBULLY'],
				"TGL_PERBARUI" =>$tgl->format('Y-m-d'),
				"AKTIF" => 'Y'
			);
		}

        $this->db->where('ID_KATA', $id);
        $this->db->update('kelas_kata', $dataUbah);
        return $this->db->affected_rows();
	}

	public function kelaskata_ubah($data,$id,$sentimen){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		
		if($sentimen=='cyberbullying'){
			$dataUbah = array(
				"TOTAL_CYBERBULLY" => $data['TOTAL_CYBERBULLY'],
				"TGL_PERBARUI" =>$tgl->format('Y-m-d'),
				"AKTIF" => 'Y'
			);
		}else{
			$dataUbah = array(
				"TOTAL_NONCYBERBULLY" => $data['TOTAL_NONCYBERBULLY'],
				"TGL_PERBARUI" =>$tgl->format('Y-m-d'),
				"AKTIF" => 'Y'
			);
		}

        $this->db->where('ID_KATA', $id);
        $this->db->update('kelas_kata', $dataUbah);
        return $this->db->affected_rows();
	}

	public function kelaskata_buat($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);

		$dataBuat = array(
			"KATA" => $data['KATA'],
			"TOTAL_CYBERBULLY" => $data['TOTAL_CYBERBULLY'],
			"TOTAL_NONCYBERBULLY" => $data['TOTAL_NONCYBERBULLY'],
			"PROBABILITAS_CYBERBULLY" => $data['PROBABILITAS_CYBERBULLY'],
			"PROBABILITAS_NONCYBERBULLY" => $data['PROBABILITAS_NONCYBERBULLY'],
			"TGL_PERBARUI" =>$tgl->format('Y-m-d'),
			"AKTIF" => 'Y'
		);
		$this->db->insert('kelas_kata', $dataBuat);
	}
	//akhir naive bayes model

	//awal preprocessing dataset artikel
	public function casefolding_dataset_artikel()
	{
		$this->db->trans_begin();
		$total_row = $this->db->count_all('dataset_artikel');
		$progres = 0;
		
		$komentar = $this->dataset_allArtikel();
		foreach($komentar->result_array() as $comment){
			$result_folding = '';
			$result_folding = $this->case_folding($comment['ISI_ARTIKEL']);
			$komen = array(
				'CASE_FOLDING' => $result_folding
			);
			$this->db->where('ID_ARTIKEL', $comment['ID_ARTIKEL']);
			$this->db->update('dataset_artikel', $komen);
		}				
        $this->db->trans_complete();
		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function cleaning_dataset_artikel()
	{
		//start the transaction
		$this->db->trans_begin();
		$total_row = $this->db->count_all('dataset_artikel');
		$progres = 0;
		
		//ambil data komentar
		$komentar = $this->dataset_allArtikel();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->cleansing($comment['CASE_FOLDING']);
			$result = $this->gabungkan_kata($result);

			$komen = array(
				'CLEANING' => $result
			);
			$this->db->where('ID_ARTIKEL', $comment['ID_ARTIKEL']);
			$this->db->update('dataset_artikel', $komen);
		}				

        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function normalisasi_dataset_artikel(){
		$this->db->trans_begin();
		$komentar = $this->dataset_allArtikel();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->normalisasi_bahasagaul($comment['CLEANING']);
			$komen = array(
				'NORMALISASI' => $result
			);
			$this->db->where('ID_ARTIKEL', $comment['ID_ARTIKEL']);
			$this->db->update('dataset_artikel', $komen);
		}				
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function stemming_dataset_artikel()
	{
		$this->db->trans_begin();
		$komentar = $this->dataset_allArtikel();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->stemmer($comment['NORMALISASI']);
			$komen = array(
				'STEMMING' => $result
			);
			$this->db->where('ID_ARTIKEL', $comment['ID_ARTIKEL']);
			$this->db->update('dataset_artikel', $komen);
		}				
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function stopword_dataset_artikel(){
		$this->db->trans_begin();
		$komentar = $this->dataset_allArtikel();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->stopword($comment['STEMMING']);
			$komen = array(
				'STOPWORD' => $result
			);
			$this->db->where('ID_ARTIKEL', $comment['ID_ARTIKEL']);
			$this->db->update('dataset_artikel', $komen);
		}				
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function tokenizing_dataset_artikel()
	{
		$this->db->trans_begin();
		$komentar = $this->dataset_allArtikel();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->tokenizer($comment['STOPWORD']);
			$komen = array(
				'TOKENIZING' => $result
			);
			$this->db->where('ID_ARTIKEL', $comment['ID_ARTIKEL']);
			$this->db->update('dataset_artikel', $komen);
		}				
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}
	//akhir preprocessing dataset artikel

	public function tes()
	{
		$tes = "^ & %aku% ak4  $ 12 Trilyun2 Enak2 banget2 ya Tragedi ular kembali memakan korban. Kali ini seorang bocah laki2 di Bandung tewas setelah dipatuk ular weling.--Insiden itu menimpa Andi Ramdani (11), bocah asal Jalan Nagrog, Gang Keramat RT 04/09, Kelurahan Pasirjati, Kecamatan Ujungberung, Kota Bandung. Andi tewas saat dilarikan ke rumah sakit umum daerah (RSUD) Bandung pada Rabu (23/1) siang.--Bagaimana awal mula kejadiannya? Simak selengkapnya di link yang ada di stories!--Sumber: detiknews--#detikcom #ularweling #dipatukular";
		$tes1 = "'DANAR' Dono y nyiyir gak ga adalah..,./ seorang...!! @mahasiswa2%&& :D z!! @mas danar_dono";
		$cek = $this->case_folding($tes);
		$cek = $this->cleansing($cek);
		$cek = $this->stemmer($cek);
		$cek = $this->stopword($cek);
		$cek = preg_split('/ /',$cek);
		$count = count($cek);
		for($i=0;$i<$count;$i++){
			$cek[$i] = $this->hilangkan_kataberulang($cek[$i]);
		}
	}

	function tokenizer($komen){
		$komen = explode(" ", $komen);
		$komen = implode("<br/>", $komen);
		return $komen;
	}

	public function tokenizing_dataset()
	{
		$this->db->trans_begin();
		$komentar = $this->dataset_allkomentar();
		
		foreach($komentar->result_array() as $comment){
			$result = "";
			$result = $this->tokenizer($comment['STOPWORD']);
			if($result<>""){
				$komen = array(
					'TOKENIZING' => $result,
					'AKTIF' => "Y"
				);
				$this->db->where('ID_DATASETKOMENTAR', $comment['ID_DATASETKOMENTAR']);
				$this->db->update('dataset_komentar', $komen);
			}else{
				$komen = array(
					'TOKENIZING' => $result,
					'AKTIF' => "N"
				);
				$this->db->where('ID_DATASETKOMENTAR', $comment['ID_DATASETKOMENTAR']);
				$this->db->update('dataset_komentar', $komen);
			}
		}
						
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function stopword($sentence){
		/*$stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
		$stopword = $stopWordRemoverFactory->createStopWordRemover();
		$outputstopword = $stopword->remove($sentence);
		return $outputstopword;*/
		$komen = $this->stopword_kata($sentence);
		return $komen;
	}

	public function stopword_kata($komen){
		$gabungkan_kata = '';
		$cek = preg_split('/ /',$komen);
		$count = count($cek);
		for($i=0;$i<$count;$i++){
			if($this->stopword_cek($cek[$i]) == true){
				unset($cek[$i]);
			}else{
				$cek[$i] = $cek[$i];
			}
		}
		$gabungkan_kata = implode(' ', $cek);
		return $gabungkan_kata;
	}

	function stopword_cek($kata){
		$this->db->from('stopword_list');
		$this->db->where('STOPWORD',$kata);
		$this->db->where('AKTIF','Y');
		$this->db->limit(1);
		$query = $this->db->get();
        $result = $query->num_rows();
		if($result > 0) {
			return true; // True jika ada
		} else {
			return false; // jika tidak ada FALSE
		}
	}

	public function stopword_dataset()
	{
		$this->db->trans_begin();
		$komentar = $this->dataset_allkomentar();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->stopword($comment['STEMMING']);
			$komen = array(
				'STOPWORD' => $result
			);
			$this->db->where('ID_DATASETKOMENTAR', $comment['ID_DATASETKOMENTAR']);
			$this->db->update('dataset_komentar', $komen);
		}				
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function stemmer($sentence)   {
		/*$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer = $stemmerFactory->createStemmer();
		$output = $stemmer->stem($sentence);
		return $output;*/

		$stem = $this->stemmer_kata($sentence);
		return $stem;
	}

	public function stemmer_kata($komen){
		$gabungkan_kata = '';
		$cek = preg_split('/ /',$komen);
		$count = count($cek);
		for($i=0;$i<$count;$i++){
			$cek[$i] = $this->stemming($cek[$i]);
		}
		$gabungkan_kata = implode(' ', $cek);
		return $gabungkan_kata;
	}
	
	//stemmer mysql
	//fungsi untuk mengecek kata dalam tabel dictionary
	function cekKamus($kata){
		$this->db->from('kamus_stemmer');
		$this->db->where('KATA',$kata);
		$this->db->where('AKTIF','Y');
		$this->db->limit(1);
		$query = $this->db->get();
        $result = $query->num_rows();

		//include "connection.php";
		//$sql = mysqli_query($conn, "SELECT * FROM dictionary WHERE word = '" . $kata . "' LIMIT 1");
		//$result = mysqli_num_rows($sql);

		if($result > 0) {
			return true; // True jika ada
		} else {
			return false; // jika tidak ada FALSE
		}
	}

	//fungsi untuk menghapus suffix seperti -ku, -mu, -kah, dsb
	function Del_Inflection_Suffixes($kata){
		$kataAsal = $kata;
		if (preg_match('/([km]u|nya|[kl]ah|pun)\z/i', $kata)) { // Cek Inflection Suffixes
			$__kata = preg_replace('/([km]u|nya|[kl]ah|pun)\z/i', '', $kata);
			return $__kata;
		}
		return $kataAsal;
	}

	// Cek Prefix Disallowed Sufixes (Kombinasi Awalan dan Akhiran yang tidak diizinkan)
	function Cek_Prefix_Disallowed_Sufixes($kata){
		if (preg_match('/^(be)[[:alpha:]]+/(i)\z/i', $kata)) { // be- dan -i
			return true;
		}
		if (preg_match('/^(se)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // se- dan -i,-kan
			return true;
		}
		if (preg_match('/^(di)[[:alpha:]]+/(an)\z/i', $kata)) { // di- dan -an
			return true;
		}
		if (preg_match('/^(me)[[:alpha:]]+/(an)\z/i', $kata)) { // me- dan -an
			return true;
		}
		if (preg_match('/^(ke)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // ke- dan -i,-kan
			return true;
		}
		return false;
	}

	// Hapus Derivation Suffixes ("-i", "-an" atau "-kan")
	function Del_Derivation_Suffixes($kata){
		$kataAsal = $kata;
		if (preg_match('/(i|an)\z/i', $kata)) { // Cek Suffixes
			$__kata = preg_replace('/(i|an)\z/i', '', $kata);
			if ($this->cekKamus($__kata)) { // Cek Kamus
				return $__kata;
			} else if (preg_match('/(kan)\z/i', $kata)) {
				$__kata = preg_replace('/(kan)\z/i', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata;
				}
			}
			/*– Jika Tidak ditemukan di kamus –*/
		}
		return $kataAsal;
	}

	// Hapus Derivation Prefix ("di-", "ke-", "se-", "te-", "be-", "me-", atau "pe-")
	function Del_Derivation_Prefix($kata){
		$kataAsal = $kata;
		/* —— Tentukan Tipe Awalan ————*/
		if (preg_match('/^(di|[ks]e)/', $kata)) { // Jika di-,ke-,se-
			$__kata = preg_replace('/^(di|[ks]e)/', '', $kata);

			if ($this->cekKamus($__kata)) {
				return $__kata;
			}

			$__kata__ = $this->Del_Derivation_Suffixes($__kata);

			if ($this->cekKamus($__kata__)) {
				return $__kata__;
			}

			if (preg_match('/^(diper)/', $kata)) { //diper-
				$__kata = preg_replace('/^(diper)/', '', $kata);
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);

				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^(ke[bt]er)/', $kata)) {  //keber- dan keter-
				$__kata = preg_replace('/^(ke[bt]er)/', '', $kata);
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);

				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}
		}

		if (preg_match('/^([bt]e)/', $kata)) { //Jika awalannya adalah "te-","ter-", "be-","ber-"
			$__kata = preg_replace('/^([bt]e)/', '', $kata);
			if ($this->cekKamus($__kata)) {
				return $__kata; // Jika ada balik
			}

			$__kata = preg_replace('/^([bt]e[lr])/', '', $kata);
			if ($this->cekKamus($__kata)) {
				return $__kata; // Jika ada balik
			}

			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if ($this->cekKamus($__kata__)) {
				return $__kata__;
			}
		}

		if (preg_match('/^([mp]e)/', $kata)) {
			$__kata = preg_replace('/^([mp]e)/', '', $kata);
			if ($this->cekKamus($__kata)) {
				return $__kata; // Jika ada balik
			}
			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if ($this->cekKamus($__kata__)) {
				return $__kata__;
			}

			if (preg_match('/^(memper)/', $kata)) {
			$__kata = preg_replace('/^(memper)/', '', $kata);
				if ($this->cekKamus($kata)) {
					return $__kata;
				}
			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]eng)/', $kata)) {
				$__kata = preg_replace('/^([mp]eng)/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}

				$__kata = preg_replace('/^([mp]eng)/', 'k', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]eny)/', $kata)) {
				$__kata = preg_replace('/^([mp]eny)/', 's', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]e[lr])/', $kata)) {
				$__kata = preg_replace('/^([mp]e[lr])/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]en)/', $kata)) {
				$__kata = preg_replace('/^([mp]en)/', 't', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}

				$__kata = preg_replace('/^([mp]en)/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]em)/', $kata)) {
				$__kata = preg_replace('/^([mp]em)/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}

				$__kata = preg_replace('/^([mp]em)/', 'p', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}

				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}
		}
		return $kataAsal;
	}

	//fungsi pencarian akar kata
	function stemming($kata){
		$kataAsal = $kata;
		$cekKata = $this->cekKamus($kata);
		if ($cekKata == true) { // Cek Kamus
			return $kata; // Jika Ada maka kata tersebut adalah kata dasar
		} else { //jika tidak ada dalam kamus maka dilakukan stemming
			$kata = $this->Del_Inflection_Suffixes($kata);
			if ($this->cekKamus($kata)) {
				return $kata;
			}

			$kata = $this->Del_Derivation_Suffixes($kata);
			if ($this->cekKamus($kata)) {
				return $kata;
			}

			$kata = $this->Del_Derivation_Prefix($kata);
			if ($this->cekKamus($kata)) {
				return $kata;
			}
		}
		return $kata;
	}

	public function stemming_dataset(){
		$this->db->trans_begin();
		$komentar = $this->dataset_allkomentar();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->stemmer($comment['NORMALISASI']);
			$result = $this->stopword($result);
			$komen = array(
				'STEMMING' => $result
			);
			
			$this->db->where('ID_DATASETKOMENTAR', $comment['ID_DATASETKOMENTAR']);
			$this->db->update('dataset_komentar', $komen);
		}				
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function ganti_katagaul($kata)
	{
		$hasil = '';
		$this->db->select('KATAFORMAL');
		$this->db->from('kamus_bahasagaul');
		$this->db->where('KATAGAUL',$kata);
		$this->db->limit('1');
		$query = $this->db->get()->row();
		if(isset($query)){
			$hasil = $query->KATAFORMAL;
		}else{
			$hasil = $kata;
		}
        return $hasil;
	}

	public function normalisasi_bahasagaul($komen){
		$gabungkan_kata = '';
		$cek = preg_split('/ /',$komen);
		$count = count($cek);
		for($i=0;$i<$count;$i++){
			$cek[$i] = $this->ganti_katagaul($cek[$i]);
		}
		$gabungkan_kata = implode(' ', $cek);
		return $gabungkan_kata;
	}

	public function normalisasi_dataset(){
		$this->db->trans_begin();
		$komentar = $this->dataset_allkomentar();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->normalisasi_bahasagaul($comment['CLEANING']);
			$komen = array(
				'NORMALISASI' => $result
			);
			$this->db->where('ID_DATASETKOMENTAR', $comment['ID_DATASETKOMENTAR']);
			$this->db->update('dataset_komentar', $komen);
		}				
        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function cleaning_dataset()
	{
		//start the transaction
		$this->db->trans_begin();
		$total_row = $this->db->count_all('dataset_komentar');
		$progres = 0;
		
		//ambil data komentar
		$komentar = $this->dataset_allkomentar();
		foreach($komentar->result_array() as $comment){
			$result = '';
			$result = $this->cleansing($comment['CASE_FOLDING']);
			$result = $this->gabungkan_kata($result);

			$komen = array(
				'CLEANING' => $result
			);
			$this->db->where('ID_DATASETKOMENTAR', $comment['ID_DATASETKOMENTAR']);
			$this->db->update('DATASET_KOMENTAR', $komen);
		}				

        $this->db->trans_complete();		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}



	public function casefolding_dataset()
	{
		$this->db->trans_begin();
		$total_row = $this->db->count_all('dataset_komentar');
		$progres = 0;
		
		$komentar = $this->dataset_allkomentar();
		foreach($komentar->result_array() as $comment){
			$result_folding = '';
			$result_folding = $this->case_folding($comment['ISI_KOMENTAR']);
			$komen = array(
				'CASE_FOLDING' => $result_folding
			);
			$this->db->where('ID_DATASETKOMENTAR', $comment['ID_DATASETKOMENTAR']);
			$this->db->update('dataset_komentar', $komen);
		}				
        $this->db->trans_complete();
		
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
	}

	public function gabungkan_kata($cek)
	{
		$gabungkan_kata = '';
		$cek = preg_split('/ /',$cek);
		$count = count($cek);
		for($i=0;$i<$count;$i++){
			$cek[$i] = $this->hilangkan_kataberulang($cek[$i]);
			//$gabungkan_kata = $gabungkan_kata .' '. $cek[$i];
		}
		$gabungkan_kata = implode(' ', $cek);
		return $gabungkan_kata;
	}

	public function hilangkan_kataberulang($komen)
	{
		$komen = preg_split('//',$komen, -1, PREG_SPLIT_NO_EMPTY);
		$nx_komen = count($komen) - 1;
		$is_implode = 0;
		if($nx_komen>1){
			if($komen[$nx_komen] == '2'){
				unset($komen[$nx_komen]);
				$komen = implode("", $komen);
				//$komen = $komen .' ' . $komen;
				$is_implode = 1;
			}
		}
		if($is_implode == 0){
			$komen = implode("", $komen);
		}
		$hasilx = '';
		$hasilx = $komen;
		return $hasilx;
	}

	function case_folding($komen){
		return strtolower($komen);
	}

	public function cleansing($komen){
		$komen = preg_replace("/[^a-zA-Z0-9]/", " ", $komen);
		$komen = str_replace("/", " ", $komen);
		return $komen;
	}

	public function dataset_komentarUbah($data){
		$dataUbah = array(
            'ID_DATASETKOMENTAR' => $data['idkomentar'],
            'ISI_KOMENTAR' => $data['isikomentar'],
            'SENTIMEN' => $data['sentimen']
        );

        $this->db->where('ID_DATASETKOMENTAR', $data['idkomentar']);
        $this->db->update('dataset_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function kamusgaulUbah($data){
		$dataUbah = array(
            'KATAFORMAL' => $data['kataformal'],
            'KATAGAUL' => $data['katagaul']
        );

        $this->db->where('ID_KATAGAUL', $data['idkatagaul']);
        $this->db->update('kamus_bahasagaul', $dataUbah);
        return $this->db->affected_rows();
	}

	public function stopwordlistUbah($data){
		$dataUbah = array(
            'STOPWORD' => $data['stopword'],
        );

        $this->db->where('ID_STOPWORD', $data['idstopword']);
        $this->db->update('stopword_list', $dataUbah);
        return $this->db->affected_rows();
	}

	public function dataset_komentarById($id){
		$this->db->from('dataset_komentar');
		$this->db->where('ID_DATASETKOMENTAR',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function kamusgaulById($id){
		$this->db->from('kamus_bahasagaul');
		$this->db->where('ID_KATAGAUL',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function stopwordlistById($id){
		$this->db->from('stopword_list');
		$this->db->where('ID_STOPWORD',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function dataset_artikelById($id){
		$this->db->select ('*'); 
		$this->db->from('dataset_artikel');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_ARTIKEL',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function dataset_kamusById($id){
		$this->db->select('*'); 
		$this->db->from('kamus_bahasagaul');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_KATAGAUL',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function dataset_stopwordById($id){
		$this->db->select('*'); 
		$this->db->from('stopword_list');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_STOPWORD',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function dataset_artikel(){
		$this->db->select ('*'); 
		$this->db->from('dataset_artikel');
		$this->db->where('AKTIF','Y');
		$this->db->order_by('ID_ARTIKEL','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function kamus_bahasagaul(){
		$this->db->select('*'); 
		$this->db->from('kamus_bahasagaul');
		$this->db->where('AKTIF','Y');
		$this->db->order_by('ID_KATAGAUL','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function kamus_katadasar(){
		$this->db->select('*'); 
		$this->db->from('kamus_stemmer');
		$this->db->where('AKTIF','Y');
		$this->db->order_by('ID_STEMMER','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function kamus_stopwordlist(){
		$this->db->select('*'); 
		$this->db->from('stopword_list');
		$this->db->where('AKTIF','Y');
		$this->db->order_by('ID_STOPWORD','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function prior(){
		$this->db->select ('*'); 
		$this->db->from('prior');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query->result();
	}

	public function kelas_kata(){
		$this->db->select ('*'); 
		$this->db->from('kelas_kata');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query->result();
	}

	public function dataset_komentar($id){
		$this->db->select ('*'); 
		$this->db->from('dataset_komentar');
		$this->db->where('dataset_komentar.AKTIF','Y');
		$this->db->where('dataset_komentar.ID_ARTIKEL',$id);
        $query = $this->db->get();
        return $query;
	}

	public function _get_datatables_query()
	{
		$this->db->from('dataset_komentar');
		$i = 0;
		foreach($this->column_search as $item){
			if($_POST['search']['value']){
				if($i===0){
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				}else{
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->column_search)-1 ==$i){
					$this->db->group_end();
				}
				$i++;
			}
			if(isset($_POST['order'])){
				$this->db->order_by($this->column_order[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
			}else{
				$this->db->order_by(key($order), $order[key($order)]);
			}
		}
	}

	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function dataset_allkelaskata(){
		$this->db->select ('*'); 
		$this->db->from('kelas_kata');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function dataset_allkomentar(){
		$this->db->select ('*'); 
		$this->db->from('dataset_komentar');
		$this->db->where('AKTIF','Y');
		//$this->db->where('MODE','training');
		//$this->db->like('ID_DATASETKOMENTAR','35');
        $query = $this->db->get();
        return $query;
	}

	public function dataset_allkomentar_training(){
		$this->db->select ('*'); 
		$this->db->from('dataset_komentar');
		$this->db->where('AKTIF','Y');
		$this->db->where('MODE','training');
		//$this->db->like('ID_DATASETKOMENTAR','35');
        $query = $this->db->get();
        return $query;
	}

	public function dataset_allArtikel(){
		$this->db->select ('*'); 
		$this->db->from('dataset_artikel');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function is_katagaulAda($kata){
		$this->db->select('*');
		$this->db->from('kamus_bahasagaul');
		$this->db->where('KATAGAUL',$kata);
		$cek = $this->db->get()->num_rows();
		return $cek;
	}

	public function is_StopwordAda($kata){
		$this->db->select('*');
		$this->db->from('stopword_list');
		$this->db->where('STOPWORD',$kata);
		$cek = $this->db->get()->num_rows();
		return $cek;
	}

	public function kamusgaul_buat($data){
		$dataBuat = array(
			'KATAGAUL' => $data['katagaul'],
			'KATAFORMAL' => $data['kataformal'],
			"AKTIF" => 'Y'
		);
		$this->db->insert('kamus_bahasagaul', $dataBuat);
	}

	public function stopwordlist_buat($kata){
		$dataBuat = array(
			'STOPWORD' => $kata,
			"AKTIF" => 'Y'
		);
		$this->db->insert('stopword_list', $dataBuat);
	}

	public function dataset_artikel_buat($data){
		$datatgl = $data['tanggalartikel'];
		$dataBuat = array(
			'JUDUL_ARTIKEL' => $data['judulartikel'],
			'TOPIK_ARTIKEL' => $data['topikartikel'],
			'ISI_ARTIKEL' => $data['isiartikel'],
			'SUMBER_ARTIKEL' => $data['sumberartikel'],
			"TANGGAL_ARTIKEL" => $data['tanggalartikel'],//$datatgl->format('Y-m-d'),
			"AKTIF" => 'Y'
		);
		$this->db->insert('dataset_artikel', $dataBuat);
	}

	public function dataset_komentar_buat($data){
		$dataBuat = array(
			'ISI_KOMENTAR' => $data['isikomentar'],
			'ID_ARTIKEL' => $data['idartikel'],
			'SENTIMEN' => $data['sentimen'],
			"AKTIF" => 'Y'
		);
		$this->db->insert('dataset_komentar', $dataBuat);
	}

	public function artikel_hapus($data){
		$dataUbah = array(
			'AKTIF' => 'N'
		);
		$this->db->where('ID_ARTIKEL', $data['idposting']);
		$this->db->update('dataset_artikel',$dataUbah);
		return $this->db->affected_rows();
	}

	public function kamusgaul_hapus($data){
		$dataUbah = array(
			'AKTIF' => 'N'
		);
		$this->db->where('ID_KATAGAUL', $data['idkatagaul']);
		$this->db->update('kamus_bahasagaul',$dataUbah);
		return $this->db->affected_rows();
	}

	public function stopwordlist_hapus($data){
		$dataUbah = array(
			'AKTIF' => 'N'
		);
		$this->db->where('ID_STOPWORD', $data['idstopword']);
		$this->db->update('stopword_list',$dataUbah);
		return $this->db->affected_rows();
	}

	public function posting_hapus($data){
		$dataUbah = array(
			'AKTIF' => 'N'
		);
		$this->db->where('ID_POSTING', $data['idposting']);
		$this->db->update('posting',$dataUbah);
		return $this->db->affected_rows();
	}

	public function posting_ubah($data){
		if(! empty($data['pathfoto']) && (! is_null($data['pathfoto']))){
			$dataUbah = array(
				'ID' => $data['idpengguna'],
				'ID_KATEGORI' => $data['kategori'],
				'JUDUL' => $data['judul'],
				'ISI_ARTIKEL' => $data['isiartikel'],
				"PHOTO" => $data['pathfoto'],
				"DESKRIPSI_ARTIKEL" => $this->input->post('deskripsiartikel'),
				"KATA_KUNCI" => $this->input->post('katakunci')
			);
			$this->gallery_buat($dataUbah);
		}else{
			$dataUbah = array(
				'ID' => $data['idpengguna'],
				'ID_KATEGORI' => $data['kategori'],
				'JUDUL' => $data['judul'],
				'ISI_ARTIKEL' => $data['isiartikel'],
				"DESKRIPSI_ARTIKEL" => $this->input->post('deskripsiartikel'),
				"KATA_KUNCI" => $this->input->post('katakunci')
			);
		}

        $this->db->where('ID_POSTING', $data['idposting']);
        $this->db->update('posting', $dataUbah);
        return $this->db->affected_rows();
	}

	public function postingById($id){
		$this->db->from('posting');
		$this->db->where('ID_POSTING',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function totalArtikelIndex(){
		$this->db->from('posting');
		$this->db->join ('kategori', 'kategori.ID_KATEGORI = posting.ID_KATEGORI' , 'left' );
		$this->db->join ('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->order_by('ID_POSTING','DESC');
		$this->db->where('posting.AKTIF','Y');
	    $query = $this->db->get();
        return $query->num_rows();
	}

	public function getArtikelIndex($limit,$offset){
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join('kategori', 'kategori.ID_KATEGORI = posting.ID_KATEGORI' , 'left' );
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->like('posting.AKTIF','Y');
		$this->db->limit($limit,$offset);
		$this->db->order_by('ID_POSTING','DESC');
	    $query = $this->db->get();
        return $query;
	}

	public function perbarui_statistik_artikel($id, $rating, $count, $view, $komentar){
		$dataUbah = array(
			'VIEW' => $view,
			'RATING' => $rating,
			'JUMLAH_RATING' => $count,
			'TOTAL_KOMENTAR' => $komentar
        );
        $this->db->where('ID_POSTING', $id);
        $this->db->update('posting', $dataUbah);
        return $this->db->affected_rows();
	}

	//rating model
	public function rating($idpengguna=null, $idposting=null,$rating=null)
    {
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);

		$data = array(
			"ID" => $idpengguna,
			"ID_POSTING" => $idposting,
			"RATING" => $rating,
			"AKTIF" => 'Y',
			"TANGGAL_RATING" => $tgl->format('Y-m-d')
		);

		$this->db->insert('rating', $data);
	}

	public function get_rating($id){
		$this->db->select('rating.RATING AS RATING, rating.TANGGAL_RATING AS TANGGAL_RATING, pengguna.ID AS ID, pengguna.FIRST_NAME, pengguna.LAST_NAME');
		$this->db->from('rating');
		$this->db->join('pengguna', 'rating.ID = pengguna.ID' , 'left');
		$this->db->where('rating.AKTIF','Y');
		$this->db->where('rating.ID_POSTING',$id);
		$this->db->order_by('rating.TANGGAL_RATING', 'DESC');
		$query = $this->db->get();
        return $query;
	}

	public function get_komentarArtikel($id){
		$this->db->select('pengguna.ID AS ID, pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, komentar.ID_KOMENTAR AS ID_KOMENTAR, komentar.KOMENTAR AS KOMENTAR, komentar.TANGGAL_KOMENTAR AS TANGGAL_KOMENTAR, komentar.SENTIMEN AS SENTIMEN, komentar.LAPORKAN AS LAPORKAN, komentar.PELANGGARAN AS PELANGGARAN, komentar.KETERANGAN AS KETERANGAN, komentar.TANGGAL_PELAPORAN AS TANGGAL_PELAPORAN, komentar.LAPORKAN, komentar.AKTIF');
		$this->db->from('komentar');
		$this->db->join('pengguna', 'komentar.ID = pengguna.ID' , 'left');
		$this->db->where('komentar.AKTIF','Y');
		$this->db->where('komentar.ID_POSTING',$id);
		$this->db->order_by('komentar.TANGGAL_KOMENTAR', 'DESC');
		$query = $this->db->get();
        return $query;
	}

	public function hapus_komentarArtikel($id){
		$dataUbah = array(
            'AKTIF' => 'N'
        );
        $this->db->where('ID_KOMENTAR', $id);
        $this->db->update('komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function hapus_komentarBalasan($id){
		$dataUbah = array(
            'AKTIF' => 'N'
        );
        $this->db->where('ID_BALASAN', $id);
        $this->db->update('balasan_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function hapus_balasanbully($id){
		$dataUbah = array(
			'AKTIF' => 'N',
			"SENTIMEN" => 'cyberbullying'
        );
        $this->db->where('ID_BALASAN', $id);
        $this->db->update('balasan_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function hapus_komentarbully($id){
		$dataUbah = array(
			'AKTIF' => 'N',
			"SENTIMEN" => 'cyberbullying'
        );
        $this->db->where('ID_KOMENTAR', $id);
        $this->db->update('komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	//kuesioner
	public function buat_penilai($nama, $email, $gender, $profesi){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$waktu = getdate();
		$kode = $waktu['year'] .'-'. $waktu['month'] .'-'. $waktu['mday'] .'-'. $waktu['hours'] .'-'. $waktu['minutes'] .'-'. $waktu['seconds'];
		$data = array(
			"KODE" => $kode,
			"NAMA_PENILAI" => $nama,
			"EMAIL_PENILAI" => $email,
			"GENDER" => $gender,
			"PROFESI" => $profesi,
			"AKTIF" => 'Y',
			"TANGGAL" => $tgl->format('Y-m-d')
		);
		$this->db->insert('penilai', $data);
		return $kode;
	}

	public function buat_jawaban($id_penilai, $id_kuesioner, $nilai, $jawab){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"ID_PENILAI" => $id_penilai,
			"ID_KUESIONER" => $id_kuesioner,
			"JAWAB" => $jawab,
			"NILAI" => $nilai,
			"AKTIF" => 'Y',
			"TANGGAL_JAWAB" => $tgl->format('Y-m-d')
		);
		$this->db->insert('jawaban', $data);
	}

	public function buat_kritiksaran($id_penilai, $jawaban){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"KRITIK_SARAN" => $jawaban,
			"ID_PENILAI" => $id_penilai,
			"TANGGAL" => $tgl->format('Y-m-d'),
			"AKTIF" => 'Y'
		);
		$this->db->insert('kritik_saran', $data);
	}

	public function allkritiksaran(){
		$this->db->from('kritik_saran');
		$this->db->join('penilai', 'penilai.ID_PENILAI = kritik_saran.ID_PENILAI' , 'left');
		$this->db->where('kritik_saran.AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function get_penilai($kode){
		$this->db->from('penilai');
		$this->db->where('penilai.AKTIF','Y');
		$this->db->where('penilai.KODE',$kode);
        $query = $this->db->get();
        return $query->row();
	}

	public function get_kuesioner(){
		$this->db->select('*'); 
		$this->db->from('kuesioner');
		$this->db->where('AKTIF','Y');
		$this->db->order_by('ID_KUESIONER','ASC');
        $query = $this->db->get();
        return $query;
	}

	public function get_nilai($id){
		$this->db->select('*'); 
		$this->db->from('nilai');
		$this->db->where('nilai.AKTIF','Y');
		$this->db->where('nilai.ID_KUESIONER',$id);
		$this->db->order_by('nilai.ID_NILAI','ASC');
        $query = $this->db->get();
        return $query;
	}


	//hapus_pelaporan
	public function hapus_pelaporan($data){
		$dataUbah = array(
			'AKTIF' => 'N',
			"SENTIMEN" => $data['sentimen'],
			"PELANGGARAN" => $data['pelanggaran'],
			"KETERANGAN" => $data['keterangan'],
			"LAPORKAN" => $data['lapor'],
			"AKTIF" => $data['aktif'],
        );
        $this->db->where('ID_KOMENTAR', $data['idkomentar']);
        $this->db->update('komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function hapus_pelaporan_balasan($data){
		$dataUbah = array(
			'AKTIF' => 'N',
			"SENTIMEN" => $data['sentimen'],
			"PELANGGARAN" => $data['pelanggaran'],
			"KETERANGAN" => $data['keterangan'],
			"LAPORKAN" => $data['lapor'],
			"AKTIF" => $data['aktif'],
        );
        $this->db->where('ID_BALASAN', $data['idbalasan']);
        $this->db->update('balasan_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function get_komentarArtikelById($id){
		$this->db->select('pengguna.ID AS ID, pengguna.FIRST_NAME, pengguna.LAST_NAME, komentar.KOMENTAR AS KOMENTAR, komentar.TANGGAL_KOMENTAR AS TANGGAL, komentar.SENTIMEN AS SENTIMEN, komentar.LAPORKAN AS DILAPORKAN, komentar.PELANGGARAN AS PELANGGARAN, komentar.KETERANGAN AS KETERANGAN, komentar.TANGGAL_PELAPORAN AS TANGGAL_PELAPORAN, komentar.ID_POSTING AS ID_POSTING, komentar.ID_KOMENTAR AS ID_KOMENTAR');
		$this->db->from('komentar');
		$this->db->join('pengguna', 'komentar.ID = pengguna.ID' , 'left');
		$this->db->where('komentar.AKTIF','Y');
		$this->db->where('komentar.ID_KOMENTAR',$id);
		$this->db->order_by('komentar.TANGGAL_KOMENTAR', 'DESC');
		$query = $this->db->get();
        return $query->row();
	}

	public function get_balasankomentarArtikelById($id){
		$this->db->select('pengguna.ID AS ID, pengguna.FIRST_NAME, pengguna.LAST_NAME, balasan_komentar.KOMENTAR AS KOMENTAR, balasan_komentar.TANGGAL_KOMENTAR AS TANGGAL, balasan_komentar.SENTIMEN AS SENTIMEN, balasan_komentar.LAPORKAN AS DILAPORKAN, balasan_komentar.PELANGGARAN AS PELANGGARAN, balasan_komentar.KETERANGAN AS KETERANGAN, balasan_komentar.TANGGAL_PELAPORAN AS TANGGAL_PELAPORAN, balasan_komentar.ID_BALASAN AS ID_BALASAN, balasan_komentar.AKTIF AS AKTIF, balasan_komentar.ID_KOMENTAR AS ID_KOMENTAR');
		$this->db->from('balasan_komentar');
		$this->db->join('pengguna', 'balasan_komentar.ID = pengguna.ID' , 'left');
		$this->db->where('balasan_komentar.AKTIF','Y');
		$this->db->where('balasan_komentar.ID_KOMENTAR',$id);
		$this->db->order_by('balasan_komentar.TANGGAL_KOMENTAR', 'DESC');
		$query = $this->db->get();
        return $query;
	}

	public function komentarByArtikel($id_posting){
		$this->db->select('*'); 
		$this->db->from('komentar');
		$this->db->join('pengguna', 'komentar.ID = pengguna.ID' , 'left' );
		$this->db->where('komentar.ID_POSTING',$id_posting);
		$this->db->where('komentar.AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function balasanKomentarByArtikel($id_komentar){
		$this->db->select('*'); 
		$this->db->from('balasan_komentar');
		$this->db->join('pengguna', 'balasan_komentar.ID = pengguna.ID' , 'left' );
		$this->db->where('balasan_komentar.ID_KOMENTAR',$id_komentar);
		$this->db->where('balasan_komentar.AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function komentar($idpengguna=null, $idposting=null,$komentar=null){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"ID" => $idpengguna,
			"ID_POSTING" => $idposting,
			"KOMENTAR" => $komentar,
			"AKTIF" => 'Y',
			"TANGGAL_KOMENTAR" => $tgl->format('Y-m-d H:i:s')
		);
		$this->db->insert('komentar', $data);
	}

	public function balasan_komentar($idpengguna, $idkomentar, $komentar, $idposting){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"ID" => $idpengguna,
			"ID_KOMENTAR" => $idkomentar,
			"KOMENTAR" => $komentar,
			"TANGGAL_KOMENTAR" => $tgl->format('Y-m-d H:i:s'),
			"AKTIF" => 'Y',
			"ID_POSTING" => $idposting
		);
		$query = $this->db->insert('balasan_komentar', $data);
		return $query; 
	}

	public function balas_komentar($idpengguna, $idkomentar, $komentar){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"ID" => $idpengguna,
			"ID_KOMENTAR" => $idkomentar,
			"KOMENTAR" => $komentar,
			"TANGGAL_KOMENTAR" => $tgl->format('Y-m-d H:i:s'),
			"AKTIF" => 'Y'
		);
		$query = $this->db->insert('balasan_komentar', $data);
		return $query; 
	}

	public function laporkan_komentar($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$dataUbah = array(
			'LAPORKAN' => 'Y',
			'SENTIMEN' => 'cyberbullying',
			'TANGGAL_PELAPORAN' => $tgl->format('Y-m-d H:i:s'),
			'PELANGGARAN' => $data['pelanggaran'],
			'KETERANGAN' => $data['keterangan']
        );

        $this->db->where('ID_KOMENTAR', $data['idkomentar']);
        $this->db->update('komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function laporkan_komentar_balasan($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$dataUbah = array(
			'LAPORKAN' => 'Y',
			'SENTIMEN' => 'cyberbullying',
			'TANGGAL_PELAPORAN' => $tgl->format('Y-m-d H:i:s'),
			'PELANGGARAN' => $data['pelanggaran'],
			'KETERANGAN' => $data['keterangan']
        );

        $this->db->where('ID_BALASAN', $data['idbalasan']);
        $this->db->update('balasan_komentar', $dataUbah);
        return $this->db->affected_rows();
	}

	public function get_komentar($id_komentar){
		$this->db->select('komentar.ID_KOMENTAR AS ID_KOMENTAR, pengguna.FIRST_NAME AS DEPAN, pengguna.LAST_NAME AS BELAKANG'); 
		$this->db->from('komentar');
		$this->db->join('pengguna', 'komentar.ID = pengguna.ID' , 'left' );
		$this->db->where('ID_KOMENTAR',$id_komentar);
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function RatingBy($idpengguna=null, $idposting=null)
	{
		$this->db->from('rating');
		$this->db->where('ID_POSTING',$idposting);
		$this->db->where('ID',$idpengguna);
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query;
	}

	public function total_rating($posting=null){
		$this->db->select_avg('RATING');
		$this->db->from('rating');
		$this->db->where('ID_POSTING',$posting);
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query->row();
	}

	public function count_rating($posting=null){
		$this->db->select('COUNT(ID) AS ID');
		$this->db->from('rating');
		$this->db->where('ID_POSTING',$posting);
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query->row();
	}

	public function count_komentar($posting=null){
		$this->db->select('COUNT(ID_KOMENTAR) AS KOMENTAR');
		$this->db->from('komentar');
		$this->db->where('ID_POSTING',$posting);
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query->row();
	}

	public function count_balasan_komentar($posting=null){
		$this->db->select('COUNT(ID_BALASAN) AS KOMENTAR');
		$this->db->from('balasan_komentar');
		$this->db->where('ID_POSTING',$posting);
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query->row();
	}

	//view dan tagar model
	public function view($artikel, $pengguna){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);

		$data = array(
			"ID" => $pengguna,
			"ID_POSTING" => $artikel,
			"VIEW" => 1,
			"AKTIF" => 'Y',
			"TANGGAL_VIEW" => $tgl->format('Y-m-d H:i:s')
		);

		$this->db->insert('view', $data);
	}

	public function tagar($kata){
		$is_tagar = 0;
		$is_tagar = $this->get_tagar($kata);
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		
		if(empty($is_tagar)){
			$data = array(
				"KATA_KUNCI" => $kata,
				"TOTAL" => 1,
				"AKTIF" => 'Y',
				"TANGGAL" => $tgl->format('Y-m-d')
			);
			$this->db->insert('tagar', $data);
		}else{
			$data = array(
				"TOTAL" => $is_tagar->TOTAL + 1,
				"TANGGAL" => $tgl->format('Y-m-d')
			);
			$this->db->where('ID_TAGAR', $is_tagar->ID_TAGAR);
        	$this->db->update('tagar', $data);
		}
	}
	
	public function hapus_tagar($idtagar){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"AKTIF" => 'N',
			"TANGGAL" => $tgl->format('Y-m-d')
		);
		$this->db->where('ID_TAGAR', $idtagar);
		$this->db->update('tagar', $data);
		return $this->db->affected_rows();
	}

	public function ubah_tagar($dataubah){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"KATA_KUNCI" => $dataubah['katakunci'],
			"TOTAL" => $dataubah['total'],
			"TANGGAL" => $tgl->format('Y-m-d')
		);
		$this->db->where('ID_TAGAR', $dataubah['id']);
		$this->db->update('tagar', $data);
		return $this->db->affected_rows();
	}

	public function tambah_tagar($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"KATA_KUNCI" => $data['katakunci'],
			"TOTAL" => $data['total'],
			"TANGGAL" => $tgl->format('Y-m-d'),
			"AKTIF" => "Y"
		);
		$this->db->insert('tagar', $data);
	}

	public function get_tagar($kata){
		$this->db->select('ID_TAGAR, KATA_KUNCI, TOTAL');
		$this->db->from('tagar');
		$this->db->where('KATA_KUNCI',$kata);
		$this->db->limit(1);
		$query = $this->db->get();
        return $query->row();
	}

	public function alltagar(){
		$this->db->from('tagar');
		$this->db->where('AKTIF','Y');
		$this->db->order_by('TOTAL','DESC');
        $query = $this->db->get();
        return $query;
	}
	
	public function allevaluasi(){
		$this->db->from('kuesioner');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function penjawab(){
		$this->db->from('penilai');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function cek_penjawab($email){
		$this->db->from('penilai');
		$this->db->where('AKTIF','Y');
		$this->db->where('EMAIL_PENILAI',$email);
		$this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
	}

	public function alljawaban(){
		$this->db->from('jawaban');
		$this->db->join ('penilai', 'penilai.ID_PENILAI = jawaban.ID_PENILAI' , 'left' );
		$this->db->join ('kuesioner', 'kuesioner.ID_KUESIONER = jawaban.ID_KUESIONER' , 'left' );
		$this->db->where('jawaban.AKTIF','Y');
		$this->db->where('kuesioner.AKTIF','Y');
		$this->db->where('penilai.AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function statistik_jawaban(){
		$this->db->select('AVG(jawaban.NILAI) AS NILAI, kuesioner.VARIABEL AS VARIABEL, , COUNT(jawaban.NILAI) AS JUMLAH, SUM(jawaban.NILAI) AS TOTAL');
		$this->db->from('jawaban');
		$this->db->join ('kuesioner', 'kuesioner.ID_KUESIONER = jawaban.ID_KUESIONER' , 'left' );
		$this->db->where('jawaban.AKTIF','Y');
		$this->db->where('kuesioner.AKTIF','Y');
		$this->db->group_by('VARIABEL');
		$this->db->order_by('VARIABEL','DESC');
		$query = $this->db->get();
        return $query->result();
	}

	public function getjawaban($id){
		$this->db->from('jawaban');
		$this->db->join ('penilai', 'penilai.ID_PENILAI = jawaban.ID_PENILAI' , 'left' );
		$this->db->join ('kuesioner', 'kuesioner.ID_KUESIONER = jawaban.ID_KUESIONER' , 'left' );
		$this->db->where('jawaban.AKTIF','Y');
		$this->db->where('kuesioner.AKTIF','Y');
		$this->db->where('penilai.AKTIF','Y');
		$this->db->where('jawaban.ID_KUESIONER',$id);
        $query = $this->db->get();
        return $query;
	}

	public function tambah_kuesioner($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$dataTambah = array(
			"PERTANYAAN" => $data['pertanyaan'],
			"VARIABEL" => $data['variabel'],
			"TANGGAL" => $tgl->format('Y-m-d'),
			"AKTIF" => 'Y',
		);
		$this->db->insert('kuesioner', $dataTambah);
	}

	public function tambah_skala($data){
		$dataTambah = array(
			"PREDIKAT" => $data['predikat'],
			"NILAI" => $data['nilai'],
			"ID_KUESIONER" => $data['id'],
			"AKTIF" => 'Y'
		);
		$this->db->insert('nilai', $dataTambah);
	}

	public function hapus_skala($data){
		$dataUbah = array(
			"AKTIF" => 'N',
		);
		$this->db->where('ID_NILAI', $data['id']);
		$this->db->update('nilai', $dataUbah);
		return $this->db->affected_rows();
	}

	public function edit_skala($data){
		$dataUbah = array(
			"NILAI" => $data['nilai'],
			"PREDIKAT" => $data['predikat'],
			"AKTIF" => 'Y',
		);
		$this->db->where('ID_NILAI', $data['id']);
		$this->db->update('nilai', $dataUbah);
		return $this->db->affected_rows();
	}

	public function hapus_kuesioner($data){
		$dataUbah = array(
			"AKTIF" => 'N',
		);
		$this->db->where('ID_KUESIONER', $data['id']);
		$this->db->update('kuesioner', $dataUbah);
		return $this->db->affected_rows();
	}

	public function hapus_jawaban($data){
		$dataUbah = array(
			"AKTIF" => 'N',
		);
		$this->db->where('ID_JAWABAN', $data['id']);
		$this->db->update('jawaban', $dataUbah);
		return $this->db->affected_rows();
	}

	public function hapus_responden($data){
		$dataUbah = array(
			"AKTIF" => 'N',
		);
		$this->db->where('ID_PENILAI', $data['id']);
		$this->db->update('penilai', $dataUbah);
		return $this->db->affected_rows();
	}

	public function hapus_kritik($data){
		$dataUbah = array(
			"AKTIF" => 'N',
		);
		$this->db->where('ID_SARAN', $data['id']);
		$this->db->update('kritik_saran', $dataUbah);
		return $this->db->affected_rows();
	}

	public function ubah_kuesioner($data){
		$dataUbah = array(
			"PERTANYAAN" => $data['pertanyaan'],
			"VARIABEL" => $data['variabel']
		);
		$this->db->where('ID_KUESIONER', $data['id']);
		$this->db->update('kuesioner', $dataUbah);
		return $this->db->affected_rows();
	}

	public function allfaq(){
		$this->db->from('faq');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function get_kuesionerById($id){
		$this->db->from('kuesioner');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_KUESIONER',$id);
        $query = $this->db->get();
        return $query->row();
	}

	public function get_nilaiById($id){
		$this->db->from('nilai');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_KUESIONER',$id);
        $query = $this->db->get();
        return $query;
	}

	public function get_faq($id){
		$this->db->from('faq');
		$this->db->where('AKTIF','Y');
		$this->db->where('ID_FAQ',$id);
        $query = $this->db->get();
        return $query->row();
	}

	public function buat_faq($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$data = array(
			"PERTANYAAN" => $data['pertanyaan'],
			"JAWABAN" => $data['jawaban'],
			"TANGGAL" => $tgl->format('Y-m-d H:i:s'),
			"AKTIF" => 'Y',
		);
		$this->db->insert('faq', $data);
	}

	public function ubah_faq($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$dataUbah = array(
			"PERTANYAAN" => $data['pertanyaan'],
			"JAWABAN" => $data['jawaban'],
			"TANGGAL" => $tgl->format('Y-m-d H:i:s'),
			"AKTIF" => 'Y',
		);
		$this->db->where('ID_FAQ',$data['id']);
		$this->db->update('faq', $dataUbah);
		return $this->db->affected_rows();
	}

	public function hapus_faq($data){
		$timezone = new DateTimeZone('Asia/Jakarta');
		$tgl = new DateTime();
		$tgl->setTimeZone($timezone);
		$dataUbah = array(
			"TANGGAL" => $tgl->format('Y-m-d H:i:s'),
			"AKTIF" => 'N',
		);
		$this->db->where('ID_FAQ',$data['id']);
		$this->db->update('faq', $dataUbah);
		return $this->db->affected_rows();
	}

	public function tagar_populer(){
		$this->db->select('KATA_KUNCI, TANGGAL');
		$this->db->from('tagar');
		$this->db->where('AKTIF','Y');
		$this->db->order_by('TOTAL','DESC');
		$this->db->limit(5);
		$query = $this->db->get();
        return $query;
	}

	public function total_view($posting){
		$this->db->select_sum('VIEW');
		$this->db->from('view');
		$this->db->where('ID_POSTING',$posting);
		$this->db->where('AKTIF','Y');
		$query = $this->db->get();
        return $query->row();
	}

	/*public function laporan_view($posting){
		$this->db->select_sum('VIEW');
		$this->db->select('TANGGAL_VIEW');
		$this->db->from('view');
		$this->db->join ('view', 'view.ID_POSTING = posting.ID_POSTING' , 'left' );
		$this->db->where('ID_POSTING',$posting);
		$this->db->where('AKTIF','Y');
		$this->db->group_by('TANGGAL_VIEW');
		$this->db->limit(30);
		$query = $this->db->get();
        return $query->row();
	}*/

	public function laporan_totalview($posting){
		$this->db->select_sum('VIEW');
		$this->db->select('TANGGAL_VIEW');
		$this->db->from('view');
		$this->db->join ('view', 'view.ID_POSTING = posting.ID_POSTING' , 'left' );
		$this->db->where('AKTIF','Y');
		$this->db->group_by('TANGGAL_VIEW');
		$this->db->limit(30);
		$query = $this->db->get();
        return $query->row();
	}

	public function getArtikel($judulUrl){
		$this->db->from('posting');
		$this->db->join ('kategori', 'kategori.ID_KATEGORI = posting.ID_KATEGORI' , 'left' );
		$this->db->join ('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->WHERE('posting.JUDUL_URL',$judulUrl);
	    $query = $this->db->get();
        return $query->row();
	}

	public function getArtikelBy($pilihan='Y', $offset=1,$limit=3)
	{
		$this->db->select ( '*' ); 
		$this->db->from('posting');
		$this->db->join ('kategori', 'kategori.ID_KATEGORI = posting.ID_KATEGORI' , 'left' );
		$this->db->join ('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->WHERE('posting.AKTIF','Y');
		$this->db->WHERE('posting.CHOICE',$pilihan);
		$this->db->limit($limit,$offset);
		$this->db->order_by('ID_POSTING','DESC');
        $query = $this->db->get();
        return $query;
	}

	public function getArtikelByThread($pilihan='Y', $offset=0,$limit=4)
	{
		$this->db->select('*'); 
		$this->db->from('posting');
		$this->db->join('kategori', 'kategori.ID_KATEGORI = posting.ID_KATEGORI' , 'left' );
		$this->db->join('pengguna', 'posting.ID = pengguna.ID' , 'left' );
		$this->db->WHERE('posting.AKTIF','Y');
		$this->db->WHERE('posting.THREAD',$pilihan);
		$this->db->limit($limit,$offset);
		$this->db->order_by('ID_POSTING','DESC');
        $query = $this->db->get()->result_array();
        return $query;
	}

	public function getArtikelByTrending()
	{
		//$query = $this->db->simple_query('SELECT * FROM view');
		$query = $this->db->query("SELECT * FROM kategori, posting, view, pengguna, rating WHERE posting.ID_POSTING = view.ID_POSTING AND pengguna.ID = posting.ID AND rating.ID_POSTING = rating.ID_POSTING AND kategori.ID_KATEGORI = posting.ID_KATEGORI AND posting.AKTIF ='Y' GROUP BY posting.ID_POSTING ORDER BY posting.ID_POSTING DESC LIMIT 5")->result_array();
        //$query = $this->db->get();
        return $query;
	}

	public function getArtikelByPopuler(){
		$query = $this->db->query("
			SELECT posting.PHOTO AS PHOTO, posting.TANGGAL AS TANGGAL, pengguna.ID AS ID, pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, posting.JUDUL_URL, posting.JUDUL, SUM(view.VIEW) AS TOTAL, kategori.NAMA_KATEGORI AS NAMA_KATEGORI FROM posting, view, pengguna, kategori WHERE posting.ID_POSTING = view.ID_POSTING AND posting.ID_KATEGORI=kategori.ID_KATEGORI AND posting.ID=pengguna.ID group by posting.ID_POSTING order by TOTAL DESC limit 5");
		return $query->result_array();
	}

	public function getArtikelPopulerByPengguna($id){
		$query = $this->db->query("
			SELECT posting.PHOTO AS PHOTO, posting.TANGGAL AS TANGGAL, pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, posting.JUDUL_URL, posting.JUDUL, posting.DESKRIPSI_ARTIKEL, SUM(view.VIEW) AS TOTAL, kategori.NAMA_KATEGORI AS NAMA_KATEGORI FROM posting, view, pengguna, kategori WHERE  posting.ID_POSTING = view.ID_POSTING AND posting.ID_KATEGORI=kategori.ID_KATEGORI AND posting.ID=pengguna.ID AND pengguna.ID='$id' group by posting.ID_POSTING order by TOTAL DESC limit 5");
		return $query->result_array();
	}

	

	//kategori model
	public function allkategori(){
		return $this->db->get('kategori');
	}

	public function kategori(){
		$this->db->from('kategori');
        $this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function kategori_pilihan(){
		$this->db->from('kategori');
		$this->db->where('FEATURED','Y');
		$this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function get_kategoriByNama($kategori){
		$this->db->from('kategori');
		$this->db->where('NAMA_KATEGORI',$kategori);
        $this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query;
	}

	public function cek_kategori(){
		$kategori = $this->input->post('namakategori');
		$this->db->from('kategori');
		$this->db->like('NAMA_KATEGORI',$kategori);
        $this->db->where('AKTIF','Y');
        $query = $this->db->get();
        return $query->num_rows();
	}

	public function kategori_hapus($data){
		$dataUbah = array(
			'AKTIF' => 'N'
		);
		$this->db->where('ID_KATEGORI', $data['idkategori']);
		$this->db->update('kategori',$dataUbah);
		return $this->db->affected_rows();
	}

	public function kategori_ubah($data){
		$dataUbah = array(
            'ID_KATEGORI' => $data['idkategori'],
			'NAMA_KATEGORI' => $data['namakategori'],
			'FEATURED' => $data['featured']
        );

        $this->db->where('ID_KATEGORI', $data['idkategori']);
        $this->db->update('kategori', $dataUbah);
        return $this->db->affected_rows();
	}

	public function kategoriById($id){
		$this->db->from('kategori');
		$this->db->where('id_kategori',$id);
		$query = $this->db->get();
		return $query->row();
	}

	//identitas model
	public function getidentitas(){
		$this->db->from('identitas');
        $this->db->limit('1');
        $query = $this->db->get();
        return $query->row();
	}

	public function getkebijakan(){
		$this->db->from('kebijakan');
        $this->db->limit('1');
        $query = $this->db->get();
        return $query->row();
	}

	public function kebijakan_ubah($data){
		$dataUbah = array(
            'NAMA_KEBIJAKAN' => $data['namakebijakan'],
            'KEBIJAKAN' => $data['kebijakan']
        );

        $this->db->where('ID_KEBIJAKAN', $data['idkebijakan']);
        $this->db->update('kebijakan', $dataUbah);
        return $this->db->affected_rows();
	}

	//kontak model
	public function getkontak(){
		$this->db->from('kontak');
        $this->db->limit('1');
        $query = $this->db->get();
        return $query->row();
	}

	public function kontak_ubah($data){
		$dataUbah = array(
            'NAMA_KONTAK' => $data['namakontak'],
            'DESKRIPSi' => $data['deskripsikontak'],
			'ID_KONTAK' => $data['idkontak']
        );

        $this->db->where('ID_KONTAK', $data['idkontak']);
        $this->db->update('kontak', $dataUbah);
        return $this->db->affected_rows();
	}


	//profil model
	public function get_daftarmengikuti($id){
		$this->db->select('pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, pengguna.ID AS ID, pengguna.PHOTO_PROFIL AS PHOTO_PROFIL, COUNT(posting.ID_POSTING) AS POSTING');
		$this->db->from('mengikuti');
		$this->db->join('detail_mengikuti', 'detail_mengikuti.ID_MENGIKUTI = mengikuti.ID_MENGIKUTI' , 'left' );
		$this->db->join('pengguna', 'pengguna.ID = detail_mengikuti.ID' , 'left' );
		$this->db->join('posting', 'pengguna.ID = posting.ID' , 'left' );
		$this->db->where('mengikuti.ID_MENGIKUTI',$id);
		$this->db->where('detail_mengikuti.AKTIF','Y');
		$this->db->group_by('ID');
		$query = $this->db->get();
		return $query;
	}

	public function get_daftarpengikut($id){
		$this->db->select('pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, pengguna.ID AS ID, pengguna.PHOTO_PROFIL AS PHOTO_PROFIL, COUNT(posting.ID_POSTING) AS POSTING');
		$this->db->from('pengikut');
		$this->db->join('detail_pengikut', 'detail_pengikut.ID_PENGIKUT = pengikut.ID_PENGIKUT' , 'left' );
		$this->db->join('pengguna', 'pengguna.ID = detail_pengikut.ID' , 'left' );
		$this->db->join('posting', 'pengguna.ID = posting.ID' , 'left' );
		$this->db->where('pengikut.ID_PENGIKUT',$id);
		$this->db->where('detail_pengikut.AKTIF','Y');
		$this->db->group_by('ID');
		$query = $this->db->get();
		return $query;
	}
	
	public function get_listpengikut($id){
		$this->db->select('pengguna.FIRST_NAME AS FIRST_NAME, pengguna.LAST_NAME AS LAST_NAME, pengguna.ID AS ID, pengguna.PHOTO_PROFIL AS PHOTO_PROFIL');
		$this->db->from('pengikut');
		$this->db->join('detail_pengikut', 'detail_pengikut.ID_PENGIKUT = pengikut.ID_PENGIKUT' , 'left' );
		$this->db->join('pengguna', 'pengguna.ID = detail_pengikut.ID' , 'left' );
		$this->db->where('pengikut.ID_PENGIKUT',$id);
		$this->db->where('detail_pengikut.AKTIF','Y');
		$this->db->group_by('ID');
		$query = $this->db->get();
		return $query;
	}

	public function getpengikut($id){
		$this->db->select('*');
		$this->db->from('pengikut');
		$this->db->join('detail_pengikut', 'detail_pengikut.ID_PENGIKUT = pengikut.ID_PENGIKUT' , 'left' );
		$this->db->where('pengikut.ID',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function getdiikuti($id){
		$this->db->select('*');
		$this->db->from('pengikut');
		$this->db->where('pengikut.ID',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function getmengikuti($id){
		$this->db->select('*');
		$this->db->from('mengikuti');
		$this->db->where('mengikuti.ID',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_pengikut($id){
		$this->db->select('*');
		$this->db->from('pengikut');
		$this->db->where('pengikut.ID',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_detailpengikut($id_pengikut){
		$this->db->select('pengguna.ID, pengguna.FIRST_NAME, pengguna.LAST_NAME, count(POSTING.ID_POSTING) AS ARTIKEL');
		$this->db->from('pengguna, detail_pengikut, pengikut, posting');
		$this->db->where('detail_pengikut.ID_PENGIKUT','pengikut.ID_PENGIKUT');
		$this->db->where('pengikut.ID','pengguna.ID');
		$this->db->where('pengguna.ID','posting.ID');
		$this->db->where('detail_pengikut.ID_PENGIKUT',$id_pengikut);
		$query = $this->db->get();
		return $query;
	}

	public function buat_mengikuti($id){
		$data = array(
			"ID" => $id,
			"TOTAL_MENGIKUTI" => 1,
			"AKTIF" => 'Y',
		);
		$this->db->insert('mengikuti', $data);
	}

	public function buat_diikuti($id){
		$data = array(
			"ID" => $id,
			"TOTAL_PENGIKUT" => 1,
			"AKTIF" => 'Y',
		);
		$this->db->insert('pengikut', $data);
	}

	public function tambah_detailmengikuti($id_bio, $id_pengguna_mengikuti){
		$data = array(
			"ID" => $id_bio,
			"ID_MENGIKUTI" => $id_pengguna_mengikuti,
			"AKTIF" => 'Y',
		);
		$this->db->insert('detail_mengikuti', $data);
	}

	public function tambah_detaildiikuti($id_pengikut, $id_pengguna_mengikuti){
		$data = array(
			"ID" => $id_pengguna_mengikuti,
			"ID_PENGIKUT" => $id_pengikut,
			"AKTIF" => 'Y',
		);
		$this->db->insert('detail_pengikut', $data);
	}

	public function getdetailmengikuti($id_bio,$id_akun_mengikuti){
		$this->db->select('*');
		$this->db->from('detail_mengikuti');
		$this->db->where('ID',$id_bio);
		$this->db->where('ID_MENGIKUTI',$id_akun_mengikuti);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function getdetailpengikut($id_bio,$id_akun_mengikuti){
		$this->db->select('*');
		$this->db->from('detail_pengikut');
		$this->db->where('ID',$id_akun_mengikuti);
		$this->db->where('ID_PENGIKUT',$id_bio);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function perbarui_total_pengikut($total, $id){
		$data = array(
			"TOTAL_PENGIKUT" => $total
		);
		$this->db->where('ID_PENGIKUT', $id);
		$this->db->update('pengikut', $data);
		return $this->db->affected_rows();
	}

	public function perbarui_total_mengikuti($total, $id){
		$data = array(
			"TOTAL_MENGIKUTI" => $total
		);
		$this->db->where('ID_MENGIKUTI', $id);
		$this->db->update('mengikuti', $data);
		return $this->db->affected_rows();
	}

	public function perbarui_detailmengikuti($id_detailmengikuti,$aktif){
		if($aktif=="Y"){
			$data = array(
				"AKTIF" => 'N'
			);
		}else{
			$data = array(
				"AKTIF" => 'Y'
			);
		}
		$this->db->where('ID_DETAILMENGIKUTI', $id_detailmengikuti);
		$this->db->update('detail_mengikuti', $data);
		return $this->db->affected_rows();
	}

	public function perbarui_detaildiikuti($id_detailpengikut,$aktif){
		if($aktif=="Y"){
			$data = array(
				"AKTIF" => 'N'
			);
		}else{
			$data = array(
				"AKTIF" => 'Y'
			);
		}
		$this->db->where('ID_DETAILPENGIKUT', $id_detailpengikut);
		$this->db->update('detail_pengikut', $data);
		return $this->db->affected_rows();
	}

	public function getUserById($id_user){
		$this->db->from('users');
		$this->db->where('id',$id_user);
		$query = $this->db->get();
		return $query->row();
	}
	
	public function getPenggunaById($id_user){
		$this->db->from('pengguna');
		$this->db->where('id_users',$id_user);
		$query = $this->db->get();
		return $query->row();
	}

	public function getPengguna($id){
		$this->db->from('pengguna');
		$this->db->where('ID',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function getPenggunaByNamaLengkapID($id=""){
		$this->db->from('pengguna');
		$this->db->where('ID',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	public function getidprofil($email){
		$this->db->from('users');
        $this->db->where('email',$email);
        $query = $this->db->get();
        return $query->row();
	}

	public function getprofil($id){
		$this->db->from('users');
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
	}

	public function users_ubah($profil)
	{
		if(! empty($profil['pathfoto']) && (! is_null($profil['pathfoto']))){
			$data = array(
				'first_name' => $profil['firstname'],
				'last_name' => $profil['lastname'],
				'company' => $profil['company'],
				'password' => $profil['password'],
				'phone' => $profil['phone'],
				'email' => $profil['email'],
				'about_me' => $profil['aboutme'],
				'photo_profil' => $profil['pathfoto']
			);
		}else{
			$data = array(
				'first_name' => $profil['firstname'],
				'last_name' => $profil['lastname'],
				'company' => $profil['company'],
				'password' => $profil['password'],
				'phone' => $profil['phone'],
				'email' => $profil['email'],
				'about_me' => $profil['aboutme']
			);
		}
		$this->ion_auth->update($profil['id'], $data);

		$dataAboutMe = array(
				'about_me' => $profil['aboutme']
			);
		$this->db->where('id', $profil['id']);
        $this->db->update('users', $dataAboutMe);
        return $this->db->affected_rows();
	}

	public function pengguna_ubah($profil)
	{
		if(! empty($profil['pathfoto']) && (! is_null($profil['pathfoto']))){
			$data = array(
				'FIRST_NAME' => $profil['firstname'],
				'LAST_NAME' => $profil['lastname'],
				'COMPANY' => $profil['company'],
				'PHONE' => $profil['phone'],
				'EMAIL' => $profil['email'],
				'ABOUT_ME' => $profil['aboutme'],
				"PHOTO_PROFIL" => $profil['pathfoto']
			);
		}else{
			$data = array(
				'FIRST_NAME' => $profil['firstname'],
				'LAST_NAME' => $profil['lastname'],
				'COMPANY' => $profil['company'],
				'PHONE' => $profil['phone'],
				'EMAIL' => $profil['email'],
				'ABOUT_ME' => $profil['aboutme']
			);
		}		
		$this->db->where('ID_USERS', $profil['id']);
        $this->db->update('pengguna', $data);
        return $this->db->affected_rows();
	}

	//identitas model
	public function identitas_ubah($data){
		$dataUbah = array(
            'NAMA_WEBSITE' => $data['namawebsite'],
            'ALAMAT_WEBSITE' => $data['alamatwebsite'],
			'META_DESKRIPSI' => $data['metadeskripsi'],
			'META_KEYWORD' => $data['metakeyword'],
			'TEMPLATE' => $data['template'],
			'ID_IDENTITAS' => $data['ididentitas']
        );
		//return json_encode($dataUbah);
        $this->db->where('ID_IDENTITAS', $data['ididentitas']);
        $this->db->update('identitas', $dataUbah);
        return $this->db->affected_rows();
	}

	public function identitas_buat($data){
		$data = array(
			"NAMA_WEBSITE" => $data['namawebsite'],
			"ALAMAT_WEBSITE" => $data['alamatwebsite'],
			"META_DESKRIPSI" => $data['metadeskripsi'],
			"META_KEYWORD" => $data['metakeyword'],
			"FAVICON" => $data['favicon']
		);
		$this->db->insert('identitas', $data);
	}

	//kategori model
	public function kategori_buat(){
		$data = array(
			"NAMA_KATEGORI" => $this->input->post('namakategori'),
			"FEATURED" => $this->input->post('featured'),
			"AKTIF" => 'Y'
		);

		$this->db->insert('kategori', $data);
	}


	//registrasi model
	public function cekAkunByEmail($email){
		$this->db->from('users');
		$this->db->where('email',$email);
		$query = $this->db->get();
		return $query->row();
	}

	public function akun_buat($data){
		$data = array(
			'FIRST_NAME' => $data['firstname'],
			'LAST_NAME' => $data['lastname'],
			'COMPANY' => $data['company'],
			'PHONE' => $data['phone'],
			'EMAIL' => $data['email'],
			'USERNAME' => $data['email'],
			'ID_USERS' => $data['id_users']
			);

		$this->db->insert('pengguna', $data);
	}


}