<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Airports</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create Airport</button>
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
                            <DataTable v-model:filters="filters" :value="airports" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                :globalFilterFields="['airport_name', 'city_id', 'icao', 'iata', 'latitude', 'longitude', 'altitude', 'timezone', 'dst']"
                                @update:filters="onFilter" @sort="onSort" @page="onPage">

                                <!-- Province Column with Filter -->
                                <Column field="airport_name" header="Airport Name" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Airport Name" class="form-control" />
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
                                
                                <Column field="latitude" header="Latitude" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Latitude" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="longitude" header="Longitude" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Longitude" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="altitude" header="Altitude" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Altitude" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="timezone" header="Timezone" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Timezone" class="form-control" />
                                    </template>
                                </Column>
                                
                                <Column field="dst" header="DST" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by DST" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="city.country.country" header="Country" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Province" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.city?.country?.country }}
                                    </template>
                                </Column>
                                <Column field="city.province.province_name" header="Province" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Province" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.city?.province?.province_name }}
                                    </template>
                                </Column>
                                <Column field="city.city_name" header="City" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by City" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.city?.city_name }}
                                    </template>
                                </Column>
                                <Column field="is_active" header="Is Active" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by is active" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.is_active }}
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Airport' : 'Create Airport'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateRecord() : createRecord()"
            @keydown="form.onKeydown($event)">            
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="city_id">City (*)</label>
                <select type="text" v-model="form.city_id" class="form-control" id="city_id"
                    aria-describedby="city_idHelp" required>
                    <option value="">Select City</option>
                    <option v-for="city in cities" :key="city.id" :value="city.id">
                        {{ city.city_name }}
                    </option>
                </select>
                <small id="city_idHelp" class="form-text text-danger" v-if="form.errors.has('city_id')"
                    v-html="form.errors.get('city_id')"></small>
            </div>

            <div class="col-md-6 form-group">
                <label for="airport_name">Airport Name (*)</label>
                <input type="text" v-model="form.airport_name" class="form-control" id="airport_name"
                    aria-describedby="airport_nameHelp" required>
                <small id="airport_nameHelp" class="form-text text-danger" v-if="form.errors.has('airport_name')"
                    v-html="form.errors.get('airport_name')"></small>
            </div>

            <div class="col-md-6 form-group">
                <label for="iata">IATA (*)</label>
                <input type="text" v-model="form.iata" class="form-control" id="iata"
                    aria-describedby="iataHelp" required>
                <small id="iataHelp" class="form-text text-danger" v-if="form.errors.has('iata')"
                    v-html="form.errors.get('iata')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="iata">ICAO</label>
                <input type="text" v-model="form.icao" class="form-control" id="icao"
                    aria-describedby="icaoHelp">
                <small id="icaoHelp" class="form-text text-danger" v-if="form.errors.has('icao')"
                    v-html="form.errors.get('icao')"></small>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="latitude">Latitude</label>
                <input type="text" v-model="form.latitude" class="form-control" id="latitude"
                    aria-describedby="latitudeHelp">
                <small id="latitudeHelp" class="form-text text-danger" v-if="form.errors.has('latitude')"
                    v-html="form.errors.get('latitude')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="longitude">Longitude</label>
                <input type="text" v-model="form.longitude" class="form-control" id="longitude"
                    aria-describedby="longitudeHelp">
                <small id="longitudeHelp" class="form-text text-danger" v-if="form.errors.has('longitude')"
                    v-html="form.errors.get('longitude')"></small>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="altitude">Altitude</label>
                <input type="text" v-model="form.altitude" class="form-control" id="altitude"
                    aria-describedby="altitudeHelp">
                <small id="altitudeHelp" class="form-text text-danger" v-if="form.errors.has('altitude')"
                    v-html="form.errors.get('altitude')"></small>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="timezone">Timezone (*)</label>
                <input type="text" v-model="form.timezone" class="form-control" id="timezone"
                    aria-describedby="timezoneHelp" required>
                <small id="timezoneHelp" class="form-text text-danger" v-if="form.errors.has('timezone')"
                    v-html="form.errors.get('timezone')"></small>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="dst">DST</label>
                <input type="text" v-model="form.dst" class="form-control" id="dst"
                    aria-describedby="dstHelp">
                <small id="dstHelp" class="form-text text-danger" v-if="form.errors.has('dst')"
                    v-html="form.errors.get('dst')"></small>
            </div>

            <div class="form-group col-md-6">
                <label for="is_active">Is Active</label>
                <div class=" w-100">
                    <InputSwitch v-model="form.is_active" class="form-control" id="is_active" />
                </div>
                <small class="text-danger" v-if="form.errors.has('is_active')"
                    v-html="form.errors.get('is_active')"></small>
            </div>
        </div>
            <button type="submit" :loading="isSubmitting" :disabled="form.busy" class="btn btn-success">{{ editMode ?
                "Update Airport" :
                "Create Airport"
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
                airport_name: '',
                city_id: '',
                iata: '',
                icao: '',
                latitude: '',
                longitude: '',
                altitude: '',
                timezone: '',
                dst: '',
                is_active: false,
                id: null,
            }),
            cities: [], // Holds the current page's data
            airports: [],
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                airport_name: { value: null },
                iata: { value: null },
                icao: { value: null },
                latitude: { value: null },
                longitude: { value: null },
                altitude: { value: null },
                timezone: { value: null },
                dst: { value: null },
                'city.city_name': { value: null },
                'city.province.province_name': { value: null },
                'country.country': { value: null }
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
                const response = await this.form.post('/api/airport');
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
                const response = await this.form.put(`/api/airport/${this.form.id}`);
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
                await axios.delete(`/api/airport/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getRecords();
                this.isSubmitting = true;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
            } finally {
                this.isSubmitting = false;
            }
        },
        async getCities() {
            try {
                this.loading = true;
                const response = await axios.get('/api/city');
                this.cities = response.data.data; // Data for the current page
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Cities:', error);
                this.loading = false;
            }
        },
        async getRecords() {
            try {
                this.loading = true;
                const response = await axios.get('/api/airport', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.airports = response.data.data.map(airport => {
                    return {
                        ...airport,
                        is_active: airport.is_active == 1 ? 'Yes' : 'No'
                    };
                }); // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Cities:', error);
                this.loading = false;
            }
        },
        showSidebar(airport) {
            if (airport) {
                this.form.fill({
                    airport_name: airport.airport_name,
                    city_id: airport.city_id,
                    iata: airport.iata,
                    icao: airport.icao,
                    latitude: airport.latitude,
                    longitude: airport.longitude,
                    altitude: airport.altitude,
                    timezone: airport.timezone,
                    dst: airport.dst,
                    is_active: airport.is_active == 'Yes' ? 1 : 0,
                    id: airport.id,
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
        this.getCities();
    },
};
</script>
