<!-- resources/js/views/vendor/Services.vue -->
<template>
    <div class="space-y-6 pb-8">
     
 <div v-if="serviceLimitMessage" 
      class="rounded-2xl p-4 border border-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-600">
      <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
          <h4 class="font-semibold text-yellow-800 dark:text-yellow-300">Service Limit Reached</h4>
          <p class="text-sm text-yellow-700 dark:text-yellow-400">{{ serviceLimitMessage }}</p>
        </div>
      </div>
    </div>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    <!-- Services SVG Icon -->
                    <svg class="inline-block w-7 h-7 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Services
                </h1>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <!-- Cog SVG Icon -->
                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Manage your service offerings and pricing
                </p>
            </div>
            <button v-if="!serviceLimitMessage" @click="openModal()" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium transform active:scale-95">
                <!-- Plus SVG Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Service</span>
            </button>
        </div>

        <!-- Filters Section -->
        <div :class="[
            'rounded-2xl shadow-lg p-4 md:p-6 border',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- Tag SVG Icon -->
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Category
                    </label>
                    <select v-model="selectedCategory" @change="filterServices" 
                        :class="[
                            'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                        <option value="">All Categories</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                
                <!-- Search Filter -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- Search SVG Icon -->
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search Service
                    </label>
                    <div class="relative">
                        <!-- Search SVG Icon -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" v-model="searchQuery" @input="filterServices" 
                            placeholder="Search by service name or description..." 
                            :class="[
                                'w-full pl-10 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                            ]">
                    </div>
                </div>
                
                <!-- Clear Filters Button -->
                <div class="flex items-end">
                    <button @click="clearFilters" 
                        :class="[
                            'inline-flex items-center gap-2 px-4 py-2.5 border rounded-xl transition text-sm font-medium transform active:scale-95',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                        ]">
                        <!-- Clear Filters SVG Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8"/>
                        </svg>
                        Clear Filters
                    </button>
                </div>
            </div>
            
            <!-- Active Filters Display -->
            <div v-if="selectedCategory || searchQuery" class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <span class="text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <!-- Filter SVG Icon -->
                    <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Active filters:
                </span>
                <span v-if="selectedCategory" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    Category: {{ getCategoryName(selectedCategory) }}
                    <button @click="selectedCategory = ''; filterServices()" class="hover:text-primary-900">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
                <span v-if="searchQuery" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    Search: "{{ searchQuery }}"
                    <button @click="searchQuery = ''; filterServices()" class="hover:text-primary-900">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
            </div>
        </div>

        <!-- Results Count & View Toggle -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <!-- List SVG Icon -->
                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Showing {{ filteredServices.length }} of {{ services.length }} services
            </p>
            <div class="flex gap-2">
                <button @click="toggleView" 
                    :class="[
                        'p-2.5 rounded-xl transition transform active:scale-95',
                        isDarkMode ? 'text-gray-400 hover:bg-gray-700 hover:text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
                    ]"
                    :title="viewMode === 'grid' ? 'Switch to List View' : 'Switch to Grid View'">
                    <svg v-if="viewMode === 'grid'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center py-16">
            <div class="flex flex-col items-center gap-3">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent"></div>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Loading services...</p>
            </div>
        </div>

        <!-- Services Grid View -->
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="service in filteredServices" :key="service.id" 
                :class="[
                    'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border',
                    isDarkMode ? 'bg-gray-800 border-gray-700 hover:shadow-gray-700' : 'bg-white border-gray-100 hover:shadow-gray-200'
                ]">
                <div class="relative">
                    
                    
                    <!-- Status Badge -->
                    <span :class="service.is_active ? 'bg-green-500' : 'bg-gray-500'" 
                        class="absolute top-3 right-3 px-2.5 py-1 text-xs font-semibold text-white rounded-lg shadow-md">
                        {{ service.is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="p-5">
                    <!-- Category Tag -->
                    <div class="mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-full" :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'">
                            <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
                            {{ service.category?.name || 'Uncategorized' }}
                        </span>
                    </div>
                    
                    <!-- Service Name -->
                    <h3 class="font-bold text-lg mb-1 line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ service.name }}</h3>
                    
                    <!-- Description -->
                    <p class="text-sm mb-3 line-clamp-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">{{ service.description || 'No description provided' }}</p>
                    
                    <!-- Duration & Price -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-1.5 text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                            <!-- Clock SVG Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ service.duration }} min
                        </div>
                        <div class="text-xl font-bold text-primary-600 dark:text-primary-400">${{ formatPrice(service.price) }}</div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-2 pt-3 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                        <button @click="editService(service)" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl transition font-medium text-sm transform active:scale-95"
                            :class="isDarkMode ? 'text-primary-400 hover:bg-gray-700' : 'text-primary-600 hover:bg-primary-50'">
                            <!-- Edit SVG Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button @click="deleteService(service.id)" 
                            class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl transition font-medium text-sm transform active:scale-95"
                            :class="isDarkMode ? 'text-red-400 hover:bg-red-900/20' : 'text-red-600 hover:bg-red-50'">
                            <!-- Trash SVG Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Empty State Grid -->
            <div v-if="filteredServices.length === 0" class="col-span-full py-16 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <!-- Package SVG Icon -->
                <svg class="w-20 h-20 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <p class="text-lg font-medium">No services found</p>
                <p class="text-sm">Try adjusting your filters or add a new service</p>
            </div>
        </div>

        <!-- Services List View (Table) -->
        <div v-else :class="[
            'rounded-2xl shadow-lg overflow-hidden border',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                    <thead :class="isDarkMode ? 'bg-gray-700/50' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Service SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Service
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Tag SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Category
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Clock SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Duration
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Dollar Sign SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"/>
                                </svg>
                                Price
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Circle SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Cog SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="[
                        isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
                    ]">
                        <tr v-for="service in filteredServices" :key="service.id" 
                            class="transition-colors hover:bg-opacity-50" :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ service.name }}</p>
                                    <p class="text-xs mt-0.5 line-clamp-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ service.description || 'No description' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                                    {{ service.category?.name || 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                <div class="flex items-center gap-1.5">
                                    <!-- Clock SVG Icon -->
                                    <svg class="w-4 h-4" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ service.duration }} min
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">${{ formatPrice(service.price) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="service.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'" 
                                    class="px-2.5 py-1 text-xs font-semibold rounded-full">
                                    {{ service.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="editService(service)" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg transition transform active:scale-95"
                                        :class="isDarkMode ? 'text-primary-400 hover:bg-gray-700' : 'text-primary-600 hover:bg-primary-50'">
                                        <!-- Edit SVG Icon -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </button>
                                    <button @click="deleteService(service.id)" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg transition transform active:scale-95"
                                        :class="isDarkMode ? 'text-red-400 hover:bg-red-900/20' : 'text-red-600 hover:bg-red-50'">
                                        <!-- Trash SVG Icon -->
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Empty State Table -->
                        <tr v-if="filteredServices.length === 0">
                            <td colspan="6" class="px-6 py-16 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Package SVG Icon -->
                                <svg class="w-20 h-20 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                </svg>
                                <p class="text-lg font-medium">No services found</p>
                                <p class="text-sm">Try adjusting your filters or add a new service</p>
                                <button @click="clearFilters" class="mt-3 text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1">
                                    Clear filters
                                    <!-- Arrow Right SVG Icon -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Service Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity"></div>
                <div :class="[
                    'rounded-2xl shadow-2xl max-w-md w-full z-50 transform transition-all border',
                    isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
                ]">
                    <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
                        ]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                                <!-- Service SVG Icon -->
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ editing ? 'Edit Service' : 'Add New Service' }}</h3>
                        </div>
                        <button @click="closeModal" 
                            class="w-8 h-8 rounded-lg flex items-center justify-center transition transform active:scale-95" 
                            :class="isDarkMode ? 'hover:bg-gray-700 text-gray-400' : 'hover:bg-gray-100 text-gray-400'">
                            <!-- Close SVG Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="saveService" class="px-6 py-4">
                        <div class="space-y-4">
                            <!-- Service Name -->
                            <div>
                                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                                    <!-- Tag SVG Icon -->
                                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Service Name *
                                </label>
                                <input type="text" v-model="form.name" required 
                                    :class="[
                                        'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                                    ]"
                                    placeholder="e.g., Haircut, Massage, etc.">
                            </div>
                            
                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                                    <!-- Folder SVG Icon -->
                                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                    Category *
                                </label>
                                <select v-model="form.category_id" required 
                                    :class="[
                                        'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                    ]">
                                    <option value="">Select Category</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                </select>
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                                    <!-- Document Text SVG Icon -->
                                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Description
                                </label>
                                <textarea v-model="form.description" rows="3" 
                                    :class="[
                                        'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                                    ]"
                                    placeholder="Describe your service..."></textarea>
                            </div>
                            
                            <!-- Duration & Price -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                                        <!-- Clock SVG Icon -->
                                        <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Duration *
                                    </label>
                                    <div class="relative">
                                        <input type="number" v-model="form.duration" required min="1"
                                            :class="[
                                                'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                            ]">
                                        <span class="absolute right-3 top-2.5 text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">min</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                                        <!-- Dollar Sign SVG Icon -->
                                        <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"/>
                                        </svg>
                                        Price *
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">$</span>
                                        <input type="number" step="0.01" v-model="form.price" required min="0"
                                            :class="[
                                                'w-full pl-7 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                            ]">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Toggle -->
                            <div :class="[
                                'flex items-center justify-between p-3 rounded-xl border',
                                isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                            ]">
                                <div class="flex items-center gap-2">
                                    <!-- Toggle On/Off SVG Icon -->
                                    <svg class="w-5 h-5" :class="form.is_active ? 'text-green-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <label class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Service Status</label>
                                </div>
                                <button type="button" @click="form.is_active = !form.is_active"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transform active:scale-95"
                                    :class="form.is_active ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600'">
                                    <span class="sr-only">Toggle status</span>
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
                                    'inline-flex items-center justify-center gap-2 px-4 py-2.5 border rounded-xl transition text-sm font-medium transform active:scale-95',
                                    isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                                ]">
                                <!-- Close SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </button>
                            <button type="submit" 
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition shadow-md hover:shadow-lg text-sm font-medium transform active:scale-95">
                                <!-- Save SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ editing ? 'Update Service' : 'Create Service' }}
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
import { useVendorStore } from '../../stores/vendor';
import { useToast } from 'vue-toastification';
import axios from 'axios';

