<template>
    <Sidebar
        v-model:visible="tenantStore.sidebarVisible"
        :header="tenantStore.editMode ? 'Edit Tenant' : 'Create Tenant'"
        position="right"
        style="width: 45%"
        :dismissable="false"
    >
        <hr />
        <form @submit.prevent="handleSubmit">
            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="flex flex-column gap-2">
                        <label for="username">Username</label>
                        <InputText id="username" v-model="form.username" />
                    </div>
                    <small
                        v-if="form.errors.has('username')"
                        v-html="form.errors.get('username')"
                        class="text-danger"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="flex flex-column gap-2">
                        <label for="domain_name">Tenant Name</label>
                        <InputText
                            id="domain_name"
                            v-model="form.domain_name"
                        />
                    </div>
                    <small
                        v-if="form.errors.has('domain_name')"
                        v-html="form.errors.get('domain_name')"
                        class="text-danger"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="flex flex-column gap-2">
                        <label for="email">Email</label>
                        <InputText id="email" v-model="form.email" />
                    </div>
                    <small
                        v-if="form.errors.has('email')"
                        v-html="form.errors.get('email')"
                        class="text-danger"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="flex flex-column gap-2">
                        <label for="password">Password</label>
                        <InputText
                            id="password"
                            v-model="form.password"
                            type="password"
                        />
                    </div>
                    <small
                        v-if="form.errors.has('password')"
                        v-html="form.errors.get('password')"
                        class="text-danger"
                    />
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="flex flex-column gap-2">
                        <label for="confirm_password">Confirm Password</label>
                        <InputText
                            id="confirm_password"
                            v-model="form.confirm_password"
                            type="password"
                        />
                    </div>
                    <small
                        v-if="form.errors.has('confirm_password')"
                        v-html="form.errors.get('confirm_password')"
                        class="text-danger"
                    />
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.busy"
                class="btn btn-success mt-4"
            >
                {{ tenantStore.editMode ? "Update Tenant" : "Create Tenant" }}
            </button>
        </form>
    </Sidebar>
    <Toast />
</template>

<script>
import { useTenantStore } from "@/stores/TenantStore";
import { showToast } from "@/utils"; // Adjust path based on project setup
import Form from "vform";

export default {
    setup() {
        const tenantStore = useTenantStore();
        return { tenantStore };
    },
    data() {
        return {
            form: new Form({
                username: "",
                email: "",
                domain_name: "",
                password: "",
                confirm_password: "",
            }),
        };
    },
    watch: {
        "tenantStore.selectedTenant": {
            handler(newTenant) {
                if (newTenant) {
                    this.form.fill({
                        username: newTenant.name,
                        email: newTenant.email,
                    });
                } else {
                    this.form.reset();
                }
            },
            immediate: true,
        },
    },
    methods: {
        async handleSubmit() {
            if (this.tenantStore.editMode) {
                console.log("Update Tenant", this.form);
            } else {
                await this.form
                    .post("/api/tenant")
                    .then((response) => {
                        showToast(this.$toast, response.data); // ✅ Call reusable function
                        this.form.reset();
                        this.tenantStore.closeSidebar();
                        this.fetchTenants();
                    })
                    .then(() => {
                        // Optional: window.location.reload();
                    })

                    .catch((error) => {
                        console.error("Error:", error?.response?.data || error);
                    });
            }
            // this.tenantStore.closeSidebar();
        },
    },
};
</script>
