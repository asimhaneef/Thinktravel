<template>
    
    <section class="section-height-700 breadcrumb-modern rd-parallax context-dark bg-gray-darkest image_mobile">
        <div id="contact_bg_image" data-speed="0.2" data-type="media" data-url="images/backgrounds/background-06-1920x950.jpg" class="rd-parallax-layer"></div>
            <div data-speed="0" data-type="html" class="rd-parallax-layer">
                <div class="bg-overlay-lg-darker">
                    <div class="shell section-top-50 section-bottom-34 section-md-top-90 section-lg-bottom-34 section-lg-top-128">
                        <div class="veil reveal-md-block">
                            <h5 class="reveal-inline-block font-default both-lines text-italic">The Best Travel for You</h5>
                        </div>
                        <div class="veil reveal-md-block offset-top-8">
                            <h1 class="text-bold" style="color:#ffa200;display:block;"  >Documents</h1>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <section class="section-90 section-md-122 text-sm-left">
				  
                  <div class="shell-wide">
                               <div class="range range-xs-center">
                    
                      <div class="cell-xs-10 cell-sm-12 cell-md-9 cell-xl-6">
                        <div class="inset-lg-right-40">
                          <h2 class="text-bold text-uppercase text-ebony-clay">Documents</h2>
                          <hr class="divider divider-lg hr-sm-left-0 bg-primary">
                          <!--p class="offset-top-3 offset-md-top-6">These are the documents that may be helpfull for you.</p-->
                          <!-- RD Mailform-->
                          
                             <div class="offset-top-9 offset-md-top-12">
                              <!--div>
                                <h4 class="text-bold">Color Header</h4>
                              </div>
                              <div class="offset-top-10">
                                <div class="hr bg-gray"></div>
                              </div-->
                              <div class="offset-top-30">
                                <!-- Classic Responsive Table-->
                                
                                  <table data-responsive="true" class="table table-custom table-primary table-fixed">
                                    <tbody>
                                        <tr>
                                          <th>Ser.</th>
                                          <th>Document Name</th>
                                        </tr>	
                                        <tr v-for="(document, index) in documents" :key="document.id">
                                          <td>{{ index + 1 }}</td>
                                          <td><a :href="APP_URL + document.files?.file_real_path" target="_BLANK" >{{ document.document_name }}</a></td>
                                        </tr>
                                        <tr>
                                          <td v-if="loading" colspan="2">Loading...</td>
                                        </tr>
                                      </tbody>
                                  </table>						 
                                
                              </div>
                            </div>
                              
                          
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </section>
</template>

<script>
import { APP_URL } from '../../constants';
export default {
name: "Documents",
    data() {
        return {
          APP_URL: APP_URL,
          documents: [],
          loading: false
        };
    },
    methods: {
      
      async getDocuments() {
            try {
                this.loading = true;
                const response = await axios.get('/web-documents');
                this.documents = response.data;
            } catch (error) {
                console.error('Error fetching records:', error);
                this.loading = false;
            } finally {
                this.loading = false;
            }
        },
    },
    created() {
        this.getDocuments();
    }
};
</script>