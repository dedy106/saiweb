<?php 
$root_app="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja";
$fitur="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/fitur";
$produk="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/produk";
$tentang="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/tentang";
 ?>

	<!-- Footer -->
		<footer id="footer" style="font-size: 13px;">
			<div class="container-fluid pb-3" id="footer-geser" style="background-color: #eaeaec;">
					<div class="row pt-4">
						<div class="col-12 col-sm-2 col-md-2 col-lg-1" id="div-img-footer-asli">
							<img src="img/esaku-landscape.png" id="img-footer">
						</div>
						<div class="col-5 col-sm-3 col-md-3 offset-md-1 col-lg-2" id="div-shortcut-footer">
							<div class="footer-title">
								<b>Perusahaan</b>
							</div>
							<div class="footer-content">
								<ul class="list-unstyled">
									<li>
										<a href="<?= $tentang  ?>">Tentang Saku</a>
									</li>
									<li>
										<a href="#">Harga</a>
									</li>
									<li>
										<a href="#">Karir</a>
									</li>
									<!-- <li>
										<a href="<?= $fitur ?>">Fitur</a>
									</li> -->
									<li>
										<a href="#">Blog</a>
									</li>
									<li>
										<a href="#">Kerjasama</a>
									</li>
									<li>
										<a href="#">Magang</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-5 col-sm-3 col-md-3 col-lg-2" id="div-shortcut-footer">
							<div class="footer-title">
								<b>SAKU Store</b>
							</div>
							<div class="footer-content">
								<ul class="list-unstyled">
									<li>
										<a href="#">Akuntansi & Keuangan</a>
									</li>
									<li>
										<a href="#">Sekolah</a>
									</li>
									<li>
										<a href="#">POS</a>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-12 col-sm-2 col-md-2 col-lg-3">
							<div class="footer-title">
								<b>Hubungi Kami</b>
							</div>
							<div class="footer-content">
								<ul class="list-unstyled">
									<li>
										<i class="fas fa-envelope fa-lg mr-2" style="color:#4d84e1;"></i> info@saku.id
									</li>
									<li>
										<i class="fas fa-phone-alt fa-lg mr-2" style="color:#4d84e1;"></i> 08123456789
									</li>
									<li class="text-justify">
										<div class="row">
											<div class="col-1">
												<a href="https://goo.gl/maps/Sh71YVReq5pFCQin9" target="_blank">
													<i class="fas fa-map-marker-alt fa-lg ml-1" style="color:#4d84e1;"></i>
												</a>
											</div>
											<div class="col-10">
												<div class="text-justify">
												<a href="https://goo.gl/maps/Sh71YVReq5pFCQin9" target="_blank">
												Jalan Raya Bojongsoang
												Pesona Bali Residence Blok D4/7
												Bojongsoang, Kab. Bandung 40288		
												</a>
												</div>
											</div>
										</div>
										
									</li>
								</ul>
							</div>
						</div>
						<div class="col-5 offset-1 col-sm-2 col-md-2 col-lg-2 ">
							<div class="footer-title">
								<!-- <b>Available in:</b> -->
								<ul class="list-unstyled">
									<li>
										<a href="https://play.google.com" target="_blank">
											<img src="img/google-play.png" class="content-zoom" style="margin-left: -15px;" width="130px">
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="row mt-5">
						<div class="col-md-2"></div>
						<div class="col-md-4">© 2019 PT Samudra Aplikasi Indonesia</div>
						<div class="col-md-2"><a href="#" class="privasi">Kebijakan Privasi</a></div>
						<div class="col-md-2">
							<?php 
                                // Program to display current page URL. 
                                $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                                "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                                $_SERVER['REQUEST_URI'];  
                            ?>
							Bagikan: <a href="http://www.facebook.com/sharer.php?u=<?= $link ?>" target="_blank"><img class="mx-1" src="img/berbagi/facebook.svg" width="20px" alt=""></a> <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Coba cek link ini deh <?= $link ?>" target="_blank" data-size="large"><img class="mx-1" src="img/berbagi/twitter.svg" width="20px" alt=""></a> <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $link ?>&title=Create LinkedIn Share button on Website Webpages" target="_blank"><img class="mx-1" src="img/berbagi/linkedin.svg" width="20px" alt=""></a> <img class="mx-1" style="cursor: pointer;" src="img/berbagi/share.svg" width="20px" alt="" onclick="copyToClipboard('#p1')">
							<p id="p1"><?= $link ?></p>
						</div>
					</div>
					<div class="col-md-12" id="div-img-footer-bawah">
						<img src="img/esaku-landscape.png" id="img-footer-bawah">&nbsp;&nbsp;&nbsp;© Copyright 2019 PT Samudra Aplikasi Indonesia
					</div>
			</div>
		</footer>
<!-- End of Footer -->