<template>
    <!-- <div v-if="loading" class="loader">Loading...</div> -->
    <!-- <div class="content-header">
        <div class="container-fluid">
            <div class="row p-1">
                <div class="col-sm-6">
                    <h1 class="m-0">Inquiries</h1>
                </div> -->
                <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px">
                            Create Inquiry
                        </button>
                    </ol>
                </div>
            </div>
        </div>
    </div> -->
    <div class="content p-3">
        <div class="container-fluid">            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <TabView>
                                <TabPanel header="Flights">
                                    <FlightsTab />
                                </TabPanel>
                                <TabPanel header="Vacations">
                                    <VacationsTab/>
                                </TabPanel>
                                <TabPanel header="Cruises">
                                    <CruisesTab />
                                </TabPanel>
                            </TabView>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import FlightsTab from './FlightsTab.vue';
import VacationsTab from './VacationsTab.vue';
import CruisesTab from './CruisesTab.vue';

export default {
    components: {
        FlightsTab,
        VacationsTab,
        CruisesTab
    },
    data() {
        return { 
            inquiries: [], 
            rows: 10, // Number of records per page
            totalRecords: 0, // Total number of records
            sortField: null,
            sortOrder: null,
            page: 1, // Current page number (1-based index)
        };
    },
    methods: {

    },
    created() {
        // this.getInquiries();
    },
};
</script>

<style scoped>

.edit-icon {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  padding: 5px 10px;
  cursor: pointer;
  z-index: 10;
}

.edit-icon i {
  font-size: 16px;
  color: #000;
}
.trash-icon {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  padding: 5px 10px;
  cursor: pointer;
  z-index: 10;
}
.trash-icon i {
  font-size: 16px;
  color: #000;
}
.loader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    z-index: 9999;
}
</style>