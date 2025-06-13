<template>
    
        <!-- Swiper-->
    <div data-height="450px" data-min-height="200px" data-slide-effect="fade" class="swiper-container swiper-slider context-white">
        <div class="swiper-wrapper">
            <div data-slide-bg="images/backgrounds/background-01-1920x950.jpg" class="swiper-slide" style="background-image: url(&quot;images/backgrounds/background-01-1920x950.jpg&quot;); background-size: cover; width: 1519px; transform: translate3d(0px, 0px, 0px); transition-duration: 0ms; opacity: 1;"></div>
            <div data-slide-bg="images/backgrounds/background-02-1920x950.jpg" class="swiper-slide" style="background-image: url(&quot;images/backgrounds/background-02-1920x950.jpg&quot;); background-size: cover; width: 1519px; transform: translate3d(0px, 0px, 0px); transition-duration: 0ms; opacity: 1;"></div>
            <div data-slide-bg="images/backgrounds/background-03-1920x950.jpg" class="swiper-slide" style="background-image: url(&quot;images/backgrounds/background-03-1920x950.jpg&quot;); background-size: cover; width: 1519px; transform: translate3d(0px, 0px, 0px); transition-duration: 0ms; opacity: 1;"></div>
        </div>
          <div class="swiper-center-caption">
            <div class="shell-wide">
              <div class="range range-xs-center">
                <div class="cell-sm-10 cell-lg-8 cell-xl-5">
                  <div>
                    <h5 class="reveal-inline-block font-default both-lines text-white text-italic">Welcome to Think Travel</h5>
                  </div>
                  <div class="offset-top-10">
                    <h1 class="text-bold" style="color:#ffa200;">Enjoy The Freedom</h1>
                  </div>
                  <!-- <p class="h5 font-default offset-top-35 veil text-white  reveal-sm-block">
				  Our experienced agents will access multiple suppliers and speciality fares for all your travel needs and provide you the best prices and service for the trip of your choice.
				  </p> -->
                </div>
              </div>
              <div class="range range-xs-center offset-lg-top-30">
                <div class="cell-xs-10 cell-sm-10 cell-md-8 cell-lg-12 cell-xl-8">
				
                  <!-- Panel-->
                  <div class="panel panel-lg bg-overlay-ebony-clay text-lg-center row">
                    <h4 class="text-bold text-orange-peel">Find Your Trip</h4>
					<router-link to="/flights" class="nav-link">                        
                        <div id="span_flights" class="tab-option tab-active col-sm" onclick="open_flight_type('rt','flights')" align="center"><span class="icon fa-plane"></span><br>Flights</div>
                    </router-link>
                    <router-link to="/vacations" class="nav-link">
                        <div id="span_vacations" class="tab-option tab-active col-sm" onclick="open_flight_type('rt','vacations')" align="center" ><span class="icon fa-th-large"  ></span> <br>Vacation Packages</div>
                    </router-link> 
                    <router-link to="/cruises" class="nav-link">
                        <div id="span_cruises" class="tab-option tab-active col-sm" onclick="open_flight_type('rt','cruises')" align="center" ><span class="icon fa-ship"></span><br>Cruises</div>
                    </router-link> 
					
							
					
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Swiper Pagination-->
          <div class="swiper-pagination"></div>
          <!-- Swiper Navigation-->
          <!-- <div class="swiper-button-prev"><span class="icon icon-sm mdi mdi-arrow-left-bold"></span></div>
          <div class="swiper-button-next"><span class="icon icon-sm mdi mdi-arrow-right-bold"></span></div> -->
    </div>
    <section style="background: #282e3c url(images/backgrounds/background-04-642x538.png) repeat;color:white;" class="section-90 section-md-122">
        <div class="shell-wide">
		  
			  <!--div class="cell-sm-10 cell-md-5 cell-xl-4 section-90 section-md-122 context-md-dark bg-image-md-fullwidth bg-image-md-fullwidth-left bg-image-md-fullwidth-atlantis"-->
                <div class="inset-md-right-60">
                  <h2 class="text-uppercase text-bold text-center text-md-left" style="color:white;">about us</h2>
                  <hr class="divider divider-lg hr-md-left-0 bg-warning offset-top-30">
                  <p class="offset-top-30 offset-md-top-63">
						Think Travel is a leading travel agency that offers excellent tour & travel services at affordable prices. Being a recognized travel specialist, we offer a comprehensive range of services that include tours, safaris, cruises, air fares and more. Our services are available for individuals as well as groups, so you can contact us to get the best prices for all your travel needs.
						<br>
						<br>
						If you want to book your trip, we warmly invite you to contact us anytime. We are available 7 days a week to help you with your unique travel needs. For more than 30 years, our agents have been consistently serving our esteemed clients with outstanding and customized tour & travel services. We can say with full confidence that your experience will be both enjoyable and memorable, and will remain with you for a lifetime.
						<br>
				  </p>
                  <!--div class="offset-top-20 offset-md-top-13 text-center text-md-left"><a href="about.html" class="btn btn-orange-peel">learn more</a></div-->
                </div>
              <!--/div-->
			  
		</div>
	</section>
</template>

<script>
export default {
    name: "Inquiry",
};
</script>