<!-- resources/js/views/vendor/Reports.vue -->
<template>
    <div class="space-y-6">
        <h1 class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Vendor Reports & Analytics</h1>

        <!-- Date Range Filter -->
        <div :class="[
            'rounded-lg shadow-sm p-4',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">From Date</label>
                    <input type="date" v-model="filters.from_date" 
                        :class="[
                            'px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">To Date</label>
                    <input type="date" v-model="filters.to_date" 
                        :class="[
                            'px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                <div class="flex gap-2">
                    <button @click="setQuickRange(7)" 
                        :class="[
                            'px-3 py-2 border rounded-lg text-sm transition',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                        ]">Last 7 Days</button>
                    <button @click="setQuickRange(30)" 
                        :class="[
                            'px-3 py-2 border rounded-lg text-sm transition',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                        ]">Last 30 Days</button>
                    <button @click="setQuickRange(90)" 
                        :class="[
                            'px-3 py-2 border rounded-lg text-sm transition',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                        ]">Last 90 Days</button>
                </div>
                <div>
                    <button @click="loadReports" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">
                        Generate Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Total Appointments</p>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold mt-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ summary.total_appointments }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ summary.appointments_today }} today</p>
            </div>

            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Completed</p>
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-green-600 mt-2">{{ summary.completed_appointments }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ summary.completion_rate }}% completion rate</p>
            </div>

            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Total Revenue</p>
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold text-primary-600 mt-2">${{ summary.total_revenue }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">${{ summary.revenue_today }} today</p>
            </div>

            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Average Order Value</p>
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <p class="text-2xl font-bold mt-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">${{ summary.average_order_value }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">per appointment</p>
            </div>
        </div>

        <!-- Secondary Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Total Customers</p>
                <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ summary.total_customers }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ summary.repeat_customers }} repeat customers</p>
            </div>
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Upcoming Appointments</p>
                <p class="text-2xl font-bold text-blue-600">{{ summary.upcoming_appointments }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ summary.pending_appointments }} pending</p>
            </div>
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Recurring & Waitlist</p>
                <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ summary.recurring_appointments + summary.waitlist_appointments }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ summary.recurring_appointments }} recurring, {{ summary.waitlist_appointments }} waitlist</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <h3 class="text-lg font-semibold mb-4" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Revenue Trend</h3>
                <canvas id="revenueTrendChart"></canvas>
                <div v-if="!hasRevenueData" class="text-center py-8" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    No revenue data available for selected period
                </div>
            </div>
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <h3 class="text-lg font-semibold mb-4" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Popular Services</h3>
                <canvas id="popularServicesChart"></canvas>
                <div v-if="!hasServicesData" class="text-center py-8" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    No service data available for selected period
                </div>
            </div>
        </div>

        <!-- Staff Performance -->
        <div :class="[
            'rounded-lg shadow-sm overflow-hidden',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <div class="px-6 py-4 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Staff Performance</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Staff Member</th>
                                <th class="px-4 py-2 text-left text-sm font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Appointments</th>
                                <th class="px-4 py-2 text-left text-sm font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Revenue</th>
                                <th class="px-4 py-2 text-left text-sm font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Avg Order</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                            <tr v-for="staff in staffPerformance" :key="staff.id">
                                <td class="px-4 py-3 text-sm" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ staff.name }}</td>
                                <td class="px-4 py-3 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ staff.total_appointments }}</td>
                                <td class="px-4 py-3 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">${{ formatNumber(staff.total_revenue) }}</td>
                                <td class="px-4 py-3 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">${{ formatNumber(staff.avg_order_value) }}</td>
                            </tr>
                            <tr v-if="staffPerformance.length === 0">
                                <td colspan="4" class="px-4 py-8 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">No staff data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div :class="[
            'rounded-lg shadow-sm overflow-hidden',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <div class="px-6 py-4 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Top Customers</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div v-for="customer in topCustomers" :key="customer.id" class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ customer.name }}</p>
                            <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ customer.email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ customer.total_appointments }} appointments</p>
                            <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">${{ formatNumber(customer.total_spent) }} spent</p>
                        </div>
                    </div>
                    <div v-if="topCustomers.length === 0" class="text-center py-8" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        No customer data available
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Status Breakdown -->
        <div :class="[
            'rounded-lg shadow-sm overflow-hidden',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <div class="px-6 py-4 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Appointment Status Breakdown</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div v-for="status in statusBreakdown" :key="status.status" class="flex items-center">
                        <div class="w-32">
                            <span class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ status.status }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                                <div class="h-full rounded-full" :class="getStatusColor(status.status)" :style="{ width: `${status.percentage}%` }"></div>
                            </div>
                        </div>
                        <div class="w-32 text-right">
                            <span class="text-sm font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ status.count }} ({{ status.percentage }}%)</span>
                        </div>
                    </div>
                    <div v-if="statusBreakdown.length === 0" class="text-center py-8" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        No appointment data available
                    </div>
                </div>
            </div>
        </div>

        <!-- Peak Hours Analysis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <h3 class="text-lg font-semibold mb-4" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Peak Hours</h3>
                <canvas id="hourlyChart"></canvas>
                <div v-if="!hasHourlyData" class="text-center py-8" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    No hourly data available
                </div>
            </div>
            <div :class="[
                'rounded-lg shadow-sm p-6',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <h3 class="text-lg font-semibold mb-4" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Weekly Distribution</h3>
                <canvas id="weeklyChart"></canvas>
                <div v-if="!hasWeeklyData" class="text-center py-8" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    No weekly data available
                </div>
            </div>
        </div>

        <!-- Export Button -->
        <div class="flex justify-end">
            <button @click="exportReport" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Export CSV</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const toast = useToast();
