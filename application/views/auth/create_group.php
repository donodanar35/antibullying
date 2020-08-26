<?php
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
                  <li class="breadcrumb-item active"><a href="#">Buat Grup</a></li>
            </ol> 
          
      <div class="card mb-12">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Edit User
            </div>
            <div class="card-body">
            
            <?php echo form_open("auth/create_group");?>
                  <p>
                        <?php echo lang('create_group_name_label', 'group_name');?> <br />
                        <?php echo form_input($group_name);?>
                  </p>
                  <p>
                        <?php echo lang('create_group_desc_label', 'description');?> <br />
                        <?php echo form_input($description);?>
                  </p>
                  <p><?php echo form_submit('submit', lang('create_group_submit_btn'));?></p>
            <?php echo form_close();?>
            
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
          </div>
      </div>
</div>



<?php
	$this->load->view('admin/layout/footer.html');
?>
