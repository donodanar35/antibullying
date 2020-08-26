<?php
      $pengguna = $this->MyModel->getPenggunaById($_SESSION['user_id']);
      $data['notifikasi'] = $this->MyModel->count_notifikasi($pengguna->ID);

	$data['judul'] = 'Admin | Manajemen User';
	$data['identitas'] = $this->MyModel->getidentitas(); 
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
                  <li class="breadcrumb-item active"><a href="#">Edit User</a></li>
            </ol> 
      <ol class="breadcrumb">    
      <div class="container">    
      <div class="card mb-12">
            <div class="card-header">
              <i class="fa fa-id-card"></i>
              <b>Edit User</b>
            </div>
            <div class="card-body">
            <?php echo form_open(uri_string());?>
                  <div class="form-group">
                      <label for="metaKeyword"><b>Nama Depan:</b></label>
                        <?php echo '<input name="first_name" type="text" class="form-control" id="first_name" aria-describedby="first_name" placeholder="Masukkan nama depan..." value='."'". $first_name['value'] ."'>"; ?>
                  </div>
                  <div class="form-group">
                      <label for="metaKeyword"><b>Nama Belakang:</b></label>
                        <?php echo '<input name="last_name" type="text" class="form-control" id="last_name" aria-describedby="last_name" placeholder="Masukkan nama belakang..." value='."'". $last_name['value'] ."'>"; ?>
                  </div>
                  <div class="form-group">
                      <label for="metaKeyword"><b>Perusahaan:</b></label>
                        <?php echo '<input name="company" type="text" class="form-control" id="company" aria-describedby="company" placeholder="Masukkan nama perusahaan/ instansi..." value='."'". $company['value'] ."'>"; ?>
                  </div>
                  <div class="form-group">
                      <label for="metaKeyword"><b>Nomer HP:</b></label>
                        <?php echo '<input name="phone" type="text" class="form-control" id="phone" aria-describedby="phone" placeholder="Masukkan nomer HP..." value='."'". $phone['value'] ."'>"; ?>
                  </div>
                  <div class="form-group">
                      <label for="metaKeyword"><b>Password:</b></label>
                        <?php echo '<input name="password" type="password" class="form-control" id="password" aria-describedby="password" placeholder="Masukkan password...">'; ?>
                  </div>
                  <div class="form-group">
                      <label for="metaKeyword"><b>Re-Password:</b></label>
                        <?php echo '<input name="password_confirm" type="password" class="form-control" id="password_confirm" aria-describedby="password_confirm" placeholder="Masukkan re-password...">'; ?>
                  </div>

                  <?php if ($this->ion_auth->is_admin()): ?>

                  <div class="form-group">
                        <label for="hak_akses"><b>Hak Akses:</b></label><br/>
                        <?php foreach ($groups as $group):?>
                              <label class="checkbox">
                              <?php
                                    $gID=$group['id'];
                                    $checked = null;
                                    $item = null;
                                    foreach($currentGroups as $grp) {
                                    if ($gID == $grp->id) {
                                          $checked= ' checked="checked"';
                                    break;
                                    }
                                    }
                              ?>
                              <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                              <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                              </label>
                        <?php endforeach?>
                  </div>
                  <?php endif ?>

                  <?php echo form_hidden('id', $user->id);?>
                  <?php echo form_hidden($csrf); ?>
                  <button id="btn-simpan"type="submit" name="submit" value="Save User" class="btn btn-primary">Simpan</button>

            <?php echo form_close();?>

            </div>
            <div class="card-footer small text-muted">Copyright &copy;<script>document.write(new Date().getFullYear());</script> KebaikanKita.com</div>
          </div>
          <div>
      </div>
      <div>
</div>



<?php
	$this->load->view('admin/layout/footer.html');
?>