const vendorStore = useVendorStore();
const toast = useToast();

const services = ref([]);
const categories = ref([]);
const showModal = ref(false);
const editing = ref(false);
const viewMode = ref('grid');
const selectedCategory = ref('');
const searchQuery = ref('');
const filteredServices = ref([]);
const isDarkMode = ref(false);
const isLoading = ref(false);
const serviceLimitMessage = ref('');
const serviceLimitReached = ref(false);
let darkModeObserver = null;

const form = ref({
    name: '',
    category_id: '',
    description: '',
    duration: 60,
    price: 0,
    is_active: true
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
                }
            }
        });
    });
    darkModeObserver.observe(htmlElement, { attributes: true });
};

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};

const getCategoryName = (categoryId) => {
    const category = categories.value.find(c => c.id == categoryId);
    return category ? category.name : '';
};

const filterServices = () => {
    let filtered = [...services.value];
    
    if (selectedCategory.value) {
        filtered = filtered.filter(service => service.category_id == selectedCategory.value);
    }
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(service => 
            service.name.toLowerCase().includes(query) || 
            (service.description && service.description.toLowerCase().includes(query))
        );
    }
    
    filteredServices.value = filtered;
};

const clearFilters = () => {
    selectedCategory.value = '';
    searchQuery.value = '';
    filterServices();
};

