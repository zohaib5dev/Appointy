<!-- resources/js/views/Vendors.vue -->
<template>
    <div :class="['min-h-screen transition-colors duration-300 py-8', isDarkMode ? 'bg-gray-900' : 'bg-gradient-to-br from-gray-50 to-gray-100']">
    
    

        <div class="container mx-auto px-4">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 :class="['text-4xl md:text-5xl font-bold mb-4', isDarkMode ? 'text-white' : 'text-gray-900']">
                    Find Trusted <span class="text-primary-600">Professionals</span>
                </h1>
                <p :class="['text-lg max-w-2xl mx-auto', isDarkMode ? 'text-gray-400' : 'text-gray-600']">
                    Browse through our curated list of verified vendors and book appointments with ease
                </p>
            </div>

            <!-- Search and Filters -->
            <div :class="[
                'rounded-xl shadow-lg p-6 mb-8 border',
                isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
            ]">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input 
                            type="text" 
                            v-model="filters.search" 
                            @input="searchVendors" 
                            placeholder="Search vendors by name..." 
                            :class="[
                                'w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent',
                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                            ]"
                        >
                    </div>
                    
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <select 
                            v-model="filters.category" 
                            @change="onCategoryChange" 
                            :class="[
                                'w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none',
                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                            ]"
                        >
                            <option value="">All Categories</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.icon }} {{ cat.name }}
                            </option>
                        </select>
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <input 
                            type="text" 
                            v-model="filters.city" 
                            @input="debouncedCitySearch" 
                            placeholder="City" 
                            :class="[
                                'w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent',
                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                            ]"
                        >
                    </div>
                    
                    <button @click="loadVendors" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Search
                    </button>
                </div>
                
                <!-- Active Filters -->
                <div v-if="filters.search || filters.category || filters.city" class="flex flex-wrap gap-2 mt-4 pt-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                    <span :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-600']">Active filters:</span>
                    <span v-if="filters.search" class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                        Search: {{ filters.search }}
                        <button @click="clearFilter('search')" class="ml-1 hover:text-primary-900 dark:hover:text-primary-300">&times;</button>
                    </span>
                    <span v-if="filters.category" class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                        Category: {{ getCategoryName(filters.category) }}
                        <button @click="clearFilter('category')" class="ml-1 hover:text-primary-900 dark:hover:text-primary-300">&times;</button>
                    </span>
                    <span v-if="filters.city" class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                        City: {{ filters.city }}
                        <button @click="clearFilter('city')" class="ml-1 hover:text-primary-900 dark:hover:text-primary-300">&times;</button>
                    </span>
                    <button @click="clearAllFilters" class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 ml-2">Clear all</button>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="flex flex-col items-center justify-center py-16">
                <div class="relative">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary-600"></div>
                    <div class="animate-pulse absolute inset-0 flex items-center justify-center">
                        <div class="h-8 w-8 bg-primary-200 rounded-full opacity-50"></div>
                    </div>
                </div>
                <p :class="['mt-4 font-medium', isDarkMode ? 'text-gray-400' : 'text-gray-600']">Loading vendors...</p>
            </div>

            <!-- Results Count -->
            <div v-else-if="!isLoading && vendors.length > 0" class="flex justify-between items-center mb-6 flex-wrap gap-4">
                <p :class="[isDarkMode ? 'text-gray-400' : 'text-gray-600']">
                    Showing <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ vendors.length }}</span> vendors
                    <span v-if="pagination.total" class="text-sm">({{ pagination.total }} total)</span>
                </p>
                <div class="flex items-center gap-2">
                    <span :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Sort by:</span>
                    <select v-model="sortBy" @change="loadVendors" :class="[
                        'text-sm border rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-primary-500',
                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]">
                        <option value="rating">Rating</option>
                        <option value="newest">Newest</option>
                        <option value="name">Name</option>
                    </select>
                </div>
            </div>

            <!-- Vendors Grid -->
            <div v-if="!isLoading && vendors.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <router-link 
                    v-for="vendor in vendors" 
                    :key="vendor.id" 
                    :to="`/vendors/${vendor.slug}`" 
                    :class="[
                        'rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1',
                        isDarkMode ? 'bg-gray-800 hover:shadow-gray-700' : 'bg-white hover:shadow-xl'
                    ]"
                >
                    <!-- Cover Image -->
                    <div class="relative h-48 overflow-hidden">
                        <img 
                            :src="getCoverImage(vendor)" 
                            :alt="vendor.business_name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        
                        <!-- Verified Badge -->
                        <div v-if="vendor.is_verified" class="absolute top-3 right-3 bg-green-500 text-white rounded-full p-1 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-white text-xl font-bold group-hover:text-primary-200 transition">
                                {{ vendor.business_name }}
                            </h3>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <!-- Rating and Location -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatRating(vendor.rating) }}</span>
                                    <span :class="['text-sm ml-1', isDarkMode ? 'text-gray-400' : 'text-gray-500']">({{ vendor.total_reviews || 0 }})</span>
                                </div>
                            </div>
                            <div :class="['flex items-center text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ vendor.city || 'Location N/A' }}
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p :class="['text-sm mb-4 line-clamp-2', isDarkMode ? 'text-gray-400' : 'text-gray-600']">{{ vendor.description || 'No description available' }}</p>
                        
                        <!-- Services Tags -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span 
                                v-for="service in vendor.services?.slice(0, 3)" 
                                :key="service.id" 
                                :class="['text-xs px-2 py-1 rounded-full', isDarkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-700']"
                            >
                                {{ service.name }}
                            </span>
                            <span 
                                v-if="vendor.services?.length > 3" 
                                :class="['text-xs px-2 py-1 rounded-full', isDarkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-700']"
                            >
                                +{{ vendor.services.length - 3 }} more
                            </span>
                        </div>
                        
                        <!-- Price Indicator -->
                        <div class="flex items-center justify-between pt-3 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                            <div :class="['flex items-center text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Starting from
                            </div>
                            <div class="text-primary-600 dark:text-primary-400 font-bold">
                                ${{ getStartingPrice(vendor.services) }}
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>

            <!-- Empty State -->
            <div v-else-if="!isLoading && vendors.length === 0" :class="['text-center py-16 rounded-xl shadow-sm', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 :class="['text-xl font-semibold mb-2', isDarkMode ? 'text-gray-300' : 'text-gray-700']">No vendors found</h3>
                <p :class="[isDarkMode ? 'text-gray-400' : 'text-gray-500']">Try adjusting your search or filter criteria</p>
                <button @click="clearAllFilters" class="mt-4 text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium">Clear all filters</button>
            </div>

            <!-- Pagination -->
            <div v-if="!isLoading && pagination.last_page > 1" class="flex justify-center items-center gap-2 mt-10">
                <button 
                    @click="goToPage(pagination.current_page - 1)" 
                    :disabled="pagination.current_page === 1"
                    :class="[
                        'px-4 py-2 border rounded-lg transition',
                        pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : (isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100'),
                        isDarkMode ? 'bg-gray-800 border-gray-700 text-gray-300' : 'bg-white border-gray-300 text-gray-700'
                    ]"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <div class="flex gap-2">
                    <button 
                        v-for="page in visiblePages" 
                        :key="page" 
                        @click="goToPage(page)" 
                        :class="[
                            page === pagination.current_page 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : (isDarkMode ? 'bg-gray-800 text-gray-300 border-gray-700 hover:bg-gray-700' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'),
                            'px-4 py-2 border rounded-lg transition-all duration-200 font-medium'
                        ]"
                    >
                        {{ page }}
                    </button>
                </div>
                
                <button 
                    @click="goToPage(pagination.current_page + 1)" 
                    :disabled="pagination.current_page === pagination.last_page"
                    :class="[
                        'px-4 py-2 border rounded-lg transition',
                        pagination.current_page === pagination.last_page ? 'opacity-50 cursor-not-allowed' : (isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100'),
                        isDarkMode ? 'bg-gray-800 border-gray-700 text-gray-300' : 'bg-white border-gray-300 text-gray-700'
                    ]"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const route = useRoute();
const router = useRouter();
const toast = useToast();
const vendors = ref([]);
const categories = ref([]);
const sortBy = ref('rating');
const isLoading = ref(true);
const isDarkMode = ref(false);
const pagination = ref({ 
    current_page: 1, 
    last_page: 1,
    total: 0 
});
const filters = ref({ search: '', category: '', city: '' });
let searchTimeout = null;
let cityTimeout = null;
let darkModeObserver = null;

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

// Computed visible pages for pagination
const visiblePages = computed(() => {
    const current = pagination.value.current_page;
    const last = pagination.value.last_page;
    const delta = 2;
    const range = [];
    
    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i);
    }
    
    if (current - delta > 2) {
        range.unshift('...');
    }
    if (current + delta < last - 1) {
        range.push('...');
    }
    
    range.unshift(1);
    if (last !== 1) range.push(last);
    
    return range;
});

