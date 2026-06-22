<!-- resources/js/views/customer/Dashboard.vue -->
<template>
    <div :class="['space-y-8 transition-colors duration-300', isDarkMode ? 'bg-gray-900' : '']">
  
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-700 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ customerName }}!</h1>
                    <p class="text-primary-100">Here's what's happening with your appointments today.</p>
                </div>
                <router-link 
                    to="/vendors" 
                    class="bg-white text-primary-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Book New Appointment
                </router-link>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div :class="[
                'rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1',
                isDarkMode ? 'bg-gray-800 hover:shadow-gray-700' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p :class="['text-sm font-medium', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Upcoming Appointments</p>
                        <p :class="['text-3xl font-bold mt-2', isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.upcoming }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full" :style="{ width: `${(stats.upcoming / Math.max(stats.upcoming + stats.completed, 1)) * 100}%` }"></div>
                    </div>
                </div>
            </div>

            <div :class="[
                'rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1',
                isDarkMode ? 'bg-gray-800 hover:shadow-gray-700' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p :class="['text-sm font-medium', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Completed</p>
                        <p :class="['text-3xl font-bold mt-2', isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.completed }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-green-600 dark:text-green-400 mt-2">+{{ stats.completedThisMonth }} this month</p>
            </div>

            <div :class="[
                'rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1',
                isDarkMode ? 'bg-gray-800 hover:shadow-gray-700' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p :class="['text-sm font-medium', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Total Spent</p>
                        <p :class="['text-3xl font-bold mt-2', isDarkMode ? 'text-white' : 'text-gray-900']">${{ formatMoney(stats.total_spent) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p :class="['text-sm mt-2', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Average ${{ getAverageSpending() }} per booking</p>
            </div>

            <div :class="[
                'rounded-xl shadow-md p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1',
                isDarkMode ? 'bg-gray-800 hover:shadow-gray-700' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p :class="['text-sm font-medium', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Favorite Vendors</p>
                        <p :class="['text-3xl font-bold mt-2', isDarkMode ? 'text-white' : 'text-gray-900']">{{ stats.favorite_vendors }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                </div>
                <p :class="['text-sm mt-2', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Most visited: {{ topVendor || 'N/A' }}</p>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div :class="[
            'rounded-xl shadow-md overflow-hidden',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <div :class="[
                'px-6 py-4 border-b flex justify-between items-center',
                isDarkMode ? 'border-gray-700' : 'border-gray-200'
            ]">
                <div>
                    <h3 :class="['text-lg font-semibold', isDarkMode ? 'text-white' : 'text-gray-900']">Upcoming Appointments</h3>
                    <p :class="['text-sm mt-1', isDarkMode ? 'text-gray-400' : 'text-gray-500']">You have {{ upcomingAppointments.length }} upcoming appointments</p>
                </div>
                <router-link to="/customer/appointments" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium flex items-center gap-1">
                    View all
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </router-link>
            </div>
            
            <div :class="['divide-y', isDarkMode ? 'divide-gray-700' : 'divide-gray-200']">
                <div v-for="appointment in upcomingAppointments" :key="appointment.id" :class="['p-6 transition-colors', isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50']">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-2xl font-bold text-primary-700 dark:text-primary-300">{{ appointment.vendor?.business_name?.charAt(0) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h4 :class="['text-lg font-semibold', isDarkMode ? 'text-white' : 'text-gray-900']">{{ appointment.vendor?.business_name }}</h4>
                                    <span :class="getStatusClass(appointment.status)" class="px-2 py-0.5 text-xs font-semibold rounded-full">
                                        {{ appointment.status }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm mt-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ appointment.service?.name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>{{ appointment.staff?.name || 'Any staff' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ formatDateTime(appointment.start_time) }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>${{ appointment.service?.price }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 lg:flex-col lg:items-end">
                            <button 
                                @click="rescheduleAppointment(appointment)" 
                                class="px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-all duration-200 flex items-center gap-1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Reschedule
                            </button>
                            <button 
                                @click="cancelAppointment(appointment.id)" 
                                class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200 flex items-center gap-1"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>
                    
                    <!-- Reminder Info -->
                    <div v-if="getDaysUntil(appointment.start_time) <= 2" class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-yellow-800 dark:text-yellow-300">Reminder: Your appointment is in {{ getDaysUntil(appointment.start_time) }} day(s)!</span>
                    </div>
                </div>
                
                <div v-if="upcomingAppointments.length === 0" class="p-12 text-center">
                    <svg class="w-20 h-20 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h4 :class="['text-lg font-medium mb-2', isDarkMode ? 'text-white' : 'text-gray-900']">No upcoming appointments</h4>
                    <p :class="['mb-4', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Ready to book your next appointment?</p>
                    <router-link to="/vendors" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300">
                        Browse vendors
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </router-link>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div :class="[
            'rounded-xl shadow-md overflow-hidden',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <div :class="[
                'px-6 py-4 border-b',
                isDarkMode ? 'border-gray-700' : 'border-gray-200'
            ]">
                <h3 :class="['text-lg font-semibold', isDarkMode ? 'text-white' : 'text-gray-900']">Recent Activity</h3>
            </div>
            <div :class="['divide-y', isDarkMode ? 'divide-gray-700' : 'divide-gray-200']">
                <div v-for="activity in recentActivities" :key="activity.id" class="p-4 flex items-center gap-3">
                    <div :class="getActivityIconClass(activity.type)" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-xl">{{ getActivityIcon(activity.type) }}</span>
                    </div>
                    <div class="flex-1">
                        <p :class="['text-sm', isDarkMode ? 'text-gray-300' : 'text-gray-900']">{{ activity.message }}</p>
                        <p :class="['text-xs mt-1', isDarkMode ? 'text-gray-500' : 'text-gray-500']">{{ formatRelativeTime(activity.created_at) }}</p>
                    </div>
                </div>
                <div v-if="recentActivities.length === 0" :class="['p-6 text-center', isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                    No recent activity
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { useAppointmentStore } from '../../stores/appointment';
import { useAuthStore } from '../../stores/auth';
import { useToast } from 'vue-toastification';
import { format, formatDistanceToNow, differenceInDays } from 'date-fns';

const appointmentStore = useAppointmentStore();
const authStore = useAuthStore();
const toast = useToast();

const stats = ref({
    upcoming: 0,
    completed: 0,
    total_spent: 0,
    favorite_vendors: 0,
    completedThisMonth: 0
});

const upcomingAppointments = ref([]);
const recentActivities = ref([]);
const topVendor = ref('');
const isDarkMode = ref(false);
let darkModeObserver = null;

const customerName = computed(() => authStore.user?.name || 'Customer');

// Apply theme function
const applyTheme = (value) => {
    if (value) {
        document.documentElement.classList.add('dark');
        document.documentElement.style.colorScheme = 'dark';
    } else {
        document.documentElement.classList.remove('dark');
        document.documentElement.style.colorScheme = 'light';
    }
};

// Check dark mode from localStorage or system preference
const checkDarkMode = () => {
    const savedTheme = localStorage.getItem('darkMode');
    if (savedTheme !== null) {
        isDarkMode.value = savedTheme === 'true';
    } else {
        isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    applyTheme(isDarkMode.value);
};

// Toggle dark mode
const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    applyTheme(isDarkMode.value);
    localStorage.setItem('darkMode', String(isDarkMode.value));
};

// Setup dark mode watcher
const setupDarkModeWatcher = () => {
    const htmlElement = document.documentElement;
    darkModeObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                const hasDarkClass = htmlElement.classList.contains('dark');
                if (hasDarkClass !== isDarkMode.value) {
                    isDarkMode.value = hasDarkClass;
                    localStorage.setItem('darkMode', String(hasDarkClass));
                }
            }
        });
    });
    darkModeObserver.observe(htmlElement, { attributes: true });
};

