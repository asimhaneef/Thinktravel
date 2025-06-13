<template>
    <section class="section-height-700 breadcrumb-modern rd-parallax context-black bg-gray-darkest image_mobile">	  
      <div id="header_img_cruises" data-speed="0.2" data-type="media" data-url="images/backgrounds/background-03-1920x950.jpg"  class="rd-parallax-layer"></div>			
        <div data-speed="0" data-type="html" class="rd-parallax-layer">
          <div class="bg-overlay-lg-darker">
            <div class="shell section-top-50 section-bottom-34 section-md-top-90 section-lg-bottom-34 section-lg-top-128">
                <div class="cell-sm-10 cell-lg-8 cell-xl-5"  >
                    <div class="veil reveal-md-block"  id="header_smltext_cruises"  >
                    <h5 class="reveal-inline-block font-default both-lines text-italic" style="color:white;">The Best Cruise for You</h5>
                    </div>
                    <div class="veil reveal-md-block offset-top-8" >
                    <h1 class="text-bold" style="color:#ffa200;display:block;"  >Cruises</h1>
                    </div>
                </div>                        
            </div>
          </div>
        </div>
    </section>
    
<section class="section-90">
  <div class="shell-wide offset-top-10 offset-md-top-12">
    <div class="range range-xs-center">
      <div class="cell-sm-10 cell-md-12 cell-xl-3">
        <!-- Responsive-tabs-->
        <div class="offset-top-10">
          <div data-type="horizontal" class="responsive-tabs responsive-tabs-boxed horizontal">
            <div class="resp-tabs-container text-left tabs-group-default">
              <!--  Start: Flights tab -->
              <div name="div_flights" id="div_flights">
                <div class="range range-xs-center range-lg-left offset-top-10 offset-md-top-2">
                  <div class="cell-lg-12  ">
                    <div id="div_flight_type">
                      <div id="div_flight_twoway">
                        <form @submit.prevent="createRecord()" id="form_flights" style="background-color:#39bae5;" class="booking_form">
                          <!--h5 align="center"> Return Flying Details</h5-->
                          <p align="center">Please provide below information to help our agents find best travel deals for you. <br> Asterisks (*) are required fields for the form. </p>
                          <div style="margin-left:20px;margin-right:20px;">
                            
                              <div class="mt-3 row">                               


                                <div class="form-group col-md-4">
                                  <label for="cruising_origin" class="form-label-outside text-black">Origin *</label>
                                  <select id="cruising_origin" v-model="form.cruising_origin" class="form-control" style="background-color:white;" required>
                                    <option value="">--Select--</option>
                                    <option v-for="airport in airports" :key="airport.id" :value="airport.id">
                                      {{ airport.iata }}  {{ airport.airport_name }} {{ airport.city.city_name }} {{ airport.city.province.country.country }}
                                    </option>
                                  </select>
                                </div>
                                <div class="form-group col-md-4">
                                  <label for="cruising_location" class="form-label-outside text-black">Destination *</label>
                                  <select id="cruising_location" v-model="form.cruising_location" class="form-control" style="background-color:white;" required>
                                    <option value="">--Select--</option>
                                    <template v-for="category in groupedRegions" :key="category.id">
                                      <optgroup :label="category.category_name" >
                                        <option v-for="region in category.regions" :key="region.id" :value="region.id">
                                          {{ region.region }}
                                        </option>
                                      </optgroup>
                                    </template>
                                  </select>
                                </div>
                                <div class="form-group col-md-4">
                                  <label for="cruising_month" class="form-label-outside text-black">Month *</label>
                                  <input id="cruising_month" type="month" v-model="form.cruising_month" :min="minMonth" class="form-control date_input" style="background-color:white;" required value="" />
                                </div>



                              </div>
                            <div class="row">
                              <div class="form-group col-md-4">
                                <label class="form-label-outside text-black">Adults *</label>
                                <select v-model="form.adults" id="adults" class="form-control" style="background-color:white;" required>
                                  <option value="">--Select--</option>
                                  <option value="1" selected>1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                </select>
                              </div>
                              <div class="form-group col-md-4">
                                <label class="form-label-outside text-black">Children (age 2 - 11) *</label>
                                <select v-model="form.children" id="children" class="form-control" style="background-color:white;" required>
                                  <option value="">--Select--</option>
                                  <option value="0" selected>0</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                </select>
                              </div>
                              <div class="form-group col-md-4">
                                <label class="form-label-outside text-black">Infants (age under 2) *</label>
                                <select v-model="form.infants" id="infants" class="form-control" style="background-color:white;" required>
                                  <option value="">--Select--</option>
                                  <option value="0" selected>0</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                </select>
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-6" style="color:black;margin: 20px 0;">
                                <label for="registration-first-name" class="form-label-outside text-black">&nbsp;</label>
                                <br> Avoid USA Connection &nbsp; <input type="checkbox" v-model="form.usa_connection" id="usa_connection" value="Yes" />
                              </div>
                              <div class="form-group col-md-6" style="color:black">
                                <label for="registration-first-name" class="form-label-outside text-black">Customer Identification</label>
                                <br>
                                <select v-model="form.customer_identification" id="customer_identification" class="form-control input-block-level" style="background-color:white;">
                                  <option value="Web">Web</option>
                                  <option value="Walk-in">Walk-in</option>
                                  <option value="Phone">Phone</option>
                                  <option value="Email">Email</option>
                                  <option value="Referral">Referral</option>
                                  <option value="Other">Other</option>
                                </select>
                                <div id='diverror_booking_status'></div>
                              </div>
                            </div>
                            <!-- ------------------------- Member info -------------------------------------------------------------- -->
                            <hr>
                            <h5 align="center"> Contact Information</h5>
                            <div id="div_member_info_flights_twoway">
                              <div class="row">
                                <div class="form-group col-md-6">
                                  <label for="member_email" class="form-label-outside text-black">Email Address *</label>
                                  <input id="member_email_flights_twoway" type="email" v-model="form.member_email" class="form-control" style="background-color:white;" value="" required />
                                  <p style="color:red;"></p>
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="contact_no" class="form-label-outside text-black">Contact No *</label>
                                  <input id="contact_no_twoway" type="tel" v-model="form.contact_no" class="form-control" style="background-color:white;" onfocus="this.placeholder ='' " value="" required />
                                  <p style="color:red;"></p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="form-group col-md-6">
                                  <label for="first_name" class="form-label-outside text-black">First Name *</label>
                                  <input id="first_name_twoway" type="text" v-model="form.first_name" class="form-control" style="background-color:white;" value="" required />
                                </div>
                                <div class="form-group col-md-6">
                                  <label for="last_name" class="form-label-outside text-black">Last Name *</label>
                                  <input id="last_name_twoway" type="text" v-model="form.last_name" class="form-control" style="background-color:white;  text-transform:uppercase;" value="" required />
                                </div>
                                
                                <div class="form-group col-md-6">
                                  <label for="gender" class="form-label-outside text-black">Gender</label>                                  
                                  <select v-model="form.gender" class="form-control" id="gender"
                                      aria-describedby="genderHelp" style="background-color:white;">
                                      <option value="">Select</option>
                                      <option value="Male">Male</option>
                                      <option value="Female">Female</option>
                                      <option value="Other">Other</option>
                                  </select>
                                </div>


                                <div class="form-group col-md-6">
                                  <label for="country_id" class="form-label-outside text-black">Country *</label>                                  
                                  <select v-model="form.country_id" class="form-control" id="country_id"
                                       style="background-color:white;" required>                                      
                                    <option value="">Select Country</option>
                                    <option v-for="country in countries" :key="country.id" :value="country.id">
                                        {{ country.country }}
                                    </option>
                                  </select>
                                </div>
                                
                
                              </div>
                            </div>
                            <div class="row">
                              <div class="form-group col-md-6">
                                <label for="payment_mode" class="form-label-outside text-black"> Mode of Payment </label>
                                <br>
                                <div>
                                  <input type="radio" v-model="form.payment_mode" id="payment_mode" value="cash" /> Cash 
                                  <input type="radio" v-model="form.payment_mode" id="payment_mode" value="debit_card" /> Debit Card 
                                  <input type="radio" v-model="form.payment_mode" id="payment_mode" value="credit_card" /> Credit Card 
                                  <input type="radio" v-model="form.payment_mode" id="payment_mode" value="other" /> Other
                                </div>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="agent_name" class="form-label-outside text-black">Agent Name </label>
                                <select v-model="form.agent_name" id="agent_name" class="form-control" style="background-color:white;">
                                  <option value="">--select--</option>
                                    <option v-for="agent in agents" :key="agent.id" :value="agent.id">
                                        {{ agent.first_name }} {{ agent.last_name }}
                                    </option>
                                </select>
                              </div>
                            </div>
                            <div class="row offset-top-20">
                              <div class="form-group col-md-12">
                                <label for="contact-us-message" class="form-label-outside text-black">Additional Notes</label>
                                <textarea id="additional_notes_twoway" v-model="form.additional_notes" style="max-height: 150px;background-color:#FFFFFF" class="form-control" placeholder="Please use this field to advice about special needs, connection preferences and budget etc." onfocus="this.placeholder ='' "></textarea>
                              </div>
                            </div>
                            <div class="row offset-top-20">
                              <div class="form-group col-md-6" align="center" style="color:black"> * I agree on <a style="cursor:pointer;" onclick="show_terms()">
                                  <u>terms and conditions</u>
                                </a>
                                <input id="agree_on_terms" type="checkbox" v-model="form.agree_on_terms" style="background-color:white;" value="Yes" required />
                              </div>
                              <div class="form-group col-md-6" align="center" style="color:black"> I allow to contact me by email (optional). <input id="contact_by_email" type="checkbox" v-model="form.contact_by_email" style="background-color:white;" value="Yes" />
                              </div>
                            </div>
                            <!-- ----------------------------------------------------------------------------------------- -->
                          </div>
                          <hr>
                          <br>
                          <br>
                          <div align="center">
                            <button type="button" @click="clearForm" style="margin-right:10px" class="btn btn-primary">Clear Form</button>
                            <input type="submit" id="submit_flights" value="Submit" class="btn btn-primary" />
                          </div>
                          <br>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
    <!-- Toast Container -->
    <div class="toast-container">
      <div v-for="(toast, index) in toasts" :key="index" :class="['toast-message', `toast-${toast.type}`]">
        <span class="toast-icon">⚠️</span> <!-- Replace with your icon -->
        <div style="width:100%;">
          <strong>{{ toast.summary }}</strong>
          <p style="margin-top:0;">{{ toast.detail }}</p>
        </div>
        <button class="toast-close" @click="removeToast(index)">×</button>
      </div>
    </div>
