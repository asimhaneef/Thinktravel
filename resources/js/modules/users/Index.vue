<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px">
                            Create User
                        </button>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card rounded-lg">
                        <div class="p-0 card-body">
                            <!-- <div class="p-2">
                                <input type="text" v-model="filters['global'].value" placeholder="Search..."
                                    class="form-control">
                            </div> -->
                            <DataTable v-model:filters="filters" :value="users" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                 @update:filters="onFilter" @sort="onSort"
                                @page="onPage">

                                <Column field="username" header="User Name" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by User Name" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="first_name" header="First Name" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by First Name" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="last_name" header="Last Name" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Last Name" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="phone" header="Phone Number" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Phone Number" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="email" header="Email" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Email" class="form-control" />
                                    </template>
                                </Column>
                                <Column field="manager.first_name" header="Manager Name" style="min-width: 12rem">
                                    <template #body="slotProps">
                                        {{ slotProps.data.manager?.first_name }} {{ slotProps.data.manager?.last_name }}
                                    </template>
                                </Column>
                                <Column field="roles" header="Role" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Role" class="form-control" />
                                    </template>
                                    <template #body="slotProps">
                                        {{ slotProps.data.roles?.[0]?.name }}
                                    </template>
                                </Column>

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
                                            
                                            <a
                                                href="javascript:void(0)"
                                                v-if="!authStore.isImpersonating"
                                                @click="impersonate(slotProps.data.id)"
                                                v-tooltip.top="'Impersonate'"
                                                class="m-2 text-primary "
                                                title="Impersonate"
                                            >
                                                <i class="pi pi-user-plus"></i>
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Update User' : 'Create User'" position="right"
        style="width: 45%">
        <hr />
        <form class="mt-3" @submit.prevent="editMode ? updateUser() : createUser()" @keydown="form.onKeydown($event)">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="username">User Name (*)</label>
                    <input type="text" v-model="form.username" class="form-control" id="username"
                        aria-describedby="userHelp" required/>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('username')"
                        v-html="form.errors.get('username')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="first_name">First Name (*)</label>
                    <input type="text" v-model="form.first_name" class="form-control" id="first_name"
                        aria-describedby="userHelp" required/>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('first_name')"
                        v-html="form.errors.get('first_name')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="last_name">Last Name (*)</label>
                    <input type="text" v-model="form.last_name" class="form-control" id="last_name"
                        aria-describedby="userHelp" required/>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('last_name')"
                        v-html="form.errors.get('last_name')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="user">Email (*)</label>
                    <input type="email" v-model="form.email" class="form-control" id="email"
                        aria-describedby="userHelp" required/>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('email')"
                        v-html="form.errors.get('email')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="phone">Phone</label>
                    <input type="text" v-model="form.phone" class="form-control" id="phone"
                        aria-describedby="userHelp" />
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('phone')"
                        v-html="form.errors.get('phone')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="manager_id">Line Manager </label>
                    <select v-model="form.manager_id" class="form-control" id="manager_id"
                        aria-describedby="role_Help">
                        <option value="">Select Manager</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            {{ user.first_name }} {{ user.last_name }}
                        </option>
                    </select>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('manager_id')"
                        v-html="form.errors.get('manager_id')"></small>
                </div>
                <div class="col-md-6 form-group" v-if="roles && roles.length > 0">
                    <label for="roles">Role <small class="text-danger">*</small></label>
                    <select v-model="form.role[0]" class="form-control" id="roles"
                        aria-describedby="role_Help" required>
                        <option value="">Select Role</option>
                        <option v-for="role in roles" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('roles')"
                        v-html="form.errors.get('roles')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="is_active">Is Active <small class="text-danger">*</small></label>
                    <select v-model="form.is_active" class="form-control" id="is_active"
                        aria-describedby="role_Help" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('is_active')"
                        v-html="form.errors.get('is_active')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="display_onlist">display Onlist </label>
                    <select v-model="form.display_onlist" class="form-control" id="display_onlist"
                        aria-describedby="role_Help" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    <small id="userHelp" class="form-text text-danger" v-if="form.errors.has('display_onlist')"
                        v-html="form.errors.get('display_onlist')"></small>
                </div>
            </div>
            <button :loading="isSubmitting" :disabled="form.busy" type="submit" class="btn btn-success">
                {{ editMode ?
                    "Update User" :
                    "Create User"
                }}
            </button>
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
import { useAuthStore } from "@/stores/authStore";

export default {
    components: {
        useConfirm
    },
    data() {
        return {
            authStore: useAuthStore(),
            form: new Form({
                username: "",
                first_name: "",
                last_name: "",
                manager_id: "",
                is_active: 1,
                display_onlist: 1,
                email: "",
                phone: "",
                role: [],
                id: null, // Add id to the form for editing
            }),
            users: [],
            roles: [],
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                username: { value: null, matchMode: 'contains' },
                first_name: { value: null, matchMode: 'contains' },
                last_name: { value: null, matchMode: 'contains' },
                phone: { value: null, matchMode: 'contains' },
                email: { value: null, matchMode: 'contains' },
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
        async createUser() {
            try {
                this.isSubmitting = true; // show loader
                const response = await this.form.post("/api/users");
                // handle success, e.g., show a success message or redirect
                this.getuser();
                this.$toast.add({ severity: 'info', summary: 'Success', detail: 'Record added successfully', life: 3000 });
                this.form.reset(); // Clear the form data
            } catch (error) {
                if (error.response.status != 422) {
                    this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error creating Record', life: 3000 });
                }
                this.isSubmitting = false;
            } finally {
                this.isSubmitting = false;
            }
        },
        async updateUser() {
            try {
                this.isSubmitting = true;
                const response = await this.form.put(`/api/users/${this.form.id}`);
                this.getuser();
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
        
        async getRoles() {
            try {
                const response = await axios.post('/api/getRoles');
                this.roles = response.data.roles;
            } catch (error) {
                console.error('Error fetching roles:', error);
            }
        },
        async getuser() {
            try {

                this.loading = true;
                const response = await axios.get('/api/users', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.users = response.data.users.data; // Data for the current page
                this.totalRecords = response.data.users.total; // Total number of records
                this.loading = false;
            } catch (error) {
                // handle error, e.g., show an error message
                console.error("Error getting user:", error);
            } finally {
                this.loading = false; // Set loading to false
            }
        },
        async deleteRecord(id) {
            try {
                await axios.delete(`/api/users/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getuser();
                this.isSubmitting = true;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
            } finally {
                this.isSubmitting = false;
            }
        },
        async impersonate(userId) {
            try {
                await axios.post(`/api/impersonate/${userId}`);
                location.reload();
            } catch (error) {
                console.error("Error impersonating user:", error);
            }
        },
        async stopImpersonation() {
            try {
                await axios.post("/api/leave-impersonate");
                location.reload();
            } catch (error) {
                console.error("Error stopping impersonation:", error);
            }
        },
        showSidebar(user) {
            if (user) {
                this.form.fill({
                    username: user.username,
                    first_name: user.first_name,
                    last_name: user.last_name,
                    manager_id: user.manager_id,
                    is_active: user.is_active,
                    display_onlist: user.display_onlist,
                    email: user.email,
                    phone: user.phone,
                    role: user.roles.map(r => r.id),
                    id: user.id, // Fill form with category details
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
            this.getuser();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getuser();
        }

    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getuser();
        this.getRoles();
    },
};
</script>
