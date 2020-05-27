<?php 
	$root_app="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja";
	$fitur="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/fitur";
	$produk="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/produk";
	$tentang="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/tentang";
	$path = "http://".$_SERVER['SERVER_NAME']."/assets/img/esaku";
	$path2 = "http://".$_SERVER['SERVER_NAME']."/assets/css/esaku";
	$path3 = "http://".$_SERVER['SERVER_NAME']."/assets/js/esaku";
?>

	<!-- Footer -->
		<section id="footer-al" style="font-size: 13px; background-color: #eaeaec;">
			<div class="container pb-3">
                    <div class="row pt-4">
                        <div class="col-5 col-sm-3 col-md-3 col-lg-2" id="div-shortcut-footer">
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
									<!-- <li>
										<a href="<?= $fitur ?>">Fitur</a>
									</li> -->
									<li>
										<a href="#">Blog</a>
									</li>
								</ul>
							</div>
                        </div>
                        <div class="col-5 col-sm-3 col-md-3 col-lg-2" id="div-shortcut-footer">
							<div class="footer-title">
								<b>Lowongan</b>
							</div>
							<div class="footer-content">
								<ul class="list-unstyled">
									<li>
										<a href="#">Karir</a>
									</li>
									<li>
										<a href="#">Magang</a>
									</li>
									<li>
										<a href="#">Kerjasama</a>
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
							<div id="crMobile">
								<div class="footer-title mb-1 dd" id="kepalaSatu-al" onclick="kepalaSatu()"><b>Perusahaan<i id="iconSatu" class="fas fa-sort-down" style="float:right"></i></b></div>
								<div class="footer-content" id="kakiSatu-al">
									<ul class="list-unstyled">
										<li class="my-0 pl-1">
											<a href="<?= $tentang  ?>">Tentang Saku</a>
										</li>
										<li class="my-1 pl-1">
											<a href="#">Harga</a>
										</li>
										<li class="my-0 pl-1">
											<a href="#">Blog</a>
										</li>
									</ul>
								</div>
								<div class="footer-title mb-1 dd" id="kepalaDua-al" onclick="kepalaDua()"><b>Lowongan<i id="iconDua" class="fas fa-sort-down" style="float:right"></i></b></div>
								<div class="footer-content" id="kakiDua-al">
									<ul class="list-unstyled">
										<li class="my-0 pl-1">
											<a href="#">Karir</a>
										</li>
										<li class="my-1 pl-1">
											<a href="#">Magang</a>
										</li>
										<li class="my-0 pl-1">
											<a href="#">Kerjasama</a>
										</li>
									</ul>
								</div>
								<div class="footer-title mb-1 dd" id="kepalaTiga-al" onclick="kepalaTiga()"><b>Saku Store<i id="iconTiga" class="fas fa-sort-down" style="float:right"></i></b></div>
								<div class="footer-content" id="kakiTiga-al">
									<ul class="list-unstyled">
										<li class="my-0 pl-1">
											<a href="#">Akuntansi & Keuangan</a>
										</li>
										<li class="my-1 pl-1">
											<a href="#">Sekolah</a>
										</li>
										<li class="my-0 pl-1">
											<a href="#">POS</a>
										</li>
									</ul>
								</div>
							</div>
							<script>
								$('#kakiSatu-al').hide();
								$('#kakiDua-al').hide();
								$('#kakiTiga-al').hide();

								var element;

								function kepalaSatu(){
									element = document.getElementById("iconSatu");
									if($("#kakiSatu-al").is(":visible")){
										$("#kakiSatu-al").hide("500");
										element.classList.add("fa-sort-down");
										element.classList.remove("fa-sort-up");
									}
									else{
										$("#kakiSatu-al").show("500");
										element.classList.remove("fa-sort-down");
										element.classList.add("fa-sort-up");
									}
								}

								function kepalaDua(){
									element = document.getElementById("iconDua");
									if($("#kakiDua-al").is(":visible")){
										$("#kakiDua-al").hide("500");
										element.classList.add("fa-sort-down");
										element.classList.remove("fa-sort-up");
									}
									else{
										$("#kakiDua-al").show("500");
										element.classList.remove("fa-sort-down");
										element.classList.add("fa-sort-up");
									}
								}

								function kepalaTiga(){
									element = document.getElementById("iconTiga");
									if($("#kakiTiga-al").is(":visible")){
										$("#kakiTiga-al").hide("500");
										element.classList.add("fa-sort-down");
										element.classList.remove("fa-sort-up");
									}
									else{
										$("#kakiTiga-al").show("500");
										element.classList.remove("fa-sort-down");
										element.classList.add("fa-sort-up");
									}
								}
							</script>
							<div class="footer-title">
								<b>Hubungi Kami</b>
							</div>
							<div class="footer-content">
								<ul class="list-unstyled">
									<li>
										<i class="fas fa-envelope fa-lg mr-2" style="color:#4d84e1;"></i> dedy@mysai.co.id
									</li>
									<li>
										<i class="fas fa-phone-alt fa-lg mr-2" style="color:#4d84e1;"></i> 082240002911
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
						<div class="col-5 offset-1 col-sm-2 col-md-2 col-lg-2" id="crWeb">
							<div class="footer-title">
								<b>Tersedia di:</b>
								<ul class="list-unstyled">
									<li>
										<a href="https://play.google.com" target="_blank">
											<img src="<?= $path ?>/google-play.png" class="content-zoom" width="130px">
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="row pb-0">
						<div class="col" id="crMobile">
							<div class="footer-title">
								<hr>
								<b>Tersedia di:</b>
								<ul class="list-unstyled">
									<li>
										<a href="https://play.google.com" target="_blank">
											<img src="<?= $path ?>/google-play.png" class="content-zoom" width="130px">
										</a>
									</li>
								</ul>
								<hr>
							</div>
						</div>
					</div>
                    <div class="row" id="crWeb-margin">
						<div class="col-lg-2" id="crWeb"><img src="<?= $path ?>/esaku-landscape.png" style="width: 100px; margin-top: -10px;"></div>
						<div class="col-lg-4" id="crWeb">© 2019 PT Samudra Aplikasi Indonesia</div>
						<div class="col-lg-2" id="crWeb"><a href="#" class="privasi">Kebijakan Privasi</a></div>
						<?php 
                            // Program to display current page URL. 
                            $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 
                            "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .  
                            $_SERVER['REQUEST_URI'];  
                        ?>
						<div class="col-lg-3" id="crWeb">
							Bagikan: <a href="http://www.facebook.com/sharer.php?u=<?= $link ?>" target="_blank"><img class="mx-2" src="<?= $path ?>/berbagi/facebook.svg" id="shareWeb" alt=""></a> <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Coba cek link ini deh <?= $link ?>" target="_blank" data-size="large"><img class="mx-2" src="<?= $path ?>/berbagi/twitter.svg" id="shareWeb" alt=""></a> <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $link ?>&title=Create LinkedIn Share button on Website Webpages" target="_blank"><img class="mx-2" src="<?= $path ?>/berbagi/linkedin.svg" id="shareWeb" alt=""></a> <img class="mx-2" style="cursor: pointer;" src="<?= $path ?>/berbagi/share.svg" id="shareWeb" alt="" onclick="copyToClipboard('#p1')">
							<p id="p1"><?= $link ?></p>
						</div>
					</div>
					<div id="crMobile">
						<div class="row" style="font-size: 8px;">
							<div class="col">© 2019 PT Samudra Aplikasi Indonesia</div>
							<div class="col" style="text-align: right;"><a href="#" class="privasi">Kebijakan Privasi</a></div>
						</div>
						<div class="row" style="font-size: 10px;">
							<div class="col mt-3">
								<a>Bagikan:</a> <a href="http://www.facebook.com/sharer.php?u=<?= $link ?>" target="_blank"><img class="mx-2" src="<?= $path ?>/berbagi/facebook.svg" id="shareWeb" alt=""></a> <a class="twitter-share-button" href="https://twitter.com/intent/tweet?text=Coba cek link ini deh <?= $link ?>" target="_blank" data-size="large"><img class="mx-2" src="<?= $path ?>/berbagi/twitter.svg" id="shareWeb" alt=""></a> <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $link ?>&title=Create LinkedIn Share button on Website Webpages" target="_blank"><img class="mx-2" src="<?= $path ?>/berbagi/linkedin.svg" id="shareWeb" alt=""></a> <img class="mx-2" style="cursor: pointer;" src="<?= $path ?>/berbagi/share.svg" id="shareWeb" alt="" onclick="copyToClipboard('#p1')">
								<!-- <p id="p1"><?= $link ?></p> -->
							</div>
						</div>
					</div>
					<!-- <div class="col-md-12" id="div-img-footer-bawah">
						<img src="img/esaku-landscape.png" id="img-footer-bawah">&nbsp;&nbsp;&nbsp;© Copyright 2019 PT Samudra Aplikasiiiii Indonesia
					</div> -->
            </div>
        </section>
<!-- End of Footer -->