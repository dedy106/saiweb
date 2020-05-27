<?php 
	$root_app="http://".$_SERVER['SERVER_NAME']."/esaku";
	$fitur="http://".$_SERVER['SERVER_NAME']."/esaku/fitur";
	$produk="http://".$_SERVER['SERVER_NAME']."/esaku/produk";
	$tentang="http://".$_SERVER['SERVER_NAME']."/esaku/tentang";
	$login="http://".$_SERVER['SERVER_NAME']."/esaku/login";
	$blog = "http://".$_SERVER['SERVER_NAME']."/esaku/iBlog";
	$path = "http://".$_SERVER['SERVER_NAME']."/assets/img/esaku";
	$path2 = "http://".$_SERVER['SERVER_NAME']."/assets/css/esaku";
	$path3 = "http://".$_SERVER['SERVER_NAME']."/assets/js/esaku";
?>

		<nav class="navbar navbar-expand-lg bg-white fixed-top" style="overflow: initial;z-index: 999;">
			<div class="container">
				<a class="navbar-brand" href="<?= $root_app ?>" title="Home"><img id="imgWeb" class="img-zoom" src="<?= $path ?>/saku.png" width="25px" height="25px" alt="logo"><img id="imgMobile" class="img-zoom" src="<?= $path ?>/esaku-landscape.png" width="auto" height="25px" alt="logo"></a>
				<button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon navbar-light" ></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
					<div class="navbar-nav">
						<a class="nav-item nav-link" href="<?= $root_app ?>"><span class="mynav">Beranda</span></a>
						<a class="nav-item nav-link" href="<?= $produk ?>"><span class="mynav">Produk</span></a>
						<!-- <a class="nav-item nav-link" href="<?= $fitur  ?>"><span class="mynav">Fitur</span></a> -->
						<a class="nav-item nav-link" href="<?= $blog  ?>"><span class="mynav">Blog</span></a>
						<a class="nav-item nav-link" href="<?= $tentang ?>"><span class="mynav">Perusahaan</span></a>
					</div>
					<div class="navbar-nav col-1">
						<a class="nav-item nav-link" href="<?= $login ?>" target="_blank"><span class="mypad rounded shadow-sm" id="btnMasuk">Masuk</span></a>
					</div>
				</div>
			</div>
		</nav>