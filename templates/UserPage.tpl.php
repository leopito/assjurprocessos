<?php
	$this->assign('title','Gerenciamentos de Processos Secure Example');
	$this->assign('nav','secureexample');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("scripts/app/users.js").wait(function(){
		$(document).ready(function(){
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">
    <div class="hero-unit">
        <h1>Secure <?php $this->eprint($this->page == 'userpage' ? 'User' : 'Admin'); ?> Page</h1>
        <p>This page is accessible only to <?php $this->eprint($this->page == 'userpage' ? 'authenticated users' : 'administrators'); ?>.  
        You are currently logged in as '<strong><?php echo $this->currentUser->Username; ?></strong>'</p>
        <?php print_r($this->currentUser); ?>
        <p>
                <a href="secureuser" class="btn btn-primary btn-large">Visit User Page</a>
                
                <a href="logout" class="btn btn-primary btn-large">Logout</a>
        </p>
    </div>
    
    
</div>

<?php print_r($this->currentUser); ?>

<?php
	$this->display('_Footer.tpl.php');
?>
