<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Airlines</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create Airline</button>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="p-0 card-body">
                            <DataTable v-model:filters="filters" :value="airlines" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                :globalFilterFields="['airline_name', 'city_id', 'icao', 'iata', 'latitude', 'longitude', 'altitude', 'timezone', 'dst']"
                                @update:filters="onFilter" @sort="onSort" @page="onPage">

                                <Column field="airline_name" header="Airline Name" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Airline Name" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="iata" header="IATA" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by IATA" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="icao" header="ICAO" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by ICAO" class="form-control" />
                                    </template>
                                </Column>
                                
                                <Column field="callsign" header="Callsign" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Callsign" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="alies" header="Alies" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Alies" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="sort_order" header="Sort Order" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Sort Order" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="airline_type.airline_type" header="Airline Type" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Airline Type" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="country.country" header="Country" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Country" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.country.country }}
                                    </template>
                                </Column>

                                <!-- Action Column -->
                                <Column field="id" header="Action" style="min-width: 200px">
                                    <template #body="slotProps">
                                        <div class="d-flex justify-content-start">
                                            <a href="javascript:void(0)" class="m-2 icon-text"
                                                @click="showSidebar(slotProps.data)" title="Edit">
                                                <i class="pi pi-file-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)"
                                                @click="handelDelete($event, slotProps.data.id)" class="m-2 text-danger"
                                                title="Delete">
                                                <i class="pi pi-trash"></i>
                                            </a>
                                        </div>
                                    </template>
                                </Column>

                                <!-- Empty and Loading Templates -->
                                <template #empty> No record found. </template>
                                <template #loading> Loading records. Please wait. </template>
                            </DataTable>

                            <!-- Paginator Component -->
                            <Paginator :first="(page - 1) * rows + 1" :rows="rows" :totalRecords="totalRecords"
                                :page="page - 1" :rowsPerPageOptions="[5, 10, 20]" @page="onPageChange" :template="{
                                    // '640px': 'PrevPageLink CurrentPageReport NextPageLink',
                                    // '960px': 'FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink',
                                    // '1300px': 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink JumpToPageDropdown JumpToPageInput',
                                    default: 'FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink JumpToPageDropdown JumpToPageInput'
                                }">
                            </Paginator>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Airline' : 'Create Airline'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateRecord() : createRecord()"
            @keydown="form.onKeydown($event)">            
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="country_id">Country (*)</label>
                <select type="text" v-model="form.country_id" class="form-control" id="city_id"
                    aria-describedby="city_idHelp" required>
                    <option value="">Select Country</option>
                    <option v-for="country in countries" :key="country.id" :value="country.id">
                        {{ country.country }}
                    </option>
                </select>
                <small id="city_idHelp" class="form-text text-danger" v-if="form.errors.has('city_id')"
                    v-html="form.errors.get('city_id')"></small>
            </div>

            <div class="col-md-6 form-group">
                <label for="airline_name">Airline Name (*)</label>
                <input type="text" v-model="form.airline_name" class="form-control" id="airline_name"
                    aria-describedby="airline_nameHelp" required>
                <small id="airline_nameHelp" class="form-text text-danger" v-if="form.errors.has('airline_name')"
                    v-html="form.errors.get('airline_name')"></small>
            </div>

            <div class="col-md-6 form-group">
                <label for="iata">IATA</label>
                <input type="text" v-model="form.iata" class="form-control" id="iata"
                    aria-describedby="iataHelp" >
                <small id="iataHelp" class="form-text text-danger" v-if="form.errors.has('iata')"
                    v-html="form.errors.get('iata')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="callsign">Callsign</label>
                <input type="text" v-model="form.callsign" class="form-control" id="callsign"
                    aria-describedby="callsignHelp">
                <small id="callsignHelp" class="form-text text-danger" v-if="form.errors.has('callsign')"
                    v-html="form.errors.get('callsign')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="alies">Alies</label>
                <input type="text" v-model="form.alies" class="form-control" id="alies"
                    aria-describedby="aliesHelp">
                <small id="aliesHelp" class="form-text text-danger" v-if="form.errors.has('alies')"
                    v-html="form.errors.get('alies')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="iata">ICAO (*)</label>
                <input type="text" v-model="form.icao" class="form-control" id="icao"
                    aria-describedby="icaoHelp" required>
                <small id="icaoHelp" class="form-text text-danger" v-if="form.errors.has('icao')"
                    v-html="form.errors.get('icao')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="airline_type_id">Airline Type</label>
                <select type="text" v-model="form.airline_type_id" class="form-control" id="airline_type_id"
                    aria-describedby="airline_type_idHelp">
                    <option value="">Select Airline Type</option>
                    <option v-for="airline_type in airline_types" :key="airline_type.id" :value="airline_type.id">
                        {{ airline_type.airline_type }}
                    </option>
                </select>
                <small id="airline_type_idHelp" class="form-text text-danger" v-if="form.errors.has('airline_type_id')"
                    v-html="form.errors.get('airline_type_id')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" v-model="form.sort_order" class="form-control" id="sort_order"
                    aria-describedby="sort_orderHelp" >
                <small id="sort_orderHelp" class="form-text text-danger" v-if="form.errors.has('sort_order')"
                    v-html="form.errors.get('sort_order')"></small>
            </div>
            
        </div>
            <button type="submit" :loading="isSubmitting" :disabled="form.busy" class="btn btn-success">{{ editMode ?
                "Update Airline" :
                "Create Airline"
                }}</button>
        </form>
    </Sidebar>
    <Toast />
    <ConfirmPopup></ConfirmPopup>
