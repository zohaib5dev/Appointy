// resources/js/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [    
    {
        path: '/login',
        name: 'Login',
        component: () => import('../views/auth/Login.vue'),
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../views/auth/Register.vue'),
        meta: { guest: true }
    },
    {
    path: '/',
    component: () => import('../layouts/GuestLayout.vue'),
    children: [
        {
            path: '',
            name: 'Home',
            component: () => import('../views/Home.vue')
        },
        {
            path: 'about',
            name: 'About',
            component: () => import('../views/About.vue')
        },
        {
            path: 'contact',
            name: 'Contact',
            component: () => import('../views/Contact.vue')
        },
        {
            path: 'vendors',
            name: 'Vendors',
            component: () => import('../views/Vendors.vue')
        },
        {
            path: 'vendors/:slug',
            name: 'VendorDetail',
            component: () => import('../views/VendorDetail.vue')
        }
    ]
},

    {
        path: '/customer',
        component: () => import('../layouts/CustomerLayout.vue'),
        meta: { requiresAuth: true, role: 'customer' },
        children: [
          
            {
                path: 'dashboard',
                name: 'CustomerDashboard',
                component: () => import('../views/customer/Dashboard.vue')
            },
            {
                path: 'appointments',
                name: 'CustomerAppointments',
                component: () => import('../views/customer/Appointments.vue')
            },
            {
                path: 'profile',
                name: 'CustomerProfile',
                component: () => import('../views/customer/Profile.vue')
            }
        ]
    },
    {
        path: '/vendor',
        component: () => import('../layouts/VendorLayout.vue'),
        meta: { requiresAuth: true, role: 'vendor' },
        children: [
            {
                path: 'dashboard',
                name: 'VendorDashboard',
                component: () => import('../views/vendor/Dashboard.vue')
            },
            {
                path: 'appointments',
                name: 'VendorAppointments',
                component: () => import('../views/vendor/Appointments.vue')
            },
            {
                path: 'invoices',
                name: 'VendorInvoices',
                component: () => import('../views/vendor/Invoices.vue')
            },
            {
                path: 'services',
                name: 'VendorServices',
                component: () => import('../views/vendor/Services.vue')
            },
            {
                path: 'staff',
                name: 'VendorStaff',
                component: () => import('../views/vendor/Staff.vue')
            },
            {
                path: 'schedule',
                name: 'VendorSchedule',
                component: () => import('../views/vendor/Schedule.vue')
            },
            {
                path: 'holidays',
                name: 'VendorHolidays',
                component: () => import('../views/vendor/Holidays.vue')
            },
            {
                path: 'reports',
                name: 'VendorReports',
                component: () => import('../views/vendor/Reports.vue')
            },
            {
                path: 'settings',
                name: 'VendorSettings',
                component: () => import('../views/vendor/Settings.vue')
            },
            {
                path: 'profile',
                name: 'VendorProfile',
                component: () => import('../views/vendor/Profile.vue')
            },
            {
                path: 'subscription',
                name: 'VendorSubscriptions',
                component: () => import('../views/vendor/Subscriptions.vue')
            },

        ]
    },
    {
        path: '/admin',
        component: () => import('../layouts/AdminLayout.vue'),
        meta: { requiresAuth: true, role: ['admin', 'super_admin'] }, // Allow both admin and super_admin
        children: [
            {
                path: 'dashboard',
                name: 'AdminDashboard',
                component: () => import('../views/admin/Dashboard.vue')
            },
            {
                path: 'plans',
                name: 'AdminPlans',
                component: () => import('../views/admin/Plans.vue')
            },
            {
                path: 'vendors',
                name: 'AdminVendors',
                component: () => import('../views/admin/Vendors.vue')
            },
            {
                path: 'invoices',
                name: 'AdminInvoices',
                component: () => import('../views/admin/Invoices.vue')
            },
            {
                path: 'customers',
                name: 'AdminCustomers',
                component: () => import('../views/admin/Customers.vue')
            },
            {
                path: 'categories',
                name: 'AdminCategories',
                component: () => import('../views/admin/Categories.vue')
            },
            {
                path: 'appointments',
                name: 'AdminAppointments',
                component: () => import('../views/admin/Appointments.vue')
            },
               {
                path: 'profile',
                name: 'AdminProfile',
                component: () => import('../views/admin/Profile.vue')
            },
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated;
    const userRole = authStore.user?.role;

    // Check if route requires authentication
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
        return;
    }

    // Check if route has role restrictions
    if (to.meta.requiresAuth && to.meta.role) {
        const allowedRoles = Array.isArray(to.meta.role) ? to.meta.role : [to.meta.role];

        // Map super_admin to admin for routing purposes
        const normalizedUserRole = userRole === 'super_admin' ? 'admin' : userRole;

        if (!allowedRoles.includes(normalizedUserRole) && !allowedRoles.includes(userRole)) {
            // Redirect to appropriate dashboard based on role
            if (userRole === 'super_admin' || userRole === 'admin') {
                next('/admin/dashboard');
            } else if (userRole === 'vendor') {
                next('/vendor/dashboard');
            } else if (userRole === 'customer') {
                next('/customer/dashboard');
            } else {
                next('/');
            }
            return;
        }
    }

    // Redirect guest users away from auth pages
    if (to.meta.guest && isAuthenticated) {
        const userRole = authStore.user?.role;
        if (userRole === 'super_admin' || userRole === 'admin') {
            next('/admin/dashboard');
        } else if (userRole === 'vendor') {
            next('/vendor/dashboard');
        } else if (userRole === 'customer') {
            next('/customer/dashboard');
        } else {
            next('/');
        }
        return;
    }

    next();
});

export default router;