// Watch for changes to isDarkMode
watch(isDarkMode, (newVal) => {
    applyTheme(newVal);
});

const formatDateTime = (datetime) => {
    return format(new Date(datetime), 'EEE, MMM dd, yyyy • h:mm a');
};

const formatRelativeTime = (datetime) => {
    return formatDistanceToNow(new Date(datetime), { addSuffix: true });
};

const formatMoney = (amount) => {
    return parseFloat(amount).toFixed(2);
};

const getAverageSpending = () => {
    if (stats.value.completed === 0) return 0;
    return (stats.value.total_spent / stats.value.completed).toFixed(2);
};

const getDaysUntil = (datetime) => {
    return differenceInDays(new Date(datetime), new Date());
};

const getStatusClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        confirmed: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        completed: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
};

const getActivityIcon = (type) => {
    const icons = {
        booking: '📅',
        cancellation: '❌',
        reschedule: '🔄',
        payment: '💰',
        review: '⭐'
    };
    return icons[type] || '📌';
};

const getActivityIconClass = (type) => {
    const classes = {
        booking: 'bg-blue-100 dark:bg-blue-900/30',
        cancellation: 'bg-red-100 dark:bg-red-900/30',
        reschedule: 'bg-yellow-100 dark:bg-yellow-900/30',
        payment: 'bg-green-100 dark:bg-green-900/30',
        review: 'bg-purple-100 dark:bg-purple-900/30'
    };
    return classes[type] || 'bg-gray-100 dark:bg-gray-700';
};