</template>
<script>
import Form from 'vform';
import axios from 'axios';
  export default {
    name: "Cruises",
    data() {
      return {
          form: new Form({             
            cruising_origin: '',
            cruising_location: '',
            cruising_month: '',
            adults: '',
            children: '',
            infants: '',
            usa_connection: '',
            customer_identification: '',
            booking_type: 'cruises',
            member_email: '',
            contact_no: '',
            first_name: '',
            last_name: '',
            gender : '',
            agent_name : '',
            country_id : '',
            additional_notes: '',
            agree_on_terms: '',
            contact_by_email: '',
          }),
          otherDocument: "", // Stores the value for "Other" input
          nationality: "", // Stores the value for "Nationality" input
          isSubmitting: false,
          loading: false,
          airports: [],
          airlines: [],
          countries: [],
          regions: [],
          agents: [],
          toasts: [],
      }
    },
    computed: {
      groupedRegions() {
        // Group regions by region_category.category_name
        const grouped = {};

        this.regions.forEach((region) => {
          const categoryName = region.region_category?.category_name || "Uncategorized";

          if (!grouped[categoryName]) {
            grouped[categoryName] = {
              category_name: categoryName,
              regions: [],
            };
          }

          grouped[categoryName].regions.push(region);
        });

        // Convert the grouped object into an array
        return Object.values(grouped);
      },
      minMonth() {
        // Get the current date
        const currentDate = new Date();
        // Extract the year and month (local time)
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, "0"); // Add 1 because months are 0-indexed
        // Format as YYYY-MM
        return `${year}-${month}`;
      },
    },
    methods: {
      clearForm(){
        this.form.reset();
      },
      showToast(type, summary, detail) {
        const toast = { type, summary, detail };
        this.toasts.push(toast);

        // Automatically remove the toast after 5 seconds
        setTimeout(() => {
          this.removeToast(this.toasts.indexOf(toast));
        }, 5000);
      },
      removeToast(index) {
        this.toasts.splice(index, 1);
      },
      show_travel_docs_opts($field){
        div_twoway_travel_doc_other.style.display = $field.value == 'other' ? 'block' : 'none';
        div_twoway_travel_doc_passport.style.display = $field.value == 'passport' ? 'block' : 'none';
      },
      async createRecord() {
        try {
                this.isSubmitting = true; // show loader
                const response = await this.form.post("/saveInquiry");
                // handle success, e.g., show a success message or redirect
                // this.$toast.add({ severity: 'success', summary: 'Success', detail: 'Inquiry added successfully', life: 3000 });
                this.showToast('success','Success','Inquiry added successfully');
                this.form.reset(); // Clear the form data
        } catch(error) {
              console.error('Error creating inquiry:', error);
              if (error.response.status != 422) {
                    this.showToast('error','Error','Error creating Record');
                    // this.showToast('error','Error',error.response.data.error);

                }
              this.loading = false;
          }
      },
      async getAirports() {
          try {
              this.loading = true;
              const response = await axios.get('/airport');
              this.airports = response.data.data; // Data for the current page
              this.loading = false;
          } catch (error) {
              console.error('Error fetching Cities:', error);
              this.loading = false;
          }
      },
      async getAirlines() {
          try {
              this.loading = true;
              const response = await axios.get('/airline');
              this.airlines = response.data.data; // Data for the current page
              this.loading = false;
          } catch (error) {
              console.error('Error fetching Cities:', error);
              this.loading = false;
          }
      },
      async getCountries() {
            try {
                this.loading = true;
                const response = await axios.get('/country');
                this.countries = response.data.data;
                // Find the default country where is_default is 1
                const defaultCountry = this.countries.find(country => country.is_default === 1);

                // Set the default country in the form
                if (defaultCountry) {
                    this.form.country_id = defaultCountry.id;
                }
            } catch (error) {
                console.error('Error fetching records:', error);
                this.loading = false;
            }
        },
      async getAgents() {
            try {
                this.loading = true;
                const response = await axios.get('/agents');
                this.agents = response.data.agents;
            } catch (error) {
                console.error('Error fetching records:', error);
                this.loading = false;
            }
        },
        async getRegions() {
            try {
                this.loading = true;
                const response = await axios.get('/region', {
                    params: {
                        filters: JSON.stringify({"region_type.region_type":{"value":"Cruises"}})
                    }
                });
                this.regions = response.data.data; // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Cities:', error);
                this.loading = false;
            }
        },
    },
    created() {
        this.getAirports();
        this.getAirlines();
        this.getCountries();
        this.getAgents();
        this.getRegions();
    },
  };
</script>
<style>
/* Toast Container */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1100;
    max-width: 300px;
    width: 100%;
}

/* Toast Message */
.toast-message {
    padding: 16px;
    margin-bottom: 12px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    background-color: #ffffff;
    border-left: 4px solid;
    animation: slideIn 0.5s ease-out;
}

/* Toast Icon */
.toast-icon {
    margin-right: 12px;
    font-size: 20px;
}

/* Toast Close Button */
.toast-close {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: inherit;
}

/* Toast Types */
.toast-success {
    border-color: #4caf50;
    color: #4caf50;
}

.toast-error {
    border-color: #f44336;
    color: #f44336;
}

.toast-info {
    border-color: #2196f3;
    color: #2196f3;
}

.toast-warning {
    border-color: #ff9800;
    color: #ff9800;
}

/* Animation */
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
</style>