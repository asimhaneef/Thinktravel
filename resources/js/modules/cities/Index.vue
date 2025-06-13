<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Cities</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create City</button>
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
                            <DataTable v-model:filters="filters" :value="cities" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                :globalFilterFields="['city_name', 'city_code', 'province_id']"
                                @update:filters="onFilter" @sort="onSort" @page="onPage">

                                <!-- Province Column with Filter -->
                                <Column field="city_name" header="City Name" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by City Name" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="city_code" header="City Code" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by City Code" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="province.province_name" header="Province" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Province" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.province?.province_name }}
                                    </template>
                                </Column>
                                <Column field="country.country" header="Country" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Country" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.country?.country }}
                                    </template>
                                </Column>

                                <Column field="is_default" header="Is Default" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by is default" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.is_default }}
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit City' : 'Create City'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateRecord() : createRecord()"
            @keydown="form.onKeydown($event)">            

            
            <div class="form-group">
                <label for="country_id">Country (*)</label>
                
                <select type="text" v-model="form.country_id" class="form-control" id="country_id" aria-describedby="country_idHelp">
                    <option value="">Select Country</option>
                    <option v-for="country in countries" :key="country.id" :value="country.id">
                        {{ country.country }}
                    </option>
                </select>
                <small id="country_idHelp" class="form-text text-danger" v-if="form.errors.has('country_id')"
                    v-html="form.errors.get('country_id')"></small>
            </div>
            <div class="form-group">
                <label for="province_id">Province (*)</label>
                <select type="text" v-model="form.province_id" class="form-control" id="province_id"
                    aria-describedby="province_idHelp">
                    <option value="">Select Province</option>
                    <option v-for="province in provinces" :key="province.id" :value="province.id">
                        {{ province.province_name }}
                    </option>
                </select>
                <small id="province_idHelp" class="form-text text-danger" v-if="form.errors.has('province_id')"
                    v-html="form.errors.get('province_id')"></small>
            </div>

            <div class="form-group">
                <label for="city_name">City Name (*)</label>
                <input type="text" v-model="form.city_name" class="form-control" id="city_name"
                    aria-describedby="city_nameHelp">
                <small id="city_nameHelp" class="form-text text-danger" v-if="form.errors.has('city_name')"
                    v-html="form.errors.get('city_name')"></small>
            </div>

            <div class="form-group">
                <label for="city_code">City Code (*)</label>
                <input type="text" v-model="form.city_code" class="form-control" id="city_code"
                    aria-describedby="city_codeHelp">
                <small id="city_codeHelp" class="form-text text-danger" v-if="form.errors.has('city_code')"
                    v-html="form.errors.get('city_code')"></small>
            </div>
            <div class="form-group col-md-6">
                <label for="is_default">Is Default</label>
                <div class=" w-100">
                    <InputSwitch v-model="form.is_default" class="form-control" id="is_default" />
                </div>
                <small class="text-danger" v-if="form.errors.has('is_default')"
                    v-html="form.errors.get('is_default')"></small>
            </div>
            <button type="submit" :loading="isSubmitting" :disabled="form.busy" class="btn btn-success">{{ editMode ?
                "Update City" :
                "Create City"
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
                city_name: '',
                city_code: '',
                province_id: '',
                country_id: '',
                is_default: false,
                id: null,
            }),
            cities: [], // Holds the current page's data
            provinces: [], // Holds the current page's data
            countries: [], // Holds the current page's data
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                city_name: { value: null },
                city_code: { value: null },
                'province.province_name': { value: null },
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
                const response = await this.form.post('/api/city');
                this.getCities();
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
                const response = await this.form.put(`/api/city/${this.form.id}`);
                this.getCities();
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
                await axios.delete(`/api/city/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getCities();
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
                this.countries = response.data.data;
            } catch (error) {
                console.error('Error fetching Countries:', error);
                this.loading = false;
            }
        },
        async getProvinces() {
            try {
                this.loading = true;
                const response = await axios.get('/api/province');
                this.provinces = response.data.data;
            } catch (error) {
                console.error('Error fetching Provinces:', error);
                this.loading = false;
            }
        },
        async getCities() {
            try {
                this.loading = true;
                const response = await axios.get('/api/city', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.cities = response.data.data.map(city => {
                    return {
                        ...city,
                        is_default: city.is_default == 1 ? 'Yes' : 'No'
                    };
                }); // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Cities:', error);
                this.loading = false;
            }
        },
        showSidebar(city) {
            if (city) {
                this.form.fill({
                    city_name: city.city_name,
                    city_code: city.city_code,
                    province_id: city.province_id,
                    country_id: city.country_id,
                    is_default: city.is_default == 'Yes' ? 1 : 0,
                    id: city.id,
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
            this.getCities();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getCities();
        }
    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getCountries();
        this.getProvinces();
        this.getCities();
    },
};
</script>
