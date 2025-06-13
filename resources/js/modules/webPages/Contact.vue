<template>
    <section class="section-height-700 breadcrumb-modern rd-parallax context-dark bg-gray-darkest image_mobile" style="position: relative; overflow: hidden; z-index: 0; height: 195px;"><div class="rd-parallax-inner" style="position: fixed; top: 0px; margin-top: -60px; padding: 60px 0px; width: 1519px; transform: translate3d(0px, -103.2px, 0px);">
        <div id="contact_bg_image" data-speed="0.2" data-type="media" data-url="images/backgrounds/background-06-1920x950.jpg" class="rd-parallax-layer" style="position: fixed; background-image: url(&quot;images/backgrounds/background-06-1920x950.jpg&quot;); height: 329.4px; transform: translate3d(0px, -39.36px, 0px);"></div>
          <div class="rd-parallax-layer-holder" style="position: relative; height: 195px;">
            <div data-speed="0" data-type="html" class="rd-parallax-layer" style="position: fixed; width: 1519px; transform: translate3d(0px, 0px, 0px);">
                <div class="bg-overlay-lg-darker">
                    <div class="shell section-top-50 section-bottom-34 section-md-top-90 section-lg-bottom-34 section-lg-top-128">
                        <div class="veil reveal-md-block">
                            <h5 class="reveal-inline-block font-default both-lines text-italic">The Best Travel for You</h5>
                        </div>
                        <div class="veil reveal-md-block offset-top-8">
                            <h1 class="text-bold" style="color:#ffa200;display:block;">Contact Us</h1>
                        </div>
                    </div>
                </div>
          </div>
        </div>
    </div>
    </section>
        <section class="section-90 section-md-122 text-sm-left">            
            <div class="shell-wide">
                        <div class="range range-xs-center">
            
                <div class="cell-xs-10 cell-sm-8 cell-md-9 cell-xl-6">
                <div class="inset-lg-right-40">
                    <h2 class="text-bold text-uppercase text-ebony-clay">Get in Touch</h2>
                    <hr class="divider divider-lg hr-sm-left-0 bg-primary">                          
                    <form id="form_contact" @submit.prevent="emailContact()" class="text-left offset-top-18 offset-md-top-30">
                    <div class="range range-xs-center">
                        <div class="cell-sm-6">
                        <div class="form-group form-group-label-outside">
                            <label for="first_name" class="form-label form-label-outside text-dark rd-input-label">First name *</label>
                            <input id="first_name" type="text" v-model="form.first_name" class="form-control" required="">
                        </div>
                        <div class="form-group form-group-label-outside offset-top-20">
                            <label for="contact_email" class="form-label form-label-outside text-dark rd-input-label">E-mail *</label>
                            <input id="contact_email" type="email" v-model="form.contact_email" class="form-control" required="">
                        </div>
                        </div>
                        <div class="cell-sm-6 offset-top-20 offset-sm-top-0">
                        <div class="form-group form-group-label-outside">
                            <label for="last_name" class="form-label form-label-outside text-dark rd-input-label">Last name *</label>
                            <input id="last_name" type="text" v-model="form.last_name" class="form-control" required="">
                        </div>
                        <div class="form-group form-group-label-outside offset-top-20">
                            <label for="contact_phone" class="form-label form-label-outside text-dark rd-input-label">Phone *</label>
                            <input id="contact_phone" type="text" v-model="form.contact_phone" class="form-control" required="">
                        </div>
                        </div> 
                    </div>
                    <div class="form-group form-group-label-outside offset-top-20">
                        <label for="message" class="form-label form-label-outside text-dark rd-input-label">Message *</label>
                        <textarea id="message" v-model="form.message" style="max-height: 150px;" class="form-control" required=""></textarea>
                    </div>
                    <div class="offset-top-18 offset-sm-top-30 text-center text-sm-left">
                        <button type="submit" style="min-width: 130px;" class="btn btn-primary">send</button>
                    </div>
                    
                    <input type="hidden" v-model="form.continue" id="continue" value="Y">
                    <input type="hidden" v-model="form.formname" id="formname" value="contactus">
                    
                    </form>
                </div>
                </div>
                <div class="cell-xs-10 cell-sm-8 cell-md-3 cell-xl-2 offset-top-66 offset-lg-top-0">
                <!-- Sidebar-->
                <aside class="text-left">
                    <div class="inset-md-left-50 inset-lg-left-75">
                    <h4 class="text-bold">Follow Us</h4>
                    <div class="text-subline offset-top-10"></div>
                    
                    <ul class="list-inline list-inline-2 offset-top-18 inset-left-10">
                        
                        <li><a href="https://facebook.com/ThinkTravelCanada" class="icon icon-xxs icon-boulder-filled icon-boulder-filled-inverse icon-circle fa fa-facebook"></a></li><li><a href="https://instagram.com/think_travel101?utm_source=ig_profile_share&amp;igshid=1kllpluar2z7b" class="icon icon-xxs icon-boulder-filled icon-boulder-filled-inverse icon-circle fa fa-instagram"></a></li>				
                    </ul>
                    
                    <div class="offset-top-40 offset-md-top-63">
                        <h4 class="text-bold">Phones</h4>
                    </div>
                    <div class="text-subline offset-top-10"></div>
                    <div class="unit unit-horizontal unit-spacing-xs offset-top-20">
                        <div class="unit-left">
                        <div class="icon icon-xxs icon-warning icon-circle mdi mdi-phone"></div>
                        </div>
                        <div class="unit-body"><a class="text-dark reveal-block">403 273 5132</a></div>
                    </div>
                    <div class="offset-top-40 offset-md-top-63">
                        <h4 class="text-bold">Address</h4>
                    </div>
                    <div class="text-subline offset-top-10"></div>
                    <div class="unit unit-horizontal unit-spacing-xs offset-top-20">
                        <div class="unit-left">
                        <div class="icon icon-xxs icon-warning icon-circle mdi mdi-map-marker"></div>
                        </div>   
                        <div class="unit-body">
                        <a href="#" class="text-dark">
                                    1503 3800 Memorial Dr NE, Calgary, Alberta, T2A 2K2<br>
                        </a>
                        </div>
                    </div>
                    <div class="unit unit-horizontal unit-spacing-xs offset-top-20">
                        <div class="unit-left">
                        <div class="icon icon-xxs icon-warning icon-circle mdi mdi-map-marker"></div>
                        </div>
                        <div class="unit-body"><a href="#" class="text-dark">
                        159A, 2525 36 Street NE, Calgary, AB T1Y 5T4					  <br><br>
                        Mon - Wed: 10:00am - 8:00pm<br>
                        Thu - Fri: 10:00am - 8:00pm<br>
                        Saturday:  10:00am - 5:30pm<br>
                        Sunday: 	 12:00am - 5:00pm<br>

                        </a></div>
                    </div>
                    </div>
                </aside>
                </div>
            </div>
            </div>
        </section>
        <section>
          <!-- RD Google Map-->          
            <div class="mapouter">
				<div class="gmap_canvas">
					<iframe width="1500" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=1503%203800%20Memorial%20Dr%20NE%2C%20Calgary%2C%20Alberta%2C%20T2A%202K2&amp;t=&amp;z=13&amp;ie=UTF8&amp;iwloc=&amp;output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
				</div>
			</div>          
        </section>
</template>

<script>
import Form from 'vform';
import axios from 'axios';
export default {
    name: "About",
    data() {
        return {
            form: new Form({
                formname: '',
                continue: '',
                message: '',
                contact_phone: '',
                contact_email: '',
                last_name: '',
                first_name: '',
            }),
            isSubmitting: false,
        }
    },
    methods: {
        async emailContact() {
            try {
                this.isSubmitting = true;
                const response = await this.form.post('/emailContact');
                this.$toast.add({ severity: 'info', summary: 'Success', detail: 'Submitted successfully', life: 3000 });
                this.form.reset(); // Clear the form data
            } catch (error) {
                if (error.response.status != 422) {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error Submitting record', life: 3000 });
                }
                this.isSubmitting = false;
            } finally {
                this.isSubmitting = false;
            }
        }
    }
};
</script>
