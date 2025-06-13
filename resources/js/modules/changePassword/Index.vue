<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Change Password</h1>
                </div>
                <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Portal Link</li>
                    </ol>
                </div> -->
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <p class="m-0 card-title">Change Password</p>
                        </div>
                        <div class="card-body">
                            
                            <div v-if="isSubmitting" class="loader">Submitting...</div>
                            <div v-if="isLoading" class="loader">Loading...</div>
                            <form class="mt-3" @submit.prevent="updatePassword()" @keydown="form.onKeydown($event)">
                                <div class="form-group">
                                    <label for="link">Current Password</label>
                                    <input type="password" v-model="form.currentPassword" class="form-control" id="link">
                                    <small v-if="form.errors.has('currentPassword')" class="form-text text-danger" v-html="form.errors.get('currentPassword')"></small>
                                    <small v-if="currentPasswordRequired" class="form-text text-danger">Current password is required</small>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="link">New Password</label>
                                    <input type="password" v-model="form.newPassword" class="form-control" id="link">
                                    <small v-if="form.errors.has('newPassword')" class="form-text text-danger" v-html="form.errors.get('newPassword')"></small>
                                    <small v-if="passwordTooShort" class="form-text text-danger">Password must be at least 8 characters long</small>
                                </div>
                                <div class="form-group">
                                    <label for="link">Confirm Password</label>
                                    <input type="password" v-model="form.confirmPassword" class="form-control" id="link">
                                    <small v-if="form.errors.has('confirmPassword')" class="form-text text-danger" v-html="form.errors.get('confirmPassword')"></small>
                                    <small v-if="passwordMismatch" class="form-text text-danger">Passwords do not match</small>
                                </div>
                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Toast ref="toast" />
</template>

<script>
import { ref } from 'vue';
import Form from 'vform';
import axios from 'axios';  // Ensure axios is imported for HTTP requests

export default {
    data() {
        return {
            form: new Form({
                currentPassword: '',
                newPassword: '',
                confirmPassword: '',
                id: null
            }), 
            isSubmitting: false,  
            isLoading: false,  
            passwordMismatch: false,  // Track password mismatch
            passwordTooShort: false,    // Track if password is too short
            currentPasswordRequired: false,  // Track if current password is empty
        };
    },
    methods: {
        async updatePassword() {
            // Check if current password is provided
            if (!this.form.currentPassword) {
                this.currentPasswordRequired = true;
                return;
            }
            this.currentPasswordRequired = false;
            
            // Check if new password is at least 8 characters long
            if (this.form.newPassword.length < 8) {
                this.passwordTooShort = true;
                return;
            }
            this.passwordTooShort = false;

            if (this.form.newPassword !== this.form.confirmPassword) {
                this.passwordMismatch = true;
                return;
            }

            this.passwordMismatch = false;  // Reset error if passwords match

            try {
                this.isSubmitting = true; // show loader
                const response = await this.form.put("/api/updatePassword");
                this.$refs.toast.add({ severity: 'success', summary: 'Success', detail: 'Password Updated Successfully', life: 3000 });
            } catch (error) {
                this.$refs.toast.add({ severity: 'error', summary: 'Error', detail: 'Error Updating Password', life: 3000 });
                console.error("Error Updating Password:", error);
            } finally {
                this.isSubmitting = false; // hide loader
            }
        },
    }
};
</script>

<style scoped>
.icon-text {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 14px;
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