<!-- resources/js/views/admin/Invoices.vue -->
<template>
    <div class="space-y-6 pb-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Invoices</h1>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Manage all vendor subscription invoices</p>
            </div>
            <div class="flex gap-2">
                <button @click="exportInvoices" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </button>
                <button @click="openGenerateModal" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Generate Invoice
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-primary-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Invoices</p>
                        <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ stats.total }}</p>
                    </div>
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Paid</p>
                        <p class="text-2xl font-bold text-green-600">{{ stats.paid }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-yellow-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ stats.pending }}</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-red-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Failed</p>
                        <p class="text-2xl font-bold text-red-600">{{ stats.failed }}</p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Revenue</p>
                        <p class="text-2xl font-bold text-purple-600">${{ stats.revenue }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div :class="[
            'rounded-2xl shadow-lg p-4 md:p-6',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Status Filter -->
                <div class="flex-1">
                    <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Status</label>
                    <select v-model="filters.status" @change="loadInvoices" 
                        :class="[
                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                
                <!-- Vendor Filter -->
                <div class="flex-1">
                    <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Vendor</label>
                    <select v-model="filters.vendor_id" @change="loadInvoices" 
                        :class="[
                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                        <option value="">All Vendors</option>
                        <option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">
                            {{ vendor.business_name }}
                        </option>
                    </select>
                </div>
                
                <!-- Date From -->
                <div class="flex-1">
                    <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">From Date</label>
                    <input type="date" v-model="filters.date_from" @change="loadInvoices" 
                        :class="[
                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                
                <!-- Date To -->
                <div class="flex-1">
                    <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">To Date</label>
                    <input type="date" v-model="filters.date_to" @change="loadInvoices" 
                        :class="[
                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                
                <!-- Search -->
                <div class="flex-[2]">
                    <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-2.5 w-5 h-5" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" v-model="filters.search" @input="searchInvoices" 
                            placeholder="Search by invoice #, vendor, plan..." 
                            :class="[
                                'w-full pl-10 pr-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                            ]">
                    </div>
                </div>
            </div>
            
            <!-- Active Filters Display -->
            <div v-if="hasActiveFilters" class="flex flex-wrap gap-2 mt-4 pt-3 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Active filters:</span>
                <span v-if="filters.status" class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg" :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'">
                    Status: {{ filters.status }}
                    <button @click="filters.status = ''; loadInvoices()" class="hover:text-primary-900">×</button>
                </span>
                <span v-if="filters.vendor_id" class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg" :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'">
                    Vendor: {{ getVendorName(filters.vendor_id) }}
                    <button @click="filters.vendor_id = ''; loadInvoices()" class="hover:text-primary-900">×</button>
                </span>
                <span v-if="filters.date_from" class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg" :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'">
                    From: {{ filters.date_from }}
                    <button @click="filters.date_from = ''; loadInvoices()" class="hover:text-primary-900">×</button>
                </span>
                <span v-if="filters.date_to" class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg" :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'">
                    To: {{ filters.date_to }}
                    <button @click="filters.date_to = ''; loadInvoices()" class="hover:text-primary-900">×</button>
                </span>
                <span v-if="filters.search" class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg" :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'">
                    Search: "{{ filters.search }}"
                    <button @click="filters.search = ''; loadInvoices()" class="hover:text-primary-900">×</button>
                </span>
                <button @click="clearFilters" class="text-xs font-medium" :class="isDarkMode ? 'text-primary-400 hover:text-primary-300' : 'text-primary-600 hover:text-primary-700'">
                    Clear all
                </button>
            </div>
        </div>

        <!-- Results Count -->
        <div class="flex justify-between items-center">
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} invoices
            </p>
        </div>

        <!-- Invoices Table -->
        <div :class="[
            'rounded-2xl shadow-lg overflow-hidden',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <!-- Mobile Card View -->
            <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                <div v-for="invoice in invoices" :key="invoice.id" class="p-4 transition" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">#{{ invoice.invoice_number }}</p>
                            <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ invoice.vendor?.business_name }}</p>
                        </div>
                        <span :class="getStatusClass(invoice.status)" class="px-2 py-1 text-xs font-semibold rounded-full capitalize">
                            {{ invoice.status }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Amount</p>
                            <p class="text-lg font-bold text-primary-600">${{ formatPrice(invoice.total) }}</p>
                        </div>
                        <div>
                            <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Plan</p>
                            <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ invoice.plan?.name }}</p>
                        </div>
                        <div>
                            <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Period</p>
                            <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDate(invoice.billing_period_start) }} - {{ formatDate(invoice.billing_period_end) }}</p>
                        </div>
                        <div>
                            <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Paid</p>
                            <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ invoice.paid_at ? formatDate(invoice.paid_at) : '-' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 pt-2 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                        <button @click="viewInvoice(invoice)" class="flex-1 text-primary-600 hover:text-primary-700 text-sm font-medium py-1">
                            View
                        </button>
                        <button @click="downloadInvoice(invoice)" class="flex-1 text-blue-600 hover:text-blue-700 text-sm font-medium py-1">
                            Download
                        </button>
                        <button v-if="invoice.status === 'pending'" @click="markAsPaid(invoice)" class="flex-1 text-green-600 hover:text-green-700 text-sm font-medium py-1">
                            Mark Paid
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                    <thead :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Invoice #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Plan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Billing Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Paid At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" :class="[
                        isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
                    ]">
                        <tr v-for="invoice in invoices" :key="invoice.id" class="transition-colors" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                #{{ invoice.invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r from-primary-100 to-primary-200 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-semibold text-primary-700">{{ invoice.vendor?.business_name?.charAt(0) || 'V' }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ invoice.vendor?.business_name }}</p>
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ invoice.vendor?.user?.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                {{ invoice.plan?.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                {{ formatDate(invoice.billing_period_start) }} - {{ formatDate(invoice.billing_period_end) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-lg font-bold text-primary-600">${{ formatPrice(invoice.total) }}</p>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">Tax: ${{ formatPrice(invoice.tax) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusClass(invoice.status)" class="px-2 py-1 text-xs font-semibold rounded-full capitalize">
                                    {{ invoice.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                {{ invoice.paid_at ? formatDate(invoice.paid_at) : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <button @click="viewInvoice(invoice)" class="text-primary-600 hover:text-primary-800 font-medium transition">
                                    View
                                </button>
                                <button @click="downloadInvoice(invoice)" class="text-blue-600 hover:text-blue-800 font-medium transition">
                                    Download
                                </button>
                                <button v-if="invoice.status === 'pending'" @click="markAsPaid(invoice)" class="text-green-600 hover:text-green-800 font-medium transition">
                                    Mark Paid
                                </button>
                            </td>
                        </tr>
                        <tr v-if="invoices.length === 0 && !isLoading">
                            <td colspan="8" class="px-6 py-12 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <svg class="w-12 h-12 mx-auto mb-3" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                No invoices found
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.last_page > 1" class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                <button @click="goToPage(pagination.current_page - 1)" 
                    :disabled="pagination.current_page === 1"
                    :class="[
                        'px-3 py-1 border rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed',
                        isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
                    ]">
                    Previous
                </button>
                <button v-for="page in displayedPages" :key="page" 
                    @click="goToPage(page)" 
                    :class="[
                        page === pagination.current_page 
                            ? 'bg-primary-600 text-white' 
                            : isDarkMode 
                                ? 'bg-gray-700 text-gray-300 border-gray-600 hover:bg-gray-600' 
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100',
                        'px-3 py-1 border rounded-lg transition'
                    ]">
                    {{ page }}
                </button>
                <button @click="goToPage(pagination.current_page + 1)" 
                    :disabled="pagination.current_page === pagination.last_page"
                    :class="[
                        'px-3 py-1 border rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed',
                        isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
                    ]">
                    Next
                </button>
            </div>
        </div>

        <!-- View Invoice Modal -->
        <div v-if="showViewModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="showViewModal = false">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div :class="[
                    'rounded-2xl shadow-2xl max-w-3xl w-full z-50 transform transition-all max-h-[90vh] overflow-y-auto',
                    isDarkMode ? 'bg-gray-800' : 'bg-white'
                ]">
                    <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
                        ]">
                        <h3 class="text-lg font-semibold flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Invoice #{{ selectedInvoice?.invoice_number }}
                        </h3>
                        <button @click="showViewModal = false" class="transition" :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="px-6 py-4" v-if="selectedInvoice">
                        <!-- Invoice Header -->
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                            <div>
                                <h2 class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">INVOICE</h2>
                                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">#{{ selectedInvoice.invoice_number }}</p>
                            </div>
                            <div class="text-right mt-2 sm:mt-0">
                                <span :class="getStatusClass(selectedInvoice.status)" class="px-3 py-1 text-sm font-semibold rounded-full capitalize">
                                    {{ selectedInvoice.status }}
                                </span>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ selectedInvoice.created_at ? formatDate(selectedInvoice.created_at) : 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Vendor & Customer Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Bill To</p>
                                <div :class="[
                                    'rounded-xl p-4',
                                    isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                                ]">
                                    <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.vendor?.business_name }}</p>
                                    <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ selectedInvoice.vendor?.user?.name }}</p>
                                    <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ selectedInvoice.vendor?.user?.email }}</p>
                                    <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ selectedInvoice.vendor?.address }}</p>
                                    <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ selectedInvoice.vendor?.city }}, {{ selectedInvoice.vendor?.state }} {{ selectedInvoice.vendor?.zip }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Invoice Details</p>
                                <div :class="[
                                    'rounded-xl p-4 space-y-1',
                                    isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                                ]">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Invoice Date:</span>
                                        <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDate(selectedInvoice.created_at) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Billing Period:</span>
                                        <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDate(selectedInvoice.billing_period_start) }} - {{ formatDate(selectedInvoice.billing_period_end) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Currency:</span>
                                        <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.currency || 'USD' }}</span>
                                    </div>
                                    <div v-if="selectedInvoice.stripe_invoice_id" class="flex justify-between text-sm">
                                        <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Stripe Invoice:</span>
                                        <span class="font-mono text-xs" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ truncateId(selectedInvoice.stripe_invoice_id) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div class="mb-6">
                            <h4 class="font-semibold mb-3" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Invoice Items</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                                    <thead :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Description</th>
                                            <th class="px-4 py-2 text-right text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y" :class="[
                                        isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
                                    ]">
                                        <tr v-for="(item, index) in getInvoiceItems(selectedInvoice)" :key="index">
                                            <td class="px-4 py-2 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'">{{ item.description }}</td>
                                            <td class="px-4 py-2 text-sm text-right" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'">${{ formatPrice(item.amount) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="border-t-2" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                        <tr>
                                            <td class="px-4 py-2 text-right font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Subtotal:</td>
                                            <td class="px-4 py-2 text-right font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">${{ formatPrice(selectedInvoice.amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-right font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Tax:</td>
                                            <td class="px-4 py-2 text-right font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">${{ formatPrice(selectedInvoice.tax) }}</td>
                                        </tr>
                                        <tr class="border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                            <td class="px-4 py-2 text-right text-lg font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Total:</td>
                                            <td class="px-4 py-2 text-right text-lg font-bold text-primary-600">${{ formatPrice(selectedInvoice.total) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div v-if="selectedInvoice.payment_details" class="mb-6">
                            <h4 class="font-semibold mb-3" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Payment Details</h4>
                            <div :class="[
                                'grid grid-cols-1 sm:grid-cols-2 gap-3 rounded-xl p-4',
                                isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                            ]">
                                <div>
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Payment Method</p>
                                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.payment_details?.method || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Transaction ID</p>
                                    <p class="font-mono text-xs" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ selectedInvoice.payment_details?.transaction_id || 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Paid At</p>
                                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedInvoice.paid_at ? formatDate(selectedInvoice.paid_at) : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Stripe Payment Intent</p>
                                    <p class="font-mono text-xs" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ truncateId(selectedInvoice.stripe_payment_intent_id) || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t flex justify-end gap-3 rounded-b-2xl" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-gray-50'
                        ]">
                        <button v-if="selectedInvoice?.status === 'pending'" @click="markAsPaid(selectedInvoice)" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-md">
                            Mark as Paid
                        </button>
                        <button @click="downloadInvoice(selectedInvoice)" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md">
                            Download PDF
                        </button>
                        <button @click="showViewModal = false" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate Invoice Modal -->
        <div v-if="showGenerateModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeGenerateModal">
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
                        <h3 class="text-xl font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Generate Invoice</h3>
                        <button @click="closeGenerateModal" class="transition" :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="generateInvoice" class="px-6 py-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Vendor *</label>
                                <select v-model="generateForm.vendor_id" required 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                    ]">
                                    <option value="">Select Vendor</option>
                                    <option v-for="vendor in vendors" :key="vendor.id" :value="vendor.id">
                                        {{ vendor.business_name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Plan *</label>
                                <select v-model="generateForm.plan_price_id" required 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                    ]">
                                    <option value="">Select Plan</option>
                                    <option v-for="price in availablePrices" :key="price.id" :value="price.id">
                                        {{ price.plan?.name }} - ${{ formatPrice(price.price) }}/{{ price.interval }}
                                    </option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Period Start *</label>
                                    <input type="date" v-model="generateForm.period_start" required 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Period End *</label>
                                    <input type="date" v-model="generateForm.period_end" required 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Amount ($)</label>
                                    <input type="number" step="0.01" v-model="generateForm.amount" 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Tax ($)</label>
                                    <input type="number" step="0.01" v-model="generateForm.tax" 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Status</label>
                                <select v-model="generateForm.status" 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                    ]">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                            <button type="button" @click="closeGenerateModal" 
                                :class="[
                                    'px-4 py-2 border rounded-lg transition',
                                    isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                                ]">
                                Cancel
                            </button>
                            <button type="submit" :disabled="isGenerating" 
                                class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ isGenerating ? 'Generating...' : 'Generate Invoice' }}
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
import { format } from 'date-fns';

