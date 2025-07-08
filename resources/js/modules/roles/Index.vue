<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Roles</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button
                        class="btn btn-success"
                        @click="showSidebar(null)"
                        style="border-radius: 20px"
                    >
                        Create - Role
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
                        <div class="card-body p-0">
                            <!-- <div class="p-2">
                                <input type="text" v-model="filters['global'].value" placeholder="Search..." class="form-control">
                            </div> -->
                            <DataTable
                                v-model:filters="filters"
                                size="small"
                                :value="Roles"
                                showGridlines
                                :rows="rows"
                                :totalRecords="totalRecords"
                                dataKey="id"
                                filterDisplay="menu"
                                :loading="loading"
                                :globalFilterFields="['name', 'guard_name']"
                                @update:filters="onFilter"
                                @sort="onSort"
                                @page="onPage"
                            >
                                <Column
                                    field="name"
                                    header="Role"
                                    style="min-width: 12rem"
                                    sortable
                                    :showFilterMatchModes="false"
                                >
                                    <template #filter="{ filterModel }">
                                        <InputText
                                            v-model="filterModel.value"
                                            placeholder="Search by Role"
                                            class="form-control"
                                        />
                                    </template>
                                </Column>

                                <Column
                                    field="guard_name"
                                    header="Guard"
                                    style="min-width: 12rem"
                                    sortable
                                    :showFilterMatchModes="false"
                                >
                                    <template #filter="{ filterModel }">
                                        <InputText
                                            v-model="filterModel.value"
                                            placeholder="Search by Short Code"
                                            class="form-control"
                                        />
                                    </template>
                                </Column>
                                <Column
                                    field="permissions"
                                    header="Permissions"
                                    style="min-width: 12rem"
                                >
                                    <template #body="slotProps">
                                        <div
                                            v-if="
                                                slotProps.data.permissions &&
                                                slotProps.data.permissions
                                                    .length
                                            "
                                            class="flex flex-wrap gap-2"
                                        >
                                            <Tag
                                                v-for="(
                                                    permission, index
                                                ) in slotProps.data.permissions"
                                                :key="index"
                                                :value="permission.name"
                                                severity="info"
                                            />
                                        </div>
                                        <Tag
                                            v-else
                                            value="--"
                                            severity="secondary"
                                        />
                                    </template>
                                </Column>

                                <Column
                                    field="id"
                                    header="Action"
                                    style="min-width: 200px"
                                >
                                    <template #body="slotProps">
                                        <div class="d-flex">
                                            <a href="javascript:void(0)" class="m-2" @click="showSidebar(slotProps.data)" title="Edit" >
                                                <i class="pi pi-file-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" @click="confirmDelete(slotProps.data.id)" class="m-2 text-danger" title="Delete">
                                                <i class="pi pi-trash"></i>
                                            </a>
                                        </div>
                                    </template>
                                </Column>

                                <template #empty> No record found. </template>
                                <template #loading>
                                    Loading records. Please wait.
                                </template>
                            </DataTable>

                            <Paginator
                                :first="(page - 1) * rows"
                                :rows="rows"
                                :totalRecords="totalRecords"
                                :page="page - 1"
                                :rowsPerPageOptions="[5, 10, 20]"
                                @page="onPageChange"
                            >
                            </Paginator>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Form -->
    <Sidebar
        v-model:visible="visibleRight"
        :header="editMode ? 'Edit Role' : 'Create Role'"
        position="right"
        style="width: 45%"
        :dismissable="false"
    >
        <hr />
        <form @submit.prevent="createRole()">
            <div class="form-group">
                <label for="name">Role*</label>
                <InputText v-model="form.name" class="form-control" id="name" />
                <small
                    v-if="form.errors.has('name')"
                    class="text-danger"
                    v-html="form.errors.get('name')"
                ></small>
            </div>

            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Module</th>
                        <th class="text-center">View</th>
                        <th class="text-center">Add</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                        <th class="text-center">Import</th>
                        <th class="text-center">Export</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(permissions, moduleName) in modules"
                        :key="moduleName"
                    >
                        <td>{{ moduleName }}</td>
                        <td class="text-center">
                            <input
                                type="checkbox"
                                v-model="permissions.view.checked"
                                v-if="permissions.view"
                            />
                        </td>
                        <td class="text-center"> 
                            <input
                                type="checkbox"
                                v-model="permissions.add.checked"
                                v-if="permissions.add"
                            />
                        </td>
                        <td class="text-center">
                            <input
                                type="checkbox"
                                v-model="permissions.edit.checked"
                                v-if="permissions.edit"
                            />
                        </td>
                        <td class="text-center">
                            <input
                                type="checkbox"
                                v-model="permissions.delete.checked"
                                v-if="permissions.delete"
                            />
                        </td>
                        <td class="text-center">
                            <input
                                type="checkbox"
                                v-model="permissions.import.checked"
                                v-if="permissions.import"
                            />
                        </td>
                        <td class="text-center">
                            <input
                                type="checkbox"
                                v-model="permissions.export.checked"
                                v-if="permissions.export"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>

            <button
                type="submit"
                :loading="isSubmitting"
                :disabled="form.busy"
                class="btn btn-success"
            >
                {{ editMode ? "Update Role" : "Create Role" }}
            </button>
        </form>
    </Sidebar>

    <Toast ref="toast"/>
    <ConfirmPopup />
