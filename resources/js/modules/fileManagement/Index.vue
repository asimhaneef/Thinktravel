<template>
    <div v-if="loading" class="loader">Loading...</div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row p-3">
                <div class="col-sm-6">
                    <h1 class="m-0">Reference Documents</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <button class="btn btn-success" icon="pi pi-arrow-left" @click="showSidebar(null)"
                            style="border-radius: 20px">
                            Create Document
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
                            <div class="card-body p-0">
                                <DataTable :value="documents" :rows="rows" scrollable scrollHeight="400px" sortMode="multiple" tableStyle="min-width: 50rem" :loading="loading"
                                :filters="filters" paginator :rowsPerPageOptions="[10, 20, 50]">
                                    
                                    <Column field="document_name" header="Title" sortable></Column>
                                    <Column field="document_category" header="Category" sortable></Column>
                                    <Column field="document_access" header="Access Level" sortable></Column>
                                    <Column field="file" header="File" style="min-width: 200px">
                                        <template #body="slotProps">
                                            <a :href="APP_URL + slotProps.data.files?.file_real_path" target="_blank" >
                                                View 
                                            </a>
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
                                    <template #empty> No record found. </template>
                                    <template #loading> Loading records. Please wait. </template>
                                </DataTable>
                                
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <Sidebar v-model:visible="visibleRight" :header="form.id ? 'Update Document' : 'Add Document'" position="right"
        style="width: 45%;" :dismissable="false">
        <hr />
        <div v-if="isSubmitting" class="loader">Submitting...</div>
        <form class="mt-3" @submit.prevent="updateDocument()"
            @keydown="form.onKeydown($event)">
            <div class="row">
                <div class="form-group col-md-12">
                    <label for="course">Label (*)</label>
                    <input type="text" v-model="form.document_name" class="form-control" id="course"
                        aria-describedby="courseHelp" />                        
                    <small id="courseHelp" class="form-text text-danger" v-if="form.errors.has('document_name')"
                        v-html="form.errors.get('document_name')"></small>
                </div>
                <div class="form-group col-md-6">
                    <label for="document_access">Access Level (*)</label>
                    <select v-model="form.document_access" class="form-control" id="document_access">
                        <option v-for="level in AccessLevels" :key="level.id" :value="level.name">
                            {{ level.name }}
                        </option>
                    </select>
                    <small id="courseHelp" class="form-text text-danger" v-if="form.errors.has('document_access')"
                        v-html="form.errors.get('document_access')"></small>
                </div>
                <div class="form-group col-md-6">
                    <label for="document_category">Category (*)</label>
                    <select v-model="form.document_category" class="form-control" id="document_category">
                        <option v-for="DocumentCategory in DocumentCategories" :key="DocumentCategory.id" :value="DocumentCategory.name">
                            {{ DocumentCategory.name }}
                        </option>
                    </select>
                    <small id="courseHelp" class="form-text text-danger" v-if="form.errors.has('document_category')"
                        v-html="form.errors.get('document_category')"></small>
                </div>
                <div class="form-group col-md-3">
                    <label for="active">Active &nbsp;</label>
                    <InputSwitch v-model="form.active" class="form-control mt-3" />
                    <small class="text-danger" v-if="form.errors.has('active')"
                        v-html="form.errors.get('active')"></small>
                </div>
                <div class="form-group col-md-9">
                    <label for="file">File (*)</label>
                    <input type="file" class="form-control" id="file" @change="handleFile">
                    <small class="text-danger" v-if="form.errors.has('file')"
                        v-html="form.errors.get('file')"></small>
                </div>
                <div v-if="previous_file" class="form-group col-md-12">
                    <label for="file">Previous File: </label>
                    <a :href="previous_file_path" target="_blank">Download/View</a>
                </div>
                <div class="form-group col-md-12">
                    <label for="document_description">Description</label>
                    <Textarea v-model="form.document_description" id="document_description" rows="3" class="form-control"></Textarea>
                    <small id="ProgramHelp" class="form-text text-danger" v-if="form.errors.has('document_description')"
                        v-html="form.errors.get('document_description')"></small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </Sidebar>
    <Toast ref="toast" />
    <confirmDialog />
</template>
<script>
import axios from 'axios';
import { APP_URL } from '../../constants';
import { ref } from 'vue';
import Form from 'vform';

