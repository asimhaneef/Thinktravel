<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Regions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create Region</button>
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
                            <DataTable v-model:filters="filters" :value="regions" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                @update:filters="onFilter" @sort="onSort" @page="onPage">
                                
                                <Column field="region" header="Region" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Region" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="region_desc" header="Region Description" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Region Description" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="parent_region.region" header="Parent Region" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Parent Region" class="form-control" />
                                    </template>
                                </Column>
                                
                                <Column field="region_category.category_name" header="Region Category" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Region Category" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="region_type.region_type" header="Region Type" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Region Type" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="sort_order" header="Sort Order" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Sort Order" class="form-control" />
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Region' : 'Create Region'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateRecord() : createRecord()"
            @keydown="form.onKeydown($event)">            
        <div class="row">

            <div class="col-md-6 form-group">
                <label for="region">Region (*)</label>
                <input type="text" v-model="form.region" class="form-control" id="region"
                    aria-describedby="regionHelp" required>
                <small id="regionHelp" class="form-text text-danger" v-if="form.errors.has('region')"
                    v-html="form.errors.get('region')"></small>
            </div>

            <div class="col-md-6 form-group">
                <label for="region_desc">Region Description</label>
                <input type="text" v-model="form.region_desc" class="form-control" id="region_desc"
                    aria-describedby="region_descHelp" >
                <small id="region_descHelp" class="form-text text-danger" v-if="form.errors.has('region_desc')"
                    v-html="form.errors.get('region_desc')"></small>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="sort_order">Sort Order</label>
                <input type="number" v-model="form.sort_order" class="form-control" id="sort_order"
                    aria-describedby="sort_orderHelp" >
                <small id="sort_orderHelp" class="form-text text-danger" v-if="form.errors.has('sort_order')"
                    v-html="form.errors.get('sort_order')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="parent_region_id">Parent Region</label>
                <select type="text" v-model="form.parent_region_id" class="form-control" id="parent_region_id"
                    aria-describedby="parent_region_idHelp">
                    <option value="">Select Parent Region</option>
                    <option v-for="region in regions" :key="region.id" :value="region.id">
                        {{ region.region }}
                    </option>
                </select>
                <small id="parent_region_idHelp" class="form-text text-danger" v-if="form.errors.has('parent_region_id')"
                    v-html="form.errors.get('parent_region_id')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="region_type_id">Region Type (*)</label>
                <select type="text" v-model="form.region_type_id" class="form-control" id="region_type_id"
                    aria-describedby="region_type_idHelp" required>
                    <option value="">Select Region Type</option>
                    <option v-for="region_type in region_types" :key="region_type.id" :value="region_type.id">
                        {{ region_type.region_type }}
                    </option>
                </select>
                <small id="region_type_idHelp" class="form-text text-danger" v-if="form.errors.has('region_type_id')"
                    v-html="form.errors.get('region_type_id')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="region_category_id">Region Category</label>
                <select type="text" v-model="form.region_category_id" class="form-control" id="region_category_id"
                    aria-describedby="region_category_idHelp">
                    <option value="">Select Category Type</option>
                    <option v-for="region_category in region_categories" :key="region_category.id" :value="region_category.id">
                        {{ region_category.category_name }}
                    </option>
                </select>
                <small id="region_category_idHelp" class="form-text text-danger" v-if="form.errors.has('region_category_id')"
                    v-html="form.errors.get('region_category_id')"></small>
            </div>
            
        </div>
            <button type="submit" :loading="isSubmitting" :disabled="form.busy" class="btn btn-success">{{ editMode ?
                "Update Region" :
                "Create Region"
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
                region: '',
                region_desc: '',
                parent_region_id: '',
                region_category_id: '',
                region_type_id: '',
                sort_order: '',
                id: null,
            }),
            regions: [],
            region_types: [],
            region_categories: [], // Holds the current page's data
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                region: { value: null },
                region_desc: { value: null },
                sort_order: { value: null },
                'parent_region.region': { value: null },
                'region_category.category_name': { value: null },
                'region_type.region_type': { value: null },
            },
            initialLoad: true,
            rows: 10, // Number of records per page
            totalRecords: 0, // Total number of records
            sortField: null,
            sortOrder: 0,
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
                const response = await this.form.post('/api/region');
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
                const response = await this.form.put(`/api/region/${this.form.id}`);
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
                await axios.delete(`/api/region/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getRecords();
                this.isSubmitting = true;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
            } finally {
                this.isSubmitting = false;
            }
        },
               
        async getRegionTypes() {
            try {
                this.loading = true;
                const response = await axios.get('/api/region-type');
                this.region_types = response.data.data; // Data for the current page
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Region Type:', error);
                this.loading = false;
            }
        },        
        async getRegionCategories() {
            try {
                this.loading = true;
                const response = await axios.get('/api/region-category');
                this.region_categories = response.data.data; // Data for the current page
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Region Category:', error);
                this.loading = false;
            }
        },
        
        async getRecords() {
            try {
                this.loading = true;
                const response = await axios.get('/api/region', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
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
        showSidebar(region) {
            if (region) {
                this.form.fill({
                    region: region.region,
                    region_desc: region.region_desc,
                    parent_region_id: region.parent_region_id,
                    region_category_id: region.region_category_id,
                    sort_order: region.sort_order,
                    region_type_id: region.region_type_id,
                    id: region.id,
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
        this.getRegionTypes();
        this.getRegionCategories();
    },
};
</script>
