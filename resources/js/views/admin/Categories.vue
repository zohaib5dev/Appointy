<!-- resources/js/views/admin/Categories.vue -->
<template>
    <div class="space-y-6 pb-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Categories</h1>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Manage service categories across the platform</p>
            </div>
            <button @click="openModal()" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Category</span>
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-primary-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Categories</p>
                        <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ categories.length }}</p>
                    </div>
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-green-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Active</p>
                        <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ activeCount }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-gray-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Inactive</p>
                        <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ inactiveCount }}</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-purple-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Services</p>
                        <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ totalServices }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="category in categories" :key="category.id" 
                :class="[
                    'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group',
                    isDarkMode ? 'bg-gray-800 hover:shadow-gray-700' : 'bg-white'
                ]">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shadow-sm" 
                                :style="{ backgroundColor: category.color || '#e0e7ff' }">
                                {{ category.icon || '📁' }}
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ category.name }}</h3>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">Slug: {{ category.slug }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button @click="editCategory(category)" 
                                class="p-1.5 text-primary-600 hover:bg-primary-50 rounded-lg transition"
                                :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-primary-50'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button @click="deleteCategory(category.id)" 
                                class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition"
                                :class="isDarkMode ? 'hover:bg-red-900/20' : 'hover:bg-red-50'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <p class="text-sm mb-4 line-clamp-2" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ category.description || 'No description provided' }}</p>
                    
                    <!-- Footer -->
                    <div class="flex justify-between items-center pt-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                        <span :class="category.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'" 
                            class="px-2.5 py-1 text-xs font-semibold rounded-full">
                            {{ category.is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="text-sm flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ category.services_count || 0 }} services
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="categories.length === 0" :class="[
            'rounded-2xl shadow-lg p-12 text-center',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <svg class="w-16 h-16 mx-auto mb-4" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">No Categories Yet</h3>
            <p class="mb-4" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Get started by creating your first category.</p>
            <button @click="openModal()" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Category
            </button>
        </div>

        <!-- Category Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div :class="[
                    'rounded-2xl shadow-2xl max-w-md w-full z-50 transform transition-all',
                    isDarkMode ? 'bg-gray-800' : 'bg-white'
                ]">
                    <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
                        ]">
                        <h3 class="text-xl font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ editing ? 'Edit Category' : 'Add New Category' }}</h3>
                        <button @click="closeModal" class="transition" :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="saveCategory" class="px-6 py-4">
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Category Name *</label>
                                <input type="text" v-model="form.name" required 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                                    ]"
                                    placeholder="e.g., Beauty, Wellness, etc.">
                            </div>
                            
                            <!-- Icon & Color -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Icon (Emoji)</label>
                                    <input type="text" v-model="form.icon" placeholder="📁" 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Color</label>
                                    <div class="flex items-center gap-2">
                                        <input type="color" v-model="form.color" 
                                            :class="[
                                                'w-12 h-12 p-1 border rounded-xl cursor-pointer',
                                                isDarkMode ? 'border-gray-600' : 'border-gray-300'
                                            ]">
                                        <input type="text" v-model="form.color" 
                                            :class="[
                                                'flex-1 px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm font-mono',
                                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                            ]">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Description</label>
                                <textarea v-model="form.description" rows="3" 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                                    ]"
                                    placeholder="Describe this category..."></textarea>
                            </div>
                            
                            <!-- Order -->
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Display Order</label>
                                <input type="number" v-model="form.order" 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                    ]">
                            </div>
                            
                            <!-- Status Toggle -->
                            <div :class="[
                                'flex items-center justify-between p-3 rounded-xl',
                                isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                            ]">
                                <label class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Category Status</label>
                                <button type="button" @click="form.is_active = !form.is_active"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                    :class="form.is_active ? 'bg-primary-600' : 'bg-gray-200'">
                                    <span class="sr-only">Use setting</span>
                                    <span aria-hidden="true" 
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="form.is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                            <button type="button" @click="closeModal" 
                                :class="[
                                    'px-4 py-2 border rounded-lg transition',
                                    isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                                ]">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition shadow-md">
                                {{ editing ? 'Update Category' : 'Create Category' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const toast = useToast();
const categories = ref([]);
const showModal = ref(false);
const editing = ref(false);
const isDarkMode = ref(false);
const form = ref({
    name: '',
    icon: '📁',
    color: '#e0e7ff',
    description: '',
    order: 0,
    is_active: true
});
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
                }
            }
        });
    });
    darkModeObserver.observe(htmlElement, { attributes: true });
};

// Computed stats
const activeCount = computed(() => {
    return categories.value.filter(c => c.is_active).length;
});

const inactiveCount = computed(() => {
    return categories.value.filter(c => !c.is_active).length;
});

const totalServices = computed(() => {
    return categories.value.reduce((sum, cat) => sum + (cat.services_count || 0), 0);
});

const loadCategories = async () => {
    try {
        const response = await axios.get('/api/admin/categories');
        categories.value = response.data.data || response.data || [];
    } catch (error) {
        toast.error('Failed to load categories');
    }
};

const openModal = () => {
    editing.value = false;
    form.value = {
        name: '',
        icon: '📁',
        color: '#e0e7ff',
        description: '',
        order: 0,
        is_active: true
    };
    showModal.value = true;
};

const editCategory = (category) => {
    editing.value = true;
    form.value = { ...category };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = false;
};

const saveCategory = async () => {
    try {
        if (editing.value) {
            await axios.put(`/api/admin/categories/${form.value.id}`, form.value);
            toast.success('Category updated successfully');
        } else {
            await axios.post('/api/admin/categories', form.value);
            toast.success('Category created successfully');
        }
        closeModal();
        await loadCategories();
    } catch (error) {
        toast.error(error.response?.data?.message || 'Failed to save category');
    }
};

const deleteCategory = async (id) => {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        try {
            await axios.delete(`/api/admin/categories/${id}`);
            toast.success('Category deleted successfully');
            await loadCategories();
        } catch (error) {
            toast.error('Failed to delete category');
        }
    }
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadCategories();

    // Listen for storage changes from other tabs
    window.addEventListener('storage', (e) => {
        if (e.key === 'darkMode') {
            isDarkMode.value = e.newValue === 'true';
        }
    });
});

onUnmounted(() => {
    if (darkModeObserver) {
        darkModeObserver.disconnect();
    }
});
</script>

<style scoped>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>