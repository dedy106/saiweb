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
		<link href="<?= $path2 ?>/produk.css" rel="stylesheet">
		<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<script src="https://kit.fontawesome.com/6c071091af.js"></script>
		<title>SAKU | Produk</title>
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
		
		<div class="container mt-5">
			<header>
				<div class="row">
					<div class="col-md-12" id="teksProduk-al" data-aos="zoom-in" data-aos-duration="1000">Produk yang kita hasilkan dan terbukti sudah digunakan oleh perusahaan yang mempercayai kami.</div>
				</div>
			</header>
			<div class="row" id="rowAwal-al">
				<div class="col-md-4 col-sm-12" data-aos="fade-right" data-aos-duration="1000"><img id="gambarKiri-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/Investasi Dash Demo.png" alt=""></div>
				<div class="col-md-8" id="kontenKanan-al">
					<div class="row" id="forWeb-al">
						<div class="col-md-3 rapihin-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/general accounting.svg" alt=""><br>General Accounting</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for insurance.svg" alt=""><br>Accounting for Insurance</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="2000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for retail.svg" alt=""><br>Accounting for Retail</div>
					</div>
					<div class="row marginRow-al" id="forWeb-al">
						<div class="col-md-3 rapihin-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for invesment.svg" alt=""><br>Accounting for Invesment</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="2000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for school.svg" alt=""><br>Accounting for School</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="2500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for university.svg" alt=""><br>Accounting for University</div>
					</div>
				</div>
				<div class="col-md-8 kontenMobile-al" id="forMobile-al">
					<div class="container">
						<div class="row">
							<div class="col-md-3 my-4 rapihin-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/general accounting.svg" alt=""><br>General Accounting</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for insurance.svg" alt=""><br>Accounting for Insurance</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1250"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for retail.svg" alt=""><br>Accounting for Retail</div>
							<div class="col-md-3 my-4 rapihin-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1250"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for invesment.svg" alt=""><br>Accounting for Invesment</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for school.svg" alt=""><br>Accounting for School</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for university.svg" alt=""><br>Accounting for University</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="row2-al">
				<div class="col mt-5 mb-4" id="forMobile-al" data-aos="fade-left" data-aos-duration="1000"><img width="290px" style="margin-left: 20px;" src="<?= $path ?>/AssetProdukPerusahaan/Produk/smartphone accounting SAKU.png" alt=""></div>
				<div class="col-md-8" id="kontenKanan-al">
					<div class="row" id="forWeb-al">
						<div class="col-md-3 rapihin2-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="2000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/budgeting.svg" alt=""><br>Budget, Planning and Monitoring</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for cooperative.svg" alt=""><br>Accounting for Cooperative</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for project.svg" alt=""><br>Accounting for Project</div>
					</div>	
					<div class="row marginRow-al" id="forWeb-al">
						<div class="col-md-3 rapihin2-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="2500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/asset management.svg" alt=""><br>Asset Management</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="2000"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/logistic.svg" alt=""><br>Logistic</div>
						<div class="col-md-3 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/POS.svg" alt=""><br>POS</div>
					</div>
				</div>
				<div class="col-md-8 kontenMobile-al" id="forMobile-al">
					<div class="container">
						<div class="row">
							<div class="col-md-3 my-4 rapihin2-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/budgeting.svg" alt=""><br>Budget, Planning and Monitoring</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for cooperative.svg" alt=""><br>Accounting for Cooperative</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/accounting for project.svg" alt=""><br>Accounting for Project</div>
							<div class="col-md-3 my-4 rapihin2-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/asset management.svg" alt=""><br>Asset Management</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-right" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/logistic.svg" alt=""><br>Logistic</div>
							<div class="col-md-3 my-4 rapih-al" id="isiKanan-al" data-aos="fade-left" data-aos-duration="1500"><img id="gambarKonten-al" src="<?= $path ?>/AssetProdukPerusahaan/Produk/POS.svg" alt=""><br>POS</div>
						</div>
					</div>
				</div>
				<div class="col-md-4" id="forWeb-al" data-aos="fade-left" data-aos-duration="1000"><img width="500px" style="margin-left: -20px;" src="<?= $path ?>/AssetProdukPerusahaan/Produk/smartphone accounting SAKU.png" alt=""></div>
			</div>
		</div>
		<hr>
		<div class="container" style="text-align: center;">
			<div class="row">
				<div class="col" id="mudahUntuk-al" data-aos="zoom-in" data-aos-duration="1000">Mudah Untuk Menyesuaikan.</div>
			</div>
			<div class="row">
				<div class="col" id="produkYang-al" data-aos="zoom-out-up" data-aos-duration="1000">Produk yang kami punya juga dapat menyesuaikan dengan bisnis anda. Jika Anda merasa bingung akan memulai dari mana,tim kami siap membantu. <hr></div>
			</div>
		</div>
		<div onClick="window.open('https://wa.me/6281338477765','new_window');">
            <img class="chat" src="<?= $path ?>/berita/wa.svg">
        </div>

		<!-- Footer -->
		<?php 
		include 'footer-al.php';
		 ?>
		<!-- End of Footer -->


		<!-- Optional JavaScript -->
		<script src="<?= $path3 ?>/produk.js"></script>
		<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script>
			AOS.init();
			// 375x812
			function buatHp() {
				// var element = document.getElementById('')
				if (screen.width < 500) {
					// alert('hai al');
				}
			}
			// buatHp();
		</script>
	</body>
</html>