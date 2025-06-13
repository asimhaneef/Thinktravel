<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Permission</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button class="btn btn-success" @click="showSidebar(null)" style="border-radius: 20px;">
                        <i class="pi pi-plus"></i> Create Permission
                    </button>
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
                            <div class="p-2">
                                <input type="text" v-model="filters['global'].value" placeholder="Search..." class="form-control">
                            </div>
                            <DataTable v-model:filters="filters" size="small" :value="permissions" showGridlines
                                :rows="rows" :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                :globalFilterFields="['name', 'guard_name']"
                                @update:filters="onFilter" @sort="onSort" @page="onPage">

                                <Column field="name" header="Permission" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" placeholder="Search by Permission" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="guard_name" header="Guard" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" placeholder="Search by Guard" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="id" header="Action" style="min-width: 200px">
                                    <template #body="slotProps">
                                        <div class="d-flex justify-content-start">
                                            <!-- <a href="javascript:void(0)" class="m-2 icon-text" @click="showSidebar(slotProps.data)" title="Edit">
                                                <i class="pi pi-file-edit"></i>
                                            </a> -->
                                            <a href="javascript:void(0)" @click="confirmDelete(slotProps.data.id)" class="m-2 text-danger" title="Delete">
                                                <i class="pi pi-trash"></i>
                                            </a>
                                        </div>
                                    </template>
                                </Column>

                                <template #empty> No record found. </template>
                                <template #loading> Loading records. Please wait. </template>
                            </DataTable>

                            <Paginator :first="(page - 1) * rows" :rows="rows" :totalRecords="totalRecords"
                                :page="page - 1" :rowsPerPageOptions="[5, 10, 20]" @page="onPageChange">
                            </Paginator>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Edit Permission' : 'Create Permission'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr>
        <form @submit.prevent="editMode ? updatePermission() : createPermission()">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="permission_name">Permissions (*)</label>
                        <!-- <select v-model="form.permission_name" class="form-control" id="permission_name" required>
                            <option value="" disabled>Select Permission</option>
                            <option v-for="permission in permission_list" :key="permission.name" :value="permission.name">
                                {{ permission.name }}
                            </option>
                        </select> -->
                        <MultiSelect  v-model="form.permission_name" :options="permission_list" optionLabel="name" filter placeholder="Select Permission"
    :maxSelectedLabels="3" class="w-full" />
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="module_name">Module (*)</label>
                        <select v-model="form.module_name" class="form-control" id="module_name" required>
                            <option value="" disabled>Select Module</option>
                            <option v-for="table in tables" :key="table.module_name" :value="table.module_name">
                                {{ table.module_name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" :disabled="form.busy" class="btn btn-success">
                {{ editMode ? 'Update Permission' : 'Create Permission' }}
            </button>
        </form>
    </Sidebar>

    <Toast ref="toast"/>
    <ConfirmPopup />
</template>

<script>
import Form from 'vform';
import axios from 'axios';

export default {
    data() {
        return {
            form: new Form({ module_name: '', permission_name: '' }),
            permissions: [],
            visibleRight: false,
            editMode: false,
            loading: false,
            filters: { 'global': { value: null, matchMode: 'contains' } },
            totalRecords: 0,
            page: 1,
            rows: 20,
            sortField: null,
            sortOrder: null,
            tables: [],
            defaultTables: [
                { module_name: 'Dashboard' },
                { module_name: 'Lookup Data' },
                { module_name: 'System Menus' },
                { module_name: 'Application Menus' },
                { module_name: 'Roles & Permissions Menus' },
            ],
            selectedPermission: null,
            permission_list: [
                { name: "View" },
                { name: "Add" },
                { name: "Edit" },
                { name: "Delete" },
                { name: "Import" },
                { name: "Export" }
            ],
        };
    },
    methods: {
        async fetchTables() {
            try {
                const response = await axios.get('/api/get-tables');
                const apiTables = response.data || [];
                const combinedTables = [...this.defaultTables, ...apiTables];
                // Remove duplicates based on module_name
                this.tables = combinedTables.filter((table, index, self) =>
                    index === self.findIndex(t => t.module_name === table.module_name)
                );
            } catch (error) {
                console.error('Error fetching tables:', error);
            }
        },
        async getPermissions() {
            try {
                this.loading = true;
                const response = await axios.get('/api/permission', { params: { rows: this.rows, page: this.page } });
                this.permissions = response.data.data;
                this.totalRecords = response.data.total;
                this.loading = false;
            } catch (error) {
                this.loading = false;
            }
        },
        showSidebar(permission) {
            this.form.reset();
            this.editMode = !!permission;
            this.selectedPermission = permission || null;
            if (permission) {
                this.form.fill({
                    permission_name: permission.name,
                    module_name: permission.module_name,
                });
            }
            this.visibleRight = true;
        },
        async createPermission() {
            // console.log(this.form.module_name);
            try {
                await this.form.post('/api/permission');
                this.$toast.add({ severity: 'success', summary: 'Success', detail: 'Permission Created!', life: 3000 });
                this.getPermissions(); // Refresh permissions list
                this.visibleRight = false; // Close sidebar
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to create permission', life: 3000 });
            }
        },
        async updatePermission() {
            try {
                await this.form.put(`/api/permission/${this.selectedPermission.id}`);
                this.$toast.add({ severity: 'success', summary: 'Success', detail: 'Permission Updated!', life: 3000 });
                this.getPermissions();
                this.visibleRight = false;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update permission', life: 3000 });
            }
        },
        async deleteRecord(id) {
            try {
                this.isSubmitting = true;
                const response = await axios.delete(`/api/permission/${id}`);
                this.getPermissions();
                this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Record deleted successfully', life: 3000 });
            } catch (error) {
                this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });

            } finally {
                this.isSubmitting = false; // hide loader
            }
        },
        confirmDelete(id) {
            this.$confirm.require({
                message: 'Are you sure you want to delete this record?',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                accept: () => {
                    this.deleteRecord(id);
                },
                reject: () => {
                    // Do nothing on reject
                }
            });
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
            this.getPermissions();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getPermissions();
        }
    },
    created() {
        this.getPermissions();
        this.fetchTables();
    }
};
</script>
