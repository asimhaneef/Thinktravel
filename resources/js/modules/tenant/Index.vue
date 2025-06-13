<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="mb-2 row">
                <div class="col-sm-6">
                    <h1 class="m-0">Tenants</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button
                        class="btn btn-success"
                        @click="tenantStore.showSidebar()"
                        style="border-radius: 20px"
                    >
                        <i class="pi pi-plus"></i> Create Tenant
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
                            <DataTable :value="tenants" showGridlines>
                                <Column
                                    field="username"
                                    header="Tenant"
                                    sortable
                                >
                                    <template #filter="{ filterModel }">
                                        <InputText
                                            v-model="filterModel.value"
                                            placeholder="Search by Tenant"
                                            class="form-control"
                                        />
                                    </template>
                                </Column>

                                <Column field="email" header="Email" sortable />

                                <Column
                                    field="domain_name"
                                    header="Domain Name"
                                    sortable
                                />

                                <Column
                                    field="id"
                                    header="Action"
                                    style="min-width: 200px"
                                >
                                    <template #body="slotProps">
                                        <div
                                            class="d-flex justify-content-start"
                                        >
                                            <a
                                                href="javascript:void(0)"
                                                class="m-2 icon-text"
                                                @click="
                                                    tenantStore.showSidebar(
                                                        slotProps.data
                                                    )
                                                "
                                                title="Edit"
                                            >
                                                <i class="pi pi-file-edit"></i>
                                            </a>
                                            <a
                                                href="javascript:void(0)"
                                                @click="
                                                    handleDelete(
                                                        slotProps.data.id
                                                    )
                                                "
                                                class="m-2 text-danger"
                                                title="Delete"
                                            >
                                                <i class="pi pi-trash"></i>
                                            </a>
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tenant Sidebar Component -->
    <TenantDrawer />
</template>

<script>
import { useTenantStore } from "@/stores/TenantStore";
import TenantDrawer from "./TenantDrawer.vue";
import axios from "axios";

export default {
    components: { TenantDrawer },
    setup() {
        const tenantStore = useTenantStore();
        return { tenantStore };
    },
    data() {
        return {
            tenants: [],
        };
    },
    methods: {
        async fetchTenants() {
            try {
                const response = await axios.get("/api/tenant");
                this.tenants = response.data.data; // ✅ Fix: Assign data properly
                
            } catch (error) {
                
                console.error("Error fetching tenants:", error);
            }
        },

        async handleDelete(id) {
            if (!confirm("Are you sure you want to delete this tenant?"))
                return;

            try {
                await axios.delete(`/api/tenant/${id}`);
                this.tenants = this.tenants.filter(
                    (tenant) => tenant.id !== id
                );
               
            } catch (error) {
                
                console.error("Error deleting tenant:", error);
            }
        },
    },
    mounted() {
        this.fetchTenants();
    },
};
</script>
