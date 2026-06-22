<!-- resources/js/views/admin/Dashboard.vue -->
<template>
    <div class="space-y-6 pb-8">
        <!-- Page Header -->
        <div>
            <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Admin Dashboard</h1>
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Overview of your platform's performance</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-for="stat in stats" :key="stat.label" 
                :class="[
                    'rounded-2xl shadow-lg p-5 border-l-4 transition-all duration-300 hover:shadow-xl',
                    stat.borderColor,
                    isDarkMode ? 'bg-gray-800' : 'bg-white'
                ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ stat.label }}</p>
                        <p class="text-2xl font-bold mt-1" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ stat.value }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-2xl"
                        :class="stat.bgColor">
                        {{ stat.icon }}
                    </div>
                </div>
                <div v-if="stat.change" class="mt-2">
                    <span :class="stat.change.startsWith('+') ? 'text-green-600' : 'text-red-600'" 
                        class="text-xs font-medium">
                        {{ stat.change }}
                    </span>
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">vs last month</span>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue Chart -->
            <div :class="[
                'rounded-2xl shadow-lg p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Revenue Overview</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Last 6 months</span>
                        <svg class="w-4 h-4" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <canvas ref="revenueChart"></canvas>
            </div>

            <!-- Status Chart -->
            <div :class="[
                'rounded-2xl shadow-lg p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Appointments by Status</h3>
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Current period</span>
                </div>
                <div class="flex flex-col items-center">
                    <canvas ref="statusChart" style="max-height: 250px; max-width: 250px;"></canvas>
                    <div class="flex flex-wrap justify-center gap-3 mt-4">
                        <div v-for="status in statusLabels" :key="status.label" class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full" :style="{ backgroundColor: status.color }"></span>
                            <span class="text-xs" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ status.label }}: {{ status.count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <router-link to="/admin/vendors" 
                :class="[
                    'rounded-2xl shadow-lg p-4 text-center hover:shadow-xl transition-all duration-200 hover:scale-105',
                    isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white'
                ]">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Manage Vendors</p>
            </router-link>
            <router-link to="/admin/plans" 
                :class="[
                    'rounded-2xl shadow-lg p-4 text-center hover:shadow-xl transition-all duration-200 hover:scale-105',
                    isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white'
                ]">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Manage Plans</p>
            </router-link>
            <router-link to="/admin/categories" 
                :class="[
                    'rounded-2xl shadow-lg p-4 text-center hover:shadow-xl transition-all duration-200 hover:scale-105',
                    isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white'
                ]">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Categories</p>
            </router-link>
            <router-link to="/admin/appointments" 
                :class="[
                    'rounded-2xl shadow-lg p-4 text-center hover:shadow-xl transition-all duration-200 hover:scale-105',
                    isDarkMode ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white'
                ]">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Appointments</p>
            </router-link>
        </div>

        <!-- Plans Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Plans -->
            <div :class="[
                'rounded-2xl shadow-lg overflow-hidden',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="px-6 py-4 border-b flex justify-between items-center" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                    <h3 class="text-lg font-semibold flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Subscription Plans
                    </h3>
                    <router-link to="/admin/plans" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                        View All →
                    </router-link>
                </div>
                <div class="divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                    <div v-for="plan in plans" :key="plan.id" class="p-4 transition" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ plan.name }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span :class="plan.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'" 
                                        class="px-2 py-0.5 text-xs font-semibold rounded-full">
                                        {{ plan.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ plan.prices?.length || 0 }} prices</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div v-if="plan.prices?.length" class="text-sm font-semibold text-primary-600">
                                    ${{ formatPrice(plan.prices[0]?.price) }}
                                    <span class="text-xs font-normal" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">/{{ plan.prices[0]?.interval }}</span>
                                </div>
                                <div v-else class="text-sm" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'">No prices</div>
                            </div>
                        </div>
                    </div>
                    <div v-if="plans.length === 0" class="p-8 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <p class="text-sm">No plans created yet</p>
                        <router-link to="/admin/plans" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                            Create your first plan →
                        </router-link>
                    </div>
                </div>
            </div>

            <!-- Recent Vendors -->
            <div :class="[
                'rounded-2xl shadow-lg overflow-hidden',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="px-6 py-4 border-b flex justify-between items-center" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                    <h3 class="text-lg font-semibold flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Recent Vendors
                    </h3>
                    <router-link to="/admin/vendors" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                        View All →
                    </router-link>
                </div>
                <div class="divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                    <div v-for="vendor in recentVendors" :key="vendor.id" class="p-4 transition" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 rounded-xl flex items-center justify-center">
                                    <span class="text-lg font-bold text-primary-700">{{ vendor.business_name?.charAt(0) || 'V' }}</span>
                                </div>
                                <div>
                                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ vendor.business_name }}</p>
                                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ vendor.user?.email }}</p>
                                </div>
                            </div>
                            <span :class="getVendorStatusClass(vendor.status)" class="px-2.5 py-1 text-xs font-semibold rounded-full capitalize">
                                {{ vendor.status }}
                            </span>
                        </div>
                    </div>
                    <div v-if="recentVendors.length === 0" class="p-8 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <p class="text-sm">No vendors registered yet</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import axios from 'axios';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const stats = ref([
    { 
        label: 'Total Vendors', 
        value: '0', 
        icon: '🏪', 
        change: '+12%',
        borderColor: 'border-l-primary-500',
        bgColor: 'bg-primary-100'
    },
    { 
        label: 'Total Customers', 
        value: '0', 
        icon: '👥', 
        change: '+8%',
        borderColor: 'border-l-blue-500',
        bgColor: 'bg-blue-100'
    },
    { 
        label: 'Total Appointments', 
        value: '0', 
        icon: '📅', 
        change: '+15%',
        borderColor: 'border-l-green-500',
        bgColor: 'bg-green-100'
    },
    { 
        label: 'Total Revenue', 
        value: '$0', 
        icon: '💰', 
        change: '+22%',
        borderColor: 'border-l-purple-500',
        bgColor: 'bg-purple-100'
    }
]);

