<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Link Category</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create Link Category</button>
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
                            <DataTable v-model:filters="filters" :value="categories" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                :globalFilterFields="['name', 'is_active']" @update:filters="onFilter"
                                @sort="onSort" @page="onPage">

                                <!-- Country Column with Filter -->
                                <Column field="name" header="Name" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Name" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="is_active" header="Is Active" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search" class="form-control" />
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Link Category' : 'Create Link Category'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateRecord() : createRecord()"
            @keydown="form.onKeydown($event)">

            <div class="form-group">
                <label for="name">Name (*)</label>
                <input type="text" v-model="form.name" class="form-control" id="name"
                    aria-describedby="nameHelp">
                <small id="nameHelp" class="form-text text-danger" v-if="form.errors.has('name')"
                    v-html="form.errors.get('name')"></small>
            </div>
            <div class="form-group col-md-6">
                <label for="is_active">Is Active</label>
                <div class=" w-100">
                    <InputSwitch v-model="form.is_active" class="form-control" id="is_active" />
                </div>
                <small class="text-danger" v-if="form.errors.has('is_active')"
                    v-html="form.errors.get('is_active')"></small>
            </div>


            <button type="submit" :loading="isSubmitting" :disabled="form.busy" class="btn btn-success">{{ editMode ?
                "Update Link Category" :
                "Create Link Category"
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

export default {
    components: {
        
    },
    data() {
        return {
            form: new Form({
                name: '',
                is_active: true,
                id: null,
            }),
            categories: [], // Holds the current page's data
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                name: { value: null, matchMode: 'contains' },
                is_active: { value: null, matchMode: 'contains' }
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
                const response = await this.form.post('/api/linkCategory');
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
                const response = await this.form.put(`/api/linkCategory/${this.form.id}`);
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
                await axios.delete(`/api/linkCategory/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getRecords();
                this.isSubmitting = true;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
            } finally {
                this.isSubmitting = false;
            }
        },
        async getRecords() {
            try {
                this.loading = true;
                const response = await axios.get('/api/linkCategory', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.categories = response.data.data.map(category=> {
                    return {
                        ...category,
                        is_active: category.is_active == 1 ? 'Yes' : 'No'
                    }
                }); // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching countries:', error);
                this.loading = false;
            }
        },
        showSidebar(category) {
            if (category) {
                this.form.fill({
                    name: category.name,
                    is_active: category.is_active,
                    id: category.id,
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
    },
};
</script>
