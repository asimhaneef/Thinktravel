<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Members</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px">
                            Create Member
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
                    <div class="card">
                        <div class="p-0 card-body">
                            <!-- <div class="p-2">
                                <input type="text" v-model="filters['global'].value" placeholder="Search..."
                                    class="form-control">
                            </div> -->
                            <DataTable v-model:filters="filters" :value="members" showGridlines :rows="rows"
                                :totalRecords="totalRecords" dataKey="id" filterDisplay="menu" :loading="loading"
                                 @update:filters="onFilter" @sort="onSort"
                                @page="onPage">

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
                                
                                <Column field="phone_no" header="Phone Number" style="min-width: 12rem" sortable>
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

                                <Column field="postal_code" header="Postal Code" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Postal Code" class="form-control" />
                                    </template>
                                </Column>

                                <!-- <Column field="member_code" header="Member Code" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Member Code" class="form-control" />
                                    </template>
                                </Column> -->

                                <Column field="city" header="City" style="min-width: 12rem" sortable>
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by City" class="form-control" />
                                    </template>
                                </Column>

                                <Column field="country.country" header="Country" style="min-width: 12rem" sortable :showFilterMatchModes="false">
                                    <template #filter="{ filterModel }">
                                        <InputText v-model="filterModel.value" type="text"
                                            placeholder="Search by Country" class="form-control" />
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
    <Sidebar v-model:visible="visibleRight" :header="editMode ? 'Update Member' : 'Create Member'" position="right"
        style="width: 45%">
        <hr />
        <form class="mt-3" @submit.prevent="editMode ? updateMember() : createMember()" @keydown="form.onKeydown($event)">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="first_name">First Name (*)</label>
                    <input type="text" v-model="form.first_name" class="form-control" id="first_name"
                        aria-describedby="memberHelp" required/>
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('first_name')"
                        v-html="form.errors.get('first_name')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="last_name">Last Name (*)</label>
                    <input type="text" v-model="form.last_name" class="form-control" id="last_name"
                        aria-describedby="memberHelp" required/>
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('last_name')"
                        v-html="form.errors.get('last_name')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="user_name">User Name (*)</label>
                    <input type="text" v-model="form.user_name" class="form-control" id="user_name"
                        aria-describedby="memberHelp" required/>
                    <small id="user_nameHelp" class="form-text text-danger" v-if="form.errors.has('user_name')"
                        v-html="form.errors.get('user_name')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="phone_no">Phone (*)</label>
                    <input type="text" v-model="form.phone_no" class="form-control" id="phone_no"
                        aria-describedby="memberHelp" required/>
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('phone_no')"
                        v-html="form.errors.get('phone_no')"></small>
                </div>                
                <div class="col-md-6 form-group">
                    <label for="gender">Gender</label>
                    <select v-model="form.gender" class="form-control" id="gender"
                        aria-describedby="genderHelp">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('gender')"
                        v-html="form.errors.get('gender')"></small>
                </div>     
                <div class="col-md-6 form-group">
                    <label for="member_code">Member Code</label>
                    <input type="text" v-model="form.member_code" class="form-control" id="member_code"
                        aria-describedby="memberHelp" />
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('member_code')"
                        v-html="form.errors.get('member_code')"></small>
                </div>           
                <div class="col-md-12 form-group">
                    <label for="email">Email (*)</label>
                    <input type="email" v-model="form.email" class="form-control" id="email"
                    aria-describedby="memberHelp" required/>
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('email')"
                    v-html="form.errors.get('email')"></small>
                </div>
                <div class="col-md-12 form-group">
                    <label for="address">Address</label>
                    <input type="text" v-model="form.address" class="form-control" id="address"
                        aria-describedby="memberHelp" />
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('address')"
                        v-html="form.errors.get('address')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="country_id">Country (*)</label>
                    <select type="text" v-model="form.country_id" class="form-control" id="country_id"
                        aria-describedby="country_idHelp" required>
                        <option value="">Select Country</option>
                        <option v-for="country in countries" :key="country.id" :value="country.id">
                            {{ country.country }}
                        </option>
                    </select>
                    <small id="country_idHelp" class="form-text text-danger" v-if="form.errors.has('country_id')"
                        v-html="form.errors.get('country_id')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="city">City</label>
                    <input type="text" v-model="form.city" class="form-control" id="city"
                        aria-describedby="memberHelp" />
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('city')"
                        v-html="form.errors.get('city')"></small>
                </div>
                <div class="col-md-6 form-group">
                    <label for="postal_code">Postal Code</label>
                    <input type="text" v-model="form.postal_code" class="form-control" id="postal_code"
                        aria-describedby="memberHelp" />
                    <small id="memberHelp" class="form-text text-danger" v-if="form.errors.has('postal_code')"
                        v-html="form.errors.get('postal_code')"></small>
                </div>
            </div>
            <button :loading="isSubmitting" :disabled="form.busy" type="submit" class="btn btn-success">
                {{ editMode ?
                    "Update Member" :
                    "Create Member"
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

export default {
    components: {
        useConfirm,
    },
    data() {
        return {
            form: new Form({
                first_name: "",
                last_name: "",
                phone_no: "",
                gender: "",
                email: "",
                address: "",
                postal_code: "",
                city: "",
                country_id: "",
                member_code: "",
                user_name: "",
                id: null, // Add id to the form for editing
            }),
            members: [],
            countries: [],
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                first_name: { value: null, matchMode: 'contains' },
                last_name: { value: null, matchMode: 'contains' },
                phone_no: { value: null, matchMode: 'contains' },
                email: { value: null, matchMode: 'contains' },
                address: { value: null, matchMode: 'contains' },
                postal_code: { value: null, matchMode: 'contains' },
                city: { value: null, matchMode: 'contains' },
                member_code: { value: null, matchMode: 'contains' },
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
        
        async getCountries() {
            try {
                this.loading = true;
                const response = await axios.get('/api/country');
                this.countries = response.data.data;
            } catch (error) {
                console.error('Error fetching records:', error);
                this.loading = false;
            }
        },
        async createMember() {
            try {
                this.isSubmitting = true; // show loader
                const response = await this.form.post("/api/members");
                // handle success, e.g., show a success message or redirect
                this.getmember();
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
        async updateMember() {
            try {
                this.isSubmitting = true;
                const response = await this.form.put(`/api/members/${this.form.id}`);
                this.getmember();
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
        async getmember() {
            try {

                this.loading = true;
                const response = await axios.get('/api/members', {
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                this.members = response.data.data;
                this.totalRecords = response.data.total; // Total number of records
                this.loading = false;
            } catch (error) {
                // handle error, e.g., show an error message
                console.error("Error getting member:", error);
            } finally {
                this.loading = false; // Set loading to false
            }
        },
        async deleteRecord(id) {
            try {
                await axios.delete(`/api/members/${id}`);
                this.$toast.add({ severity: 'info', summary: 'Deleted Successfully!', detail: 'Record deleted successfully', life: 3000 });
                this.getmember();
                this.isSubmitting = true;
            } catch (error) {
                this.$toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
            } finally {
                this.isSubmitting = false;
            }
        },
        showSidebar(member) {
            if (member) {
                this.form.fill({
                    first_name: member.first_name,
                    last_name: member.last_name,
                    user_name: member.user_name,
                    gender: member.gender,
                    phone_no: member.phone_no,
                    email: member.email,
                    address: member.address,
                    postal_code: member.postal_code,
                    member_code: member.member_code,
                    city: member.city,
                    country_id: member.country_id,
                    id: member.id, 
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
            this.getmember();
        },
        onPageChange(event) {
            this.page = event.page + 1;
            this.rows = event.rows;
            this.getmember();
        }

    },
    mounted() {
        this.checkFirstLogin();  // Call this method after user login or page load
    },
    created() {
        this.getmember();
        this.getCountries();
    },
};
</script>
