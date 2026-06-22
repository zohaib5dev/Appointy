<!-- resources/js/views/admin/Plans.vue -->
<template>
    <div class="space-y-6 pb-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Subscription Plans</h1>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Manage subscription plans and pricing</p>
            </div>
            <button @click="openModal()" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Add Plan</span>
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
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Plans</p>
                        <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ plans.length }}</p>
                    </div>
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
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
                        <p class="text-2xl font-bold text-green-600">{{ activeCount }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Prices</p>
                        <p class="text-2xl font-bold text-purple-600">{{ totalPrices }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div :class="[
                'rounded-2xl shadow-lg p-5 border-l-4 border-blue-500',
                isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Avg. Price</p>
                        <p class="text-2xl font-bold text-blue-600">${{ averagePrice }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="plan in plans" :key="plan.id" 
                :class="[
                    'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border',
                    isDarkMode ? 'bg-gray-800 border-gray-700 hover:shadow-gray-700' : 'bg-white border-gray-100'
                ]">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ plan.name }}</h3>
                            <p class="text-sm mt-1 line-clamp-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ plan.description || 'No description' }}</p>
                        </div>
                        <div class="flex space-x-1 opacitsy-0 group-hover:opacity-100 transition-opacity duration-200 ml-2">
                            <button @click="editPlan(plan)" class="p-1.5 text-primary-600 rounded-lg transition" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-primary-50'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button @click="deletePlan(plan.id)" class="p-1.5 text-red-600 rounded-lg transition" :class="isDarkMode ? 'hover:bg-red-900/20' : 'hover:bg-red-50'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Pricing Information -->
                    <div class="space-y-2 mb-4">
                        <div v-for="price in plan.prices" :key="price.id" 
                            :class="[
                                'flex justify-between items-center p-3 rounded-xl transition',
                                isDarkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-50 hover:bg-gray-100'
                            ]">
                            <span class="text-sm font-medium capitalize flex items-center gap-2" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                                <span class="w-2 h-2 rounded-full" :class="getIntervalColor(price.interval)"></span>
                                {{ price.interval }}
                            </span>
                            <div class="text-right">
                                <span class="text-lg font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">${{ formatPrice(price.price) }}</span>
                                <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'" v-if="price.interval !== 'one_time'">/{{ price.interval === 'yearly' ? 'yr' : price.interval === 'monthly' ? 'mo' : 'quarter' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Limits -->
                    <div :class="[
                        'space-y-2 mb-4 p-3 rounded-xl',
                        isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                    ]">
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Services
                            </span>
                            <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ plan.services_limit === -1 ? '♾️' : plan.services_limit }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Staff
                            </span>
                            <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ plan.staff_limit === -1 ? '♾️' : plan.staff_limit }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Appointments
                            </span>
                            <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ plan.appointments_limit === -1 ? '♾️' : plan.appointments_limit.toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                                Storage
                            </span>
                            <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatStorage(plan.storage_limit) }}</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-between items-center pt-3 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                        <span :class="plan.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'" 
                            class="px-2.5 py-1 text-xs font-semibold rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full" :class="plan.is_active ? 'bg-green-500' : 'bg-gray-400'"></span>
                            {{ plan.is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="text-xs" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'">{{ plan.prices?.length || 0 }} price{{ plan.prices?.length !== 1 ? 's' : '' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="plans.length === 0 && !isLoading" :class="[
            'rounded-2xl shadow-lg p-12 text-center',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
        ]">
            <svg class="w-16 h-16 mx-auto mb-4" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h3 class="text-lg font-semibold mb-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">No Plans Created</h3>
            <p class="mb-4" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Get started by creating your first subscription plan.</p>
            <button @click="openModal()" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Plan
            </button>
        </div>

        <!-- Plan Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
                <div :class="[
                    'rounded-2xl shadow-2xl max-w-2xl w-full z-50 transform transition-all max-h-[90vh] overflow-y-auto',
                    isDarkMode ? 'bg-gray-800' : 'bg-white'
                ]">
                    <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
                        ]">
                        <h3 class="text-xl font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ editing ? 'Edit Plan' : 'Add New Plan' }}</h3>
                        <button @click="closeModal" class="transition" :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form @submit.prevent="savePlan" class="px-6 py-4">
                        <div class="space-y-4">
                            <!-- Basic Information -->
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Plan Name *</label>
                                <input type="text" v-model="form.name" required 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                                    ]"
                                    placeholder="e.g., Basic, Pro, Enterprise">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Description</label>
                                <textarea v-model="form.description" rows="2" 
                                    :class="[
                                        'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                                    ]"
                                    placeholder="Describe the plan..."></textarea>
                            </div>

                            <!-- Limits -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Services Limit *</label>
                                    <input type="number" v-model="form.services_limit" required 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                    <p class="text-xs mt-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Use -1 for unlimited</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Staff Limit *</label>
                                    <input type="number" v-model="form.staff_limit" required 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                    <p class="text-xs mt-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Use -1 for unlimited</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Appointments Limit *</label>
                                    <input type="number" v-model="form.appointments_limit" required 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                    <p class="text-xs mt-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Use -1 for unlimited</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Storage Limit (MB) *</label>
                                    <input type="number" v-model="form.storage_limit" required 
                                        :class="[
                                            'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                </div>
                            </div>

                            <!-- Pricing Section -->
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <label class="block text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Pricing Plans *</label>
                                    <button type="button" @click="addPrice" 
                                        class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Price
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <div v-for="(price, index) in form.prices" :key="index" 
                                        :class="[
                                            'p-4 rounded-xl border',
                                            isDarkMode ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-200'
                                        ]">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Interval</label>
                                                <select v-model="price.interval" required 
                                                    :class="[
                                                        'w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                                        isDarkMode ? 'bg-gray-600 border-gray-500 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                    ]">
                                                    <option value="monthly">Monthly</option>
                                                    <option value="yearly">Yearly</option>
                                                    <option value="quarterly">Quarterly</option>
                                                    <option value="one_time">One Time</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Price ($)</label>
                                                <input type="number" step="0.01" v-model="price.price" required 
                                                    :class="[
                                                        'w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                                        isDarkMode ? 'bg-gray-600 border-gray-500 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                    ]">
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Stripe Price ID *</label>
                                                <div class="flex gap-2">
                                                    <input type="text" v-model="price.price_id" required 
                                                        placeholder="price_xxxxxxxxxxxxx" 
                                                        :class="[
                                                            'flex-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                                                            isDarkMode ? 'bg-gray-600 border-gray-500 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                                                        ]">
                                                    <button type="button" @click="removePrice(index)" 
                                                        class="px-3 py-2 text-red-600 rounded-lg transition" :class="isDarkMode ? 'hover:bg-red-900/20' : 'hover:bg-red-50'">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p v-if="form.prices.length === 0" class="text-sm text-red-500">At least one price is required</p>
                                </div>
                            </div>

                            <!-- Status Toggle -->
                            <div :class="[
                                'flex items-center justify-between p-3 rounded-xl',
                                isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                            ]">
                                <label class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Plan Status</label>
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
                        
                        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3 sticky bottom-0 py-4 border-t -mx-6 px-6 rounded-b-2xl"
                            :class="[
                                isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
                            ]">
                            <button type="button" @click="closeModal" 
                                :class="[
                                    'px-4 py-2 border rounded-lg transition',
                                    isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                                ]">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition shadow-md">
                                {{ editing ? 'Update Plan' : 'Create Plan' }}
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
const plans = ref([]);
const isLoading = ref(false);
const showModal = ref(false);
const editing = ref(false);
const isDarkMode = ref(false);
let darkModeObserver = null;

