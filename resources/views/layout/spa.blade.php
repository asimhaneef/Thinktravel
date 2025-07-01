<!DOCTYPE html>
<html lang="en" class="wide wow-animation smoothscroll scrollTo">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="keywords" content="intense web design multipurpose template">
    <title>Think Travel</title>
    <link rel="icon" href="{{asset(config('constants.IMAGES_PATH').'/fav.png')}}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic%7CMontserrat:400,700">
    <link href="{{ asset(config('constants.ASSETS_PATH') . '/webPages.css') }}" rel="stylesheet" />
    
    @vite(['resources/js/core.min.js'])
    @vite(['resources/css/web.css', 'resources/js/web.js'])
    @vite(['resources/css/jquery-ui.css'])
    @vite(['resources/js/jquery-1.12.4.js'])
    @vite(['resources/js/jquery-ui.js'])
    @vite(['resources/js/script.js'])
</head>

<body>
    <div id="app">
        <div class="page text-center">
            <header class="page-head">
        <!-- RD Navbar Top Panel-->
        <div class="rd-navbar-wrap" style="height: 192px;">
          <nav data-md-device-layout="rd-navbar-fixed" data-lg-device-layout="rd-navbar-static" data-md-stick-up-offset="100px" data-lg-stick-up-offset="110px" data-auto-height="false" class="rd-navbar rd-navbar-top-panel rd-navbar-default rd-navbar-white rd-navbar-static" data-lg-auto-height="true" data-md-layout="rd-navbar-fullwidth" data-lg-layout="rd-navbar-static" data-lg-stick-up="true">
            <div class="rd-navbar-inner">
              <!-- RD Navbar Top Panel-->
              <div class="rd-navbar-top-panel bg-ebony-clay">
                <div class="left-side">
                  <!-- Contact Info-->
                  <address class="contact-info text-left">
                    <div class="reveal-inline-block">
                        <router-link to="/contact" class="unit unit-middle unit-horizontal unit-spacing-xs">
                            <span class="unit-left"><span class="icon icon-xxs icon-warning icon-circle mdi mdi-phone"></span></span><span class="unit-body"><span class="text-alto">403 273 5132</span></span>
                        </router-link>
                    </div>
                    <div class="reveal-inline-block">
                        <router-link to="/contact" class="unit unit-middle unit-horizontal unit-spacing-xs">
                            <span class="unit-left"><span class="icon icon-xxs icon-warning icon-circle mdi mdi-map-marker"></span></span><span class="unit-body"><span class="text-alto">Marlborough Mall</span></span>
                        </router-link>
                    </div>
                  </address>
                </div>
                <div class="right-side">
                  
				  
				  
								  
				  <div class="reveal-inline-block text-middle">
					<a href="login" class="unit unit-middle unit-horizontal unit-spacing-xs">
						<span class="unit-left text-middle">
							<span class="icon icon-xxs icon-warning icon-circle mdi fa-user"></span>
						</span>
						<span class="unit-body">
							<span class="text-warning">Agent Login</span>
						</span>
					</a>
					</div>
				  
				  
                  
				  <div class="reveal-inline-block text-middle">
                    <ul class="list-inline list-inline-0">
						<li><a href="https://facebook.com/ThinkTravelCanada" target="_BLANK" class="icon icon-xxs icon-warning icon-circle mdi mdi-facebook"></a></li><li><a href="https://instagram.com/think_travel101?utm_source=ig_profile_share&amp;igshid=1kllpluar2z7b" target="_BLANK" class="icon icon-xxs icon-warning icon-circle mdi mdi-instagram"></a></li>                    </ul>
                  </div>
                </div>
              </div>
              <!-- RD Navbar Panel-->
              <div class="rd-navbar-panel">
                <!-- RD Navbar Toggle-->
                <button data-rd-navbar-toggle=".rd-navbar, .rd-navbar-nav-wrap" class="rd-navbar-toggle"><span></span></button>
                <!-- RD Navbar Top Panel Toggle-->
                <button data-rd-navbar-toggle=".rd-navbar, .rd-navbar-top-panel" class="rd-navbar-top-panel-toggle"><span></span></button>
                <!-- Navbar Brand-->			   
                <router-link to="/" class="navbar-brand">
                    <img id="my_logo22" src="images/logo_sml.jpg" alt="">
                </router-link>
			   
              </div>
			  
			<div class="rd-navbar-menu-wrap">
                <div class="rd-navbar-nav-wrap">
                  <div class="rd-navbar-mobile-scroll">
                    <!-- Navbar Brand Mobile-->
                    <div class="rd-navbar-mobile-brand"><a href="index-3.html"><img width="70" height="30" src="images/logo_sml.jpg" alt=""></a></div>
                    <div class="form-search-wrap">
                      <!-- RD Search Form-->
                      <form action="https://www.thinktravel.ca/search-results.html" method="GET" class="form-search rd-search">
                        <div class="form-group">
                          <label for="rd-navbar-form-search-widget" class="form-label form-search-label form-label-sm rd-input-label">Search</label>
                          <input id="rd-navbar-form-search-widget" type="text" name="s" autocomplete="off" class="form-search-input input-sm form-control form-control-gray-lightest input-sm">
                        </div>
                        <button type="submit" class="form-search-submit"><span class="fa fa-search"></span></button>
                      </form>
                    </div>
                    <!-- RD Navbar Nav-->
                    <ul class="rd-navbar-nav">
                        <li class="active">
					        <router-link to="/" class="nav-link">
                                <i class="pi pi-user nav-icon"></i>
                                <p>Home</p>
                            </router-link>
                        </li>
					
					    <li>
                            <router-link to="/inquiry" class="nav-link">
                                <i class="pi pi-user nav-icon"></i>
                                <p>Inquiry</p>
                            </router-link>
                        </li>
					  
					    <li>
                            <router-link to="/documents" class="nav-link">
                                <i class="pi pi-user nav-icon"></i>
                                <p>Document</p>
                            </router-link>
                        </li>
					  
                        <li>
                            <router-link to="/contact" class="nav-link">
                                <i class="pi pi-user nav-icon"></i>
                                <p>Contact Us</p>
                            </router-link>
                        </li>
                    </ul>
                  </div>
                </div>
                
				<!-- RD Navbar Search-->
                <!--div class="rd-navbar-search rd-navbar-search-top-panel"><a data-rd-navbar-toggle=".rd-navbar-inner,.rd-navbar-search" href="#" class="rd-navbar-search-toggle mdi"><span></span></a>
                  <form action="search-results.html" data-search-live="rd-search-results-live" method="GET" class="rd-navbar-search-form search-form-icon-right rd-search">
                    <div class="form-group">
                      <label for="rd-navbar-search-form-input" class="form-label rd-input-label">Type and hit enter...</label>
                      <input id="rd-navbar-search-form-input" type="text" name="s" autocomplete="off" class="rd-navbar-search-form-input form-control form-control-gray-lightest"/>
                    </div>
                    <div id="rd-search-results-live" class="rd-search-results-live"></div>
                  </form>
                </div-->
				
              </div>			  
			  
            </div>
          </nav>
		  
        </div>
		
		
        
      </header>
            <main class="page-content">
            <router-view></router-view>
            </main>
            <!-- Footer Default-->
            <footer class="section-relative section-90 page-footer bg-ebony-clay context-dark" style="background-color:#2c343d;">
                <div class="shell-wide">
                <div class="range range-xs-center range-xl-justify text-lg-left">
                    
                    <div class="cell-xs-8 cell-sm-5 cell-md-3 cell-lg-3 cell-xl-2 cell-sm-push-2">
                    <h4>Contact Us</h4>
                    <div class="text-subline"></div>
                    <!-- Contact Info-->
                    <address class="contact-info text-left offset-top-20">
                        <div class="reveal-inline-block"><a href="contactus.html" class="unit unit-middle unit-horizontal unit-spacing-xs"><span class="unit-left"><span class="icon icon-xxs icon-warning icon-circle mdi mdi-phone"></span></span><span class="unit-body"><span class="text-alto">403 273 5132</span></span></a></div><br>
                        <!--div class="reveal-inline-block"><a href="mailto:info@thinktravel.ca" class="unit unit-middle unit-horizontal unit-spacing-xs offset-top-18"><span class="unit-left"><span class="icon icon-xxs icon-warning icon-circle mdi mdi-email-outline"></span></span><span class="unit-body"><span class="text-alto">< ?php echo $RECEIVING_EMAIL; ?></span></span></a></div><br-->
                        <div class="reveal-inline-block"><a href="contactus.html" class="unit unit-horizontal unit-spacing-xs offset-top-18"><span class="unit-left"><span class="icon icon-xxs icon-warning icon-circle mdi mdi-map-marker"></span></span><span class="unit-body"><span class="text-alto">Marlborrough Mall, 1503 3800 Memorial Dr NE, Calgary, Alberta, T2A 2K2 <br class="veil reveal-lg-inline-block"> </span></span></a></div>
                        <div class="reveal-inline-block"><a href="contactus.html" class="unit unit-horizontal unit-spacing-xs offset-top-18"><span class="unit-left"><span class="icon icon-xxs icon-warning icon-circle mdi mdi-map-marker"></span></span><span class="unit-body"><span class="text-alto">Sunridge Mall, 159A, 2525 36 Street NE, Calgary, AB T1Y 5T4 <br class="veil reveal-lg-inline-block"> </span></span></a></div>
                    </address>
                    </div>
                    
                    <!--div class="cell-xs-10 cell-sm-8 cell-md-5 cell-lg-3 cell-xl-3 offset-top-66 offset-lg-top-0 cell-sm-push-3">
                    <h4>Newsletter</h4>
                    <div class="text-subline"></div>
                    <p class="text-alto">Enter your email address to get the latest traveling news, special offers and other discount information delivered right to your inbox.</p>
                    <div class="offset-top-30">
                            <form data-form-output="form-subscribe-footer" data-form-type="subscribe" method="post" action="bat/rd-mailform.php" class="rd-mailform rd-mailform-subscribe">
                                <div class="form-group">
                                <div class="input-group">
                                    <input placeholder="Enter your e-mail" type="email" name="email" class="form-control"><span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary">Subscribe</button></span>
                                </div>
                                </div>
                                <div id="form-subscribe-footer" class="form-output"></div>
                            </form>
                    </div>
                    </div-->
                    
                    
                    <div class="cell-xs-8 cell-sm-5 cell-md-3 cell-lg-3 cell-xl-2 offset-top-66 offset-lg-top-0 cell-sm-push-4">
                    <h4>@ThinkTravel</h4>
                    <div class="text-subline"></div>
                    <div class="offset-top-20">
                        <a ><img width="82" height="82"  src="images/footer_gallery/28387005085_f40c1f39a3_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/27771103363_75a7bcdef0_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/28308261231_ace1c0244e_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/28308262691_f0d1b61f9e_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/28387002665_51543e6fed_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/28387004745_1e272e2c97_q.jpg" alt="" ></a>
                        </div>  
                    </div>
                    
                    
                    <div class="cell-xs-10 cell-sm-6 cell-md-3 cell-lg-3 cell-xl-2 offset-top-26 offset-sm-top-0">
                    <!-- Footer brand-->
                    <div class="footer-brand">
                        <router-link to="/" class="icon icon-xxs icon-warning icon-circle mdi mdi-phone">
                            <img width='120' height='50' src='images/logo_sml.jpg' alt=''/>
                        </router-link>
                        <p class="no-md-wrap text-dark offset-xl-top-10">&copy; <span id="copyright-year"></span>. All rights reserved. </p> 
                    </div>
                    <ul class="list-inline list-inline-2 reveal-inline-block offset-top-10">
                        <!--li><a href="#" class="icon icon-xxs icon-boulder-filled icon-circle fa fa-facebook"></a></li>
                        <li><a href="#" class="icon icon-xxs icon-boulder-filled icon-circle fa fa-twitter"></a></li>
                        <li><a href="#" class="icon icon-xxs icon-boulder-filled icon-circle fa fa-google-plus"></a></li>
                        <li><a href="#" class="icon icon-xxs icon-boulder-filled icon-circle fa fa-instagram"></a></li>
                        <li><a href="#" class="icon icon-xxs icon-boulder-filled icon-circle fa fa-rss"></a></li-->
                        
                        <li class="social-space"><a href="https://facebook.com/ThinkTravelCanada" target="_BLANK" class="icon icon-xxs icon-warning icon-circle mdi mdi-facebook"></a></li><li><a href="https://instagram.com/think_travel101?utm_source=ig_profile_share&amp;igshid=1kllpluar2z7b" target="_BLANK" class="icon icon-xxs icon-warning icon-circle mdi mdi-instagram"></a></li>				
                    </ul>
                    <p class="no-md-wrap text-dark">
                    <a href="termsConditions.html">Terms and Conditions</a><br>
                    <a href="privacy.html">Privacy Policy</a><br>
                    <!-- <a href="termsofuse.php">Terms of use</a><br> -->
                    <span id="copyright-year"></span>Developed by  <a href="http://threeapples.ca/" target="_BLANK">Three Apples Systems Inc.</a>
                    </p>
                    </div>
                    
                    <div class="cell-xs-8 cell-sm-5 cell-md-3 cell-lg-3 cell-xl-2 offset-top-66 offset-lg-top-0 cell-sm-push-4">
                    <h4>Member of</h4>
                    <div class="text-subline"></div>
                    <div class="offset-top-20">
                        <a ><img width="182" height="82"  src="images/ensemble.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/iata_logo.jpg" alt="" ></a>
                        <!--a ><img width="82" height="82"  src="images/footer_gallery/28308261231_ace1c0244e_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/28308262691_f0d1b61f9e_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/28387002665_51543e6fed_q.jpg" alt="" ></a>
                        <a ><img width="82" height="82"  src="images/footer_gallery/28387004745_1e272e2c97_q.jpg" alt="" ></a-->
                        </div>  
                    </div>
                    
                </div>
                
                
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
