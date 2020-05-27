<?php
	$path = "http://".$_SERVER['SERVER_NAME']."/assets/img/esaku";
	$path2 = "http://".$_SERVER['SERVER_NAME']."/assets/css/esaku";
	$path3 = "http://".$_SERVER['SERVER_NAME']."/assets/js/esaku";
	$path4 = "http://".$_SERVER['SERVER_NAME']."/assets/fonts/esaku";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<!-- <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> -->
		<link href="<?= $path2 ?>/tentangg.css" rel="stylesheet">
		<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<script src="https://kit.fontawesome.com/6c071091af.js"></script>
		<title>SAKU | Tentang Kami</title>
		<link rel="icon" type="image/png" href="<?= $path ?>/saku.png" sizes="32x32">
		<style>
			@font-face {
				font-family: "Font Mas Afnan";
				src: url('<?= $path4 ?>/SF-Pro-Text-Regular.otf');
			}

			body {
				font-family: 'Font Mas Afnan';
			}
		</style>
	</head>
	<body>
		<!-- Loader -->
		<!-- <div class="preloader-wrapper">
		    <div class="preloader">
		        <img src="img/loader.gif" alt="loading">
		    </div>
		</div> -->
		<!-- Loader -->
		<!-- Navigation -->
			<?php 
			include 'nav.php';
			 ?>
		<!-- End of Navigation -->
		<header>
			<div class="row mt-5">
				<div class="col-md-12">
					<img id="gambarCover-al" src="<?= $path ?>/AssetProdukPerusahaan/Perusahaan/reCity-View.jpg" alt="">
				</div>
				<div class="col-md-12" id="teksAtas-al">
					<div class="col-md-12" data-aos="zoom-in" data-aos-duration="1000">Pengembangan Software IT</div>
					<div class="col-md-12" data-aos="zoom-in" data-aos-duration="1500">akuntansi dan keuangan</div>
					<div class="col-md-12" data-aos="zoom-in" data-aos-duration="2000" id="garisBawah-al">_________________</div>
				</div>
			</div>
		</header>
		
		<div class="container" style="margin-bottom: 100px;">
			<div class="row">
				<div class="col-md-12" id="teksTentang-al">Tentang Kami</div>
			</div>
			<div class="row" id="webYa-al">
				<div class="col-md-5">
					<div class="row" id="teksU1-al" data-aos="fade-right" data-aos-duration="1500">20 Tahun</div>
					<div class="row" id="teksU2-al" data-aos="fade-right" data-aos-duration="2000">lebih pengalaman pengembangan aplikasi.</div>
				</div>
				<div class="col-md-7" id="teksDes-al" data-aos="fade-left" data-aos-duration="1500"><p>Bermula dari membangun aplikasi yang berbasis desktop pada tahun 1998 sampai hadir menjadi aplikasi online seperti saat ini.</p></div>
			</div>
			<div class="row" id="forMobile-al">
				<div class="col-md-5">
					<div class="row" id="teksU1-al" data-aos="fade-right" data-aos-duration="1500">Berkomitmen</div>
					<div class="row" id="teksU2-al" data-aos="fade-right" data-aos-duration="2000">selalu bersama pengguna.</div>
				</div>
				<div class="col-md-7 teksDes-al2" id="teksDes-al" data-aos="fade-left" data-aos-duration="1500"><p>Kenapa kami dipercaya institusi-institusi besar? karena kami selalu menjaga kerahasiaan data dan menyimpan data dengan aman.</p></div>
			</div>
			<div class="row" id="forWeb-al">
				<div class="col-md-7 teksDes-al2" id="teksDes-al" data-aos="fade-right" data-aos-duration="1500"><p>Kenapa kami dipercaya institusi-institusi besar? karena kami selalu menjaga kerahasiaan data dan menyimpan data dengan aman.</p></div>
				<div class="col-md-5">
					<div class="row" id="teksU1-al" data-aos="fade-left" data-aos-duration="1500">Berkomitmen</div>
					<div class="row" id="teksU2-al" data-aos="fade-left" data-aos-duration="2000">selalu bersama pengguna.</div>
				</div>
			</div>
			<div class="row" id="webYa-al">
				<div class="col-md-5">
					<div class="row" id="teksU1-al" data-aos="fade-right" data-aos-duration="1500">#1 Pelayanan</div>
					<div class="row" id="teksU2-al" data-aos="fade-right" data-aos-duration="2000">sehingga kami tetap bertahan sampai sekarang.</div>
				</div>
				<div class="col-md-7" id="teksDes-al" data-aos="fade-left" data-aos-duration="1500"><p>Berkelanjutan memantau penggunaan. Kami tidak akan meninggalkan klien yang mempercayai aplikasi dari kami. Jadi tidak perlu khawatir jika ada permasalahan, karena kita akan membantu dalam menyelesaikannya.</p></div>
			</div>
			<div class="row" id="forMobile-al">
				<div class="col-md-5">
					<div class="row" id="teksU1-al" data-aos="fade-right" data-aos-duration="1500">Fokus</div>
					<div class="row" id="teksU2-al" data-aos="fade-right" data-aos-duration="2000">membangun aplikasi akuntansi dan keuangan.</div>
				</div>
				<div class="col-md-7 teksDes-al2" id="teksDes-al" data-aos="fade-left" data-aos-duration="1500"><p>Tidak perlu diragukan lagi dengan pengalaman dan fokus pengembangan kami sehingga mampu membantu merealisasikan keinginan Anda dalam menerapkan komputerisasi akuntansi.</p></div>
			</div>
			<div class="row" id="forWeb-al">
				<div class="col-md-7 teksDes-al2" id="teksDes-al" data-aos="fade-right" data-aos-duration="1500"><p>Tidak perlu diragukan lagi dengan pengalaman dan fokus pengembangan kami sehingga mampu membantu merealisasikan keinginan Anda dalam menerapkan komputerisasi akuntansi.</p></div>
				<div class="col-md-5">
					<div class="row" id="teksU1-al" data-aos="fade-left" data-aos-duration="1500">Fokus</div>
					<div class="row" id="teksU2-al" data-aos="fade-left" data-aos-duration="2000">membangun aplikasi akuntansi dan keuangan.</div>
				</div>
			</div>
		</div>

		<!-- Footer -->
		<?php 
			include 'footer-al.php';
		?>
		<!-- End of Footer -->


		<!-- Optional JavaScript -->
		<script src="<?= $path3 ?>/tentang.js"></script>
		<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script>
			AOS.init();
		</script>
	</body>
</html>