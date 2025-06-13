<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Provinces</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create Province</button>
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
                            <DataTable v-model:filters="filters" :value="provinces" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                :globalFilterFields="['province_name', 'province_code', 'country_id']"
                                @update:filters="onFilter" @sort="onSort" @page="onPage">

                                <!-- Province Column with Filter -->
                                <Column field="province_name" header="Province Name" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Province Name" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="province_code" header="Province Code" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Province Code" class="form-control" />
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Province' : 'Create Province'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateProvince() : createProvince()"
            @keydown="form.onKeydown($event)">            

            <div class="form-group">
                <label for="country_id">Country (*)</label>
                <select type="text" v-model="form.country_id" class="form-control" id="country_id"
                    aria-describedby="country_idHelp">
                    <option value="">Select Country</option>
                    <option v-for="country in countries" :key="country.id" :value="country.id">
                        {{ country.country }}
                    </option>
                </select>
                <small id="country_idHelp" class="form-text text-danger" v-if="form.errors.has('country_id')"
                    v-html="form.errors.get('country_id')"></small>
            </div>

            <div class="form-group">
                <label for="province_name">Province Name (*)</label>
                <input type="text" v-model="form.province_name" class="form-control" id="province_name"
                    aria-describedby="province_nameHelp">
                <small id="province_nameHelp" class="form-text text-danger" v-if="form.errors.has('province_name')"
                    v-html="form.errors.get('province_name')"></small>
            </div>

            <div class="form-group">
                <label for="province_code">Province Code (*)</label>
                <input type="text" v-model="form.province_code" class="form-control" id="province_code"
                    aria-describedby="province_codeHelp">
                <small id="province_codeHelp" class="form-text text-danger" v-if="form.errors.has('province_code')"
                    v-html="form.errors.get('province_code')"></small>
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
                "Update Province" :
                "Create Province"
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
                province_name: '',
                province_code: '',
                country_id: '',
                is_default: false,
                id: null,
            }),
            countries: [], // Holds the current page's data
            provinces: [], // Holds the current page's data
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                province_name: { value: null },
                province_code: { value: null },
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
        async createProvince() {
            try {
                this.isSubmitting = true;
                const response = await this.form.post('/api/province');
                this.getProvince();
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
        async updateProvince() {
            try {
                this.isSubmitting = true;
                const response = await this.form.put(`/api/province/${this.form.id}`);
                this.getProvince();
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
                await axios.delete(`/api/province/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getProvince();
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
                console.error('Error fetching provinces:', error);
                this.loading = false;
            }
        },
        async getProvince() {
            try {
                this.loading = true;
                const response = await axios.get('/api/province', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.provinces = response.data.data.map(province => {
                    return {
                        ...province,
                        is_default: province.is_default == 1 ? 'Yes' : 'No'
                    };
                }); // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching provinces:', error);
                this.loading = false;
            }
        },
        showSidebar(province) {
            if (province) {
                this.form.fill({
                    province_name: province.province_name,
                    province_code: province.province_code,
                    country_id: province.country_id,
                    is_default: province.is_default == 'Yes' ? 1 : 0,
                    id: province.id,
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
            this.getProvince();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getProvince();
        }
    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getProvince();
        this.getCountries();
    },
};
</script>
