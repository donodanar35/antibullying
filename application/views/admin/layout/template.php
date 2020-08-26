<?php 
    require_once('header.html');
    $this->load->library('ion_auth');
	if (!$this->ion_auth->is_admin())
	{
		require_once('sidebar_user.html');
	}else{
        require_once('sidebar.html');
    }    
    require_once('content.php');
    require_once('footer.html');