const loadDashboard = async () => {
    try {
        // Load upcoming appointments
        const response = await appointmentStore.fetchAppointments({ status: 'upcoming' });
        upcomingAppointments.value = response.data || [];
        stats.value.upcoming = upcomingAppointments.value.length;
        
        // Load stats (you'll need to implement these API endpoints)
        // For now, using mock data
        stats.value.completed = 12;
        stats.value.total_spent = 1250.00;
        stats.value.favorite_vendors = 3;
        stats.value.completedThisMonth = 4;
        topVendor.value = 'Wellness Medical Clinic';
        
        // Mock recent activities
        recentActivities.value = [
            { id: 1, type: 'booking', message: 'Booked appointment with Wellness Medical Clinic', created_at: new Date() },
            { id: 2, type: 'review', message: 'Left a review for Spa & Wellness Center', created_at: new Date(Date.now() - 86400000) },
        ];
    } catch (error) {
        console.error('Failed to load dashboard:', error);
        toast.error('Failed to load dashboard');
    }
};

const cancelAppointment = async (id) => {
    if (confirm('Are you sure you want to cancel this appointment?')) {
        try {
            await appointmentStore.cancelAppointment(id);
            toast.success('Appointment cancelled successfully');
            await loadDashboard();
        } catch (error) {
            toast.error('Failed to cancel appointment');
        }
    }
};

const rescheduleAppointment = (appointment) => {
    // Navigate to appointments page with reschedule action
    router.push({
        path: '/customer/appointments',
        query: { action: 'reschedule', id: appointment.id }
    });
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadDashboard();

    // Listen for storage changes from other tabs
    window.addEventListener('storage', (e) => {
        if (e.key === 'darkMode') {
            isDarkMode.value = e.newValue === 'true';
            applyTheme(isDarkMode.value);
        }
    });

    // Watch for system theme changes
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const handleSystemThemeChange = (e) => {
        if (localStorage.getItem('darkMode') === null) {
            isDarkMode.value = e.matches;
            applyTheme(isDarkMode.value);
        }
    };
    darkModeMediaQuery.addEventListener('change', handleSystemThemeChange);
});

onUnmounted(() => {
    if (darkModeObserver) {
        darkModeObserver.disconnect();
    }
    window.removeEventListener('storage', () => {});
});
</script>

<style scoped>
/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Hover effects */
.hover\:shadow-xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Custom animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

:deep(.bg-white) {
    animation: fadeIn 0.5s ease-out;
}

/* Dark mode card backgrounds */
.dark .bg-white {
    background-color: #1f2937;
}
</style>