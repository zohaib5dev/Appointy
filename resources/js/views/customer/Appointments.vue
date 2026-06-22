<!-- resources/js/views/customer/Appointments.vue -->
<template>
    <div :class="['min-h-screen py-8 transition-colors duration-300', isDarkMode ? 'bg-gray-900' : 'bg-gradient-to-br from-gray-50 to-gray-100']">
  
  

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 :class="['text-3xl md:text-4xl font-bold mb-2', isDarkMode ? 'text-white' : 'text-gray-900']">
                    My Appointments
                </h1>
                <p :class="[isDarkMode ? 'text-gray-400' : 'text-gray-600']">Manage and track all your bookings in one place</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div :class="['rounded-xl shadow-sm p-4 hover:shadow-md transition', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <div class="flex items-center justify-between">
                        <div>
                            <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Upcoming</p>
                            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ upcomingCount }}</p>
                        </div>
                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div :class="['rounded-xl shadow-sm p-4 hover:shadow-md transition', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <div class="flex items-center justify-between">
                        <div>
                            <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Completed</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ completedCount }}</p>
                        </div>
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div :class="['rounded-xl shadow-sm p-4 hover:shadow-md transition', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <div class="flex items-center justify-between">
                        <div>
                            <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Cancelled</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ cancelledCount }}</p>
                        </div>
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div :class="['rounded-xl shadow-sm p-4 hover:shadow-md transition', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <div class="flex items-center justify-between">
                        <div>
                            <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Total Spent</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">${{ totalSpent }}</p>
                        </div>
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div :class="['rounded-xl shadow-sm mb-6 overflow-x-auto', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                <div class="flex space-x-1 p-1 min-w-max">
                    <button 
                        @click="activeTab = 'upcoming'" 
                        :class="[
                            'flex items-center gap-2 px-6 py-3 rounded-lg font-medium transition-all duration-200',
                            activeTab === 'upcoming' 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : isDarkMode ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-600 hover:bg-gray-100'
                        ]"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Upcoming
                        <span v-if="upcomingCount > 0" class="ml-2 px-2 py-0.5 text-xs bg-white/20 rounded-full">{{ upcomingCount }}</span>
                    </button>
                    <button 
                        @click="activeTab = 'past'" 
                        :class="[
                            'flex items-center gap-2 px-6 py-3 rounded-lg font-medium transition-all duration-200',
                            activeTab === 'past' 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : isDarkMode ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-600 hover:bg-gray-100'
                        ]"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Past
                    </button>
                    <button 
                        @click="activeTab = 'cancelled'" 
                        :class="[
                            'flex items-center gap-2 px-6 py-3 rounded-lg font-medium transition-all duration-200',
                            activeTab === 'cancelled' 
                                ? 'bg-primary-600 text-white shadow-md' 
                                : isDarkMode ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-600 hover:bg-gray-100'
                        ]"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cancelled
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center items-center py-20">
                <div class="relative">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary-600"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Appointments List -->
            <div v-else class="space-y-4">
                <div v-for="appointment in filteredAppointments" :key="appointment.id" 
                     :class="['rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <!-- Status Banner -->
                    <div v-if="appointment.status === 'cancelled'" class="bg-red-50 dark:bg-red-900/20 px-6 py-2 border-b border-red-100 dark:border-red-800">
                        <p class="text-sm text-red-600 dark:text-red-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Cancelled on {{ formatDate(appointment.updated_at) }}
                        </p>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <!-- Left Section -->
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <!-- Vendor Avatar -->
                                    <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center flex-shrink-0">
                                         

                                          <h3 class="font-bold text-lg mb-1 line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-gray-900'"> {{ appointment.vendor?.business_name?.charAt(0) || '?' }}</h3>
          
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                            <h3 :class="['text-xl font-bold', isDarkMode ? 'text-white' : 'text-gray-900']">
                                                {{ appointment.vendor?.business_name }}
                                            </h3>
                                            <span :class="getStatusClass(appointment.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ getStatusText(appointment.status) }}
                                            </span>
                                        </div>
                                        <p :class="[isDarkMode ? 'text-gray-400' : 'text-gray-600', 'mb-3']">
                                            {{ appointment.service?.name }} • {{ appointment.service?.duration }} mins
                                        </p>
                                        
                                        <!-- Appointment Details Grid -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                            <div class="flex items-center text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ formatDateTime(appointment.start_time) }}</span>
                                            </div>
                                            <div class="flex items-center text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                                                <svg class="w-4 h-4 mr-2 flex-shrink-0" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>{{ appointment.staff?.name || 'Any staff' }}</span>
                                            </div>
                                            <div class="flex items-center text-sm font-semibold">
                                                <svg class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-primary-600 dark:text-primary-400">${{ appointment.total_amount }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Booking Number -->
                                        <div class="mt-3">
                                            <p :class="['text-xs', isDarkMode ? 'text-gray-500' : 'text-gray-400']">Booking #: {{ appointment.booking_number }}</p>
                                        </div>
                                        
                                        <!-- Reminder -->
                                        <div v-if="isUpcoming(appointment.start_time) && appointment.status !== 'cancelled'" class="mt-4 p-3 bg-yellow-100  rounded-lg flex items-center gap-2">
                                            <svg class="w-5 h-5 text-black-600 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-sm text-black-800 ">Reminder: Your appointment is in {{ getDaysUntil(appointment.start_time) }} days</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-row lg:flex-col gap-2 flex-wrap">
                                <button v-if="canCancel(appointment.status)"
                                        @click="cancelAppointment(appointment.id)"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2 border"
                                        :class="[
                                            isDarkMode 
                                                ? 'text-red-400 border-red-800 hover:bg-red-900/30' 
                                                : 'text-red-600 border-red-200 hover:bg-red-50'
                                        ]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Cancel
                                </button>
                                <button v-if="canReschedule(appointment.status)"
                                        @click="openRescheduleModal(appointment)"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2 border"
                                        :class="[
                                            isDarkMode 
                                                ? 'text-primary-400 border-primary-800 hover:bg-primary-900/30' 
                                                : 'text-primary-600 border-primary-200 hover:bg-primary-50'
                                        ]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Reschedule
                                </button>
                                <button v-if="canReview(appointment)"
                                        @click="openReviewModal(appointment)"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2 border"
                                        :class="[
                                            isDarkMode 
                                                ? 'text-yellow-400 border-yellow-800 hover:bg-yellow-900/30' 
                                                : 'text-yellow-600 border-yellow-200 hover:bg-yellow-50'
                                        ]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                    Write Review
                                </button>
                                <button @click="viewDetails(appointment)"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center gap-2 border"
                                        :class="[
                                            isDarkMode 
                                                ? 'text-gray-300 border-gray-600 hover:bg-gray-700' 
                                                : 'text-gray-600 border-gray-200 hover:bg-gray-50'
                                        ]">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div v-if="filteredAppointments.length === 0 && !isLoading" :class="['rounded-xl shadow-sm p-12 text-center', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 :class="['mt-4 text-lg font-medium', isDarkMode ? 'text-white' : 'text-gray-900']">No {{ activeTab }} appointments</h3>
                    <p :class="['mt-2 text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Get started by booking an appointment with a vendor.</p>
                    <div class="mt-6">
                        <router-link to="/vendors" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-all duration-200 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Browse Vendors
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reschedule Modal -->
        <div v-if="showRescheduleModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="showRescheduleModal = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div :class="['rounded-xl shadow-xl max-w-md w-full z-50 p-6', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <h3 :class="['text-xl font-bold mb-4', isDarkMode ? 'text-white' : 'text-gray-900']">Reschedule Appointment</h3>
                    <div class="space-y-4">
                        <div>
                            <label :class="['block text-sm font-medium mb-1', isDarkMode ? 'text-gray-300' : 'text-gray-700']">Select New Date</label>
                            <input type="date" v-model="rescheduleData.date" :min="minDate" @change="loadRescheduleSlots" :class="['w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500', isDarkMode ? 'bg-gray-700 border-gray-600 text-white' : 'border-gray-300']">
                        </div>
                        <div v-if="rescheduleSlots.length > 0">
                            <label :class="['block text-sm font-medium mb-2', isDarkMode ? 'text-gray-300' : 'text-gray-700']">Available Time Slots</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button v-for="slot in rescheduleSlots" :key="slot.start_time" @click="selectRescheduleSlot(slot)" :class="[
                                    'px-3 py-2 rounded-lg text-sm font-medium transition',
                                    selectedRescheduleSlot === slot 
                                        ? 'bg-primary-600 text-white' 
                                        : isDarkMode ? 'bg-gray-700 text-gray-200 hover:bg-gray-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                ]">
                                    {{ slot.formatted_time }}
                                </button>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-4">
                            <button @click="showRescheduleModal = false" :class="['flex-1 px-4 py-2 border rounded-lg transition', isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50']">Cancel</button>
                            <button @click="confirmReschedule" :disabled="!selectedRescheduleSlot" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Modal -->
        <div v-if="showReviewModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="showReviewModal = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div :class="['rounded-xl shadow-xl max-w-md w-full z-50 p-6', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <h3 :class="['text-xl font-bold mb-4', isDarkMode ? 'text-white' : 'text-gray-900']">Write a Review</h3>
                    <div class="space-y-4">
                        <div>
                            <label :class="['block text-sm font-medium mb-1', isDarkMode ? 'text-gray-300' : 'text-gray-700']">Rating</label>
                            <div class="flex gap-2">
                                <button v-for="i in 5" :key="i" @click="reviewData.rating = i" class="text-2xl focus:outline-none">
                                    <span :class="i <= reviewData.rating ? 'text-yellow-400' : 'text-gray-300'">★</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label :class="['block text-sm font-medium mb-1', isDarkMode ? 'text-gray-300' : 'text-gray-700']">Comment</label>
                            <textarea v-model="reviewData.comment" rows="4" :class="['w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500', isDarkMode ? 'bg-gray-700 border-gray-600 text-white placeholder-gray-400' : 'border-gray-300']" placeholder="Share your experience..."></textarea>
                        </div>
                        <div class="flex gap-3 pt-4">
                            <button @click="showReviewModal = false" :class="['flex-1 px-4 py-2 border rounded-lg transition', isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50']">Cancel</button>
                            <button @click="submitReview" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Modal -->
        <div v-if="showDetailsModal && selectedAppointment" class="fixed inset-0 z-50 overflow-y-auto" @click.self="showDetailsModal = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div :class="['rounded-xl shadow-xl max-w-2xl w-full z-50 max-h-[90vh] overflow-y-auto', isDarkMode ? 'bg-gray-800' : 'bg-white']">
                    <div :class="['sticky top-0 border-b px-6 py-4 flex justify-between items-center', isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200']">
                        <h3 :class="['text-xl font-bold', isDarkMode ? 'text-white' : 'text-gray-900']">Appointment Details</h3>
                        <button @click="showDetailsModal = false" :class="[isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600']">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Booking Info -->
                        <div :class="['rounded-lg p-4', isDarkMode ? 'bg-gray-700' : 'bg-gray-50']">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Booking Number</p>
                                    <p :class="['font-semibold', isDarkMode ? 'text-white' : 'text-gray-900']">{{ selectedAppointment.booking_number }}</p>
                                </div>
                                <div>
                                    <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Status</p>
                                    <span :class="getStatusClass(selectedAppointment.status)" class="inline-block px-2 py-1 text-xs font-semibold rounded-full mt-1">
                                        {{ getStatusText(selectedAppointment.status) }}
                                    </span>
                                </div>
                                <div>
                                    <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Payment Status</p>
                                    <span :class="[
                                        selectedAppointment.payment_status === 'paid' 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'inline-block px-2 py-1 text-xs font-semibold rounded-full mt-1'
                                    ]">
                                        {{ selectedAppointment.payment_status?.toUpperCase() || 'N/A' }}
                                    </span>
                                </div>
                                <div>
                                    <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Total Amount</p>
                                    <p class="font-semibold text-primary-600 dark:text-primary-400 text-lg">${{ selectedAppointment.total_amount }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vendor Info -->
                        <div>
                            <h4 :class="['font-semibold mb-3 flex items-center gap-2', isDarkMode ? 'text-white' : 'text-gray-900']">
                                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Vendor Information
                            </h4>
                            <div :class="['rounded-lg p-4', isDarkMode ? 'bg-gray-700' : 'bg-gray-50']">
                                <p :class="['font-medium', isDarkMode ? 'text-white' : 'text-gray-900']">{{ selectedAppointment.vendor?.business_name }}</p>
                                <p :class="['text-sm mt-1', isDarkMode ? 'text-gray-400' : 'text-gray-600']">{{ selectedAppointment.vendor?.address }}, {{ selectedAppointment.vendor?.city }}, {{ selectedAppointment.vendor?.state }}</p>
                                <p :class="['text-sm mt-1', isDarkMode ? 'text-gray-400' : 'text-gray-600']">{{ selectedAppointment.vendor?.phone }}</p>
                            </div>
                        </div>
                        
                        <!-- Service & Staff Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 :class="['font-semibold mb-3 flex items-center gap-2', isDarkMode ? 'text-white' : 'text-gray-900']">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Service Details
                                </h4>
                                <div :class="['rounded-lg p-4', isDarkMode ? 'bg-gray-700' : 'bg-gray-50']">
                                    <p :class="['font-medium', isDarkMode ? 'text-white' : 'text-gray-900']">{{ selectedAppointment.service?.name }}</p>
                                    <p :class="['text-sm mt-1', isDarkMode ? 'text-gray-400' : 'text-gray-600']">{{ selectedAppointment.service?.description }}</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">⏱️ {{ selectedAppointment.service?.duration }} mins</span>
                                        <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">${{ selectedAppointment.service?.price }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 :class="['font-semibold mb-3 flex items-center gap-2', isDarkMode ? 'text-white' : 'text-gray-900']">
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Staff Member
                                </h4>
                                <div :class="['rounded-lg p-4', isDarkMode ? 'bg-gray-700' : 'bg-gray-50']">
                                    <p :class="['font-medium', isDarkMode ? 'text-white' : 'text-gray-900']">{{ selectedAppointment.staff?.name || 'Any available staff' }}</p>
                                    <p :class="['text-sm mt-1', isDarkMode ? 'text-gray-400' : 'text-gray-600']">{{ selectedAppointment.staff?.position || 'Staff' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Schedule Info -->
                        <div>
                            <h4 :class="['font-semibold mb-3 flex items-center gap-2', isDarkMode ? 'text-white' : 'text-gray-900']">
                                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Schedule
                            </h4>
                            <div :class="['rounded-lg p-4', isDarkMode ? 'bg-gray-700' : 'bg-gray-50']">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">Start Time</p>
                                        <p :class="['font-medium', isDarkMode ? 'text-white' : 'text-gray-900']">{{ formatDateTime(selectedAppointment.start_time) }}</p>
                                    </div>
                                    <div>
                                        <p :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']">End Time</p>
                                        <p :class="['font-medium', isDarkMode ? 'text-white' : 'text-gray-900']">{{ formatDateTime(selectedAppointment.end_time) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Customer Notes -->
                        <div v-if="selectedAppointment.customer_notes">
                            <h4 :class="['font-semibold mb-3', isDarkMode ? 'text-white' : 'text-gray-900']">Your Notes</h4>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                                <p :class="[isDarkMode ? 'text-gray-300' : 'text-gray-700']">{{ selectedAppointment.customer_notes }}</p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons in Modal -->
                        <div class="flex gap-3 pt-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                            <button v-if="canCancel(selectedAppointment.status)"
                                    @click="cancelAppointment(selectedAppointment.id); showDetailsModal = false"
                                    class="flex-1 px-4 py-2 font-medium rounded-lg transition border"
                                    :class="[
                                        isDarkMode 
                                            ? 'text-red-400 border-red-800 hover:bg-red-900/30' 
                                            : 'text-red-600 border-red-200 hover:bg-red-50'
                                    ]">
                                Cancel Appointment
                            </button>
                            <button v-if="canReschedule(selectedAppointment.status)"
                                    @click="openRescheduleModal(selectedAppointment); showDetailsModal = false"
                                    class="flex-1 px-4 py-2 font-medium rounded-lg transition border"
                                    :class="[
                                        isDarkMode 
                                            ? 'text-primary-400 border-primary-800 hover:bg-primary-900/30' 
                                            : 'text-primary-600 border-primary-200 hover:bg-primary-50'
                                    ]">
                                Reschedule
                            </button>
                            <button @click="showDetailsModal = false"
                                    class="flex-1 px-4 py-2 font-medium rounded-lg transition bg-gray-600 text-white hover:bg-gray-700">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useAppointmentStore } from '../../stores/appointment';
import { useToast } from 'vue-toastification';
import { format, isAfter, isBefore, isToday, differenceInDays } from 'date-fns';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const appointmentStore = useAppointmentStore();
const toast = useToast();

const activeTab = ref('upcoming');
const appointments = ref([]);
const isLoading = ref(false);
const isDarkMode = ref(false);
const showRescheduleModal = ref(false);
const showReviewModal = ref(false);
const showDetailsModal = ref(false);
const selectedAppointment = ref(null);
const rescheduleData = ref({ date: '', appointment: null });
const rescheduleSlots = ref([]);
const selectedRescheduleSlot = ref(null);
const reviewData = ref({ rating: 5, comment: '' });
const minDate = format(new Date(), 'yyyy-MM-dd');
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

// Computed stats
const upcomingCount = computed(() => {
    const now = new Date();
    return appointments.value.filter(a => {
        const appointmentDate = new Date(a.start_time);
        const isFuture = isAfter(appointmentDate, now) || isToday(appointmentDate);
        return (a.status === 'pending' || a.status === 'confirmed') && isFuture;
    }).length;
});

const completedCount = computed(() => {
    return appointments.value.filter(a => a.status === 'completed').length;
});

const cancelledCount = computed(() => {
    return appointments.value.filter(a => a.status === 'cancelled').length;
});

const totalSpent = computed(() => {
    return appointments.value
        .filter(a => a.status === 'completed')
        .reduce((sum, a) => sum + parseFloat(a.total_amount || 0), 0)
        .toFixed(2);
});

// Filter appointments
const filteredAppointments = computed(() => {
    const now = new Date();
    
    if (activeTab.value === 'upcoming') {
        return appointments.value.filter(a => {
            const appointmentDate = new Date(a.start_time);
            const isFuture = isAfter(appointmentDate, now) || isToday(appointmentDate);
            return (a.status === 'pending' || a.status === 'confirmed') && isFuture;
        });
    } else if (activeTab.value === 'past') {
        return appointments.value.filter(a => {
            const appointmentDate = new Date(a.start_time);
            const isPast = isBefore(appointmentDate, now) && !isToday(appointmentDate);
            return a.status === 'completed' && isPast;
        });
    } else {
        return appointments.value.filter(a => a.status === 'cancelled');
    }
});

const formatDateTime = (datetime) => {
    if (!datetime) return 'N/A';
    return format(new Date(datetime), 'MMM dd, yyyy h:mm a');
};

const formatDate = (datetime) => {
    if (!datetime) return 'N/A';
    return format(new Date(datetime), 'MMM dd, yyyy');
};

const getDaysUntil = (datetime) => {
    return differenceInDays(new Date(datetime), new Date());
};

const isUpcoming = (datetime) => {
    const days = getDaysUntil(datetime);
    return days <= 3 && days >= 0;
};


const getStatusClass = (status) => {
  const classes = {
    pending:
      "bg-yellow-50 text-yellow-700",

    confirmed:
      "bg-blue-50 text-blue-700",

    in_progress:
      "bg-indigo-50 text-indigo-700",

    completed:
      "bg-green-50 text-green-700",

    cancelled:
      "bg-red-50 text-red-700",

    no_show:
      "bg-gray-50 text-gray-700",
  };

  return (
    classes[status] ||
    "bg-gray-50 text-gray-700"
  );
};

const getStatusText = (status) => {
    const texts = {
        pending: 'Pending',
        confirmed: 'Confirmed',
        in_progress: 'In Progress',
        completed: 'Completed',
        cancelled: 'Cancelled',
        no_show: 'No Show'
    };
    return texts[status] || status;
};

const canCancel = (status) => {
    return status === 'pending' || status === 'confirmed';
};

const canReschedule = (status) => {
    return status === 'pending' || status === 'confirmed';
};

const canReview = (appointment) => {
    return appointment.status === 'completed' && !appointment.review;
};

const loadAppointments = async () => {
    isLoading.value = true;
    try {
        const response = await appointmentStore.fetchAppointments({ per_page: 100 });
        appointments.value = response.data || [];
    } catch (error) {
        toast.error('Failed to load appointments');
    } finally {
        isLoading.value = false;
    }
};

const cancelAppointment = async (id) => {
    if (confirm('Are you sure you want to cancel this appointment?')) {
        try {
            await appointmentStore.cancelAppointment(id);
            toast.success('Appointment cancelled successfully');
            await loadAppointments();
            showDetailsModal.value = false;
        } catch (error) {
            toast.error('Failed to cancel appointment');
        }
    }
};

const openRescheduleModal = (appointment) => {
    selectedAppointment.value = appointment;
    rescheduleData.value = {
        date: format(new Date(appointment.start_time), 'yyyy-MM-dd'),
        appointment: appointment
    };
    showRescheduleModal.value = true;
    setTimeout(() => {
        loadRescheduleSlots();
    }, 100);
};

const loadRescheduleSlots = async () => {
    if (!rescheduleData.value.date || !selectedAppointment.value) return;
    
    try {
        const slots = await appointmentStore.fetchAvailableSlots({
            vendor_id: selectedAppointment.value.vendor_id,
            service_id: selectedAppointment.value.service_id,
            date: rescheduleData.value.date,
            staff_id: selectedAppointment.value.staff_id
        });
        rescheduleSlots.value = slots;
        selectedRescheduleSlot.value = null;
    } catch (error) {
        toast.error('Failed to load available slots');
    }
};

const selectRescheduleSlot = (slot) => {
    selectedRescheduleSlot.value = slot;
};

const confirmReschedule = async () => {
    if (!selectedRescheduleSlot.value) return;
    
    try {
        await appointmentStore.rescheduleAppointment(
            selectedAppointment.value.id,
            selectedRescheduleSlot.value.start_time
        );
        toast.success('Appointment rescheduled successfully');
        showRescheduleModal.value = false;
        await loadAppointments();
    } catch (error) {
        toast.error('Failed to reschedule appointment');
    }
};

const openReviewModal = (appointment) => {
    selectedAppointment.value = appointment;
    reviewData.value = { rating: 5, comment: '' };
    showReviewModal.value = true;
};

const submitReview = async () => {
    try {
        await axios.post('/api/reviews', {
            appointment_id: selectedAppointment.value.id,
            rating: reviewData.value.rating,
            comment: reviewData.value.comment
        });
        toast.success('Review submitted successfully');
        showReviewModal.value = false;
        await loadAppointments();
    } catch (error) {
        toast.error('Failed to submit review');
    }
};

const viewDetails = async (appointment) => {
    try {
        const response = await axios.get(`/api/appointments/${appointment.id}`);
        selectedAppointment.value = response.data;
        showDetailsModal.value = true;
    } catch (error) {
        selectedAppointment.value = appointment;
        showDetailsModal.value = true;
        toast.info('Showing basic appointment information');
    }
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadAppointments();

    window.addEventListener('storage', (e) => {
        if (e.key === 'darkMode') {
            isDarkMode.value = e.newValue === 'true';
            applyTheme(isDarkMode.value);
        }
    });

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