export default {
    components: {
        
    },
    data() {
        return { 
            APP_URL: APP_URL,
            documents: [],
            AccessLevels: [
                { id: 1, name: 'Public' },
                { id: 2, name: 'Private' },
                { id: 3, name: 'Both' },
            ],
            DocumentCategories: [
                { id: 1, name: 'Frequent' },
                { id: 1, name: 'Other' },
            ],
            form: new Form({
                id: null,
                document_name: "",
                document_access: "",
                document_category: "",
                document_description: "",
                active: true,
                file: null,
                previous_file: null,
                previous_file_path: null,
            }),
            previous_file: null,
            previous_file_path: null,            
            visibleRight: false,
            isSubmitting: false,
            editMode: false,
            loading: false,
            filters: {
                document_name: { value: null },
                document_category: { value: null }
            },
            initialLoad: true,
            rows: 10, // Number of records per page
            totalRecords: 0, // Total number of records
            sortField: null,
            sortOrder: null,
            page: 1, // Current page number (1-based index)
        };
    },
    methods: {
        async getDocuments() {
            try {
                this.loading = true;
                const response = await axios.get("/api/file-managment",{
                    params: {
                        filters: JSON.stringify(this.filters),
                        sortField: this.sortField,
                        sortOrder: this.sortOrder,
                        rows: this.rows,
                        page: this.page
                    }
                });
                // handle success, e.g., show a success message or redirect
                this.documents = response.data.data;
                this.totalRecords = response.data.total;
                this.loading = false;
            } catch (error) {
                // handle error, e.g., show an error message
                console.error("Error getting Program:", error);
                this.loading = false;
            } finally {
                this.loading = false; // Set loading to false
            }
        },
        async updateDocument(){
            this.isSubmitting = true; // show loader
            const formData = new FormData();
            for (const key in this.form) {
                if (this.form.hasOwnProperty(key)) {
                    if (key === 'file' && this.form[key]) {
                        formData.append(key, this.form[key]);
                    }  else {
                        formData.append(key, this.form[key]);
                    }
                }
            }
            try {
                await axios.post('/api/file-managment', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                if (this.form.id) {
                    this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Record updated successfully', life: 3000 });
                } else{
                    this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Record created successfully', life: 3000 });

                }
            } catch (error) {
                if (this.form.id) {
                    this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error updating record', life: 3000 });
                } else{
                    this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error creating record', life: 3000 });
                }
                if (error.response && error.response.data && error.response.data.errors) {
                    this.form.errors.set(error.response.data.errors);
                }
                console.error('Error saving post:', error);
            } finally {
                this.getDocuments();
                this.isSubmitting = false; // hide loader
                this.loading = false;
            }
        }, 
        
        async deleteRecord(id) {
                try {
                    this.isSubmitting = true;
                    const response = await axios.delete(`/api/file-managment/${id}`);
                    this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Record deleted successfully', life: 3000 });
                    
                } catch (error) {
                    this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error deleting record', life: 3000 });
                    
                } finally {
                    this.isSubmitting = false; // hide loader
                    this.loading = false;
                    this.getDocuments();
                }
        },       
        handleFile(event) {
            const file = event.target.files[0];
            this.form.file = file;
        },
        showSidebar(document) {
            if (document) {
                this.form.fill({
                    document_name: document.document_name,
                    document_category: document.document_category,
                    document_access: document.document_access,
                    document_description: document.document_description,
                    active: document.active == 1 ? true : false,
                    id: document.id, // Fill form with category details

                });
                if (document.files ) {
                    this.previous_file = document.files.file_name;
                    this.previous_file_path = APP_URL+document.files.file_real_path;
                }else{
                    this.previous_file = null;
                    this.previous_file_path = null;
                }
                this.editMode = true;
            } else {
                this.form.reset();
                this.editMode = false;
            }
            this.visibleRight = true;
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

    },
    created() {
        this.getDocuments();
    },
};
</script>

<style scoped>

.edit-icon {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  padding: 5px 10px;
  cursor: pointer;
  z-index: 10;
}

.edit-icon i {
  font-size: 16px;
  color: #000;
}
.trash-icon {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  padding: 5px 10px;
  cursor: pointer;
  z-index: 10;
}
.trash-icon i {
  font-size: 16px;
  color: #000;
}
.loader {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    z-index: 9999;
}
</style>