const form = ref({
    name: '',
    description: '',
    services_limit: -1,
    staff_limit: -1,
    appointments_limit: -1,
    storage_limit: 5000,
    is_active: true,
    prices: [
        {
            interval: 'monthly',
            price_id: '',
            price: 0
        }
    ]
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

// Computed stats
const activeCount = computed(() => {
    return plans.value.filter(p => p.is_active).length;
});

const totalPrices = computed(() => {
    return plans.value.reduce((sum, plan) => sum + (plan.prices?.length || 0), 0);
});

const averagePrice = computed(() => {
    let total = 0;
    let count = 0;
    plans.value.forEach(plan => {
        if (plan.prices) {
            plan.prices.forEach(price => {
                total += parseFloat(price.price);
                count++;
            });
        }
    });
    if (count === 0) return '0.00';
    return (total / count).toFixed(2);
});

const getIntervalColor = (interval) => {
    const colors = {
        monthly: 'bg-blue-500',
        yearly: 'bg-green-500',
        quarterly: 'bg-purple-500',
        one_time: 'bg-orange-500'
    };
    return colors[interval] || 'bg-gray-500';
};

const loadPlans = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/api/admin/plans');
        plans.value = response.data.data || response.data || [];
    } catch (error) {
        toast.error('Failed to load plans');
    } finally {
        isLoading.value = false;
    }
};

const openModal = () => {
    editing.value = false;
    form.value = {
        name: '',
        description: '',
        services_limit: -1,
        staff_limit: -1,
        appointments_limit: -1,
        storage_limit: 5000,
        is_active: true,
        prices: [
            {
                interval: 'monthly',
                price_id: '',
                price: 0
            }
        ]
    };
    showModal.value = true;
};

const editPlan = (plan) => {
    editing.value = true;
    form.value = {
        id: plan.id,
        name: plan.name,
        description: plan.description || '',
        services_limit: plan.services_limit,
        staff_limit: plan.staff_limit,
        appointments_limit: plan.appointments_limit,
        storage_limit: plan.storage_limit,
        is_active: plan.is_active,
        prices: plan.prices?.map(price => ({
            id: price.id,
            interval: price.interval,
            price_id: price.price_id,
            price: price.price
        })) || []
    };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editing.value = false;
};

const addPrice = () => {
    form.value.prices.push({
        interval: 'monthly',
        price_id: '',
        price: 0
    });
};

const removePrice = (index) => {
    form.value.prices.splice(index, 1);
};

const savePlan = async () => {
    if (form.value.prices.length === 0) {
        toast.error('Please add at least one price');
        return;
    }

    try {
        if (editing.value) {
            await axios.put(`/api/admin/plans/${form.value.id}`, form.value);
            toast.success('Plan updated successfully');
        } else {
            await axios.post('/api/admin/plans', form.value);
            toast.success('Plan created successfully');
        }
        closeModal();
        await loadPlans();
    } catch (error) {
        toast.error(error.response?.data?.message || 'Failed to save plan');
    }
};

const deletePlan = async (id) => {
    if (confirm('Are you sure you want to delete this plan? This will also delete all associated prices.')) {
        try {
            await axios.delete(`/api/admin/plans/${id}`);
            toast.success('Plan deleted successfully');
            await loadPlans();
        } catch (error) {
            toast.error('Failed to delete plan');
        }
    }
};

const formatPrice = (price) => {
    return Number(price).toFixed(2);
};

const formatStorage = (mb) => {
    if (mb === -1) return '♾️ Unlimited';
    if (mb >= 1024) {
        return `${(mb / 1024).toFixed(1)} GB`;
    }
    return `${mb} MB`;
};

const truncateId = (id) => {
    if (!id) return '';
    if (id.length <= 20) return id;
    return id.substring(0, 17) + '...';
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadPlans();

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