const isDarkMode = ref(false);
let darkModeObserver = null;

const filters = ref({
    from_date: '',
    to_date: ''
});

const summary = ref({
    total_appointments: 0,
    completed_appointments: 0,
    total_revenue: 0,
    average_order_value: 0,
    appointments_today: 0,
    revenue_today: 0,
    total_customers: 0,
    repeat_customers: 0,
    upcoming_appointments: 0,
    pending_appointments: 0,
    recurring_appointments: 0,
    waitlist_appointments: 0,
    completion_rate: 0,
    cancellation_rate: 0,
    no_show_rate: 0
});

const statusBreakdown = ref([]);
const staffPerformance = ref([]);
const topCustomers = ref([]);

// Chart instances
let revenueTrendChart = null;
let popularServicesChart = null;
let hourlyChart = null;
let weeklyChart = null;

const chartData = ref({
    revenue_trend: [],
    popular_services: [],
    hourly_distribution: [],
    weekly_distribution: []
});

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
                        if (chartData.value.revenue_trend.length > 0) {
                            initCharts();
                        }
                    }, 100);
                }
            }
        });
    });
    darkModeObserver.observe(htmlElement, { attributes: true });
};

// Computed properties for checking data availability
const hasRevenueData = computed(() => {
    return chartData.value.revenue_trend && chartData.value.revenue_trend.length > 0;
});

const hasServicesData = computed(() => {
    return chartData.value.popular_services && chartData.value.popular_services.length > 0;
});

const hasHourlyData = computed(() => {
    return chartData.value.hourly_distribution && chartData.value.hourly_distribution.length > 0;
});

const hasWeeklyData = computed(() => {
    return chartData.value.weekly_distribution && chartData.value.weekly_distribution.length > 0;
});

const getChartColors = () => {
    return {
        gridColor: isDarkMode.value ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
        textColor: isDarkMode.value ? '#9ca3af' : '#6b7280',
        borderColor: isDarkMode.value ? '#374151' : '#e5e7eb'
    };
};

