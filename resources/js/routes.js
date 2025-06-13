export default [
    //Website Routes Starts
    {
        path: "/",
        name: "/",
        component: () => import("./modules/webPages/Index.vue"),
    },
    {
        path: "/about",
        name: "/about",
        component: () => import("./modules/webPages/About.vue"),
    },
    {
        path: "/contact",
        name: "/contact",
        component: () => import("./modules/webPages/Contact.vue"),
    },
    
    {
        path: "/inquiry",
        name: "/inquiry",
        component: () => import("./modules/webPages/Inquiry.vue"),
    },
    {
        path: "/documents",
        name: "/documents",
        component: () => import("./modules/webPages/Documents.vue"),
    },
    {
        path: "/flights",
        name: "/flights",
        component: () => import("./modules/webPages/Flights.vue"),
    },
    {
        path: "/vacations",
        name: "/vacations",
        component: () => import("./modules/webPages/Vacations.vue"),
    },
    {
        path: "/cruises",
        name: "/cruises",
        component: () => import("./modules/webPages/Cruises.vue"),
    },
    //Website Routes Ends

    //Admin Routes Starts
    {
        path: "/inquiries-dashboard",
        name: "/admin.inquiries-dashboard",
        component: () => import("./modules/inquiryDashboard/Index.vue"),
        meta: {
            requiresPermission: "dashboard.view", // Permission required for this route
        },
    },
    {
        path: "/sales-dashboard",
        name: "/admin.sales-dashboard",
        component: () => import("./modules/salesDashboard/Index.vue"),
        meta: {
            requiresPermission: "dashboard.view", // Permission required for this route
        },
    },
    {
        path: "/dashboard",
        name: "/admin.dashboard",
        component: () => import("./modules/dashboard/Index.vue"),
        meta: {
            requiresPermission: "dashboard.view", // Permission required for this route
        },
    },
    {
        path: "/codes-list",
        name: "/admin.codes-list",
        component: () => import("./modules/codes/Index.vue"),
        meta: {
            requiresPermission: "codes-lists.view", // Permission required for this route
        },
    },
    {
        path: "/regions",
        name: "/admin.regions",
        component: () => import("./modules/regions/Index.vue"),
        meta: {
            requiresPermission: "regions.view", // Permission required for this route
        },
    },
    {
        path: "/users",
        name: "/admin.users",
        component: () => import("./modules/users/Index.vue"),
        meta: {
            requiresPermission: "users.view", // Permission required for this route
        },
    },
    {
        path: "/airports",
        name: "admin.airports",
        component: () => import("./modules/airports/Index.vue"),
        meta: {
            requiresPermission: "airports.view", // Permission required for this route
        },
    },    
    {
        path: "/airlines",
        name: "admin.airlines",
        component: () => import("./modules/airlines/Index.vue"),
        meta: {
            requiresPermission: "airlines.view", // Permission required for this route
        },
    },
    {
        path: "/countries",
        name: "admin.countries",
        component: () => import("./modules/country/Index.vue"),
        meta: {
            requiresPermission: "countries.view", // Permission required for this route
        },
    },
    {
        path: "/provinces",
        name: "admin.provinces",
        component: () => import("./modules/provinces/Index.vue"),
        meta: {
            requiresPermission: "provinces.view", // Permission required for this route
        },
    },
    {
        path: "/link-categories",
        name: "admin.link-categories",
        component: () => import("./modules/linkCategories/Index.vue"),
        meta: {
            requiresPermission: "link-categories.view", // Permission required for this route
        },
    },    
    {
        path: "/cities",
        name: "admin.cities",
        component: () => import("./modules/cities/Index.vue"),
        meta: {
            requiresPermission: "cities.view", // Permission required for this route
        },
    },
    {
        path: "/members",
        name: "admin.members",
        component: () => import("./modules/members/Index.vue"),
        meta: {
            requiresPermission: "members.view", // Permission required for this route
        },
    },
    {
        path: "/file-management",
        name: "admin.fileManagement",
        component: () => import("./modules/fileManagement/Index.vue"),
    },    
    {
        path: "/inquiries",
        name: "admin.inquiries",
        component: () => import("./modules/inquiries/Index.vue"),
        meta: {
            requiresPermission: "bookings.view", // Permission required for this route
        },
    },   
    {
        path: "/sale-form",
        name: "admin.sale-form",
        component: () => import("./modules/saleForm/Index.vue"),
        meta: {
            requiresPermission: "sale-forms.view", // Permission required for this route
        },
    },
    {
        path: "/sale-form-add/:id",
        name: "admin.sale-form-add",
        component: () => import("./modules/saleForm/Index.vue"),
        meta: {
            requiresPermission: "sale-forms.edit", // Permission required for this route
        },
    },
    {
        path: "/change-password",
        name: "admin.change-password",
        component: () => import("./modules/changePassword/Index.vue"),
    }, 
    {
        path: "/roles",
        name: "admin.roles",
        component: () => import("./modules/roles/Index.vue"),
        // meta: {
        //     requiresPermission: "tenants.view", // Permission required for this route
        // },
    },
    {
        path: "/permissions",
        name: "admin.permissions",
        component: () => import("./modules/permissions/Index.vue"),
        // meta: {
        //     requiresPermission: "tenants.view", // Permission required for this route
        // },
    },
    {
        path: "/Tenant",
        name: "Tenant",
        component: () => import("./modules/tenant/Index.vue"),
        // meta: {
        //     requiresPermission: "tenants.view", // Permission required for this route
        // },
    },
    
    {
        path: "/two-factor-challenge",
        name: "admin.two-factor-challenge",
        component: () => import("./modules/profile/TwoFactorChallenge.vue"),
        // meta: {
        //     requiresPermission: "tenants.view", // Permission required for this route
        // },
    }, 
];