const getCoverImage = (vendor) => {
    if (vendor.cover_image) {
        return `/storage/${vendor.cover_image.replace(/^\/storage\//, '')}`;
    }
    // Fallback images based on category or random
   const fallbacks = [
        '/cover.png',
    ];
    return fallbacks[Math.floor(Math.random() * fallbacks.length)];

};

const formatRating = (rating) => {
    return rating ? parseFloat(rating).toFixed(1) : '0.0';
};

const getStartingPrice = (services) => {
    if (!services || services.length === 0) return 'N/A';
    const prices = services.map(s => s.price);
    return Math.min(...prices);
};

const getCategoryName = (categoryId) => {
    const category = categories.value.find(c => c.id == categoryId);
    return category ? category.name : 'Selected';
};

const updateUrlParams = () => {
    const query = {};
    if (filters.value.search) query.search = filters.value.search;
    if (filters.value.category) query.category = filters.value.category;
    if (filters.value.city) query.city = filters.value.city;
    if (sortBy.value !== 'rating') query.sort = sortBy.value;
    
    router.replace({ query });
};

const loadVendors = async (page = 1) => {
    isLoading.value = true;
    try {
        const response = await axios.get('/api/vendors', { 
            params: { 
                ...filters.value, 
                page,
                sort: sortBy.value
            } 
        });
        vendors.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            total: response.data.total
        };
        
        updateUrlParams();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (error) {
        toast.error('Failed to load vendors');
        console.error('Error loading vendors:', error);
    } finally {
        isLoading.value = false;
    }
};

