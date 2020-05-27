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

	$artikel = "http://".$_SERVER['SERVER_NAME']."/esaku/iArtikel";
	$video = "http://".$_SERVER['SERVER_NAME']."/esaku/iVideo";
	$artikelU = "http://".$_SERVER['SERVER_NAME']."/esaku/iArtikelUtama";
?>

<div class="fixed-top" data-toggle="affix" id="navbar">
    <nav class="navbar bg-white navbar-expand-sm navbar-9" id="first">
        <div class="container-fluid">
			<a class="navbar-brand" href="<?= $root_app ?>" title="Home"><img id="imgWeb" class="img-zoom" src="<?= $path ?>/saku.png" width="25px" height="25px" alt="logo"><img id="imgMobile" class="img-zoom" src="<?= $path ?>/esaku-landscape.png" width="auto" height="25px" alt="logo"></a>
			<button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon navbar-light" ></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
				<div class="navbar-nav col-11">
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
	<nav class="navbar navbar-expand-lg navbar-light bg-white py-0" id="second">
		<div class="container-fluid">
			<button class="navbar-toggler navbar-light" type="button" onclick="openNav()">
				<span class="navbar-toggler-icon navbar-light" ></span>
			</button>
			<input id="tCari2" placeholder="Cari sebuah artikel" onkeypress="clickPress2(event)">
			<!-- <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; open</span> -->
			<!-- <button class="navbar-toggler navbar-light" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup2" aria-controls="navbarNavAltMarkup2" aria-expanded="false" aria-label="Toggle navigation"> -->
			<div class="col-md-8 collapse navbar-collapse" id="navbarNavAltMarkup2">
				<ul class="navbar-nav">
                    <li class="nav-link active-scroll ml-3 mr-4"><span class="yourNav" style="font-weight: bold">Keyword</span></li>
                    <li class="nav-link active-scroll yourNav2 home mx-2" onclick="location.href='<?= $blog ?>'"><span class="">Home</span></li>
                    <li class="nav-link active-scroll yourNav2 artikel mx-2" onclick="location.href='<?= $artikel ?>'"><span class="">Artikel Terbaru</span></li>
                    <li class="nav-link active-scroll yourNav2 video mx-2" onclick="location.href='<?= $video ?>'"><span class="">Video</span></li>
                    <li class="nav-link active-scroll yourNav2 kategori mx-2">Kategori</li>
				</ul>
				<!-- <input id="tCari2" placeholder="Cari sebuah artikel" onkeypress="clickPress2(event)"> -->
			</div>
			<div class="col-md-4">
				<!-- <form class="form-inline my-lg-0"> -->
					<input id="tCari" placeholder="Cari sebuah artikel" onkeypress="clickPress(event)">
				<!-- </form> -->
			</div>
		</div>
	</nav>
	<div id="mySidenav" class="sidenav">
		<!-- <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a> -->
		<img class="img-zoom ml-3 my-3" src="<?= $path ?>/esaku-landscape.png" width="auto" height="25px" alt="logo"></a>
		<hr class="my-0">
		<a class="pl-3 py-2" href="#" style="font-size: 22px; font-weight: bold; color: #747474">The Keyword</a>
		<hr class="my-0">
		<input class="ml-3 my-2" id="tCari2" placeholder="Cari sebuah artikel" onkeypress="clickPress2(event)">
		<a class="nav-item nav-link" id="home" onclick="location.href='<?= $blog ?>'"><span style="color: #525252" class="mynavSide">Home</span></a>
		<a class="nav-item nav-link" id="art" onclick="location.href='<?= $artikel ?>'"><span class="mynavSide">Artikel Terbaru</span></a>
		<a class="nav-item nav-link" id="vide" onclick="location.href='<?= $video ?>'"><span class="mynavSide">Video</span></a>
		<a class="nav-item nav-link"><span class="mynavSide">Kategori : </span></a>
		<a class="nav-item nav-link"><span class="mynavSide">Artikel<div class="col" id="katArt2" style=""></div></span></a>
		<a class="nav-item nav-link"><span class="mynavSide">Video<div class="col" id="katVid2" style=""></div></span></a>
		<!-- <a class="nav-item nav-link"><span class="mynavSide"></span></a> -->
	</div>
	<nav class="navbar navbar-expand-lg navbar-light bg-white" id="third">
		<div class="">
			<div class="row">
				<div class="col" style="color: #4E8DF5">Artikel</div>
			</div>
			<div class="row">
				<div class="col" id="katArt">
				</div>
			</div>
			<div class="row">
				<div class="col" style="color: #4E8DF5">Video</div>
			</div>
			<div class="row">
				<div class="col" id="katVid">
				</div>
			</div>
		</div>
	</nav>
</div>