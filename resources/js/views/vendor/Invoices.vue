<!-- resources/js/views/vendor/Invoices.vue -->
<template>
    <div class="space-y-6 pb-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 px-4 sm:px-0">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    <!-- Invoice SVG Icon -->
                    <svg class="inline-block w-7 h-7 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    Invoices
                </h1>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <!-- Receipt SVG Icon -->
                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Your customer invoices
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button @click="exportInvoices" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
                    <!-- Export SVG Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">Export</span>
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div :class="[
            'rounded-2xl shadow-lg p-4 md:p-6 border mx-4 sm:mx-0',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <!-- Date From -->
                <div>
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3H9"/>
                        </svg>
                        From Date
                    </label>
                    <input type="date" v-model="filters.date_from" @change="loadInvoices" 
                        :class="[
                            'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                
                <!-- Date To -->
                <div>
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9"/>
                        </svg>
                        To Date
                    </label>
                    <input type="date" v-model="filters.date_to" @change="loadInvoices" 
                        :class="[
                            'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" v-model="filters.search" @input="searchInvoices" 
                            placeholder="Search by invoice #, customer..." 
                            :class="[
                                'w-full pl-10 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                            ]">
                    </div>
                </div>
            </div>
            
            <!-- Active Filters -->
            <div v-if="hasActiveFilters" class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <span class="text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Active filters:
                </span>
               
                <button v-if="filters.date_from" @click="filters.date_from = ''; loadInvoices()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    From: {{ filters.date_from }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <button v-if="filters.date_to" @click="filters.date_to = ''; loadInvoices()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    To: {{ filters.date_to }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
             
                <button v-if="filters.search" @click="filters.search = ''; loadInvoices()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    Search: {{ filters.search }}
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                
                <button @click="clearAllFilters" 
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition dark:bg-red-900/30 dark:text-red-400 ml-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8"/>
                    </svg>
                    Clear All
                </button>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 px-4 sm:px-1">
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} invoices
            </p>
        </div>

        <!-- Invoice List -->
        <div :class="[
            'rounded-2xl shadow-lg overflow-hidden border mx-4 sm:mx-0',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <!-- Mobile Card View -->
            <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                <div v-for="invoice in invoices" :key="invoice.id" 
                    class="p-4 transition-all hover:bg-opacity-50" :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                {{ invoice.invoice_number }}
                            </p>
                            <!-- Appointment Number -->
                            <p class="text-xs flex items-center gap-1 mt-0.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ formatDate(invoice.created_at) }}
                            </p>
                            <p class="text-xs flex items-center gap-1 mt-0.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                                </svg>
                                Appointment: {{ invoice.appointment?.booking_number || 'N/A' }}
                            </p>
                        </div>
                        <span :class="getPaymentStatusBadge(invoice.appointment?.payment_status)">
                            {{ getPaymentStatusLabel(invoice.appointment?.payment_status) }}
                        </span>
                    </div>
                    
                    <div class="space-y-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-primary-700 dark:text-primary-300">{{ invoice.customer?.name?.charAt(0) || '?' }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium truncate" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ invoice.customer?.name || 'N/A' }}</p>
                                <p class="text-xs truncate flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ invoice.customer?.phone || 'N/A' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-sm bg-opacity-50 rounded-xl p-3" :class="isDarkMode ? 'bg-gray-700/30' : 'bg-gray-50'">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 2V9m0 2v1m0 2v1m-6-9a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7z"/>
                                    </svg>
                                    Amount
                                </p>
                                <p class="font-bold text-primary-600 dark:text-primary-400">${{ invoice.total }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                     Date
                                </p>
                                <p class="font-medium text-sm" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDate(invoice.created_at) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2">
                            <button @click="viewInvoice(invoice)" 
                                class="inline-flex items-center gap-1 px-4 py-2 bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 rounded-xl hover:bg-primary-100 dark:hover:bg-primary-900/50 transition text-sm font-medium">
                                View
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                            <div class="flex gap-2">
                                <button @click="downloadInvoice(invoice)" 
                                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" 
                                    :class="isDarkMode ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-700'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State Mobile -->
                <div v-if="invoices.length === 0 && !isLoading" class="p-8 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                    <p class="font-medium">No invoices found</p>
                    <p class="text-sm">Try adjusting your filters</p>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                    <thead :class="isDarkMode ? 'bg-gray-700/50' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                Invoice #
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                                </svg>
                                Appointment #
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Customer
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 2V9m0 2v1m0 2v1m-6-9a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7z"/>
                                </svg>
                                Amount
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Date
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                Status
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
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
                        <tr v-for="invoice in invoices" :key="invoice.id" 
                            class="transition-colors hover:bg-opacity-50" :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                {{ invoice.invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                {{ invoice.appointment?.booking_number || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-xs font-semibold text-primary-700 dark:text-primary-300">{{ invoice.customer?.name?.charAt(0) || '?' }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium truncate" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ invoice.customer?.name || 'N/A' }}</p>
                                        <p class="text-xs truncate flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ invoice.customer?.phone || 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary-600 dark:text-primary-400">
                                ${{ invoice.total }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                {{ formatDate(invoice.created_at) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getPaymentStatusBadge(invoice.appointment?.payment_status)">
                                    {{ getPaymentStatusLabel(invoice.appointment?.payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click="viewInvoice(invoice)" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button @click="downloadInvoice(invoice)" 
                                        class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" 
                                        :class="isDarkMode ? 'text-gray-400 hover:text-white' : 'text-gray-500 hover:text-gray-700'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </button>                                     
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Empty State Desktop -->
                        <tr v-if="invoices.length === 0 && !isLoading">
                            <td colspan="7" class="px-6 py-16 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="w-20 h-20 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                                <p class="text-lg font-medium">No invoices found</p>
                                <p class="text-sm">Try adjusting your filters or search criteria</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center items-center py-16">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-10 w-10 border-4 border-primary-500 border-t-transparent"></div>
                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Loading invoices...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="pagination.last_page > 1" :class="[
                'px-4 md:px-6 py-4 border-t',
                isDarkMode ? 'border-gray-700 bg-gray-800/50' : 'border-gray-200 bg-gray-50'
            ]">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm flex items-center gap-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }}
                    </div>
                    <div class="flex flex-wrap justify-center gap-1.5">
                        <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-2 border rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed text-sm',
                                isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
                            ]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span class="hidden sm:inline">Previous</span>
                        </button>
                        <button v-for="page in displayedPages" :key="page" 
                            @click="goToPage(page)" 
                            :class="[
                                'px-3.5 py-2 rounded-lg transition text-sm font-medium min-w-[40px]',
                                page === pagination.current_page 
                                    ? 'bg-primary-600 text-white shadow-md' 
                                    : isDarkMode 
                                        ? 'bg-gray-700 text-gray-300 border border-gray-600 hover:bg-gray-600' 
                                        : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-100'
                            ]">
                            {{ page }}
                        </button>
                        <button @click="goToPage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page"
                            :class="[
                                'inline-flex items-center gap-1 px-3 py-2 border rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed text-sm',
                                isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
                            ]">
                            <span class="hidden sm:inline">Next</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Details Modal -->
        <div v-if="showDetailsModal" class="fixed inset-0 z-50 overflow-y-auto px-4" @click.self="showDetailsModal = false">
            <div class="flex items-center justify-center min-h-screen py-8">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity"></div>
                <div :class="[
                    'rounded-2xl shadow-2xl max-w-3xl w-full z-50 transform transition-all max-h-[90vh] overflow-y-auto border',
                    isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
                ]">
                    <!-- Modal Header -->
                    <div class="px-4 sm:px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl z-10" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
                        ]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                    Invoice Details
                                </h3>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    {{ selectedInvoice?.invoice_number }}
                                </p>
                            </div>
                        </div>
                        <button @click="showDetailsModal = false" 
                            class="w-8 h-8 rounded-lg flex items-center justify-center transition" 
                            :class="isDarkMode ? 'hover:bg-gray-700 text-gray-400' : 'hover:bg-gray-100 text-gray-400'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="px-4 sm:px-6 py-4" v-if="selectedInvoice">
                        <div class="space-y-6">
                            <!-- Header Info -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Invoice #</p>
                                    <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.invoice_number }}</p>
                                </div>
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Appointment #</p>
                                    <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.appointment?.booking_number || 'N/A' }}</p>
                                </div>
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Amount</p>
                                    <p class="text-xl font-bold text-primary-600 dark:text-primary-400">${{ selectedInvoice.total }}</p>
                                </div>
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Date</p>
                                    <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDate(selectedInvoice.created_at) }}</p>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h4 class="font-semibold mb-3" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Customer Information</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Name</p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.customer?.name || 'N/A' }}</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Email</p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.customer?.email || 'N/A' }}</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border sm:col-span-2',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Phone</p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.customer?.phone || 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Line Items -->
                            <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h4 class="font-semibold mb-3" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Service</h4>
                                <div :class="[
                                    'rounded-xl overflow-hidden border',
                                    isDarkMode ? 'border-gray-600' : 'border-gray-200'
                                ]">
                                    <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-600' : 'divide-gray-200'">
                                        <thead :class="isDarkMode ? 'bg-gray-700/50' : 'bg-gray-50'">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Service</th>
                                                <th class="px-4 py-2 text-center text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Duration</th>
                                                <th class="px-4 py-2 text-right text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y" :class="isDarkMode ? 'divide-gray-600' : 'divide-gray-200'">
                                            <tr v-for="item in parsedItems(selectedInvoice.items)" :key="item.service_id">
                                                <td class="px-4 py-3">
                                                    <div>
                                                        <div class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                                            {{ item.service_name || 'N/A' }}
                                                        </div>
                                                        <div class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                                            {{ item.description || '' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                                                    {{ item.duration || 0 }} min
                                                </td>
                                                <td class="px-4 py-3 text-right font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                                    ${{ Number(item.price || 0).toFixed(2) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot :class="isDarkMode ? 'bg-gray-700/50' : 'bg-gray-50'">
                                            <tr>
                                                <td colspan="2" class="px-4 py-2 text-sm font-medium text-right" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Subtotal</td>
                                                <td class="px-4 py-2 text-sm text-right font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">${{ selectedInvoice.subtotal || 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="px-4 py-2 text-sm font-medium text-right" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Tax</td>
                                                <td class="px-4 py-2 text-sm text-right font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">${{ selectedInvoice.tax || 0 }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="px-4 py-2 text-sm font-bold text-right" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Total</td>
                                                <td class="px-4 py-2 text-sm font-bold text-right text-primary-600 dark:text-primary-400">${{ selectedInvoice.total || 0 }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div v-if="selectedInvoice.notes" class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h4 class="font-semibold mb-3" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Notes</h4>
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">{{ selectedInvoice.notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="px-4 sm:px-6 py-4 border-t flex flex-col sm:flex-row justify-end gap-3 rounded-b-2xl" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800/50' : 'border-gray-200 bg-gray-50'
                        ]">
                        <button @click="showDetailsModal = false" :class="[
                            'inline-flex items-center gap-2 px-4 py-2.5 border rounded-xl transition text-sm font-medium w-full sm:w-auto justify-center',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
                        ]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Close
                        </button>
                        <button v-if="selectedInvoice?.status === 'draft' || selectedInvoice?.status === 'sent'" 
                            @click="markAsPaid" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition shadow-md hover:shadow-lg text-sm font-medium w-full sm:w-auto justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Mark as Paid
                        </button>
                        <button @click="downloadInvoice(selectedInvoice)" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition shadow-md hover:shadow-lg text-sm font-medium w-full sm:w-auto justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';

const toast = useToast();

// State
const invoices = ref([]); 
const isLoading = ref(false);
const showDetailsModal = ref(false);
const selectedInvoice = ref(null);
const isDarkMode = ref(false);
let darkModeObserver = null;
let searchTimeout = null;

// Filters
const filters = ref({ 
    date_from: '',
    date_to: '', 
    search: ''
});

// Pagination
const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0
});

const getPaymentStatusBadge = (status) => {
    const statusMap = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        paid: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        refunded: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
    };
    const baseClass = 'px-2.5 py-1 text-xs font-medium rounded-full';
    const statusKey = status?.toLowerCase() || 'pending';
    return `${baseClass} ${statusMap[statusKey] || statusMap.pending}`;
};

const getPaymentStatusLabel = (status) => {
    const labels = {
        pending: '⏳ Pending',
        paid: '✅ Paid',
        refunded: '↩️ Refunded',
        failed: '❌ Failed',
    };
    return labels[status?.toLowerCase()] || status || 'Pending';
};

// Dark mode
const checkDarkMode = () => {
    const savedTheme = localStorage.getItem('darkMode');
    if (savedTheme !== null) {
        isDarkMode.value = savedTheme === 'true';
    } else {
        isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
};

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

// Computed
const hasActiveFilters = computed(() => {
    return filters.value.date_from || 
           filters.value.date_to || 
           filters.value.search;
});

const displayedPages = computed(() => {
    const total = pagination.value.last_page;
    const current = pagination.value.current_page;
    const delta = 2;
    const range = [];
    for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
        range.push(i);
    }
    if (current - delta > 2) range.unshift('...');
    if (current + delta < total - 1) range.push('...');
    range.unshift(1);
    if (total !== 1) range.push(total);
    return range;
});

const parsedItems = (items) => {
    try {
        return typeof items === 'string' ? JSON.parse(items) : items || [];
    } catch (e) {
        return [];
    }
};

// Methods
const clearAllFilters = () => { 
    filters.value.date_from = '';
    filters.value.date_to = ''; 
    filters.value.search = '';
    loadInvoices();
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const loadInvoices = async (page = 1) => {
    isLoading.value = true;
    try {
        const params = { ...filters.value, page };
        const response = await axios.get('/api/vendor/invoices', { params });
        invoices.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            total: response.data.total,
            from: response.data.from,
            to: response.data.to
        }; 
    } catch (error) {
        toast.error('Failed to load invoices');
    } finally {
        isLoading.value = false;
    }
};

const searchInvoices = () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadInvoices();
    }, 500);
};

const viewInvoice = async (invoice) => {
    try {
        const response = await axios.get(`/api/vendor/invoices/${invoice.id}`);
        selectedInvoice.value = response.data;
        showDetailsModal.value = true;
    } catch (error) {
        toast.error('Failed to load invoice details');
    }
};

const downloadInvoice = async (invoice) => {
    try {
        const response = await axios.get(`/api/vendor/invoices/${invoice.id}/download`, {
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `invoice_${invoice.invoice_number}.pdf`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        toast.success('Download started');
    } catch (error) {
        toast.error('Failed to download invoice');
    }
};

const exportInvoices = async () => {
    try {
        const response = await axios.get('/api/vendor/invoices/export', {
            params: filters.value,
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `invoices_${Date.now()}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        toast.success('Export started');
    } catch (error) {
        toast.error('Failed to export invoices');
    }
};

const markAsPaid = async () => {
    // Implementation for marking invoice as paid
};

const goToPage = (page) => {
    if (page >= 1 && page <= pagination.value.last_page) {
        loadInvoices(page);
    }
};

// Lifecycle
onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher(); 
    loadInvoices();

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