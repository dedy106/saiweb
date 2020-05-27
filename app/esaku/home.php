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
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
		<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
		<!-- <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<!-- <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> -->
		<link href="<?= $path2 ?>/index.css" rel="stylesheet">
		<script src="<?= $path3 ?>/velocity.js"></script>
		<script src="https://kit.fontawesome.com/6c071091af.js"></script>
		<title>SAKU | Sistem Akuntansi Keuangan Terpadu</title>
		<style>
			@font-face {
				font-family: "Font Mas Afnan";
				src: url('<?= $path4 ?>/SF-Pro-Text-Regular.otf');
			}

			body {
				font-family: 'Font Mas Afnan';
			}
			
			/* .logo-img {
				fill: red;
			} */

			.icon {
				display: inline-block;
				width: 70px;
				height: 70px;
				background-size: cover;
			}

			.icon-target {
				background-image: url(<?= $path ?>/target.svg);
				filter: invert(50%) sepia(25%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);
			}

			.icon-target:hover,
			.icon-target:focus {
				/* background-color: red; */
				/* filter:red; */
				filter: invert(0) sepia(0) saturate(0) hue-rotate(0) brightness(0) contrast(0);
			}
		</style>
		<link rel="icon" type="image/png" href="<?= $path ?>/saku.png" sizes="32x32">
	</head>
	<!-- <script src="//code.jivosite.com/widget.js" data-jv-id="ayNQAsT3Iz" async></script> -->
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
		<!-- Header -->
		<header class="wrapper mb-2">
			<!-- <div class="sliding-background">
				
			</div>
			<div class="myslide">

			</div> -->
			<div class="content">
				<div class="row align-items-center mr-0 mb-3" >
					<div class="col-lg-12 text-center ml-0" style="margin-top: 100px;">
						<div class="header-fadeIn">
							<div class="header-title">
								<div class="saku-header-title">Tinggalkan pencatatan akuntansi manual</div>
								<div class="desc">Mulai gunakan software aplikasi keuangan online, SAKU membantu dalam mengatur <br> pencatatan sehingga menghasilkan laporan yang realtime dan akurat.</div>
								<!-- <h1 class="display-4 saku-header-title">SsssAKU</h1>
								<small>Sistem Akuntansi Keuangan Terpadu</small> -->
							</div>
							<button class="btn btn-saku">
								<i class="fas fa-play-circle"></i> Demo
							</button>
						</div>
					</div>
				</div>
				<div class="row align-items-center" data-aos="zoom-in" data-aos-offset="200" data-aos-delay="50" data-aos-duration="1500">
					<div class="col-lg-12 text-center">
						<div class="header-img">
							<img src="<?= $path ?>/DashSAKU.png" width="100%">
						</div>
					</div>
				</div>
			</div>
			
		
			
		</header>
		<!-- End of Header -->

		<!-- Intro -->
		<section id="intro">
			<div class="container">
				<div class="row text-center">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12">
						<div class="saku-intro-title intro-easeUp">
							<h5>
								<b style="color: #5084da">Percayakan pencatatan bisnis Anda dengan SAKU.</b>
							</h5>
						</div>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-12 col-sm-12 col-md-4 col-lg-4">
						<div class="intro-easeUp intro-items">
							<div class="saku-intro-img">
								<img src="<?= $path ?>/userfriendly.svg" width="75px">
							</div>
							<div class="saku-intro-subtitle">
								Mudah Digunakan
							</div>
							<div class="saku-intro-content">
								<small>
								Antarmuka yang dirancang agar user lebih cepat adaptasi dan mempercepat proses melakukan transaksi.
								</small>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-4 col-lg-4">
						<div class="intro-easeUp intro-items">
							<div class="saku-intro-img">
								<!-- <img src="img/target.svg" class="logo-img" width="75px"> -->
								<!-- <svg class="logo-img">
									<use xlink:href="img/target.svg">
								</svg> -->
								<span class="icon icon-target"></span>
							</div>
							<div class="saku-intro-subtitle">
								Buat Keputusan Bisnis
							</div>
							<div class="saku-intro-content">
								<small>
								Sajian dashboard yang memberikan rangkuman transaksi bisnis anda.
								</small>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-4 col-lg-4">
						<div class="intro-easeUp intro-items">
							<div class="saku-intro-img ">
								<img src="<?= $path ?>/support.svg" width="75px">
							</div>
							<div class="saku-intro-subtitle">
								Dukungan Penuh
							</div>
							<div class="saku-intro-content">
								<small>
								Dukungan dari mulai penggunaan aplikasi sampai perkembangan aplikasi lebih lanjut.
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End of Intro -->

		<!-- Banner -->
		<section id="banner">
			<div class="jumbotron pb-0 mb-0" style="background: #E9ECEF">
				<div class="container">
					<div class="row align-items-center justify-content-center">
						<div class="col-md-4 text-center">
							<div class="banner-slideRight">
								<img src="<?= $path ?>/work.jpg" width="100%">
							</div>
						</div>
						<div class="col-md-8">
							<div class="banner-slideLeft pb-3">
								<h3><b>
								Kita bisa membicarakan kebutuhan yang Anda inginkan.</b>
								</h3>
								<p>
								SAKU aplikasi keuangan yang fleksibel, kami dapat membangun sistem mengikuti proses bisnis yang ada di perusahaan Anda. Tim kami siap membantu dengan langkah-langkah yang kita susun dan sepakati bersama.
								</p>
								<button onClick="window.open('https://wa.me/6281338477765','new_window');" class="btn px-4 mt-2" id="btn-konsul">Konsultasi Gratis</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End of Banner -->

		<!-- Fitur -->
		<section id="fitur" name="fitur">
		<div class="container">
				<div class="row">
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/GoMobile.svg" width="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6 class="mt-2"><b>Go Mobile</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Lihat laporan bisnis tanpa harus mencetaknya, cukup di smartphone.
								</small>
							</div>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/MultiCurrency.svg" height="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6><b>Multi Currency</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Mengelola pencatatan dalam beragam mata uang.
								</small>
							</div>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/Reconsiliation.svg" width="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6><b>Bank Reconsiliation</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Pencocokan nilai saldo bank dan saldo buku besar secara otomatis.
								</small>
							</div>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/UserH.svg" height="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6><b>User Hierarchy</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Hak akses user aman sesuai hak akses yang dibutuhkan.
								</small>
							</div>
						</div>
					</div>
				</div>
				<div class="row align-items-center">
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/tracking.svg" height="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6><b>Tracking Transaction</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Telusuri nilai laporan keuangan sampai ke detail jurnal.
								</small>
							</div>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/anywhere.svg" height="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6><b>Run Anywhere</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Pantau keuangan perusahaan tanpa harus duduk di belakang meja kerja.
								</small>
							</div>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/secure.svg" height="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6><b>Secure Data</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Kami berkomitmen untuk selalu mengamankan setiap transaksi bisnis.
								</small>
							</div>
						</div>
					</div>
					<div class="col-6 col-sm-6 col-md-3 col-lg-3 text-center">
						<div class="fitur-easeUp">
							<div class="saku-fitur-img">
								<img src="<?= $path ?>/fitur/smartJ.svg" height="100%">
							</div>
							<div class="saku-fitur-subtitle">
								<h6><b>Smart Journal</b></h6>
							</div>
							<div class="saku-fitur-content">
								<small>
								Tidak perlu khawatir jika anda tidak memahami ilmu Akuntansi.
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End of Fitur -->

		<!-- Sekilas -->
		<section id="sekilas">
			<div class="jumbotron mb-0 mt-0" style="background: #E9ECEF">
				<div class="sekilas-fadeIn">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
							<div class="saku-banner-title">
								<h3>
									<b>Sekilas SAKU</b>
								</h3>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
							<!-- Credit Slider -->
							<div class="slide" id="js-slide">
							    <div class="slideWrap">
							      <div class="slideMain" id="js-slideMain">
							        <div class="slideContent" id="js-slideContent">
							          <ul class="slideGroup js-slideGroup">
							            <li class="slideChild js-slideChild"><img src="<?= $path ?>/slide1.jpg" alt="" class="img-rsp">
							            	<div class="slide-title mt-5">
							            		<h3>Dashboard</h3>
							            	</div>
							            </li>
							            <li class="slideChild js-slideChild"><img src="<?= $path ?>/slide2.png" alt="" class="img-rsp">
							            	<div class="slide-title mt-5">
							            		<h3>Apa aja</h3>
							            	</div>
							            </li>
							            <li class="slideChild js-slideChild"><img src="<?= $path ?>/slide2.png" alt="" class="img-rsp2">
							            	<div class="slide-title mt-5">
							            		<h3>Ini Contoh</h3>
							            	</div>
							            </li>
							          </ul>
							        </div>
							      </div>
							    </div>
							  </div>
							<!-- https://www.jqueryscript.net/slider/touch-carousel-velocity.html -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 text-center">
							<div class="saku-banner-subtitle">
								<h3>
									<!-- Dashboard -->
								</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End of Sekilas -->

		<!-- Perusahaan -->
		<section id="perusahaan">
			<div class="container">
				<div class="row perusahaan align-items-center">
					<div class="col-12 col-sm-12 col-md-6 col-lg-6 pr-0 mr-0">
						<div class="perusahaan-slideRight">
							<div class="perusahaan-title">
								<h3>
									<b>Perusahaan yang mempercayai SAKU</b>
								</h3>
							</div>
							<div class="perusahaan-subtitle">
							<p class="lead">
									<small>
										Pengalaman kami lebih dari 20 tahun membangun sistem keuangan, banyak perusahaan skala UMKM sampai skala grup bisnis percaya dengan pelayanan yang sudah kami berikan.
									</small>
								</p>
							</div>
							<div class="link">
								<a class="mt-1" href="#" target="_blank" id="btn-klien">Lihat Klien Kami&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></a>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6 col-lg-6 mr-0 pr-0 ml-0 pl-0">
						<div class="perusahaan-slideLeft">
							<div class="container mt-3 mb-3" style="background: #FFF">
								<div class="row my-5">
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-1"><p style="position: absolute; top: 50%; left: 55%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/Telkom Indonesia.png" id="gambar1-1" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-1"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/PT Telkom Prima Citra Certifia - TPCC.png" id="gambar1-2" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-1"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/PT Sigma Metrasys Solution.png" id="gambar1-3" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-1"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/Sandhy PutraMakmur - SPM.png" id="gambar1-4" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-1"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/Koperasi Saraswati.png" id="gambar1-5" alt=""></p></div>
								</div>
								<div class="row my-5">
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-2"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 2/Yayasan Pendidikan Telkom - YPT.png" id="gambar2-1" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-2"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 2/Yayasan Kesehatan Telkom - YAKES.png" id="gambar2-2" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-2"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 2/Telkom Medika.png" id="gambar2-3" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-2"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 2/Sarana Janesia Utama - SJU.png" id="gambar2-4" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-2"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 2/PT Bhakti Unggul Teknovasi - BUT.png" id="gambar2-5" alt=""></p></div>
								</div>
								<div class="row my-5">
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-3"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 3/Telkom University.png" id="gambar3-1" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-3"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 3/Telkom School.png" id="gambar3-2" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-3"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 3/Trengginas Jaya - TRENGGINAS.png" id="gambar3-3" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-3"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 3/Graha Informatika Nusantara - GRATIKA.png" id="gambar3-4" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin" id="baris-3"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 2/Yayasan Taruna Bakti - TARBAk.png" id="gambar3-5" alt=""></p></div>
								</div>
								<div class="row my-5">
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 2/K-Lite.png" id="gambar4-1" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/PT Fokus Bina Nusa.png" id="gambar4-2" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/Asosiasi Perusahaan Pialang Asuransi dan Reasuransi Indonesia - APARINDO.png" id="gambar4-3" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/Citra Sinergi Multimedia - CSM.png" id="gambar4-4" alt=""></p></div>
									<div class="col-2 col-sm-2 col-md-2 col-ld-2 buat-margin"><p style="position: absolute; top: 50%; left: 50%; -ms-transform: translate(-50%, -50%); transform: translate(-50%, -50%);"><img src="<?= $path ?>/Kelompok 1/zora.png" id="gambar4-5" alt=""></p></div>
								</div>
							</div>		
						</div>
					</div>
				</div>
			</div>
			<div onClick="window.open('https://wa.me/6281338477765','new_window');">
                <img class="chat" src="<?= $path ?>/berita/wa.svg">
            </div>
		</section>
		<!-- End of Perusahaan -->


		<?php 
			include 'footer-al.php';
		?>

		<!-- Footer -->
		<?php 
		// include 'footer.php';
		 ?>
<!-- End of Footer -->


		<!-- Optional JavaScript -->
		<script src="<?= $path3 ?>/index.js"></script>
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<!-- <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script> -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<!-- <script type="text/javascript">
		var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || 
		{widgetcode:"c411397492439e01cc1325aeaf7fb78b71f0861e8d9fee01c5b22603a90b9c9547fa19f029362b0f0405fd92e31cc4f1", values:{},ready:function(){}};
		var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;
		s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);d.write("<div id='zsiqwidget'></div>");
		</script> -->
		<script>
		AOS.init();
		</script>
	</body>
</html>