const onCategoryChange = () => {
    loadVendors();
};

const searchVendors = () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadVendors();
    }, 500);
};

const debouncedCitySearch = () => {
    if (cityTimeout) clearTimeout(cityTimeout);
    cityTimeout = setTimeout(() => {
        loadVendors();
    }, 500);
};

const goToPage = (page) => {
    if (page === '...') return;
    if (page >= 1 && page <= pagination.value.last_page) {
        loadVendors(page);
    }
};

const clearFilter = (filter) => {
    filters.value[filter] = '';
    loadVendors();
};

const clearAllFilters = () => {
    filters.value = { search: '', category: '', city: '' };
    loadVendors();
};

const loadCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data.data || response.data;
    } catch (error) {
        console.error('Failed to load categories:', error);
    }
};

// Initialize filters from URL query params
const initializeFromUrl = () => {
    const query = route.query;
    if (query.search) filters.value.search = query.search;
    if (query.category) {
        filters.value.category = query.category;
    }
    if (query.city) filters.value.city = query.city;
    if (query.sort) sortBy.value = query.sort;
};

onMounted(async () => {
    checkDarkMode();
    setupDarkModeWatcher();
    await loadCategories();
    initializeFromUrl();
    await loadVendors();

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
    // Clean up event listeners
    window.removeEventListener('storage', () => {});
});
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.dark ::-webkit-scrollbar-track {
    background: #1f2937;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.dark ::-webkit-scrollbar-thumb {
    background: #4b5563;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 0.5;
    }
    50% {
        opacity: 0.3;
    }
}
</style>