</template>

<script>
import Form from "vform";
import axios from "axios";

export default {
    data() {
        return {
            form: new Form({ 
                id: null,
                name: "",
            }),
            selectedRole: null,
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                'name': { value: null, matchMode: 'contains' },
                'guard_name': { value: null, matchMode: 'contains' },
            },
            modules: {},
            Roles: [],
            departments: [],
            totalRecords: 0,
            page: 1,
            rows: 20,
            sortField: null,
            sortOrder: null,
        };
    },
    methods: {
        async getPermissions() {
            try {
                const response = await axios.get("/api/get-permissions");
                const permissionsData = response.data;

                this.modules = {};
                for (const moduleName in permissionsData) {
                    this.modules[moduleName] = {};
                    permissionsData[moduleName].forEach((permission) => {
                        const action = permission.name.split(".")[1];
                        this.modules[moduleName][action] = { checked: false };
                    });
                }
            } catch (error) {
                console.error("Error fetching permissions:", error);
            }
        },
        async createRole() {
            try {
                this.isSubmitting = true;
                const selectedPermissions = [];

                for (const moduleName in this.modules) {
                    for (const action in this.modules[moduleName]) {
                        if (this.modules[moduleName][action].checked) {
                            selectedPermissions.push(`${moduleName}.${action}`);
                        }
                    }
                }

                const payload = {
                    id: this.form.id,
                    name: this.form.name,                 
                    permissions: selectedPermissions,
                };
                await axios.post("/api/roles", payload);

                this.getRoles();
                this.visibleRight = false;
            } catch (error) {
                console.error("Error creating role:", error);
            } finally {
                this.isSubmitting = false;
            }
        },
        async getRoles() {
            this.loading = true;
            const response = await axios.get("/api/roles");
            this.Roles = response.data.data;
            this.totalRecords = response.data.total;
            this.loading = false;
        },
        showSidebar(role) {
            this.form.reset();
            // console.log(role);
            this.editMode = !!role;
            if (role) {
                this.form.id = role.id;
                this.form.name = role.name;
                // Reset all permissions to false first
                Object.keys(this.modules).forEach(module => {
                    Object.keys(this.modules[module]).forEach(permission => {
                        this.modules[module][permission].checked = false;
                    });
                });
                
                // Set checked permissions based on role.permissions
                if (role.permissions && role.permissions.length > 0) {
                    role.permissions.forEach(permission => {
                        const parts = permission.name.split('.');
                        const module = parts[0];
                        const permType = parts[1];
                        
                        if (this.modules[module] && this.modules[module][permType]) {
                            this.modules[module][permType].checked = true;
                        }
                    });
                }
            }
            this.visibleRight = true;
        },
        async getDepartment() {
            try {
                this.loading = true;
                const response = await axios.get("/api/department", {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page,
                    },
                });
                this.departments = response.data.data; // Data for the current page
            } catch (error) {
                console.error("Error fetching departments:", error);
                this.loading = false;
            }
        },
        async deleteRecord(id) {
            try {
                this.isSubmitting = true;
                const response = await axios.delete(`/api/roles/${id}`);
                this.getRoles();
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
            this.getRoles();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getRoles();
        }
    },

    /**
     * Lifecycle hook: created
     *
     * Fetches the list of roles and permissions when the component is created.
     * Also fetches the list of departments, but this is currently commented out.
     */
    created() {
        this.getRoles();
        this.getPermissions();
        // this.getDepartment();
    },
    computed: {
        // formattedDepartments() {
        //     return this.departments.map((dept) => ({
        //         name: dept.name,
        //         code: dept.id.toString(), // Convert ID to string for dropdown compatibility
        //     }));
        // },
    },
};
</script>