const toggleView = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};

const loadServices = async () => {
    isLoading.value = true;
    try {
        const response = await vendorStore.fetchServices();
        services.value = response.data?.data || response.data || response;
            if (response.limit) {
      serviceLimitMessage.value = response.limit.message || '';
      serviceLimitReached.value = response.limit.limitReached || false;
    } else {
      serviceLimitMessage.value = '';
      serviceLimitReached.value = false;
    }
        filterServices();
    } catch (error) {
        console.error('Failed to load services:', error);
        toast.error('Failed to load services');
    } finally {
        isLoading.value = false;
    }
};

const loadCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data?.data || response.data || [];
    } catch (error) {
        console.error('Failed to load categories:', error);
        toast.error('Failed to load categories');
    }
};

const openModal = () => {
    editing.value = false;
    form.value = {
        name: '',
        category_id: '',
        description: '',
        duration: 60,
        price: 0,
        is_active: true
    };
    showModal.value = true;
};

const editService = (service) => {
    editing.value = true;
    form.value = { ...service };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = false;
};

const saveService = async () => {
    try {
        if (editing.value) {
            await vendorStore.updateService(form.value.id, form.value);
            toast.success('Service updated successfully');
        } else {
            await vendorStore.createService(form.value);
            toast.success('Service created successfully');
        }
        closeModal();
        await loadServices();
    } catch (error) {
        console.error('Failed to save service:', error);
        toast.error(error.response?.data?.message || 'Failed to save service');
    }
};

const deleteService = async (id) => {
    if (confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
        try {
            await vendorStore.deleteService(id);
            toast.success('Service deleted successfully');
            await loadServices();
        } catch (error) {
            console.error('Failed to delete service:', error);
            toast.error('Failed to delete service');
        }
    }
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadServices();
    loadCategories();

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