const statusLabels = ref([
    { label: 'Pending', color: '#f59e0b', count: 0 },
    { label: 'Confirmed', color: '#3b82f6', count: 0 },
    { label: 'Completed', color: '#10b981', count: 0 },
    { label: 'Cancelled', color: '#ef4444', count: 0 }
]);

const recentVendors = ref([]);
const plans = ref([]);
const isDarkMode = ref(false);
let revenueChart = null;
let statusChart = null;
let darkModeObserver = null;

// Check dark mode from localStorage or system preference
const checkDarkMode = () => {
    const savedTheme = localStorage.getItem('darkMode');
    if (savedTheme !== null) {
        isDarkMode.value = savedTheme === 'true';
    } else {
        isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
};

// Watch for theme changes using MutationObserver
const setupDarkModeWatcher = () => {
    const htmlElement = document.documentElement;
    darkModeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const hasDarkClass = htmlElement.classList.contains('dark');
                if (hasDarkClass !== isDarkMode.value) {
                    isDarkMode.value = hasDarkClass;
                    localStorage.setItem('darkMode', String(hasDarkClass));
                    // Re-initialize charts when theme changes
                    setTimeout(() => {
                        initCharts();
                    }, 100);
                }
            }
        });
    });
    darkModeObserver.observe(htmlElement, { attributes: true });
};

const getVendorStatusClass = (status) => {
    const classes = {
        active: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        suspended: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        rejected: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
};

const formatPrice = (price) => {
    if (!price && price !== 0) return '0.00';
    return Number(price).toFixed(2);
};

const loadDashboard = async () => {
    try {
        const response = await axios.get('/api/admin/dashboard');
        const data = response.data;
        
        // Update stats
        stats.value[0].value = data.stats.total_vendors || 0;
        stats.value[1].value = data.stats.total_customers || 0;
        stats.value[2].value = data.stats.total_appointments || 0;
        stats.value[3].value = `$${data.stats.total_revenue || 0}`;
        
        // Update status labels with real data
        if (data.stats.appointment_status) {
            statusLabels.value = [
                { label: 'Pending', color: '#f59e0b', count: data.stats.appointment_status.pending || 0 },
                { label: 'Confirmed', color: '#3b82f6', count: data.stats.appointment_status.confirmed || 0 },
                { label: 'Completed', color: '#10b981', count: data.stats.appointment_status.completed || 0 },
                { label: 'Cancelled', color: '#ef4444', count: data.stats.appointment_status.cancelled || 0 }
            ];
        }
        
        recentVendors.value = data.recent_vendors || [];
        plans.value = data.plans || [];
        
        // Initialize charts after data is loaded
        setTimeout(() => {
            initCharts();
        }, 200);
    } catch (error) {
        console.error('Failed to load dashboard:', error);
    }
};

const getChartColors = () => {
    return {
        gridColor: isDarkMode.value ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
        textColor: isDarkMode.value ? '#9ca3af' : '#6b7280',
        labelColor: isDarkMode.value ? '#9ca3af' : '#6b7280'
    };
};

const initCharts = () => {
    // Destroy existing charts if they exist
    if (revenueChart) {
        revenueChart.destroy();
        revenueChart = null;
    }
    if (statusChart) {
        statusChart.destroy();
        statusChart = null;
    }

    const colors = getChartColors();

    // Revenue Chart
    const revenueCanvas = document.getElementById('revenueChart');
    if (revenueCanvas) {
        const ctx = revenueCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        const gradientColor = isDarkMode.value ? 'rgba(59, 130, 246, 0.3)' : 'rgba(59, 130, 246, 0.2)';
        const gradientEnd = isDarkMode.value ? 'rgba(59, 130, 246, 0.0)' : 'rgba(59, 130, 246, 0.0)';
        gradient.addColorStop(0, gradientColor);
        gradient.addColorStop(1, gradientEnd);
        
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [12500, 15900, 21000, 24500, 27800, 31000],
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: isDarkMode.value ? '#1f2937' : '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: colors.gridColor
                        },
                        ticks: {
                            color: colors.textColor
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: colors.textColor
                        }
                    }
                }
            }
        });
    }

    // Status Chart
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        const ctx = statusCanvas.getContext('2d');
        const data = statusLabels.value.map(s => s.count);
        const colors = statusLabels.value.map(s => s.color);
        
        statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: statusLabels.value.map(s => s.label),
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderColor: isDarkMode.value ? '#1f2937' : '#ffffff',
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%'
            }
        });
    }
};

// Clean up charts on component unmount
onUnmounted(() => {
    if (revenueChart) {
        revenueChart.destroy();
        revenueChart = null;
    }
    if (statusChart) {
        statusChart.destroy();
        statusChart = null;
    }
    if (darkModeObserver) {
        darkModeObserver.disconnect();
    }
});

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadDashboard();

    // Listen for storage changes from other tabs
    window.addEventListener('storage', (e) => {
        if (e.key === 'darkMode') {
            isDarkMode.value = e.newValue === 'true';
            setTimeout(() => {
                initCharts();
            }, 100);
        }
    });
});
</script>