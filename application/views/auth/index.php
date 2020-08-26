<?php
	$data['judul'] = 'Admin | Manajemen User';
	$data['identitas'] = $this->MyModel->getidentitas();
	$pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
	$data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID); 
    $this->load->view('admin/layout/header.html',$data);
	$this->load->library('ion_auth');
	
	if (!$this->ion_auth->is_admin())
	{
		$this->load->view('admin/layout/sidebar_user.html');
	}else{
        $this->load->view('admin/layout/sidebar.html');
    }    
?>
<div id="content-wrapper">
  <div class="container-fluid">
	  <!-- Breadcrumbs-->
	  <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="<?php echo base_url('auth');?>">Manajemen User</a>
      </li>
   	 </ol>  
    
<ol class="breadcrumb">
    <div class="container">
          <div class="card mb-12">
            <div class="card-header">
			<i class="fa fa-id-card"></i>
              <b>Daftar User</b>
            </div>
            <div class="card-body">
              <div class="table-responsive">
			
			  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
			  	<thead>
                	<tr>
                    	<th><center>No</center></th>
                        <th><center>Nama Depan</center></th>
                        <th><center>Nama Belakang</center></th>
                        <th><center>E-mail</center></th>
						<th><center>Grup</center></th>
						<th><center>Action</center></th>
                    </tr>
				</thead>
				<tbody>
				<?php $n=1; foreach ($users as $user):?>
					<tr>
						<td><?php echo $n;?></td>
						<td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
						<td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
						<td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
						<td>
							<?php foreach ($user->groups as $group):?>
								<?php echo $group->name ;?><br />
							<?php endforeach?>
						</td>
						<td>
							<center><a class="btn btn-success" href="<?php echo base_url('auth/edit_user/').$user->id; ?>">Edit</a></center>
						</td>
					</tr>
				<?php $n++; endforeach;?>
				</tbody>
			</table>
			  
              </div>
            </div>
            <div class="card-footer small text-muted">Copyright &copy;<script>document.write(new Date().getFullYear());</script> KebaikanKita.com</div>
          </div>
        </ol>

  </div>
</div>

<?php
	$this->load->view('admin/layout/footer.html');
?>