const toast = useToast();

const invoices = ref([]);
const vendors = ref([]);
const isLoading = ref(false);
const isGenerating = ref(false);
const showViewModal = ref(false);
const showGenerateModal = ref(false);
const selectedInvoice = ref(null);
const isDarkMode = ref(false);
let darkModeObserver = null;

const stats = ref({
    total: 0,
    paid: 0,
    pending: 0,
    failed: 0,
    revenue: 0
});

const filters = ref({
    status: '',
    vendor_id: '',
    date_from: '',
    date_to: '',
    search: ''
});

const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0
});

const generateForm = ref({
    vendor_id: '',
    plan_price_id: '',
    period_start: '',
    period_end: '',
    amount: 0,
    tax: 0,
    status: 'pending'
});

let searchTimeout = null;

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

const hasActiveFilters = computed(() => {
    return filters.value.status || filters.value.vendor_id || 
           filters.value.date_from || filters.value.date_to || 
           filters.value.search;
});

const availablePrices = ref([]);

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

const formatPrice = (price) => {
    if (!price && price !== 0) return '0.00';
    return Number(price).toFixed(2);
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return format(new Date(date), 'MMM dd, yyyy');
};

const formatDateTime = (datetime) => {
    if (!datetime) return 'N/A';
    return format(new Date(datetime), 'MMM dd, yyyy h:mm a');
};