const formatNumber = (value) => {
    if (!value) return '0.00';
    return parseFloat(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

const getStatusColor = (status) => {
    const colors = {
        'Completed': 'bg-green-600',
        'Pending': 'bg-yellow-600',
        'Confirmed': 'bg-blue-600',
        'In Progress': 'bg-purple-600',
        'Cancelled': 'bg-red-600',
        'No Show': 'bg-gray-600'
    };
    return colors[status] || 'bg-primary-600';
};

const setQuickRange = (days) => {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - days);
    
    filters.value.from_date = startDate.toISOString().split('T')[0];
    filters.value.to_date = endDate.toISOString().split('T')[0];
    loadReports();
};

const loadReports = async () => {
    try {
        const response = await axios.get('/api/vendor/reports', { 
            params: {
                from_date: filters.value.from_date,
                to_date: filters.value.to_date
            }
        });
        
        const data = response.data;
        
        summary.value = data.summary;
        statusBreakdown.value = data.status_breakdown || [];
        staffPerformance.value = data.staff_performance || [];
        topCustomers.value = data.top_customers || [];
        
        chartData.value = {
            revenue_trend: data.revenue_trend || [],
            popular_services: data.popular_services || [],
            hourly_distribution: data.hourly_distribution || [],
            weekly_distribution: data.weekly_distribution || []
        };
        
        // Wait for DOM to update then initialize charts
        await nextTick();
        initCharts();
        
        toast.success('Reports loaded successfully');
    } catch (error) {
        console.error('Error loading reports:', error);
        toast.error('Failed to load reports');
    }
};

const initCharts = () => {
    const colors = getChartColors();
    
    // Destroy existing charts if they exist
    if (revenueTrendChart) revenueTrendChart.destroy();
    if (popularServicesChart) popularServicesChart.destroy();
    if (hourlyChart) hourlyChart.destroy();
    if (weeklyChart) weeklyChart.destroy();

    // Revenue Trend Chart
    const revenueCanvas = document.getElementById('revenueTrendChart');
    if (revenueCanvas && chartData.value.revenue_trend.length > 0) {
        const revenueCtx = revenueCanvas.getContext('2d');
        revenueTrendChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: chartData.value.revenue_trend.map(item => item.month),
                datasets: [{
                    label: 'Revenue ($)',
                    data: chartData.value.revenue_trend.map(item => item.revenue),
                    borderColor: '#3b82f6',
                    backgroundColor: isDarkMode.value ? 'rgba(59, 130, 246, 0.15)' : 'rgba(59, 130, 246, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: isDarkMode.value ? '#1f2937' : '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: colors.textColor
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            },
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    }
                }
            }
        });
    }

    // Popular Services Chart
    const servicesCanvas = document.getElementById('popularServicesChart');
    if (servicesCanvas && chartData.value.popular_services.length > 0) {
        const servicesCtx = servicesCanvas.getContext('2d');
        popularServicesChart = new Chart(servicesCtx, {
            type: 'bar',
            data: {
                labels: chartData.value.popular_services.map(item => item.name),
                datasets: [{
                    label: 'Number of Bookings',
                    data: chartData.value.popular_services.map(item => item.total),
                    backgroundColor: isDarkMode.value ? 'rgba(16, 185, 129, 0.7)' : '#10b981',
                    borderRadius: 4,
                    borderColor: isDarkMode.value ? 'rgba(5, 150, 105, 0.8)' : '#059669',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: colors.textColor
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' bookings';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    }
                }
            }
        });
    }

    // Hourly Distribution Chart
    const hourlyCanvas = document.getElementById('hourlyChart');
    if (hourlyCanvas && chartData.value.hourly_distribution.length > 0) {
        const hourlyCtx = hourlyCanvas.getContext('2d');
        hourlyChart = new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: chartData.value.hourly_distribution.map(item => item.label),
                datasets: [{
                    label: 'Appointments',
                    data: chartData.value.hourly_distribution.map(item => item.appointments),
                    backgroundColor: isDarkMode.value ? 'rgba(139, 92, 246, 0.7)' : '#8b5cf6',
                    borderRadius: 4,
                    borderColor: isDarkMode.value ? 'rgba(124, 58, 237, 0.8)' : '#7c3aed',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: colors.textColor
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    }
                }
            }
        });
    }

    // Weekly Distribution Chart
    const weeklyCanvas = document.getElementById('weeklyChart');
    if (weeklyCanvas && chartData.value.weekly_distribution.length > 0) {
        const weeklyCtx = weeklyCanvas.getContext('2d');
        weeklyChart = new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: chartData.value.weekly_distribution.map(item => item.day),
                datasets: [{
                    label: 'Appointments',
                    data: chartData.value.weekly_distribution.map(item => item.appointments),
                    backgroundColor: isDarkMode.value ? 'rgba(239, 68, 68, 0.7)' : '#ef4444',
                    borderRadius: 4,
                    borderColor: isDarkMode.value ? 'rgba(220, 38, 38, 0.8)' : '#dc2626',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: colors.textColor
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: colors.textColor
                        },
                        grid: {
                            color: colors.gridColor
                        }
                    }
                }
            }
        });
    }
};

const exportReport = async () => {
    try {
        const response = await axios.get('/api/vendor/reports/export', {
            params: {
                from_date: filters.value.from_date,
                to_date: filters.value.to_date
            },
            responseType: 'blob'
        });
        
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `vendor_report_${Date.now()}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
        
        toast.success('Report exported successfully');
    } catch (error) {
        console.error('Export error:', error);
        toast.error('Failed to export report');
    }
};

const initDateRange = () => {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 30);
    
    filters.value.from_date = startDate.toISOString().split('T')[0];
    filters.value.to_date = endDate.toISOString().split('T')[0];
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    initDateRange();
    loadReports();

    // Listen for storage changes from other tabs
    window.addEventListener('storage', (e) => {
        if (e.key === 'darkMode') {
            isDarkMode.value = e.newValue === 'true';
            setTimeout(() => {
                if (chartData.value.revenue_trend.length > 0) {
                    initCharts();
                }
            }, 100);
        }
    });
});

onUnmounted(() => {
    if (darkModeObserver) {
        darkModeObserver.disconnect();
    }
    // Clean up charts
    if (revenueTrendChart) revenueTrendChart.destroy();
    if (popularServicesChart) popularServicesChart.destroy();
    if (hourlyChart) hourlyChart.destroy();
    if (weeklyChart) weeklyChart.destroy();
});
</script>

<style scoped>
canvas {
    max-height: 300px;
    width: 100% !important;
}
</style>