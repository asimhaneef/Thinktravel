<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Codes List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px;">Create Code</button>
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
                            <DataTable v-model:filters="filters" :value="codes" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                @update:filters="onFilter" @sort="onSort" @page="onPage">
                                
                                <Column field="codes_list" header="Codes List" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Codes List" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="data_entry" header="Data Entry" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Data Entry" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="label" header="Label" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Label" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="meaning" header="Meaning" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Meaning" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="is_active" header="Is Active" style="min-width: 12rem" sortable
                                    :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Is Active" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.is_active == 1 ? 'Yes': 'No' }}
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Code' : 'Create Code'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form class="mt-3" @submit.prevent="editMode ? updateRecord() : createRecord()"
            @keydown="form.onKeydown($event)">            
        <div class="row">

            <div class="col-md-6 form-group">
                <label for="data_entry">Data Entry (*)</label>
                <input type="text" v-model="form.data_entry" class="form-control" id="data_entry"
                    aria-describedby="data_entryHelp" required>
                <small id="data_entryHelp" class="form-text text-danger" v-if="form.errors.has('data_entry')"
                    v-html="form.errors.get('data_entry')"></small>
            </div>

            <div class="col-md-6 form-group">
                <label for="label">Label (*)</label>
                <input type="text" v-model="form.label" class="form-control" id="label"
                    aria-describedby="labelHelp" required>
                <small id="labelHelp" class="form-text text-danger" v-if="form.errors.has('label')"
                    v-html="form.errors.get('label')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="meaning">Meaning</label>
                <input type="text" v-model="form.meaning" class="form-control" id="meaning"
                    aria-describedby="meaningHelp" >
                <small id="meaningHelp" class="form-text text-danger" v-if="form.errors.has('meaning')"
                    v-html="form.errors.get('meaning')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="codes_list">Codes List (*)</label>
                <select type="text" v-model="form.codes_list" class="form-control" id="codes_list"
                    aria-describedby="codes_listHelp" required>
                    <option v-for="codeList in codeLists" :key="codeList.id" :value="codeList.id">
                        {{ codeList.name }}
                    </option>
                </select>
                <small id="codes_listHelp" class="form-text text-danger" v-if="form.errors.has('codes_list')"
                    v-html="form.errors.get('codes_list')"></small>
            </div>
            <div class="col-md-6 form-group">
                <label for="is_active">Is Active</label>
                <select type="text" v-model="form.is_active" class="form-control" id="is_active"
                    aria-describedby="is_activeHelp">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
                <small id="is_activeHelp" class="form-text text-danger" v-if="form.errors.has('is_active')"
                    v-html="form.errors.get('is_active')"></small>
            </div>
            
        </div>
            <button type="submit" :loading="isSubmitting" :disabled="form.busy" class="btn btn-success">{{ editMode ?
                "Update Code" :
                "Create Code"
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
                codes_list: 'Suppliers',
                data_entry: '',
                label: '',
                meaning: '',
                is_active: true,
                id: null,
            }),
            codes: [],// Holds the current page's data
            codeLists:[
                {name:'Supplier',value: 'Supplier',id:'Supplier'}
            ],
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                codes_list: { value: null },
                data_entry: { value: null },
                label: { value: null },
                meaning: { value: null },
                is_active: { value: null },
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
                const response = await this.form.post('/api/codes-list');
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
                const response = await this.form.put(`/api/codes-list/${this.form.id}`);
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
                await axios.delete(`/api/codes-list/${id}`);
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
                const response = await axios.get('/api/codes-list', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.codes = response.data.data; // Data for the current page
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                console.error('Error fetching Cities:', error);
                this.loading = false;
            }
        },
        showSidebar(code) {
            if (code) {
                this.form.fill({
                    codes_list: code.codes_list,
                    data_entry: code.data_entry,
                    label: code.label,
                    meaning: code.meaning,
                    is_active: code.is_active,
                    id: code.id,
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