const truncateId = (id) => {
    if (!id) return '';
    if (id.length <= 20) return id;
    return id.substring(0, 17) + '...';
};

const getStatusClass = (status) => {
    const classes = {
        paid: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        refunded: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
};

const getVendorName = (id) => {
    const vendor = vendors.value.find(v => v.id == id);
    return vendor ? vendor.business_name : '';
};

const getInvoiceItems = (invoice) => {
    if (invoice.items && typeof invoice.items === 'object') {
        if (Array.isArray(invoice.items)) {
            return invoice.items;
        }
        return Object.values(invoice.items);
    }
    return [
        { description: invoice.plan?.name || 'Subscription', amount: invoice.amount }
    ];
};

const loadVendors = async () => {
    try {
        const response = await axios.get('/api/admin/vendors?per_page=100');
        vendors.value = response.data.data || [];
    } catch (error) {
        console.error('Failed to load vendors:', error);
    }
};

const loadPlanPrices = async () => {
    try {
        const response = await axios.get('/api/admin/plan-prices');
        availablePrices.value = response.data.data || [];
    } catch (error) {
        console.error('Failed to load plan prices:', error);
    }
};

const loadStats = async () => {
    try {
        const response = await axios.get('/api/admin/invoices/stats');
        stats.value = response.data;
    } catch (error) {
        console.error('Failed to load stats:', error);
    }
};

const loadInvoices = async (page = 1) => {
    isLoading.value = true;
    try {
        const response = await axios.get('/api/admin/invoices', { 
            params: { ...filters.value, page } 
        });
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

const clearFilters = () => {
    filters.value = {
        status: '',
        vendor_id: '',
        date_from: '',
        date_to: '',
        search: ''
    };
    loadInvoices();
};

const viewInvoice = async (invoice) => {
    try {
        const response = await axios.get(`/api/admin/invoices/${invoice.id}`);
        selectedInvoice.value = response.data;
        showViewModal.value = true;
    } catch (error) {
        toast.error('Failed to load invoice details');
    }
};

const downloadInvoice = async (invoice) => {
    try {
        const response = await axios.get(`/api/admin/invoices/${invoice.id}/download`, {
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(response.data);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `invoice_${invoice.invoice_number}.pdf`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        toast.success('Invoice downloaded successfully');
    } catch (error) {
        toast.error('Failed to download invoice');
    }
};

const markAsPaid = async (invoice) => {
    try {
        await axios.put(`/api/admin/invoices/${invoice.id}/mark-paid`);
        toast.success('Invoice marked as paid');
        await loadInvoices(pagination.value.current_page);
        await loadStats();
        if (showViewModal.value) {
            showViewModal.value = false;
        }
    } catch (error) {
        toast.error('Failed to mark invoice as paid');
    }
};

const openGenerateModal = () => {
    generateForm.value = {
        vendor_id: '',
        plan_price_id: '',
        period_start: format(new Date(), 'yyyy-MM-dd'),
        period_end: format(new Date(new Date().setMonth(new Date().getMonth() + 1)), 'yyyy-MM-dd'),
        amount: 0,
        tax: 0,
        status: 'pending'
    };
    showGenerateModal.value = true;
};

const closeGenerateModal = () => {
    showGenerateModal.value = false;
};

const generateInvoice = async () => {
    isGenerating.value = true;
    try {
        const response = await axios.post('/api/admin/invoices/generate', generateForm.value);
        toast.success('Invoice generated successfully');
        closeGenerateModal();
        await loadInvoices();
        await loadStats();
    } catch (error) {
        toast.error(error.response?.data?.message || 'Failed to generate invoice');
    } finally {
        isGenerating.value = false;
    }
};

const exportInvoices = async () => {
    try {
        const response = await axios.get('/api/admin/invoices/export', {
            params: filters.value,
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(response.data);
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

const goToPage = (page) => {
    if (page >= 1 && page <= pagination.value.last_page) {
        loadInvoices(page);
    }
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadVendors();
    loadPlanPrices();
    loadStats();
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