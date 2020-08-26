<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antibullying extends CI_Controller {
	
	public function __Contruct(){
		parent::__Contruct();
		$this->load->helper('url');
		$this->load->library(array('ion_auth', 'form_validation'));
        $this->load->model(array('MyModel','ion_auth_model'));        
	}
	
	public function getdatasetkomentar(){
		echo json_encode($this->MyModel->dataset_komentarById($_POST['idx']));
	}
	
	public function getkamusgaul(){
		echo json_encode($this->MyModel->kamusgaulById($_POST['idx']));
	}
	
	public function getstopwrdlist(){
		echo json_encode($this->MyModel->stopwordlistById($_POST['idx']));
    }

	public function tes(){
		$tes = "^ & %aku% ak4  $ 12 Trilyun2 oke2 nggak Enak2 banget2 ya Tragedi ular kembali memakan korban. Kali ini seorang bocah laki2 di Bandung tewas setelah dipatuk ular weling.--Insiden itu menimpa Andi Ramdani (11), bocah asal Jalan Nagrog, Gang Keramat RT 04/09, Kelurahan Pasirjati, Kecamatan Ujungberung, Kota Bandung. Andi tewas saat dilarikan ke rumah sakit umum daerah (RSUD) Bandung pada Rabu (23/1) siang.--Bagaimana awal mula kejadiannya? Simak selengkapnya di link yang ada di stories!--Sumber: detiknews--#detikcom #ularweling #dipatukular";
		$gabungkan_kata = $this->MyModel->gabungkan_kata($tes);
		/*$gabungkan_kata = '';
		$cek = preg_split('/ /',$tes);
		$count = count($cek);
		for($i=0;$i<$count;$i++){
			$cek[$i] = $this->MyModel->ganti_katagaul($cek[$i]);
		}
		$gabungkan_kata = implode(' ', $cek);*/
		print_r($gabungkan_kata);
	}

	public function testing(){
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer = $stemmerFactory->createStemmer();
		$output = $stemmer->stem('memakan');
		echo $output;
	}
	
	public function uji(){		
		$tes = "ada adalah adanya adapun agak agaknya agar akan akankah akhir akhiri akhirnya aku akulah amat amatlah anda andalah antar antara antaranya apa apaan apabila apakah apalagi apatah artinya asal asalkan atas atau ataukah ataupun awal awalnya bagai bagaikan bagaimana bagaimanakah bagaimanapun bagi bagian bahkan bahwa bahwasanya baik bakal bakalan balik banyak bapak baru bawah beberapa begini beginian beginikah beginilah begitu begitukah begitulah begitupun bekerja belakang belakangan belum belumlah benar benarkah benarlah berada berakhir berakhirlah berakhirnya berapa berapakah berapalah berapapun berarti berawal berbagai berdatangan beri berikan berikut berikutnya berjumlah berkali-kali berkata berkehendak berkeinginan berkenaan berlainan berlalu berlangsung berlebihan bermacam bermacam-macam bermaksud bermula bersama bersama-sama bersiap bersiap-siap bertanya bertanya-tanya berturut berturut-turut bertutur berujar berupa besar betul betulkah biasa biasanya bila bilakah bisa bisakah boleh bolehkah bolehlah buat bukan bukankah bukanlah bukannya bulan bung cara caranya cukup cukupkah cukuplah cuma dahulu dalam dan dapat dari daripada datang dekat demi demikian demikianlah dengan depan di dia diakhiri diakhirinya dialah diantara diantaranya diberi diberikan diberikannya dibuat dibuatnya didapat didatangkan digunakan diibaratkan diibaratkannya diingat diingatkan diinginkan dijawab dijelaskan dijelaskannya dikarenakan dikatakan dikatakannya dikerjakan diketahui diketahuinya dikira dilakukan dilalui dilihat dimaksud dimaksudkan dimaksudkannya dimaksudnya diminta dimintai dimisalkan dimulai dimulailah dimulainya dimungkinkan dini dipastikan diperbuat diperbuatnya dipergunakan diperkirakan diperlihatkan diperlukan diperlukannya dipersoalkan dipertanyakan dipunyai diri dirinya disampaikan disebut disebutkan disebutkannya disini disinilah ditambahkan ditandaskan ditanya ditanyai ditanyakan ditegaskan ditujukan ditunjuk ditunjuki ditunjukkan ditunjukkannya ditunjuknya dituturkan dituturkannya diucapkan diucapkannya diungkapkan dong dua dulu empat enggak enggaknya entah entahlah guna gunakan hal hampir hanya hanyalah hari harus haruslah harusnya hendak hendaklah hendaknya hingga ia ialah ibarat ibaratkan ibaratnya ibu ikut ingat ingat-ingat ingin inginkah inginkan ini inikah inilah itu itukah itulah jadi jadilah jadinya jangan jangankan janganlah jauh jawab jawaban jawabnya jelas jelaskan jelaslah jelasnya jika jikalau juga jumlah jumlahnya justru kala kalau kalaulah kalaupun kalian kami kamilah kamu kamulah kan kapan kapankah kapanpun karena karenanya kasus kata katakan katakanlah katanya ke keadaan kebetulan kecil kedua keduanya keinginan kelamaan kelihatan kelihatannya kelima keluar kembali kemudian kemungkinan kemungkinannya kenapa kepada kepadanya kesampaian keseluruhan keseluruhannya keterlaluan ketika khususnya kini kinilah kira kira-kira kiranya kita kitalah kok kurang lagi lagian lah lain lainnya lalu lama lamanya lanjut lanjutnya lebih lewat lima luar macam maka makanya makin malah malahan mampu mampukah mana manakala manalagi masa masalah masalahnya masih masihkah masing masing-masing mau maupun melainkan melakukan melalui melihat melihatnya memang memastikan memberi memberikan membuat memerlukan memihak meminta memintakan memisalkan memperbuat mempergunakan memperkirakan memperlihatkan mempersiapkan mempersoalkan mempertanyakan mempunyai memulai memungkinkan menaiki menambahkan menandaskan menanti menanti-nanti menantikan menanya menanyai menanyakan mendapat mendapatkan mendatang mendatangi mendatangkan menegaskan mengakhiri mengapa mengatakan mengatakannya mengenai mengerjakan mengetahui menggunakan menghendaki mengibaratkan mengibaratkannya mengingat mengingatkan menginginkan mengira mengucapkan mengucapkannya mengungkapkan menjadi menjawab menjelaskan menuju menunjuk menunjuki menunjukkan menunjuknya menurut menuturkan menyampaikan menyangkut menyatakan menyebutkan menyeluruh menyiapkan merasa mereka merekalah merupakan meski meskipun meyakini meyakinkan minta mirip misal misalkan misalnya mula mulai mulailah mulanya mungkin mungkinkah nah naik namun nanti nantinya nyaris nyatanya oleh olehnya pada padahal padanya pak paling panjang pantas para pasti pastilah penting pentingnya per percuma perlu perlukah perlunya pernah persoalan pertama pertama-tama pertanyaan pertanyakan pihak pihaknya pukul pula pun punya rasa rasanya rata rupanya saat saatnya saja sajalah saling sama sama-sama sambil sampai sampai-sampai sampaikan sana sangat sangatlah satu saya sayalah se sebab sebabnya sebagai sebagaimana sebagainya sebagian sebaik sebaik-baiknya sebaiknya sebaliknya sebanyak sebegini sebegitu sebelum sebelumnya sebenarnya seberapa sebesar sebetulnya sebisanya sebuah sebut sebutlah sebutnya secara secukupnya sedang sedangkan sedemikian sedikit sedikitnya seenaknya segala segalanya segera seharusnya sehingga seingat sejak sejauh sejenak sejumlah sekadar sekadarnya sekali sekali-kali sekalian sekaligus sekalipun sekarang sekarang sekecil seketika sekiranya sekitar sekitarnya sekurang-kurangnya sekurangnya sela selain selaku selalu selama selama-lamanya selamanya selanjutnya seluruh seluruhnya semacam semakin semampu semampunya semasa semasih semata semata-mata semaunya sementara semisal semisalnya sempat semua semuanya semula sendiri sendirian sendirinya seolah seolah-olah seorang sepanjang sepantasnya sepantasnyalah seperlunya seperti sepertinya sepihak sering seringnya serta serupa sesaat sesama sesampai sesegera sesekali seseorang sesuatu sesuatunya sesudah sesudahnya setelah setempat setengah seterusnya setiap setiba setibanya setidak-tidaknya setidaknya setinggi seusai sewaktu siap siapa siapakah siapapun sini sinilah soal soalnya suatu sudah sudahkah sudahlah supaya tadi tadinya tahu tahun tak tambah tambahnya tampak tampaknya tandas tandasnya tanpa tanya tanyakan tanyanya tapi tegas tegasnya telah tempat tengah tentang tentu tentulah tentunya tepat terakhir terasa terbanyak terdahulu terdapat terdiri terhadap terhadapnya teringat teringat-ingat terjadi terjadilah terjadinya terkira terlalu terlebih terlihat termasuk ternyata tersampaikan tersebut tersebutlah tertentu tertuju terus terutama tetap tetapi tiap tiba tiba-tiba tidak tidakkah tidaklah tiga tinggi toh tunjuk turut tutur tuturnya ucap ucapnya ujar ujarnya umum umumnya ungkap ungkapnya untuk usah usai waduh wah wahai waktu waktunya walau walaupun wong yaitu yakin yakni yang selagi";
		$tes1 = "'ada','adalah','adanya','adapun','agak','agaknya','agar','akan','akankah','akhir','akhiri','akhirnya','aku','akulah','amat','amatlah','anda','andalah','antar','antara','antaranya','apa','apaan','apabila','apakah','apalagi','apatah','artinya','asal','asalkan','atas','atau','ataukah','ataupun','awal','awalnya','bagai','bagaikan','bagaimana','bagaimanakah','bagaimanapun','bagi','bagian','bahkan','bahwa','bahwasanya','baik','bakal','bakalan','balik','banyak','bapak','baru','bawah','beberapa','begini','beginian','beginikah','beginilah','begitu','begitukah','begitulah','begitupun','bekerja','belakang','belakangan','belum','belumlah','benar','benarkah','benarlah','berada','berakhir','berakhirlah','berakhirnya','berapa','berapakah','berapalah','berapapun','berarti','berawal','berbagai','berdatangan','beri','berikan','berikut','berikutnya','berjumlah','berkali-kali','berkata','berkehendak','berkeinginan','berkenaan','berlainan','berlalu','berlangsung','berlebihan','bermacam','bermacam-macam','bermaksud','bermula','bersama','bersama-sama','bersiap','bersiap-siap','bertanya','bertanya-tanya','berturut','berturut-turut','bertutur','berujar','berupa','besar','betul','betulkah','biasa','biasanya','bila','bilakah','bisa','bisakah','boleh','bolehkah','bolehlah','buat','bukan','bukankah','bukanlah','bukannya','bulan','bung','cara','caranya','cukup','cukupkah','cukuplah','cuma','dahulu','dalam','dan','dapat','dari','daripada','datang','dekat','demi','demikian','demikianlah','dengan','depan','di','dia','diakhiri','diakhirinya','dialah','diantara','diantaranya','diberi','diberikan','diberikannya','dibuat','dibuatnya','didapat','didatangkan','digunakan','diibaratkan','diibaratkannya','diingat','diingatkan','diinginkan','dijawab','dijelaskan','dijelaskannya','dikarenakan','dikatakan','dikatakannya','dikerjakan','diketahui','diketahuinya','dikira','dilakukan','dilalui','dilihat','dimaksud','dimaksudkan','dimaksudkannya','dimaksudnya','diminta','dimintai','dimisalkan','dimulai','dimulailah','dimulainya','dimungkinkan','dini','dipastikan','diperbuat','diperbuatnya','dipergunakan','diperkirakan','diperlihatkan','diperlukan','diperlukannya','dipersoalkan','dipertanyakan','dipunyai','diri','dirinya','disampaikan','disebut','disebutkan','disebutkannya','disini','disinilah','ditambahkan','ditandaskan','ditanya','ditanyai','ditanyakan','ditegaskan','ditujukan','ditunjuk','ditunjuki','ditunjukkan','ditunjukkannya','ditunjuknya','dituturkan','dituturkannya','diucapkan','diucapkannya','diungkapkan','dong','dua','dulu','empat','enggak','enggaknya','entah','entahlah','guna','gunakan','hal','hampir','hanya','hanyalah','hari','harus','haruslah','harusnya','hendak','hendaklah','hendaknya','hingga','ia','ialah','ibarat','ibaratkan','ibaratnya','ibu','ikut','ingat','ingat-ingat','ingin','inginkah','inginkan','ini','inikah','inilah','itu','itukah','itulah','jadi','jadilah','jadinya','jangan','jangankan','janganlah','jauh','jawab','jawaban','jawabnya','jelas','jelaskan','jelaslah','jelasnya','jika','jikalau','juga','jumlah','jumlahnya','justru','kala','kalau','kalaulah','kalaupun','kalian','kami','kamilah','kamu','kamulah','kan','kapan','kapankah','kapanpun','karena','karenanya','kasus','kata','katakan','katakanlah','katanya','ke','keadaan','kebetulan','kecil','kedua','keduanya','keinginan','kelamaan','kelihatan','kelihatannya','kelima','keluar','kembali','kemudian','kemungkinan','kemungkinannya','kenapa','kepada','kepadanya','kesampaian','keseluruhan','keseluruhannya','keterlaluan','ketika','khususnya','kini','kinilah','kira','kira-kira','kiranya','kita','kitalah','kok','kurang','lagi','lagian','lah','lain','lainnya','lalu','lama','lamanya','lanjut','lanjutnya','lebih','lewat','lima','luar','macam','maka','makanya','makin','malah','malahan','mampu','mampukah','mana','manakala','manalagi','masa','masalah','masalahnya','masih','masihkah','masing','masing-masing','mau','maupun','melainkan','melakukan','melalui','melihat','melihatnya','memang','memastikan','memberi','memberikan','membuat','memerlukan','memihak','meminta','memintakan','memisalkan','memperbuat','mempergunakan','memperkirakan','memperlihatkan','mempersiapkan','mempersoalkan','mempertanyakan','mempunyai','memulai','memungkinkan','menaiki','menambahkan','menandaskan','menanti','menanti-nanti','menantikan','menanya','menanyai','menanyakan','mendapat','mendapatkan','mendatang','mendatangi','mendatangkan','menegaskan','mengakhiri','mengapa','mengatakan','mengatakannya','mengenai','mengerjakan','mengetahui','menggunakan','menghendaki','mengibaratkan','mengibaratkannya','mengingat','mengingatkan','menginginkan','mengira','mengucapkan','mengucapkannya','mengungkapkan','menjadi','menjawab','menjelaskan','menuju','menunjuk','menunjuki','menunjukkan','menunjuknya','menurut','menuturkan','menyampaikan','menyangkut','menyatakan','menyebutkan','menyeluruh','menyiapkan','merasa','mereka','merekalah','merupakan','meski','meskipun','meyakini','meyakinkan','minta','mirip','misal','misalkan','misalnya','mula','mulai','mulailah','mulanya','mungkin','mungkinkah','nah','naik','namun','nanti','nantinya','nyaris','nyatanya','oleh','olehnya','pada','padahal','padanya','pak','paling','panjang','pantas','para','pasti','pastilah','penting','pentingnya','per','percuma','perlu','perlukah','perlunya','pernah','persoalan','pertama','pertama-tama','pertanyaan','pertanyakan','pihak','pihaknya','pukul','pula','pun','punya','rasa','rasanya','rata','rupanya','saat','saatnya','saja','sajalah','saling','sama','sama-sama','sambil','sampai','sampai-sampai','sampaikan','sana','sangat','sangatlah','satu','saya','sayalah','se','sebab','sebabnya','sebagai','sebagaimana','sebagainya','sebagian','sebaik','sebaik-baiknya','sebaiknya','sebaliknya','sebanyak','sebegini','sebegitu','sebelum','sebelumnya','sebenarnya','seberapa','sebesar','sebetulnya','sebisanya','sebuah','sebut','sebutlah','sebutnya','secara','secukupnya','sedang','sedangkan','sedemikian','sedikit','sedikitnya','seenaknya','segala','segalanya','segera','seharusnya','sehingga','seingat','sejak','sejauh','sejenak','sejumlah','sekadar','sekadarnya','sekali','sekali-kali','sekalian','sekaligus','sekalipun','sekarang','sekarang','sekecil','seketika','sekiranya','sekitar','sekitarnya','sekurang-kurangnya','sekurangnya','sela','selain','selaku','selalu','selama','selama-lamanya','selamanya','selanjutnya','seluruh','seluruhnya','semacam','semakin','semampu','semampunya','semasa','semasih','semata','semata-mata','semaunya','sementara','semisal','semisalnya','sempat','semua','semuanya','semula','sendiri','sendirian','sendirinya','seolah','seolah-olah','seorang','sepanjang','sepantasnya','sepantasnyalah','seperlunya','seperti','sepertinya','sepihak','sering','seringnya','serta','serupa','sesaat','sesama','sesampai','sesegera','sesekali','seseorang','sesuatu','sesuatunya','sesudah','sesudahnya','setelah','setempat','setengah','seterusnya','setiap','setiba','setibanya','setidak-tidaknya','setidaknya','setinggi','seusai','sewaktu','siap','siapa','siapakah','siapapun','sini','sinilah','soal','soalnya','suatu','sudah','sudahkah','sudahlah','supaya','tadi','tadinya','tahu','tahun','tak','tambah','tambahnya','tampak','tampaknya','tandas','tandasnya','tanpa','tanya','tanyakan','tanyanya','tapi','tegas','tegasnya','telah','tempat','tengah','tentang','tentu','tentulah','tentunya','tepat','terakhir','terasa','terbanyak','terdahulu','terdapat','terdiri','terhadap','terhadapnya','teringat','teringat-ingat','terjadi','terjadilah','terjadinya','terkira','terlalu','terlebih','terlihat','termasuk','ternyata','tersampaikan','tersebut','tersebutlah','tertentu','tertuju','terus','terutama','tetap','tetapi','tiap','tiba','tiba-tiba','tidak','tidakkah','tidaklah','tiga','tinggi','toh','tunjuk','turut','tutur','tuturnya','ucap','ucapnya','ujar','ujarnya','umum','umumnya','ungkap','ungkapnya','untuk','usah','usai','waduh','wah','wahai','waktu','waktunya','walau','walaupun','wong','yaitu','yakin','yakni','yang','selagi'";
		$tes2 = "selamat<br/>ulang<br/>guru<br/>smp<br/>it<br/>nur<br/>hidayah<br/>mas<br/>biker";
		$arr = array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'kadang','oke','biar','biarkan','biarlah','entar','sebentar','nanti','cocok','sesuai','aduh','sungguh','bah','wah','wow',
			'ah','eh','waduh','alah','halah','hah','ha','heh','hei','halo','widih','an','woi','hari','ibu','mas','mbak','saudara','saudari',
			'gitu','sih','ya','tidak','salah','jarang','dimana','kemana','mana','nih','barangkali','barangsiapa','nya','si',
			'beliau','ananda','ajudan','pa','ja','pe','ye','ya','pak','yth','situ','ybs','dst','dsb','dll','ternyata','nyata',
			'weh','wah','yoi','cuy','gan','bro','gerangan','konon','katanya','kiranya','sekiranya','agaknya','agak',
			'up','down','on','in','out','with','of','the','jelas','nya','ah','sekali','kali','bong','dong','cenderung',
			'nyadar','sadar','menyadari','merasa','rasa','mentang','bu','oi','woy','woi','amang','jadi','jarang',
			'yah','yeah','ya','terkadang','orang','layak','pantas','kayak','agak','hi','hei','hmm','hmmm','kah','mas',
			'hadeh','aduh','oom','ingat','terlalu','sok','iya','coba','mencoba','xxxxx','kakak','ananda','kecuali',
			'harusnya','harus','seharusnya','sebaiknya','disarankan','direkomendasikan','setidaknya','pikir',
			'tir','nantikan','akhirnya','berharap','harap','mengharapkan','haduh','nam','begitulah','hoi',
			'diiyakan','hadeh','sebelumnya','pe','de','peh','oo','buk','ah','ba','beginilah','begini','udah','udahlah',
			'dah','bi','ng','nc','pgr','begini','beginian','beginilah','loh','dsbt','mam','li','ayolah',
			'emangnya','emang','sejarang','jarang','sebenarnya','dalamnya','wah','ini','inii','ki','mi',
			'sekedar','sedari','sedangkan','sungguh','sesungguhnya','perlu','ji','sedangkan','kok','ang',
			'yuk','yok',
			'al','halo','waduh','aduh','dianya','kamunya','merekanya','kaminya','kitanya','katanya','tok',
			'kapanpun','kapan','ba','ndu','seandainya','mbaknya','masnya','sepertinya','diiyakan','uh','san',
			'wow','waw','tek','kalian','adik','kakak','lho','bli','enggan','tuh','loh','andaikan','andai',
			'tahu','setahu','setahuku','anggap','seprindik','maksud','masa','zaman','kang','bang','mas','apa','sudah','udah','telah',
			'melulu','mang','kurang','baik','dah','masing','noh','nih','kagak','pasti','memang','mudah','gin','gan','agan',
			'wey','setelah','sebelum','dih','maksud','artinya','arti','berarti','yakni','merupakan','ialah','kuy',
			'dalam','selalu','pe','ih','eh','ah','oh','nge','apakah','wi','lok','na','bo','berapa','duluan','duluan',
			'umum','wajar','ku','mu','semenjak','gaes','langsung','itu','ini','loh','ih','mpo','ta','te',
			'siapa','ge','bu','selalu','sampai','tentu','po','mu','al','hal','hi','lh','idih','halah','sebelumnya',
			'nampaknya','sepertinya','kayaknya','pernah','terkadang','kadang','auk','yow','kenapa','mah',
			'hei','hey','ayo','wih','wah','wow','ckne','kan','1','2','3','4','5','6','7','8','9','0',
			'sat','begitu','banyak','tentang','halah','aja','notaben','segala','pokoknya','pokok','begini',
			'ayo','az','kemana','hai','akunya','berarti','wah','segitu','dikit','sih','hanya','anggap',
			'sebagai','mesti','termasuk','jadi','ternyata','banget','bilang','kata','ingin','harap','entar',
			'terus','pantas','yakin','maksudnya','emang','memang','hore','nah','oiya','eh','deh','dikemanakan',
			'aka','olehku','olehnya','oleh','sepenuhnya','terlalu','dalam','ter','ke','me','berikutnya','berikut',
			'entar','banyak','sedikit','sebagian','cuma','begitulah','begitu','apapun','siapapun','kemanapun',
			'bagaimanapun','semestinya','mestinya','mesti','hayo','sana','pastinya','kepastian','pasti',
			'akibat','sebab','menyebabkan','mengakibatkan','hasilnya','dimana','lah','mana','betul',
			'harap','ingin','menjadi','pakai','daripada','sekalian','hah','ih','7h','musti','betapa',
			'halnya','hal','sesuatu','alangkah','ketidak','bagai','ibarat','wa','gih','lae','int','itulah','inilah',
			'biarpun','begitu','begituan','berarti','jik',
			'lho','lah','loh','mustinya','seharusnya','harusnya','harus','anu','awal','awalnya','akhir','akhirnya',
			'sang','banget','kali','ilustrasi','diilustrasikan','bayang','bayangkan','pokoknya','pokok','ah','orang',
			'meliputi','liput','contoh','contohnya','akibat','aja','ayo','silahkan','dipersilahkan','silah','mari',
			'ada','adalah','adanya','adapun','agak','agaknya','agar','akan','akankah','akhir','akhiri','akhirnya',
			'aku','akulah','amat','amatlah','anda','andalah','antar','antara','antaranya','apa','apaan','apabila',
			'apakah','apalagi','apatah','artinya','asal','asalkan','atas','atau','ataukah','ataupun','awal','awalnya',
			'bagai','bagaikan','bagaimana','bagaimanakah','bagaimanapun','bagi','bagian','bahkan','bahwa','bahwasanya',
			'baik','bakal','bakalan','balik','banyak','bapak','baru','bawah','beberapa','begini','beginian','beginikah',
			'beginilah','begitu','begitukah','begitulah','begitupun','bekerja','belakang','belakangan','belum','belumlah',
			'benar','benarkah','benarlah','berada','berakhir','berakhirlah','berakhirnya','berapa','berapakah',
			'berapalah','berapapun','arti','berarti','berawal','berbagai','berdatangan','beri','berikan','berikut',
			'berikutnya','berjumlah','berkali','kali','berkata','berkehendak','berkeinginan','berkenaan','berlainan',
			'berlalu','berlangsung','berlebihan','bermacam','bermacam-macam','bermaksud','bermula','bersama',
			'bersama-sama','siap','bersiap','bersiap-siap','tanya','bertanya','bertanya-tanya','berturut','berturut-turut',
			'bertutur','berujar','berupa','besar','betul','betulkah','biasa','biasanya','bila','bilakah','bisa',
			'bisakah','boleh','bolehkah','bolehlah','buat','bukan','bukankah','bukanlah','bukannya','bulan','bung',
			'cara','caranya','cukup','cukupkah','cukuplah','cuma','dahulu','dalam','dan','dapat','dari','daripada',
			'datang','dekat','demi','demikian','demikianlah','dengan','depan','di','dia','diakhiri','diakhirinya',
			'dialah','diantara','diantaranya','diberi','diberikan','diberikannya','dibuat','dibuatnya','didapat',
			'didatangkan','digunakan','diibaratkan','diibaratkannya','diingat','diingatkan','diinginkan','dijawab',
			'dijelaskan','dijelaskannya','dikarenakan','dikatakan','dikatakannya','dikerjakan','diketahui',
			'diketahuinya','dikira','dilakukan','dilalui','dilihat','dimaksud','dimaksudkan','dimaksudkannya',
			'dimaksudnya','diminta','dimintai','dimisalkan','dimulai','dimulailah','dimulainya','dimungkinkan',
			'dini','dipastikan','diperbuat','diperbuatnya','dipergunakan','diperkirakan','diperlihatkan','diperlukan',
			'diperlukannya','dipersoalkan','dipertanyakan','dipunyai','diri','dirinya','disampaikan','disebut',
			'disebutkan','disebutkannya','disini','disinilah','ditambahkan','ditandaskan','ditanya','ditanyai',
			'ditanyakan','ditegaskan','ditujukan','ditunjuk','ditunjuki','ditunjukkan','ditunjukkannya',
			'ditunjuknya','dituturkan','dituturkannya','diucapkan','diucapkannya','diungkapkan','dong','dua','dulu',
			'empat','enggak','enggaknya','entah','entahlah','guna','gunakan','hal','hampir','hanya','hanyalah','hari',
			'harus','haruslah','harusnya','hendak','hendaklah','hendaknya','hingga','ia','ialah','ibarat','ibaratkan',
			'ibaratnya','ibu','ikut','ingat','ingat-ingat','ingin','inginkah','inginkan','ini','inikah','inilah','itu',
			'itukah','itulah','jadi','jadilah','jadinya','jangan','jangankan','janganlah','jauh','jawab','jawaban',
			'jawabnya','jelas','jelaskan','jelaslah','jelasnya','jika','jikalau','juga','jumlah','jumlahnya','justru',
			'kala','kalau','kalaulah','kalaupun','kalian','kami','kamilah','kau','engkau','kamu','kamulah','kan','kapan','kapankah',
			'kapanpun','karena','karenanya','kasus','kata','katakan','katakanlah','katanya','ke','keadaan','kebetulan',
			'kecil','kedua','keduanya','keinginan','kelamaan','kelihatan','kelihatannya','kelima','keluar','kembali',
			'kemudian','kemungkinan','kemungkinannya','kenapa','kepada','kepadanya','kesampaian','keseluruhan',
			'keseluruhannya','keterlaluan','ketika','khusus','khususnya','kini','kinilah','kira','kira-kira','kiranya',
			'kita','kitalah','kok','kurang','lagi','lagian','lah','lain','lainnya','lalu','lama','lamanya','lanjut',
			'lanjutnya','lebih','lewat','lima','luar','macam','maka','makanya','makin','malah','malahan','mampu',
			'mampukah','mana','manakala','manalagi','masa','masalah','masalahnya','masih','masihkah','masing',
			'masing-masing','mau','maupun','melainkan','melakukan','melalui','melihat','melihatnya','memang',
			'memastikan','memberi','memberikan','membuat','memerlukan','memihak','meminta','memintakan',
			'memisalkan','memperbuat','mempergunakan','memperkirakan','memperlihatkan','mempersiapkan',
			'mempersoalkan','mempertanyakan','mempunyai','memulai','memungkinkan','menaiki','menambahkan',
			'menandaskan','menanti','menanti-nanti','menantikan','menanya','menanyai','menanyakan','mendapat',
			'mendapatkan','mendatang','mendatangi','mendatangkan','menegaskan','mengakhiri','mengapa',
			'mengatakan','mengatakannya','mengenai','mengerjakan','mengetahui','menggunakan','menghendaki',
			'mengibaratkan','mengibaratkannya','mengingat','mengingatkan','menginginkan','mengira','mengucapkan',
			'mengucapkannya','mengungkapkan','menjadi','menjawab','menjelaskan','menuju','menunjuk','menunjuki',
			'menunjukkan','menunjuknya','menurut','menuturkan','menyampaikan','menyangkut','menyatakan',
			'menyebutkan','menyeluruh','menyiapkan','merasa','mereka','merekalah','merupakan','meski',
			'meskipun','meyakini','meyakinkan','minta','mirip','misal','misalkan','misalnya','mula','mulai',
			'mulailah','mulanya','mungkin','mungkinkah','nah','naik','namun','nanti','nantinya','nyaris','pas',
			'nyatanya','oleh','olehnya','pada','padahal','padanya','pak','paling','panjang','pantas','para',
			'pasti','pastilah','penting','pentingnya','per','percuma','perlu','perlukah','perlunya','pernah',
			'persoalan','pertama','pertama-tama','tama','pertanyaan','pertanyakan','pihak','pihaknya','pukul','pula',
			'pun','punya','rasa','rasanya','rata','rupanya','saat','saatnya','saja','sajalah','saling','sama',
			'sama-sama','sambil','sampai','sampai-sampai','sampaikan','sana','sangat','sangatlah','satu','saya',
			'sayalah','se','sebab','sebabnya','sebagai','sebagaimana','sebagainya','sebagian','sebaik',
			'sebaik-baiknya','baiknya','sebaiknya','sebaliknya','sebanyak','sebegini','sebegitu','sebelum','sebelumnya',
			'sebenarnya','seberapa','sebesar','sebetulnya','sebisanya','sebuah','sebut','sebutlah','sebutnya',
			'secara','secukupnya','sedang','sedangkan','sedemikian','sedikit','sedikitnya','seenaknya','segala',
			'segalanya','segera','seharusnya','sehingga','seingat','sejak','sejauh','sejenak','sejumlah',
			'sekadar','sekadarnya','sekali','sekali-kali','sekalian','sekaligus','sekalipun','sekarang',
			'sekarang','sekecil','seketika','sekiranya','sekitar','sekitarnya','sekurang-kurangnya','sekurang','kurangnya',
			'sekurangnya','sela','selain','selaku','selalu','selama','selama-lamanya','selamanya',
			'selanjutnya','seluruh','seluruhnya','semacam','semakin','semampu','semampunya','semasa',
			'semasih','semata','semata-mata','mata','semaunya','sementara','misal','semisal','semisalnya','sempat',
			'semua','semuanya','semula','sendiri','sendirian','sendirinya','seolah','seolah-olah','olah',
			'seorang','sepanjang','sepantasnya','sepantasnyalah','seperlunya','seperti','sepertinya','sepihak',
			'sering','seringnya','serta','serupa','sesaat','sesama','sesampai','sesegera','sesekali','seseorang',
			'sesuatu','sesuatunya','sesudah','sesudahnya','setelah','setempat','setengah','seterusnya','setiap',
			'setiba','setibanya','setidak-tidaknya','setidak','tidaknya','setidaknya','setinggi','seusai','sewaktu','siap','siapa',
			'siapakah','siapapun','sini','sinilah','soal','soalnya','suatu','sudah','sudahkah','sudahlah',
			'supaya','tadi','tadinya','tahu','tahun','tak','tambah','tambahnya','tampak','tampaknya','tandas',
			'tandasnya','tanpa','tanya','tanyakan','tanyanya','tapi','tegas','tegasnya','telah','tempat',
			'tengah','tentang','tentu','tentulah','tentunya','tepat','terakhir','terasa','terbanyak',
			'terdahulu','terdapat','terdiri','terhadap','terhadapnya','teringat','teringat-ingat','terjadi',
			'terjadilah','terjadinya','terkira','terlalu','terlebih','terlihat','termasuk','ternyata',
			'tersampaikan','tersebut','tersebutlah','tertentu','tertuju','terus','terutama','tetap','tetapi',
			'tiap','tiba','tiba-tiba','tidak','tidakkah','tidaklah','tiga','tinggi','toh','tunjuk',
			'turut','tutur','tuturnya','ucap','ucapnya','ujar','ujarnya','umum','umumnya','ungkap',
			'ungkapnya','untuk','usah','usai','waduh','wah','wahai','waktu','waktunya','walau','walaupun',
			'wong','yaitu','yakin','yakni','yang','selagi','segalanya','segala','don','dont','wah',
			'jokowi','anies','baswedan','amy','anis','susi','sus','tino','sidin','intan','ar','vic',
			'tan','malaka','soekarno','hatta','ahmad','muhammad','re','tabeyuya','mataram','olive',
			'prabowo',
			'deng','xiaoping','danar','benarnya','benar','sebenarnya','persis','persisnya',
			'zaitun','seterusnya','ranchmarket','nat','waze','itin','tin','croissant',
			'quora','facebook','twiter','youtube','whatsap','wa','ig','fb','tw','yt','gmail','google',
			'kaskus','line','yamaha','honda','kfc','mendikbud',
			'zaman','jaman','zamannya','jamannya','waktu','waktunya','masa','masanya',
			'basis','berbasis','webview','plumpang','tanjung','priok',
			'tintin','enid','blyton','ande','lumut','darek','yacht','cocktails','wine',
			'hazelnut','poppy','gugel','codeine','opium','papaver','somniferum','opium',
			'marijuana','hemp','staple','gandum','cbd','vice','versa','nasrudin','keliru',
			'cocok','sesuai','sepadan','uljas','cinthya','anya','083','vwo','havo','vmbo','000',
			'rp','rupiah','bundesheer','gipsy','670','360','was','were','is','are','am','my','baby',
			'das','europ','ische','klaten','alias','sebut','disebut','klatennya','paramadina','gwk',
			'dong','sussex','newcastle','was','st','james','park','alan','shearer','dkk','hooligans',
			'united','toon','arm','gouda','kastengel','kastengelnya','mozzarella','hummus',
			'syelly','tuhumury','rangorang','kalio','alhasil','hasil','hasilnya','menghasilkan','cc',
			'lumayan','ami','nyetoknya','camembert','brie','light','khauda','gouda',
			'sandwich','burger','rana','building','dhaka','bangladesh','istambul','troia','portugal',
			'faro','albufeira','bumi','earth','perbah','bareng','tangerang','711','cenderung','kecenderungan',
			'll','||','|','secepatnya','lieur','ning','nung','lyd','there','will','riz',
			'cocktails','cocktail','riz','ris','rij','rich','errr','dit','david','bobby','hack','aqq',
			'turki','greek','yunani','alana','sasmita','bot','acc','danke','persia','shahrazad',
			'taurus','aldebaran','saint','seiya','sarasvati','ay','maroko','perancis',
			'aise','ayisha','gie','gi','hokkian','fujian','slavic','alina','gianlugi','ijk','scherazade',
			'faustina','sihi','kobe','bryant','us','seattle',
			'jakarta','bandung','makassar','solo','manchester','cattleya','slipi','honda','tebet',
			'padang','sidoarjo','abirama','hollywood','niscaya','tris','revi','ampat',
			'indonesia','inggris','jerman','belanda','afganistan','india','pakistan','jepang','singapur',
			'singapura','malaysia','thailand','filipina','laos','kamboja','vietnam','italia','spanyol',
			'jerman','estonia','iran','irak','austria','doha','qatar','abu','dhabi',
			'asean','huh','segini','gin','kesemek','salad','krampus','croissant',
			'london','southall','malawi','banyuwangi','jember','surabaya','padang','sinabung','amsterdam',
			'bland','haggis','pad','man','newton','donut','donat','sanggup',
			'dre','ted','syel','syam','harnessed','wind','am','by','way','netflix','as',
			'0','00','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25',
			'26','27','28','29','30','31','32','33','35','36','37','38','39','40','41','42',
			'43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58',
			'59','60','61','62','63','64','65','66','67','68','69','70','71','72','73','74',
			'75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90',
			'91','92','93','94','95','96','97','98','99','100','200','250','409','500','550',
			'500','1000','10000',
			'2000','2001','2002','2003','2004','2005','2006','2007','2008','2010','2011','2012',
			'2013','2014','2015','2016','2017','2018','2019','2020','2021','2022','365','366',
			'januari','februari','maret','april','mei','juni','juli','agustus','september',
			'november','desember','detik','second','sekon','menit','jam','hari','minggu','bulan',
			'tahun','windu','dasawarsa','abad','r1','may','his','her','anyway','xd',
			'wilayah','kota','kabupaten','ibukota','tempat','daerah','kecamatan','desa','rt','rw','kelurahan',
			'kilo','centi','centimeter','meter','kilometer','hektar','tanggal','mb','kb','giga','byte',
			'tril','cat','walk','nan','gary','vaynerchuck','woelah','gobind','vadesh','reza','gunawan','adjie', 
			'santoso','irma','rahayu','etc','ami','yosep','vodca','wiski','rum','tequila','stuff',
			'eropa','asia','amerika','afrika','austalia','1k','tbk','karibia','ala','vegan','ristekdikti',
			'catwalknya','ethno','carinival','festival','parade','masa','masanya','underground','crossing',
			'laura','cl','bcl','dynand','fariz','jfc','banggat','as','chicago','jjs','emos','belianda','vakantiegeld',
			'belasan','puluh','ratus','ribu','juta','milyar','trilyun','tldr','zidane','del','piero',
			'veron','batistuta','buffon','frey','pagliuca','peruzzi','toldo','abbiati',
			'zanetti','zambrotta','inzaghi','maldini','thuram','nesta','cannavaro','nesta','trezegol','ronaldo',
			'syahrini','apalagi','vc','riweuh','ecek','annisa','rainy','abidin','rahmahdi','kah',
			'cents','two','sar','fujinomiya','damavand','mountaineering','kinabalu','yusha',
			'fuji','nyess','fi','are','most','fellow','my','villains','skynet','markus','rbf',
			'george','washington','benjamin','franklin','tiongkok','yoga','washington','dollar',
			'islam','budha','kristen','katolik','hindhu','protestan','soegijapranata','itupun',
			'senin','selasa','rabu','kamis','jumat','sabtu','minggu','fyi','djarot','whatsapp',
			'bbc','cnn','detik','kompas','kaskus','re','huawei','menkes','bri','tertentu','tentu',
			'hid','gar','perancis','belgium','belgia','canada','kanada','quebec','kevin','sanjaya',
			'sangiang','ujung','kulon','anyer','malang','yogyakart','nelson','mandela',
			'jokowiisme','prabowoisme','kurniawan','nutmeg','hairy','oliver','liao',
			'dismemberment','ebervald','chinaisasi','konsekuensi','darimana','fullcream','tetrapack',
			'dahlan','iskan','chairul','tanjung','tlkm','salim','oot','ct','cinthya',
			'william','tanuwijaya','tokopedia','bukalapak','co','uefa','manchester','city','united',
			'psg','barcelona','real','madrid','liverpool','roxettenya','sky','sport','and','the','athletic','uk',
			'queen','rain','band','roxette','samyang','dower','gledek',
			'jawa','java','kalimantan','sumatera','sulawesi','papua','bantul','parangtritis','bantul',
			'satu','satunya','dua','duanya','ish','wee','ooi','oei','oey','ung','ongko','ocean',
			'pertama','kesatu','kedua','keduanya','tiga','ketiganya','tadi','hokkian','ngadiman',
			'britons','johnson','hong','kong','boris','bor','brussel','britania','eu','gank',
			'kilogram','ons','m3','matchingin','has','classy','nerds','berj','buzz','lightyear',
			'tea','connoiseur','lehrling','doddy','prayogo','finite','element','sjr','q1','400','niche',
			'puluhan','ratusan','ribuan','jutaan','milyaran','trilyunan','disertakan','serta',
			'fundraising','borough','palate','canary','plain','camden','kentish','town','vienna',
			'cabeny','busaba','asterix','tualang','opor','non','navier','stokes','omar','malik','well',
			'ibu','ayah','perempuan','pria','wanita','laki','reddy','aprianto','koh','ko',
			'odong','latin','carousel','fish','chip','kebab','pepsodent','dunkin','donut','gus','dur',
			'europass','samosir','dre','iljas','riz','masala','syam','nanung','theresia','golden',
			'inchi','ebony','anw','wah','seringkali','forest','gump','meter','menteng','prada'
		);

		$uji = "'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
		'kadang','oke','biar','biarkan','biarlah','entar','sebentar','nanti','cocok','sesuai','aduh','sungguh','bah','wah','wow',
		'ah','eh','waduh','alah','halah','hah','ha','heh','hei','halo','widih','an','woi','hari','ibu','mas','mbak','saudara','saudari',
		'gitu','sih','ya','tidak','salah','jarang','dimana','kemana','mana','nih','barangkali','barangsiapa','nya','si',
		'beliau','ananda','ajudan','pa','ja','pe','ye','ya','pak','yth','situ','ybs','dst','dsb','dll','ternyata','nyata',
		'weh','wah','yoi','cuy','gan','bro','gerangan','konon','katanya','kiranya','sekiranya','agaknya','agak',
		'up','down','on','in','out','with','of','the','jelas','nya','ah','sekali','kali','bong','dong','cenderung',
		'nyadar','sadar','menyadari','merasa','rasa','mentang','bu','oi','woy','woi','amang','jadi','jarang',
		'yah','yeah','ya','terkadang','orang','layak','pantas','kayak','agak','hi','hei','hmm','hmmm','kah','mas',
		'hadeh','aduh','oom','ingat','terlalu','sok','iya','coba','mencoba','xxxxx','kakak','ananda','kecuali',
		'harusnya','harus','seharusnya','sebaiknya','disarankan','direkomendasikan','setidaknya','pikir',
		'tir','nantikan','akhirnya','berharap','harap','mengharapkan','haduh','nam','begitulah','hoi',
		'diiyakan','hadeh','sebelumnya','pe','de','peh','oo','buk','ah','ba','beginilah','begini','udah','udahlah',
		'dah','bi','ng','nc','pgr','begini','beginian','beginilah','loh','dsbt','mam','li','ayolah',
		'emangnya','emang','sejarang','jarang','sebenarnya','dalamnya','wah','ini','inii','ki','mi',
		'sekedar','sedari','sedangkan','sungguh','sesungguhnya','perlu','ji','sedangkan','kok','ang',
		'yuk','yok',
		'al','halo','waduh','aduh','dianya','kamunya','merekanya','kaminya','kitanya','katanya','tok',
		'kapanpun','kapan','ba','ndu','seandainya','mbaknya','masnya','sepertinya','diiyakan','uh','san',
		'wow','waw','tek','kalian','adik','kakak','lho','bli','enggan','tuh','loh','andaikan','andai',
		'tahu','setahu','setahuku','anggap','seprindik','maksud','masa','zaman','kang','bang','mas','apa','sudah','udah','telah',
		'melulu','mang','kurang','baik','dah','masing','noh','nih','kagak','pasti','memang','mudah','gin','gan','agan',
		'wey','setelah','sebelum','dih','maksud','artinya','arti','berarti','yakni','merupakan','ialah','kuy',
		'dalam','selalu','pe','ih','eh','ah','oh','nge','apakah','wi','lok','na','bo','berapa','duluan','duluan',
		'umum','wajar','ku','mu','semenjak','gaes','langsung','itu','ini','loh','ih','mpo','ta','te',
		'siapa','ge','bu','selalu','sampai','tentu','po','mu','al','hal','hi','lh','idih','halah','sebelumnya',
		'nampaknya','sepertinya','kayaknya','pernah','terkadang','kadang','auk','yow','kenapa','mah',
		'hei','hey','ayo','wih','wah','wow','ckne','kan','1','2','3','4','5','6','7','8','9','0',
		'sat','begitu','banyak','tentang','halah','aja','notaben','segala','pokoknya','pokok','begini',
		'ayo','az','kemana','hai','akunya','berarti','wah','segitu','dikit','sih','hanya','anggap',
		'sebagai','mesti','termasuk','jadi','ternyata','banget','bilang','kata','ingin','harap','entar',
		'terus','pantas','yakin','maksudnya','emang','memang','hore','nah','oiya','eh','deh','dikemanakan',
		'aka','olehku','olehnya','oleh','sepenuhnya','terlalu','dalam','ter','ke','me','berikutnya','berikut',
		'entar','banyak','sedikit','sebagian','cuma','begitulah','begitu','apapun','siapapun','kemanapun',
		'bagaimanapun','semestinya','mestinya','mesti','hayo','sana','pastinya','kepastian','pasti',
		'akibat','sebab','menyebabkan','mengakibatkan','hasilnya','dimana','lah','mana','betul',
		'harap','ingin','menjadi','pakai','daripada','sekalian','hah','ih','7h','musti','betapa',
		'halnya','hal','sesuatu','alangkah','ketidak','bagai','ibarat','wa','gih','lae','int','itulah','inilah',
		'biarpun','begitu','begituan','berarti','jik',
		'lho','lah','loh','mustinya','seharusnya','harusnya','harus','anu','awal','awalnya','akhir','akhirnya',
		'sang','banget','kali','ilustrasi','diilustrasikan','bayang','bayangkan','pokoknya','pokok','ah','orang',
		'meliputi','liput','contoh','contohnya','akibat','aja','ayo','silahkan','dipersilahkan','silah','mari',
		'ada','adalah','adanya','adapun','agak','agaknya','agar','akan','akankah','akhir','akhiri','akhirnya',
		'aku','akulah','amat','amatlah','anda','andalah','antar','antara','antaranya','apa','apaan','apabila',
		'apakah','apalagi','apatah','artinya','asal','asalkan','atas','atau','ataukah','ataupun','awal','awalnya',
		'bagai','bagaikan','bagaimana','bagaimanakah','bagaimanapun','bagi','bagian','bahkan','bahwa','bahwasanya',
		'baik','bakal','bakalan','balik','banyak','bapak','baru','bawah','beberapa','begini','beginian','beginikah',
		'beginilah','begitu','begitukah','begitulah','begitupun','bekerja','belakang','belakangan','belum','belumlah',
		'benar','benarkah','benarlah','berada','berakhir','berakhirlah','berakhirnya','berapa','berapakah',
		'berapalah','berapapun','arti','berarti','berawal','berbagai','berdatangan','beri','berikan','berikut',
		'berikutnya','berjumlah','berkali','kali','berkata','berkehendak','berkeinginan','berkenaan','berlainan',
		'berlalu','berlangsung','berlebihan','bermacam','bermacam-macam','bermaksud','bermula','bersama',
		'bersama-sama','siap','bersiap','bersiap-siap','tanya','bertanya','bertanya-tanya','berturut','berturut-turut',
		'bertutur','berujar','berupa','besar','betul','betulkah','biasa','biasanya','bila','bilakah','bisa',
		'bisakah','boleh','bolehkah','bolehlah','buat','bukan','bukankah','bukanlah','bukannya','bulan','bung',
		'cara','caranya','cukup','cukupkah','cukuplah','cuma','dahulu','dalam','dan','dapat','dari','daripada',
		'datang','dekat','demi','demikian','demikianlah','dengan','depan','di','dia','diakhiri','diakhirinya',
		'dialah','diantara','diantaranya','diberi','diberikan','diberikannya','dibuat','dibuatnya','didapat',
		'didatangkan','digunakan','diibaratkan','diibaratkannya','diingat','diingatkan','diinginkan','dijawab',
		'dijelaskan','dijelaskannya','dikarenakan','dikatakan','dikatakannya','dikerjakan','diketahui',
		'diketahuinya','dikira','dilakukan','dilalui','dilihat','dimaksud','dimaksudkan','dimaksudkannya',
		'dimaksudnya','diminta','dimintai','dimisalkan','dimulai','dimulailah','dimulainya','dimungkinkan',
		'dini','dipastikan','diperbuat','diperbuatnya','dipergunakan','diperkirakan','diperlihatkan','diperlukan',
		'diperlukannya','dipersoalkan','dipertanyakan','dipunyai','diri','dirinya','disampaikan','disebut',
		'disebutkan','disebutkannya','disini','disinilah','ditambahkan','ditandaskan','ditanya','ditanyai',
		'ditanyakan','ditegaskan','ditujukan','ditunjuk','ditunjuki','ditunjukkan','ditunjukkannya',
		'ditunjuknya','dituturkan','dituturkannya','diucapkan','diucapkannya','diungkapkan','dong','dua','dulu',
		'empat','enggak','enggaknya','entah','entahlah','guna','gunakan','hal','hampir','hanya','hanyalah','hari',
		'harus','haruslah','harusnya','hendak','hendaklah','hendaknya','hingga','ia','ialah','ibarat','ibaratkan',
		'ibaratnya','ibu','ikut','ingat','ingat-ingat','ingin','inginkah','inginkan','ini','inikah','inilah','itu',
		'itukah','itulah','jadi','jadilah','jadinya','jangan','jangankan','janganlah','jauh','jawab','jawaban',
		'jawabnya','jelas','jelaskan','jelaslah','jelasnya','jika','jikalau','juga','jumlah','jumlahnya','justru',
		'kala','kalau','kalaulah','kalaupun','kalian','kami','kamilah','kau','engkau','kamu','kamulah','kan','kapan','kapankah',
		'kapanpun','karena','karenanya','kasus','kata','katakan','katakanlah','katanya','ke','keadaan','kebetulan',
		'kecil','kedua','keduanya','keinginan','kelamaan','kelihatan','kelihatannya','kelima','keluar','kembali',
		'kemudian','kemungkinan','kemungkinannya','kenapa','kepada','kepadanya','kesampaian','keseluruhan',
		'keseluruhannya','keterlaluan','ketika','khusus','khususnya','kini','kinilah','kira','kira-kira','kiranya',
		'kita','kitalah','kok','kurang','lagi','lagian','lah','lain','lainnya','lalu','lama','lamanya','lanjut',
		'lanjutnya','lebih','lewat','lima','luar','macam','maka','makanya','makin','malah','malahan','mampu',
		'mampukah','mana','manakala','manalagi','masa','masalah','masalahnya','masih','masihkah','masing',
		'masing-masing','mau','maupun','melainkan','melakukan','melalui','melihat','melihatnya','memang',
		'memastikan','memberi','memberikan','membuat','memerlukan','memihak','meminta','memintakan',
		'memisalkan','memperbuat','mempergunakan','memperkirakan','memperlihatkan','mempersiapkan',
		'mempersoalkan','mempertanyakan','mempunyai','memulai','memungkinkan','menaiki','menambahkan',
		'menandaskan','menanti','menanti-nanti','menantikan','menanya','menanyai','menanyakan','mendapat',
		'mendapatkan','mendatang','mendatangi','mendatangkan','menegaskan','mengakhiri','mengapa',
		'mengatakan','mengatakannya','mengenai','mengerjakan','mengetahui','menggunakan','menghendaki',
		'mengibaratkan','mengibaratkannya','mengingat','mengingatkan','menginginkan','mengira','mengucapkan',
		'mengucapkannya','mengungkapkan','menjadi','menjawab','menjelaskan','menuju','menunjuk','menunjuki',
		'menunjukkan','menunjuknya','menurut','menuturkan','menyampaikan','menyangkut','menyatakan',
		'menyebutkan','menyeluruh','menyiapkan','merasa','mereka','merekalah','merupakan','meski',
		'meskipun','meyakini','meyakinkan','minta','mirip','misal','misalkan','misalnya','mula','mulai',
		'mulailah','mulanya','mungkin','mungkinkah','nah','naik','namun','nanti','nantinya','nyaris','pas',
		'nyatanya','oleh','olehnya','pada','padahal','padanya','pak','paling','panjang','pantas','para',
		'pasti','pastilah','penting','pentingnya','per','percuma','perlu','perlukah','perlunya','pernah',
		'persoalan','pertama','pertama-tama','tama','pertanyaan','pertanyakan','pihak','pihaknya','pukul','pula',
		'pun','punya','rasa','rasanya','rata','rupanya','saat','saatnya','saja','sajalah','saling','sama',
		'sama-sama','sambil','sampai','sampai-sampai','sampaikan','sana','sangat','sangatlah','satu','saya',
		'sayalah','se','sebab','sebabnya','sebagai','sebagaimana','sebagainya','sebagian','sebaik',
		'sebaik-baiknya','baiknya','sebaiknya','sebaliknya','sebanyak','sebegini','sebegitu','sebelum','sebelumnya',
		'sebenarnya','seberapa','sebesar','sebetulnya','sebisanya','sebuah','sebut','sebutlah','sebutnya',
		'secara','secukupnya','sedang','sedangkan','sedemikian','sedikit','sedikitnya','seenaknya','segala',
		'segalanya','segera','seharusnya','sehingga','seingat','sejak','sejauh','sejenak','sejumlah',
		'sekadar','sekadarnya','sekali','sekali-kali','sekalian','sekaligus','sekalipun','sekarang',
		'sekarang','sekecil','seketika','sekiranya','sekitar','sekitarnya','sekurang-kurangnya','sekurang','kurangnya',
		'sekurangnya','sela','selain','selaku','selalu','selama','selama-lamanya','selamanya',
		'selanjutnya','seluruh','seluruhnya','semacam','semakin','semampu','semampunya','semasa',
		'semasih','semata','semata-mata','mata','semaunya','sementara','misal','semisal','semisalnya','sempat',
		'semua','semuanya','semula','sendiri','sendirian','sendirinya','seolah','seolah-olah','olah',
		'seorang','sepanjang','sepantasnya','sepantasnyalah','seperlunya','seperti','sepertinya','sepihak',
		'sering','seringnya','serta','serupa','sesaat','sesama','sesampai','sesegera','sesekali','seseorang',
		'sesuatu','sesuatunya','sesudah','sesudahnya','setelah','setempat','setengah','seterusnya','setiap',
		'setiba','setibanya','setidak-tidaknya','setidak','tidaknya','setidaknya','setinggi','seusai','sewaktu','siap','siapa',
		'siapakah','siapapun','sini','sinilah','soal','soalnya','suatu','sudah','sudahkah','sudahlah',
		'supaya','tadi','tadinya','tahu','tahun','tak','tambah','tambahnya','tampak','tampaknya','tandas',
		'tandasnya','tanpa','tanya','tanyakan','tanyanya','tapi','tegas','tegasnya','telah','tempat',
		'tengah','tentang','tentu','tentulah','tentunya','tepat','terakhir','terasa','terbanyak',
		'terdahulu','terdapat','terdiri','terhadap','terhadapnya','teringat','teringat-ingat','terjadi',
		'terjadilah','terjadinya','terkira','terlalu','terlebih','terlihat','termasuk','ternyata',
		'tersampaikan','tersebut','tersebutlah','tertentu','tertuju','terus','terutama','tetap','tetapi',
		'tiap','tiba','tiba-tiba','tidak','tidakkah','tidaklah','tiga','tinggi','toh','tunjuk',
		'turut','tutur','tuturnya','ucap','ucapnya','ujar','ujarnya','umum','umumnya','ungkap',
		'ungkapnya','untuk','usah','usai','waduh','wah','wahai','waktu','waktunya','walau','walaupun',
		'wong','yaitu','yakin','yakni','yang','selagi','segalanya','segala','don','dont','wah',
		'jokowi','anies','baswedan','amy','anis','susi','sus','tino','sidin','intan','ar','vic',
		'tan','malaka','soekarno','hatta','ahmad','muhammad','re','tabeyuya','mataram','olive',
		'prabowo','susi','edi','ganjar','pramono','ahok','eric','thohir',
		'deng','xiaoping','danar','benarnya','benar','sebenarnya','persis','persisnya',
		'zaitun','seterusnya','ranchmarket','nat','waze','itin','tin','croissant',
		'quora','facebook','twiter','youtube','whatsap','wa','ig','fb','tw','yt','gmail','google',
		'kaskus','line','yamaha','honda','kfc','mendikbud',
		'zaman','jaman','zamannya','jamannya','waktu','waktunya','masa','masanya',
		'basis','berbasis','webview','plumpang','tanjung','priok',
		'tintin','enid','blyton','ande','lumut','darek','yacht','cocktails','wine',
		'hazelnut','poppy','gugel','codeine','opium','papaver','somniferum','opium',
		'marijuana','hemp','staple','gandum','cbd','vice','versa','nasrudin','keliru',
		'cocok','sesuai','sepadan','uljas','cinthya','anya','083','vwo','havo','vmbo','000',
		'rp','rupiah','bundesheer','gipsy','670','360','was','were','is','are','am','my','baby',
		'das','europ','ische','klaten','alias','sebut','disebut','klatennya','paramadina','gwk',
		'dong','sussex','newcastle','was','st','james','park','alan','shearer','dkk','hooligans',
		'united','toon','arm','gouda','kastengel','kastengelnya','mozzarella','hummus',
		'syelly','tuhumury','rangorang','kalio','alhasil','hasil','hasilnya','menghasilkan','cc',
		'lumayan','ami','nyetoknya','camembert','brie','light','khauda','gouda',
		'sandwich','burger','rana','building','dhaka','bangladesh','istambul','troia','portugal',
		'faro','albufeira','bumi','earth','perbah','bareng','tangerang','711','cenderung','kecenderungan',
		'll','||','|','secepatnya','lieur','ning','nung','lyd','there','will','riz',
		'cocktails','cocktail','riz','ris','rij','rich','errr','dit','david','bobby','hack','aqq',
		'turki','greek','yunani','alana','sasmita','bot','acc','danke','persia','shahrazad',
		'taurus','aldebaran','saint','seiya','sarasvati','ay','maroko','perancis',
		'aise','ayisha','gie','gi','hokkian','fujian','slavic','alina','gianlugi','ijk','scherazade',
		'faustina','sihi','kobe','bryant','us','seattle',
		'jakarta','bandung','makassar','solo','manchester','cattleya','slipi','honda','tebet',
		'padang','sidoarjo','abirama','hollywood','niscaya','tris','revi','ampat',
		'indonesia','inggris','jerman','belanda','afganistan','india','pakistan','jepang','singapur',
		'singapura','malaysia','thailand','filipina','laos','kamboja','vietnam','italia','spanyol',
		'jerman','estonia','iran','irak','austria','doha','qatar','abu','dhabi',
		'asean','huh','segini','gin','kesemek','salad','krampus','croissant',
		'london','southall','malawi','banyuwangi','jember','surabaya','padang','sinabung','amsterdam',
		'bland','haggis','pad','man','newton','donut','donat','sanggup',
		'dre','ted','syel','syam','harnessed','wind','am','by','way','netflix','as',
		'dutch','pond','ivan','lanin','twitter','pon','onze','imperial','ounce','gram ',
		'lb','ounces','lbs','lb','libra','poundsterling','livresterling','regginang',
		'0','00','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25',
		'26','27','28','29','30','31','32','33','35','36','37','38','39','40','41','42',
		'43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58',
		'59','60','61','62','63','64','65','66','67','68','69','70','71','72','73','74',
		'75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90',
		'91','92','93','94','95','96','97','98','99','100','200','250','409','500','550',
		'500','1000','10000','453','34','misbakhul','munir','english','ywda','ceo','misbakhul ',
		'snail','soothing','gel','sedang','okelah','oke','asgard','thor','brian','mq',
		'oso','brooke','adakah','zam','ryu','neorosains','heru','purwanto','xxx',
		'muhammad','ibnul','wahhab','150','kemenpora','pssi','fifa','worldcup','kuwait',
		'ali','jaber','pekanbaru','mandala','krida','manahan','jalak','harupat',
		'veronica','koeman','bakar','baasyir','mercedes','benz','bmw','pubg','hd',
		'drive','thru','ice','cone','mcd','anggrayudi','hardiannicko','idk','naruto','yahoo',
		'rengginang','joshua','oppenheimer','pernahkah','benarkah','dapatkah','maulwi','saelan',
		'jateng','jatim','jabar','sumut','bogor','taiwan','madiun','ternary','sumbar',
		'bilamana','eky','aloe','vera','caregiver','hairi','jasmine','jasindo',
		'ppl','majalengka','progo','karawang','widodo','pplp','bpjs','psa','sgs',
		'satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan','sepuluh',
		'1945','1949','1950','1956','1863','1966','201','1970','109','1995',
		'marshmallow','tiger','maeshmallow','at','idi','rrt','beby','does',
		'musa','isa','yakub','ibrahim','nuh','yunus','fir','aun','ibnu','abas','adam','ka',
		'mirae','panin','maybank','ksei','mtdl','metrodata','shu','pt','lapkeu','electronics',
		'semarang','samarinda','smart','fm','terboyo','jatibarang','brian','stevianus',
		'wonodri','sukawi','sutarip','soemarmo','soeharto','gibbon','tumaritis','series',
		'mohammad','kanedi','dsj','sanalah','umar','bin','khattab','kinky','saudi','arabia',
		'mariah','carey','cent','bts','salman','adi','hidayat','sauang','collin',
		'masrur','harun','amri','ci','sri','hs','mandarin','duya','voltaire','rus',
		'bimo','amri','mirfaqo','kiwari','ahmad','makki','charles','darwin','carl','sagan','sigmund','freud',
		'4199','1966','4200','echo','chamber','asnawi','breakdown','yme',
		'zulfi','bimo','bramantyo','osn','imo','lisa','maryam','lovelace',
		'i','ii','iii','iv','v','vi','vii','viii','ix','x','xi','xii','xiii','xiv','xv','xvi',
		'muhammadiyah','nu','nissa','sabyan','bassist','last','gigs','pete','townshend',
		'gareng','petruk','wicak','anggoro','yaman','saw','swt','fatimiyah','ayubiyah',
		'mesir','uni','emirat','arab','uea','azhar','turky','madinah','mekah','suriah','mekkah',
		'2000','2001','2002','2003','2004','2005','2006','2007','2008','2010','2011','2012',
		'2013','2014','2015','2016','2017','2018','2019','2020','2021','2022','365','366','2030',
		'januari','februari','maret','april','mei','juni','juli','agustus','september',
		'november','desember','detik','second','sekon','menit','jam','hari','minggu','bulan',
		'tahun','windu','dasawarsa','abad','r1','may','his','her','anyway','xd',
		'wilayah','kota','kabupaten','ibukota','tempat','daerah','kecamatan','desa','rt','rw','kelurahan',
		'kilo','centi','centimeter','meter','kilometer','hektar','tanggal','mb','kb','giga','byte',
		'tril','cat','walk','nan','gary','vaynerchuck','woelah','gobind','vadesh','reza','gunawan','adjie', 
		'santoso','irma','rahayu','etc','ami','yosep','vodca','wiski','rum','tequila','stuff',
		'eropa','asia','amerika','afrika','austalia','1k','tbk','karibia','ala','vegan','ristekdikti',
		'catwalknya','ethno','carinival','festival','parade','masa','masanya','underground','crossing',
		'laura','cl','bcl','dynand','fariz','jfc','banggat','as','chicago','jjs','emos','belianda','vakantiegeld',
		'belasan','puluh','ratus','ribu','juta','milyar','trilyun','tldr','zidane','del','piero',
		'veron','batistuta','buffon','frey','pagliuca','peruzzi','toldo','abbiati',
		'zanetti','zambrotta','inzaghi','maldini','thuram','nesta','cannavaro','nesta','trezegol','ronaldo',
		'syahrini','apalagi','vc','riweuh','ecek','annisa','rainy','abidin','rahmahdi','kah',
		'cents','two','sar','fujinomiya','damavand','mountaineering','kinabalu','yusha',
		'fuji','nyess','fi','are','most','fellow','my','villains','skynet','markus','rbf',
		'george','washington','benjamin','franklin','tiongkok','yoga','washington','dollar',
		'islam','budha','kristen','katolik','hindhu','protestan','konghucu',
		'soegijapranata','itupun','teng','lang','lsl',
		'senin','selasa','rabu','kamis','jumat','sabtu','minggu','fyi','djarot','whatsapp',
		'bbc','cnn','detik','kompas','kaskus','re','huawei','menkes','bri','tertentu','tentu',
		'hid','gar','perancis','belgium','belgia','canada','kanada','quebec','kevin','sanjaya',
		'sangiang','ujung','kulon','anyer','malang','yogyakart','nelson','mandela',
		'jokowiisme','prabowoisme','kurniawan','nutmeg','hairy','oliver','liao',
		'dismemberment','ebervald','chinaisasi','konsekuensi','darimana','fullcream','tetrapack',
		'dahlan','iskan','chairul','tanjung','tlkm','salim','oot','ct','cinthya',
		'william','tanuwijaya','tokopedia','bukalapak','co','uefa','manchester','city','united',
		'psg','barcelona','real','madrid','liverpool','roxettenya','sky','sport','and','the','athletic','uk',
		'queen','rain','band','roxette','samyang','dower','gledek',
		'jawa','java','kalimantan','sumatera','sulawesi','papua','bantul','parangtritis','bantul',
		'satu','satunya','dua','duanya','ish','wee','ooi','oei','oey','ung','ongko','ocean',
		'pertama','kesatu','kedua','keduanya','tiga','ketiganya','tadi','hokkian','ngadiman',
		'britons','johnson','hong','kong','boris','bor','brussel','britania','eu','gank',
		'kilogram','ons','m3','matchingin','has','classy','nerds','berj','buzz','lightyear',
		'tea','connoiseur','lehrling','doddy','prayogo','finite','element','sjr','q1','400','niche',
		'puluhan','ratusan','ribuan','jutaan','milyaran','trilyunan','disertakan','serta',
		'fundraising','borough','palate','canary','plain','camden','kentish','town','vienna',
		'cabeny','busaba','asterix','tualang','opor','non','navier','stokes','omar','malik','well',
		'ibu','ayah','perempuan','pria','wanita','laki','reddy','aprianto','koh','ko',
		'odong','latin','carousel','fish','chip','kebab','pepsodent','dunkin','donut','gus','dur',
		'europass','samosir','dre','iljas','riz','masala','syam','nanung','theresia','golden',
		'inchi','ebony','anw','wah','seringkali','forest','gump','meter','menteng','prada',
		
		'baseball','stick','midwest','tikka','lada','hainam','pounds','chips','inch','edge',
		'steel','stainless','lumpia','twist','deadline','yogurt','turbin','protagoras','stempel',
		'papuma','adipura','tril','wiken','influencer','tropis','cricket','wuah','magnum',
		'rewind','pause','pengin','hanni','tisu','kraken','platinum','ketes','bong','sendok',
		'takambang','tangkuban','malinkundang','benchmark','catleya','malls','mall',
		'suwir','krecek','rm','gudeg','nangka','pager','gagang','keyboard',
		'vulkanik','radiu','erupsi','kapur','dsri','geprek','liter','tangki',
		'launcher','bundle','lagi','platform','unlimited','scroll','feedback','spoiler',
		'buffer','kuota','compressing','mb','reksadana','software','property',
		'anwar','chairil','seraya','planter','ngegiting','ganja','narkotika',
		'zat','morfin','painter','pranala','wamil','suit','pencil','menial','warkop',
		'zigeuner','roman','marathon','boots','stoic','melati','bazaar','blackhole',
		'gps','keder','bonek','jedung','jedang','feta','mozarella','rasanta','junkfood',
		'ami','dslr','mirorless','kangkung','oseng','bangus','pasa','istanbul','600',
		'risky','300','arcade','lounge','dingdong','retro','ngehi','tensed',
		'decks','gagu','umpama','rana','lemper','cireng','tortilla','ritter','powerbank',
		'pedometer','kala','prabu','sana','namagakribet','igi','gian','gianluigi','ljk',
		'gabriel','sha','han','mungkis','fatty','sarah','diaspora','pertama','cola','coca',
		'gat','stuck','kiyut','liner','voa','extention','marina','parttime','summer',
		'inlander','brexit','tetelan','tab','matura','startup','sebelas','biomedis',
		'sitasi','mensitasi','kimia','rijanta','itb','djoko','santosa','dikti',
		'watchtime','cpc','cpm','tra','mal','mall','malls','triana','ground',
		'playmaker','dribbling','winger','animator','eli','eittt','yushan','anime',
		'infj','istj','intj','loop','auxiliary','intp','tampik','isfj','mbti','lima',
		'five','wang','huang','sallallahu','alaihi','1998','senantiasa','dolar','nun',
		'lampias','kaul','tahbis','jumat','jumatnya','sabtu','sabtunya','minggu','minggunya',
		'senin','seninnya','seninya','selasa','selasanya','rabu','rabunya','kamis','kamisnya',
		'pingpong','skb','lks','blitar','sisdiknas','kanisius','imboost','isn','peer',
		'pharma','statin','abnyak','japlak','sjok','visus','ablasio','dioptri','lasik',
		'ijut','hrd','baso','papas','ekspat','ponek','occidentalist','gar','wig',
		'kidul','makrab','emping','acu','quoarawan','phd','raka','bakrie','riady','mochtar',
		'dongak','pound','gram','pernah','1963','cakrabirawa','sovyet','jusuf','nal','anyak',
		'saem','ksdkds'";
		$cek = $this->MyModel->cleansing($uji);
		$cek = $this->MyModel->stopword($cek);
		echo $cek;
	}

	public function get_info_datasetkomentar_training(){
		echo json_encode($this->MyModel->get_info_datasetkomentar_training());
	}

	public function get_info_datasetkomentar_testing(){
		echo json_encode($this->MyModel->get_info_datasetkomentar_testing());
	}
	
	public function get_info_datasetkomentar(){
		echo json_encode($this->MyModel->get_info_datasetkomentar());
	}

	public function hapus_pengujian(){
        if($this->MyModel->hapus_pengujian($_POST['id'])>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>'Data pengujian berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Data pengujian gagal dihapus!'
			);
			echo json_encode($callback);
		}
	}
	
	public function pengujian(){
		$id = $_POST['id'];
		$cek = $this->MyModel->pengujian($id);
		if($cek>0){
			$callback = array(
				'status'=>'sukses',
				'pesan'=>"Pengujian berhasil!"
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'status'=>'gagal',
				'pesan'=>"Pengujian gagal!"
			);
			echo json_encode($callback);
		}
	}

	public function tambah_pengujian(){
		//echo json_encode($_POST);
		//var_dump($_POST);
		if($_POST['mode']=='mode'){
			$callback = array(
				'status'=>'gagal',
				'pesan'=>'Harap pilih modenya!'
			);
			echo json_encode($callback);
		}else{
			$this->form_validation->set_rules('segmenawalparam', 'SEGMEN AWAL', 'required|integer');
			$this->form_validation->set_rules('segmenakhirparam', 'SEGMEN AKHIR', 'required|integer');
			if(($_POST['segmenawalparam'] < $_POST['segmenawalx'])){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>"Parameter segmen awal data terlalu kecil!"
				);
				echo json_encode($callback);
			}else if($_POST['segmenawalparam'] > $_POST['segmenakhirx']){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>"Parameter segmen awal data " . $_POST['segmenawalparam'] . " tidak boleh lebih besar daripada segmen akhir" . $_POST['segmenakhirx']
				);
				echo json_encode($callback);
			}else if(($_POST['segmenakhirparam'] > $_POST['segmenakhirx'])){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>"Parameter segmen akhir data terlalu besar!"
				);
				echo json_encode($callback);
			}else if($_POST['segmenakhirparam'] < $_POST['segmenawalx']){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>"Parameter segmen akhir data tidak boleh lebih kecil daripada segmen awal data!"
				);
				echo json_encode($callback);
			}else{
				if($this->form_validation->run()==false){
					$callback = array(
						'status'=>'gagal',
						'pesan'=>validation_errors()
					);
					echo json_encode($callback);
				}else{
					$this->MyModel->tambah_pengujian($_POST);
					$callback = array(
						'status'=>'sukses',
						'pesan'=>"Pengujian ditambahkan!"
					);
					echo json_encode($callback);
				}
			}
		}
	}

	public function kalkuasi_sentimen(){
		$komentar = "";
		$komentar = $_POST['komentar'];

		$this->form_validation->set_rules('komentar', 'KOMENTAR', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			$cek = $this->MyModel->kalkuasi_sentimen($komentar);
			echo json_encode($cek);
		}
	}

	public function laporan_pengujian(){
        $laporan = $this->MyModel->laporan_pengujian();
        $DATA_TES = array();
		$AKURASI = array();
		$ERROR_RATE = array();
		$SPECIFICITY = array();
		$RECALL = array();
		$PRECISI = array();
		$F_SCORE = array();
        $judul = "Perkembangan Pengujian";
        foreach($laporan->result() as $report){
            $DATA_TES[] = round($report->DATA_TES,2);
			$AKURASI[] = round($report->AKURASI * 100,2);
			$ERROR_RATE[] = round($report->ERROR_RATE * 100,2);
			$SPECIFICITY[] = round($report->SPECIFICITY * 100,2);
			$RECALL[] = round($report->RECALL * 100,2);
			$PRECISI[] = round($report->PRECISI * 100,2);
			$F_SCORE[] = round($report->F_SCORE * 100,2);
        }
        $callback = array(
            "sukses" => "sukses",
            "judul" => $judul,
            "data_tes" => $DATA_TES,
			"akurasi" => $AKURASI,
			"error_rate" => $ERROR_RATE,
			"specificity" => $SPECIFICITY,
			"recall" => $RECALL,
			"precisi" => $PRECISI,
			"f_score" => $F_SCORE
        );
		echo json_encode($callback);
    }

	public function gabungkan_kata($cek){
		$gabungkan_kata = '';
		$cek = preg_split('/ /',$cek);
		$count = count($cek);
		for($i=0;$i<$count;$i++){
			$cek[$i] = $this->hilangkan_kataberulang($cek[$i]);
			$gabungkan_kata = $gabungkan_kata .' '. $cek[$i];
		}
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
				$komen = $komen .' ' . $komen;
				$is_implode = 1;
			}
		}
		if($is_implode == 0){
			$komen = implode("", $komen);
		}
		$hasil = '';
		$hasil = $komen;
		return $hasil;
	}

	function case_folding($komen){
		return strtolower($komen);
	}

	function tokenizer($komen){
		$komen = explode(" ", $komen);
		$komen = implode("<br/>", $komen);
		return $komen;
	}

	public function cleansing($komen){
		$komen = preg_replace("/[^a-zA-Z0-9]/", " ", $komen);
		return $komen;
	}

	function penerima_bantuan(){
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'Hello World!');
        $pdf->Output();
    }

	public function stemmer($sentence)   {
		/*$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stemmer = $stemmerFactory->createStemmer();
		$output = $stemmer->stem($sentence);
		return $output;*/
		$stemming = $this->MyModel->stemmer($sentence);
		return $stemming;
    }

    public function stopword($sentence){
		/*$stopWordRemoverFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
		$stopword = $stopWordRemoverFactory->createStopWordRemover();
		$outputstopword = $stopword->remove($sentence);
		return $outputstopword;*/
		$stopword = $this->MyModel->stopword($sentence);
		return $stopword;
	}

	public function curl(){
		$url = 'https://www.detik.com/';
		$options = array(
			CURLOPT_CUSTOMREQUEST  =>"GET",    // Atur type request, get atau post
			CURLOPT_POST           =>false,    // Atur menjadi GET
			CURLOPT_FOLLOWLOCATION => true,    // Follow redirect aktif
			CURLOPT_CONNECTTIMEOUT => 120,     // Atur koneksi timeout
			CURLOPT_TIMEOUT        => 120,     // Atur response timeout
		);

		$ch      = curl_init( $url );          // Inisialisasi Curl
		curl_setopt_array( $ch, $options );    // Set Opsi
		$content = curl_exec( $ch );           // Eksekusi Curl
		curl_close( $ch );                     // Stop atau tutup script

		$header['content'] = $content;
	}

	public function casefolding_dataset_artikel(){
		$is_casefolding = $this->MyModel->casefolding_dataset_artikel();
		if($is_casefolding==1){
			$callback = array(
				'pesan'=>'case folding sukses!'
			);
			echo json_encode($callback);
		}else{			
			$callback = array(
				'pesan'=>'case folding gagal!'
			);
			echo json_encode($callback);
		}
	}
	
	public function cleaning_dataset_artikel(){
		$is_worked = $this->MyModel->cleaning_dataset_artikel();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'cleaning sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'cleaning gagal!'
			);
			echo json_encode($callback);
		}
	}
	
	public function stemming_dataset_artikel(){
		$is_worked = $this->MyModel->stemming_dataset_artikel();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Stemming sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Stemming gagal!'
			);
			echo json_encode($callback);
		}
	}

	public function stopword_dataset_artikel(){
		$is_worked = $this->MyModel->stopword_dataset_artikel();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Stopword sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Stopword gagal!'
			);
			echo json_encode($callback);
		}
	}

	public function tokenizing_dataset_artikel(){
		$is_worked = $this->MyModel->tokenizing_dataset_artikel();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Tokenizing sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Tokenizing gagal!'
			);
			echo json_encode($callback);
		}
	}

	public function normalisasi_dataset_artikel(){
		$is_worked = $this->MyModel->normalisasi_dataset_artikel();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Normalisasi bahasa gaul sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Normalisasi bahasa gaul gagal!'
			);
			echo json_encode($callback);
		}
	}

	public function casefolding_dataset(){
		$is_casefolding = $this->MyModel->casefolding_dataset();
		if($is_casefolding==1){
			$callback = array(
				'pesan'=>'case folding sukses!'
			);
			echo json_encode($callback);
		}else{			
			$callback = array(
				'pesan'=>'case folding gagal!'
			);
			echo json_encode($callback);
		}
	}
	
	public function cleaning_dataset(){
		$is_worked = $this->MyModel->cleaning_dataset();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'cleaning sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'cleaning gagal!'
			);
			echo json_encode($callback);
		}
	}
	
	public function stemming_dataset(){
		$is_worked = $this->MyModel->stemming_dataset();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Stemming sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Stemming gagal!'
			);
			echo json_encode($callback);
		}
	}

	public function stopword_dataset(){
		$is_worked = $this->MyModel->stopword_dataset();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Stopword sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Stopword gagal!'
			);
			echo json_encode($callback);
		}
	}

	public function tokenizing_dataset(){
		$is_worked = $this->MyModel->tokenizing_dataset();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Tokenizing sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Tokenizing gagal!'
			);
			echo json_encode($callback);
		}
	}

	public function normalisasi_dataset(){
		$is_worked = $this->MyModel->normalisasi_dataset();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Normalisasi bahasa gaul sukses!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Normalisasi bahasa gaul gagal!'
			);
			echo json_encode($callback);
		}
	}


	public function get_antibullying_model(){
		$this->MyModel->hapus_datakelaskata();
		$this->MyModel->hapus_dataprior();
		$is_reset = $this->MyModel->reset_kelaskatamodel();
		if($is_reset==1){
			$is_kelaskata = $this->MyModel->get_kelaskatamodel();
			if($is_kelaskata==1){
				$is_prior = $this->MyModel->get_priormodel();
				if($is_prior==1){
					$is_probabilitas = $this->MyModel->probabilitas_kata();
					if($is_probabilitas==1){
						$callback = array(
							'pesan'=>'Model antibullying berhasil dibuat!'
						);
						echo json_encode($callback);
					}else{
						$callback = array(
							'pesan'=>'Model probabilitas kata gagal dibuat!'
						);
						echo json_encode($callback);
					}
				}else{
					$callback = array(
						'pesan'=>'Model prior gagal dibuat!'
					);
					echo json_encode($callback);
				}
			}else{
				$callback = array(
					'pesan'=>'Model kelas kata gagal dibuat!'
				);
				echo json_encode($callback);
			}
		}else{
			$callback = array(
				'pesan'=>'Model kelas kata gagal direset!'
			);
			echo json_encode($callback);
		}
	}

	public function get_hapus_seluruh_pengujian(){
		$is_hapus_detailpengujian = $this->MyModel->hapus_datadetailpengujian();
		if($is_hapus_detailpengujian==1){
			$is_hapus_pengujian = $this->MyModel->hapus_datapengujian();
			if($is_hapus_pengujian==1){
				$callback = array(
					'pesan'=>'Pengujian berhasil dihapus!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'pesan'=>'Pengujian gagal dihapus!'
				);
				echo json_encode($callback);
			}
		}else{
			$callback = array(
				'pesan'=>'Detail pengujian gagal dihapus!'
			);
		}
	}

	public function get_hapus_dataset_null(){
		$is_hapus_null = $this->MyModel->hapus_dataset_null();
		if($is_hapus_null>1){
			$callback = array(
				'pesan'=>'Dataset null berhasil dihapus!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Dataset null tidak ada!'
			);
			echo json_encode($callback);
		}
	}

	public function get_split_dataset(){
		if($_POST['splitdatatesting']>50){
			$callback = array(
				'status' => 'gagal',
				'pesan'=>'Splitting tidak boleh lebih dari 50%!'
			);
			echo json_encode($callback);
		}else{
			$this->form_validation->set_rules('splitdatatesting', 'Split data testing', 'required');  
			if($this->form_validation->run()==false){
				$callback = array(
					'status'=>'gagal',
					'pesan'=>validation_errors()
				);
				echo json_encode($callback);
			}else{
				$is_splitting = $this->MyModel->split_dataset($_POST);
				if($is_splitting>0){
					$callback = array(
						'status' => 'sukses',
						'pesan'=>'Dataset berhasil di-split!'
					);
					echo json_encode($callback);
				}else{
					$callback = array(
						'status' => 'gagal',
						'pesan'=>'Dataset gagal di-split!'
					);
					echo json_encode($callback);
				}
			}
		}
		
	}

	public function get_info_datasetnull(){
		$null = $this->MyModel->totaldatasetnull();
		$callback = array(
			'jmlnull'=> $null
		);
		echo json_encode($callback);
	}

	public function get_hapus_antibullying_model(){
		$is_hapus_kelaskata = $this->MyModel->hapus_datakelaskata();
		if($is_hapus_kelaskata>0){
			$is_hapus_prior = $this->MyModel->hapus_dataprior();
			if($is_hapus_prior>0){
				$callback = array(
					'pesan'=>'Model antibullying berhasil dihapus!'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'pesan'=>'Model prior gagal dihapus!'
				);
				echo json_encode($callback);
			}
		}else{
			$callback = array(
				'pesan'=>'Model kelas kata gagal dihapus!'
			);
			echo json_encode($callback);
		}
	}

	public function get_kelaskata_model(){
		$is_worked = $this->MyModel->get_kelaskatamodel();
		if($is_worked>0){
			$callback = array(
				'pesan'=>'Model kelas kata berhasil dibuat!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Model kelas kata gagal dibuat!'
			);
			echo json_encode($callback);
		}
	}

	public function get_probabilitaskata_model(){
		$is_worked = $this->MyModel->probabilitas_kata();
		if($is_worked==1){
			$callback = array(
				'pesan'=>'Model probabilitas kelas kata berhasil dibuat!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Model probabilitas kelas kata gagal dibuat!'
			);
			echo json_encode($callback);
		}
	}

	public function get_kelaskatamodel_reset(){
		$is_worked = $this->MyModel->reset_kelaskatamodel();
		if($is_worked>0){
			$callback = array(
				'pesan'=>'Model kelas kata berhasil direset!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Model kelas kata gagal direset!'
			);
			echo json_encode($callback);
		}
	}

	public function get_priormodel(){
		$is_worked = $this->MyModel->get_priormodel();
		if($is_worked>0){
			$callback = array(
				'pesan'=>'Prior berhasil dibuat!'
			);
			echo json_encode($callback);
		}else{
			$callback = array(
				'pesan'=>'Prior gagal dibuat!'
			);
			echo json_encode($callback);
		}
	}

	public function artikel_ubah(){
        $this->form_validation->set_rules('judulartikel', 'Judul Artikel', 'required');
		$this->form_validation->set_rules('topikartikel', 'Topik Artikel', 'required');
		$this->form_validation->set_rules('sumberartikel', 'Sumber Artikel', 'required');
		$this->form_validation->set_rules('tanggalartikel', 'Tanggal Artikel', 'required');
		$this->form_validation->set_rules('isiartikel', 'Isi Artikel', 'required');  
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			$this->MyModel->dataset_artikelUbah($_POST);
            $callback = array(
				'status'=>'sukses',
				'pesan'=>'berhasil disimpan'
            );
            echo json_encode($callback);
        }
    }

	public function hapus_artikel()
	{
		if($this->MyModel->artikel_hapus($_POST)>0){
            header('Location: ' . base_url() . 'admin/antibullying');
        }else{
            header('Location: ' . base_url() . 'admin/antibullying');
        }
	}

	public function hapus_kamusgaul(){	
		if($this->MyModel->kamusgaul_hapus($_POST)>0){
            header('Location: ' . base_url() . 'admin/antibullying_kamusgaul');
        }else{
            header('Location: ' . base_url() . 'admin/antibullying_kamusgaul');
		}
	}

	public function hapus_stopwordlist(){	
		if($this->MyModel->stopwordlist_hapus($_POST)>0){
            header('Location: ' . base_url() . 'admin/antibullying_stopwordlist');
        }else{
            header('Location: ' . base_url() . 'admin/antibullying_stopwordlist');
		}
	}
	
	public function getdataset_artikel(){
		echo json_encode($this->MyModel->dataset_artikelById($_POST['idx']));
	}

	public function getdataset_kamusgaul(){
		echo json_encode($this->MyModel->dataset_kamusById($_POST['idx']));
	}

	public function getdataset_stopword(){
		echo json_encode($this->MyModel->dataset_stopwordById($_POST['idx']));
	}

	public function komentar_ubah(){
        $this->form_validation->set_rules('isikomentar', 'KOMENTAR', 'required');
        $this->form_validation->set_rules('sentimen', 'SENTIMEN', 'required');  
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if($this->MyModel->dataset_komentarUbah($_POST)>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'berhasil disimpan'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'gagal disimpan'
				);
				echo json_encode($callback);
			}
        }
    }

	public function kamusgaulubah(){
		$this->form_validation->set_rules('katagaul', 'Kata Gaul', 'required');
        $this->form_validation->set_rules('kataformal', 'Kata Formal', 'required');  
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if($this->MyModel->kamusgaulUbah($_POST)>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'berhasil disimpan'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'gagal disimpan'
				);
				echo json_encode($callback);
			}
		}
	}
	
	public function stopwordubah(){
        $this->form_validation->set_rules('stopword', 'Stopword', 'required');  
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if($this->MyModel->stopwordlistUbah($_POST)>0){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'berhasil disimpan'
				);
				echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'gagal disimpan'
				);
				echo json_encode($callback);
			}
		}
    }

	public function cek(){
		echo $this->MyModel->is_katagaulAda('ok');
	}

	public function kamusgaul_buat(){
		$this->form_validation->set_rules('katagaul', 'Kata Gaul', 'required');
		$this->form_validation->set_rules('kataformal', 'Kata Formal', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if(($this->MyModel->is_katagaulAda($_POST['katagaul']))<1){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'berhasil disimpan'
				);
				$this->MyModel->kamusgaul_buat($_POST);
            	echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'kata gaul sudah ada di kamus'
				);
            	echo json_encode($callback);
			}
		}
		
	}

	public function Stopword_buat(){
		$this->form_validation->set_rules('stopword', 'Stopword', 'required');
        if($this->form_validation->run()==false){
            $callback = array(
				'status'=>'gagal',
				'pesan'=>validation_errors()
            );
            echo json_encode($callback);
        }else{
			if(($this->MyModel->is_StopwordAda($_POST['stopword']))<1){
				$callback = array(
					'status'=>'sukses',
					'pesan'=>'berhasil disimpan'
				);
				$this->MyModel->stopwordlist_buat($_POST['stopword']);
            	echo json_encode($callback);
			}else{
				$callback = array(
					'status'=>'gagal',
					'pesan'=>'Stopword sudah ada!'
				);
            	echo json_encode($callback);
			}
		}
		
	}

	public function dataset_artikel_buat(){
		$this->form_validation->set_rules('judulartikel', 'Judul Artikel', 'required');
		$this->form_validation->set_rules('topikartikel', 'Topik Artikel', 'required');
		$this->form_validation->set_rules('isiartikel', 'Isi Artikel', 'required');
        $this->form_validation->set_rules('sumberartikel', 'Sumber Artikel', 'required');
		$this->form_validation->set_rules('tanggalartikel', 'Tanggal Artikel', 'required');
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
            $this->MyModel->dataset_artikel_buat($_POST);
            echo json_encode($callback);
		}
	}

	public function get_datasetkomentar()
	{
		$list = $this->MyModel->dataset_allkomentar();
		foreach($list->result() as $li){
			$row = array();
			$row[] = $li->ID_DATASETKOMENTAR;
			$row[] = $li->ID_ARTIKEL;
			$row[] = $li->ISI_KOMENTAR;
			$row[] = $li->CASE_FOLDING;
			$row[] = $li->CLEANING;
			$row[] = $li->NORMALISASI;
			$row[] = $li->STEMMING;
			$row[] = $li->STOPWORD;
			$row[] = $li->TOKENIZING;
			if($li->SENTIMEN=='non-cyberbullying' || $li->SENTIMEN=='positif'){
				$row[] = '<center>
				<a href="#" class="btn btn-primary ubahkategori" data-toggle="modal" data-target="#formEdit" data-idy="'. $li->ISI_KOMENTAR .'">N</a></center>';
			}else{
				$row[] = '<center>
				<a href="#" class="btn btn-danger ubahkategori" data-toggle="modal" data-target="#formEdit" data-idy="'. $li->ISI_KOMENTAR .'">Y</a></center>';
			}
			$data[] = $row;
		}	
		$output = array(
			"draw" => 0,
			"recordsTotal" =>0,
			"recordsFiltered" =>0,
			"data" => $data
		);
		echo json_encode($output);
	}

	public function get_prior()
	{
		$list = $this->MyModel->prior();
		foreach($list as $li){
			$row = array();
			$row[] = $li->ID_PRIOR;
			$row[] = $li->TGL_DIPERBARUI;
			$row[] = $li->JENIS;
			$row[] = $li->KELAS;
			$row[] = $li->TOTAL_KELASDATA;
			$row[] = $li->TOTAL_SELURUHDATA;
			$row[] = $li->PROBABILITAS;
			$row[] = $li->TOTAL_KELASKATA;
			$row[] = $li->TOTAL_SELURUHKATAUNIK;
			$row[] = $li->TOTAL_KELASKATA_PLUS_KATAUNIK;
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

	public function get_kelaskata()
	{
		$list = $this->MyModel->kelas_kata();
		foreach($list as $li){
			$row = array();
			$row[] = $li->ID_KATA;
			$row[] = $li->TGL_PERBARUI;
			$row[] = $li->KATA;
			$row[] = $li->TOTAL_CYBERBULLY;
			$row[] = $li->TOTAL_NONCYBERBULLY;
			$row[] = $li->PROBABILITAS_CYBERBULLY;
			$row[] = $li->PROBABILITAS_NONCYBERBULLY;
			$data[] = $row;
		}	
		$output = array(
			"draw" => 1,
			"recordsTotal" => 0,
			"recordsFiltered" => 0,
			"data" => $data
		);
		echo json_encode($output);
	}

	public function dataset_komentar_buat()
	{
		$this->form_validation->set_rules('isikomentar', 'Isi Komentar', 'required');
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
            $this->MyModel->dataset_komentar_buat($_POST);
            echo json_encode($callback);
		}
	}

}