</template>

<script>
import { ref } from 'vue';
import Form from 'vform';
import axios from 'axios';
import { useConfirm } from "primevue/useconfirm";

export default {
    components: {
        useConfirm,
    },
    data() {
        return {
            form: new Form({
                airline_name: '',
                country_id: '',
                iata: '',
                icao: '',
                callsign: '',
                alies: '',
                sort_order: '',
                airline_type_id: '',
                id: null,
            }),
            countries: [], // Holds the current page's data
            airlines: [],
            airline_types: [],
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                airline_name: { value: null },
                iata: { value: null },
                icao: { value: null },
                callsign: { value: null },
                alies: { value: null },
                sort_order: { value: null },
                airline_type_id: { value: null },
                'country.country': { value: null },
                'airline_type.airline_type': { value: null },
            },
            initialLoad: true,
            rows: 10, // Number of records per page
            totalRecords: 0, // Total number of records
            sortField: null,
            sortOrder: null,
            page: 1, // Current page number (1-based index)
        };
    },
    watch: {
        visibleRight(newValue) {
            if (!newValue) {
                // Clear form errors when sidebar is closed
                this.form.errors.clear();
            }
        }
    },
    methods: {
        async checkFirstLogin() {
            try {
                const response = await axios.get('/api/check-first-login');

                if (response.data.redirect) {
                // Redirect the user to the change-password route
                this.$router.push({ path: '/change-password' });
                }
            } catch (error) {
                console.error('Error checking first login:', error);
            }
        },
        async handelDelete(event, id) {
            this.$confirm.require({
                target: event.currentTarget,
                message: 'Do you want to delete this record?',
                icon: 'pi pi-info-circle',
                rejectProps: {
                    label: 'Cancel',
                    severity: 'secondary',
                    outlined: true
                },
                acceptProps: {
                    label: 'Delete',
                    severity: 'danger'
                },
                accept: () => {
                    console.log(id);
                    this.deleteRecord(id); // Proceed with the deletion

                },
            });
        },
        async createRecord() {
            try {
                this.isSubmitting = true;
                const response = await this.form.post('/api/airline');
                this.getRecords();
                this.$toast.add({ severity: 'info', summary: 'Success', detail: 'Record created successfully', life: 3000 });
                this.form.reset(); // Clear the form data
            } catch (error) {
                if (error.response.status != 422) {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error creating record', life: 3000 });
                }
                this.isSubmitting = false;
            } finally {
                this.isSubmitting = false;
            }
        },
        async updateRecord() {
            try {
                this.isSubmitting = true;
                const response = await this.form.put(`/api/airline/${this.form.id}`);
                this.getRecords();
                this.$toast.add({ severity: 'info', summary: 'Success', detail: 'Record updated successfully', life: 3000 });
            } catch (error) {
                if (error.response.status != 422) {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error updating record', life: 3000 });
                }
                this.isSubmitting = false;
            } finally {
                this.isSubmitting = false;
            }
        },
        async deleteRecord(id) {
            try {
                await axios.delete(`/api/airline/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getRecords();
                this.isSubmitting = true;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
            } finally {
                this.isSubmitting = false;
            }
        },
        async getCountries() {
            try {
                this.loading = true;
                const response = await axios.get('/api/country');
                this.countries = response.data.data; // Data for the current page
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Countries:', error);
                this.loading = false;
            }
        },        
        async getAirlineTypes() {
            try {
                this.loading = true;
                const response = await axios.get('/api/airline-type');
                this.airline_types = response.data.data; // Data for the current page
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Airline Type:', error);
                this.loading = false;
            }
        },
        async getRecords() {
            try {
                this.loading = true;
                const response = await axios.get('/api/airline', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.airlines = response.data.data; // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Cities:', error);
                this.loading = false;
            }
        },
        showSidebar(airline) {
            if (airline) {
                this.form.fill({
                    airline_name: airline.airline_name,
                    country_id: airline.country_id,
                    iata: airline.iata,
                    icao: airline.icao,
                    callsign: airline.callsign,
                    alies: airline.alies,
                    sort_order: airline.sort_order,
                    airline_type_id: airline.airline_type_id,
                    id: airline.id,
                });
                this.editMode = true;
            } else {
                this.form.reset();
                this.editMode = false;
            }
            this.visibleRight = true;
        },
        onFilter(filters) {
            this.page = 1;
            this.filters = filters;
            this.applyFilter('filter', filters);
        },
        onSort(event) {
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.applyFilter('sort', event);
        },
        onPage(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.applyFilter('page', event);
        },
        applyFilter(actionType, event) {
            this.getRecords();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getRecords();
        }
    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getRecords();
        this.getCountries();
        this.getAirlineTypes();
    },
};
</script>
