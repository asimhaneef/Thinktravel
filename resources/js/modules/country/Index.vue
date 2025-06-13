<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Country</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create Country</button>
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
                            <DataTable v-model:filters="filters" :value="countries" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                :globalFilterFields="['country', 'country_code']" @update:filters="onFilter"
                                @sort="onSort" @page="onPage">

                                <!-- Country Column with Filter -->
                                <Column field="country" header="Country" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Country" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="country_code" header="Country Code" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Country Code" class="form-control" />
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Country' : 'Create Country'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateCountry() : createCountry()"
            @keydown="form.onKeydown($event)">

            <div class="form-group">
                <label for="country">Country (*)</label>
                <input type="text" v-model="form.country" class="form-control" id="country"
                    aria-describedby="countryHelp">
                <small id="countryHelp" class="form-text text-danger" v-if="form.errors.has('country')"
                    v-html="form.errors.get('country')"></small>
            </div>

            <div class="form-group">
                <label for="country_code">Country Code (*)</label>
                <input type="text" v-model="form.country_code" class="form-control" id="country_code"
                    aria-describedby="country_codeHelp">
                <small id="country_codeHelp" class="form-text text-danger" v-if="form.errors.has('country_code')"
                    v-html="form.errors.get('country_code')"></small>
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
                "Update Country" :
                "Create Country"
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
        useConfirm
    },
    data() {
        return {
            form: new Form({
                country: '',
                country_code: '',
                is_default: false,
                id: null,
            }),
            countries: [], // Holds the current page's data
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                country: { value: null },
                country_code: { value: null }
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
        async createCountry() {
            try {
                this.isSubmitting = true;
                const response = await this.form.post('/api/country');
                this.getCountry();
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
        async updateCountry() {
            try {
                this.isSubmitting = true;
                const response = await this.form.put(`/api/country/${this.form.id}`);
                this.getCountry();
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
                await axios.delete(`/api/country/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getCountry();
                this.isSubmitting = true;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
            } finally {
                this.isSubmitting = false;
            }
        },
        async getCountry() {
            try {
                this.loading = true;
                const response = await axios.get('/api/country', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.countries = response.data.data.map(country => {
                    return {
                        ...country,
                        is_default: country.is_default == 1 ? 'Yes' : 'No'
                    } 
                }); // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching countries:', error);
                this.loading = false;
            }
        },
        showSidebar(country) {
            if (country) {
                this.form.fill({
                    country: country.country,
                    country_code: country.country_code,
                    is_default: country.is_default == 'Yes' ? 1 : 0,
                    id: country.id,
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
            this.getCountry();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getCountry();
        }
    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getCountry();
    },
};
</script>
