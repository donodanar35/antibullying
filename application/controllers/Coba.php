<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba extends CI_Controller {
	public function __Contruct(){
		parent::__Contruct();
		$this->load->helper('url');
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->model(array('MyModel','ion_auth_model'));
    }

    public function indexku(){
        //$this->load->view('coba/cobaupload');
        $balasan = $this->MyModel->balasanKomentarByArtikel(1);
        //var_dump($balasan->result_array());
        echo $balasan->num_rows();
        foreach($balasan->result_array() as $balas){
            if(empty($balas['ID_KOMENTAR']) || (is_null($balas['ID_KOMENTAR']))){
                echo 'kosong ';
            }else{
                
            }
            //var_dump($balas);
        }
    }

    public function tes_stemming($kata){
        $stemming = $this->MyModel->stemming($kata);
        echo $stemming;
    }

    public function tes_stemmer(){
        $stemming = $this->MyModel->stemmer("Apa iya manusia itu menyukai meratap dan berkeluh kesah susah ya manusia yang seperti itu");
        echo $stemming;
    }

    public function tes_stopword(){
        $stopword = $this->MyModel->stopword("Apa iya manusia itu suka meratap dan berkeluh kesah susah ya manusia yang seperti itu");
        echo $stopword;
    }

    public function tes_preprocessing(){
        $komen = "Apa iya? manusia itu suka meratap dan berkeluh kesah susah ya. susah juga kalau manusia yang seperti itu ya.";
        $case_folding = $this->MyModel->case_folding($komen);
		$cleansing = $this->MyModel->cleansing($case_folding);
		$cek = $this->MyModel->gabungkan_kata($cleansing);
		$normalisasi = $this->MyModel->normalisasi_bahasagaul($cek);
        $stemming = $stemming = $this->MyModel->stemmer($normalisasi);
        $result = $this->MyModel->stopword($stemming);
        echo $result;
    }

    public function index(){
        /*$arr = array(
            '1'=>'satu',
            '2'=>'dua',
            '3'=>'tiga',
            '4'=>'empat'
        );
        $er = array(
            '1'=>'satu1',
            '2'=>'dua2',
            '3'=>'tiga3',
            '4'=>'empat4'
        );
        foreach($arr as $ar){
            foreach($er as $e){
                echo "$e ";
            }
            echo "$ar <br/>";
        }*/
        $stopword = array(
			'ada','adalah','adanya','adapun','agak','agaknya','agar','akan','akankah',
			'akhir','akhiri','akhirnya','aku','akulah','amat','amatlah','anda','andalah',
			'antar','antara','antaranya','apa','apaan','apabila','apakah','apalagi',
			'apatah','artinya','asal','asalkan','atas','atau','ataukah','ataupun','awal',
			'awalnya','bagai','bagaikan','bagaimana','bagaimanakah','bagaimanapun','bagi',
			'bagian','bahkan','bahwa','bahwasanya','baik','bakal','bakalan','balik','banyak',
			'bapak','baru','bawah','beberapa','begini','beginian','beginikah','beginilah',
			'begitu','begitukah','begitulah','begitupun','bekerja','belakang','belakangan',
			'belum','belumlah','benar','benarkah','benarlah','berada','berakhir','berakhirlah',
			'berakhirnya','berapa','berapakah','berapalah','berapapun','berarti','berawal',
			'berbagai','berdatangan','beri','berikan','berikut','berikutnya','berjumlah',
			'berkali-kali','berkata','berkehendak','berkeinginan','berkenaan','berlainan',
			'berlalu','berlangsung','berlebihan','bermacam','bermacam-macam','bermaksud',
			'bermula','bersama','bersama-sama','bersiap','bersiap-siap','bertanya',
			'bertanya-tanya','berturut','berturut-turut','bertutur','berujar','berupa',
			'besar','betul','betulkah','biasa','biasanya','bila','bilakah','bisa','bisakah',
			'boleh','bolehkah','bolehlah','buat','bukan','bukankah','bukanlah','bukannya',
			'bulan','bung','cara','caranya','cukup','cukupkah','cukuplah','cuma','dahulu',
			'dalam','dan','dapat','dari','daripada','datang','dekat','demi','demikian',
			'demikianlah','dengan','depan','di','dia','diakhiri','diakhirinya','dialah',
			'diantara','diantaranya','diberi','diberikan','diberikannya','dibuat','dibuatnya',
			'didapat','didatangkan','digunakan','diibaratkan','diibaratkannya','diingat',
			'diingatkan','diinginkan','dijawab','dijelaskan','dijelaskannya','dikarenakan',
			'dikatakan','dikatakannya','dikerjakan','diketahui','diketahuinya','dikira',
			'dilakukan','dilalui','dilihat','dimaksud','dimaksudkan','dimaksudkannya',
			'dimaksudnya','diminta','dimintai','dimisalkan','dimulai','dimulailah',
			'dimulainya','dimungkinkan','dini','dipastikan','diperbuat','diperbuatnya',
			'dipergunakan','diperkirakan','diperlihatkan','diperlukan','diperlukannya',
			'dipersoalkan','dipertanyakan','dipunyai','diri','dirinya','disampaikan',
			'disebut','disebutkan','disebutkannya','disini','disinilah','ditambahkan',
			'ditandaskan','ditanya','ditanyai','ditanyakan','ditegaskan','ditujukan',
			'ditunjuk','ditunjuki','ditunjukkan','ditunjukkannya','ditunjuknya','dituturkan',
			'dituturkannya','diucapkan','diucapkannya','diungkapkan','dong','dua','dulu',
			'empat','enggak','enggaknya','entah','entahlah','guna','gunakan','hal','hampir',
			'hanya','hanyalah','hari','harus','haruslah','harusnya','hendak','hendaklah',
			'hendaknya','hingga','ia','ialah','ibarat','ibaratkan','ibaratnya','ibu','ikut',
			'ingat','ingat-ingat','ingin','inginkah','inginkan','ini','inikah','inilah','itu',
			'itukah','itulah','jadi','jadilah','jadinya','jangan','jangankan','janganlah',
			'jauh','jawab','jawaban','jawabnya','jelaskan','jelaslah','jelasnya',
			'jika','jikalau','juga','jumlah','jumlahnya','justru','kala','kalau','kalaulah',
			'kalaupun','kalian','kami','kamilah','kamu','kamulah','kan','kapan','kapankah',
			'kapanpun','karena','karenanya','kasus','kata','katakan','katakanlah','katanya',
			'ke','keadaan','kebetulan','kecil','kedua','keduanya','keinginan','kelamaan',
			'kelihatan','kelihatannya','kelima','keluar','kembali','kemudian','kemungkinan',
			'kemungkinannya','kenapa','kepada','kepadanya','kesampaian','keseluruhan',
			'keseluruhannya','keterlaluan','ketika','khususnya','kini','kinilah','kira',
			'kira-kira','kiranya','kita','kitalah','kok','kurang','lagi','lagian','lah',
			'lain','lainnya','lalu','lama','lamanya','lanjut','lanjutnya','lebih','lewat',
			'lima','luar','macam','maka','makanya','makin','malah','malahan','mampu',
			'mampukah','mana','manakala','manalagi','masa','masalah','masalahnya','masih',
			'masihkah','masing','masing-masing','mau','maupun','melainkan','melakukan',
			'melalui','melihat','melihatnya','memang','memastikan','memberi','memberikan',
			'membuat','memerlukan','memihak','meminta','memintakan','memisalkan','memperbuat',
			'mempergunakan','memperkirakan','memperlihatkan','mempersiapkan','mempersoalkan',
			'mempertanyakan','mempunyai','memulai','memungkinkan','menaiki','menambahkan',
			'menandaskan','menanti','menanti-nanti','menantikan','menanya','menanyai',
			'menanyakan','mendapat','mendapatkan','mendatang','mendatangi','mendatangkan',
			'menegaskan','mengakhiri','mengapa','mengatakan','mengatakannya','mengenai',
			'mengerjakan','mengetahui','menggunakan','menghendaki','mengibaratkan',
			'mengibaratkannya','mengingat','mengingatkan','menginginkan','mengira',
			'mengucapkan','mengucapkannya','mengungkapkan','menjadi','menjawab',
			'menjelaskan','menuju','menunjuk','menunjuki','menunjukkan','menunjuknya',
			'menurut','menuturkan','menyampaikan','menyangkut','menyatakan','menyebutkan',
			'menyeluruh','menyiapkan','merasa','mereka','merekalah','merupakan','meski',
			'meskipun','meyakini','meyakinkan','minta','mirip','misal','misalkan',
			'misalnya','mula','mulai','mulailah','mulanya','mungkin','mungkinkah',
			'nah','naik','namun','nanti','nantinya','nyaris','nyatanya','oleh',
			'olehnya','pada','padahal','padanya','pak','paling','panjang','pantas',
			'para','pasti','pastilah','penting','pentingnya','per','percuma','perlu',
			'perlukah','perlunya','pernah','persoalan','pertama','pertama-tama',
			'pertanyaan','pertanyakan','pihak','pihaknya','pukul','pula','pun','punya',
			'rasa','rasanya','rata','rupanya','saat','saatnya','saja','sajalah','saling',
			'sama','sama-sama','sambil','sampai','sampai-sampai','sampaikan','sana',
			'sangat','sangatlah','satu','saya','sayalah','se','sebab','sebabnya',
			'sebagai','sebagaimana','sebagainya','sebagian','sebaik','sebaik-baiknya',
			'sebaiknya','sebaliknya','sebanyak','sebegini','sebegitu','sebelum','sebelumnya',
			'sebenarnya','seberapa','sebesar','sebetulnya','sebisanya','sebuah',
			'sebut','sebutlah','sebutnya','secara','secukupnya','sedang','sedangkan',
			'sedemikian','sedikit','sedikitnya','seenaknya','segala','segalanya','segera',
			'seharusnya','sehingga','seingat','sejak','sejauh','sejenak','sejumlah',
			'sekadar','sekadarnya','sekali','sekali-kali','sekalian','sekaligus','sekalipun',
			'sekarang','sekarang','sekecil','seketika','sekiranya','sekitar','sekitarnya',
			'sekurang-kurangnya','sekurangnya','sela','selain','selaku','selalu','selama',
			'selama-lamanya','selamanya','selanjutnya','seluruh','seluruhnya','semacam',
			'semakin','semampu','semampunya','semasa','semasih','semata','semata-mata',
			'semaunya','sementara','semisal','semisalnya','sempat','semua','semuanya',
			'semula','sendiri','sendirian','sendirinya','seolah','seolah-olah','seorang',
			'sepanjang','sepantasnya','sepantasnyalah','seperlunya','seperti','sepertinya',
			'sepihak','sering','seringnya','serta','serupa','sesaat','sesama','sesampai',
			'sesegera','sesekali','seseorang','sesuatu','sesuatunya','sesudah','sesudahnya',
			'setelah','setempat','setengah','seterusnya','setiap','setiba','setibanya',
			'setidak-tidaknya','setidaknya','setinggi','seusai','sewaktu','siap','siapa',
			'siapakah','siapapun','sini','sinilah','soal','soalnya','suatu','sudah','sudahkah',
			'sudahlah','supaya','tadi','tadinya','tahu','tahun','tak','tambah','tambahnya',
			'tampak','tampaknya','tandas','tandasnya','tanpa','tanya','tanyakan','tanyanya',
			'tapi','tegas','tegasnya','telah','tempat','tengah','tentang','tentu','tentulah',
			'tentunya','tepat','terakhir','terasa','terbanyak','terdahulu','terdapat',
			'terdiri','terhadap','terhadapnya','teringat','teringat-ingat','terjadi',
			'terjadilah','terjadinya','terkira','terlalu','terlebih','terlihat','termasuk',
			'ternyata','tersampaikan','tersebut','tersebutlah','tertentu','tertuju',
			'terutama','tetap','tetapi','tiap','tiba','tiba-tiba','tidakkah',
			'tidaklah','tiga','tinggi','toh','tunjuk','turut','tutur','tuturnya','ucap',
			'ucapnya','ujar','ujarnya','umum','umumnya','ungkap','ungkapnya','untuk',
			'usah','usai','waduh','wah','wahai','waktu','waktunya','walau','walaupun','wong',
			'yaitu','yakin','yakni','yang','selagi','ya','iya'
        );
        
        foreach($stopword as $ar){
            $this->MyModel->stopwordlist_buat($ar);
        }
        echo "sukses";
    }

    public function pos(){
        var_dump($_POST);
        //echo 'ok';

        /*$config['upload_path'] = './images/galeri/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 2000;
        $config['max_width'] = 1500;
        $config['max_height'] = 1500;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            //var_dump($error);
        } else {
            $data = array('image_metadata' => $this->upload->data());
            //var_dump($data);
        }*/
    }


    public function upload_image()
  {
 
      $countfiles = count($_FILES['files']['name']);
  
      for($i=0;$i<$countfiles;$i++){
  
        if(!empty($_FILES['files']['name'][$i])){
  
          // Define new $_FILES array - $_FILES['file']
          $_FILES['file']['name'] = $_FILES['files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['files']['size'][$i];
 
          // Set preference
          $config['upload_path'] = './uploads/'; 
          $config['allowed_types'] = 'jpg|jpeg|png|gif';
          $config['max_size'] = '5000'; // max_size in kb
          $config['file_name'] = $_FILES['files']['name'][$i];
  
          //Load upload library
          $this->load->library('upload',$config); 
          $arr = array('msg' => 'something went wrong', 'success' => false);
          // File upload
          if($this->upload->do_upload('file')){
           
           $data = $this->upload->data(); 
           //$insert['name'] = $data['file_name'];
           //$this->db->insert('images',$insert);
           //$get = $this->db->insert_id();
            $arr = array('msg' => 'Image has been uploaded successfully', 'success' => true);
 
          }
        }
  
      }
      echo json_encode($arr);
  
  }


    public function upload_file(){
        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
        
        if (empty($_POST['title']))
        {
            $status = "error";
            $msg = "Please enter a title";
        }
        
        if ($status != "error")
        {
            $config['upload_path'] = './images/';
            $config['allowed_types'] = 'gif|jpg|png|doc|txt';
            $config['max_size'] = 1024 * 8;
            $config['encrypt_name'] = TRUE;
    
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload($file_element_name))
            {
                $status = 'error';
                $msg = $this->upload->display_errors('', '');
            }
            else
            {
                $data = $this->upload->data();
                $status = 'success';
                $msg = 'ok';
            }
            @unlink($_FILES[$file_element_name]);
        }
        //echo json_encode(array('status' => $status, 'msg' => $msg));
    }

    public function process()
    {
        $F = array();

        $count_uploaded_files = count( $_FILES['images']['name'] );
        var_dump($_FILES);
        if(isset($_FILES)){
            $config['upload_path'] = './images/galeri';
            //$config['file_name'] = $_FILES['images']['name'];
            //$config['allowed_types'] = 'gif|jpg|png|jpeg';
            
            $this->load->library('upload', $config);
            //$this->upload->initialize($config);

            $summary = $this->upload->data();
        }
        
        //var_dump($_POST);
        /*
        $files = $_FILES;
        for( $i = 0; $i < $count_uploaded_files; $i++ )
        {
            $_FILES['userfile'] = [
                'name'     => $files['images']['name'][$i],
                'type'     => $files['images']['type'][$i],
                'tmp_name' => $files['images']['tmp_name'][$i],
                'error'    => $files['images']['error'][$i],
                'size'     => $files['images']['size'][$i]
            ];

            $F[] = $_FILES['userfile'];
            var_dump($F);
            // Here is where you do your CodeIgniter upload ...
        }
        echo json_encode($F);*/
    }
}