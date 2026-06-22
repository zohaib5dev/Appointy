<!-- resources/js/views/vendor/Appointments.vue -->
<template>
    <div class="space-y-6 pb-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    <!-- Calendar Check SVG Icon -->
                    <svg class="inline-block w-7 h-7 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17l2 2 4-4"/>
                    </svg>
                    Appointments
                </h1>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <!-- Clock SVG Icon -->
                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Manage and track all your appointments
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button @click="exportAppointments" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
                    <!-- Export SVG Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">Export</span>
                </button>
                <button @click="showCalendarView = !showCalendarView" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium">
                    <!-- Calendar SVG Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="hidden sm:inline">{{ showCalendarView ? 'List View' : 'Calendar View' }}</span>
                    <span class="sm:hidden">{{ showCalendarView ? 'List' : 'Calendar' }}</span>
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div :class="[
            'rounded-2xl shadow-lg p-4 md:p-6 border',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- Circle SVG Icon -->
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        Status
                    </label>
                    <select v-model="filters.status" @change="loadAppointments" 
                        :class="[
                            'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                        <option value="">All Status</option>
                        <option value="pending">⏳ Pending</option>
                        <option value="confirmed">✅ Confirmed</option>
                        <option value="in_progress">🔄 In Progress</option>
                        <option value="completed">🎯 Completed</option>
                        <option value="cancelled">❌ Cancelled</option>
                        <option value="no_show">🚫 No Show</option>
                    </select>
                </div>
                
                <!-- Date From -->
                <div>
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- Calendar Plus SVG Icon -->
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m3-3H9"/>
                        </svg>
                        From Date
                    </label>
                    <input type="date" v-model="filters.date_from" @change="loadAppointments" 
                        :class="[
                            'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                
                <!-- Date To -->
                <div>
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- Calendar Minus SVG Icon -->
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9"/>
                        </svg>
                        To Date
                    </label>
                    <input type="date" v-model="filters.date_to" @change="loadAppointments" 
                        :class="[
                            'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                </div>
                
                <!-- Staff Filter -->
                <div>
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- User MD SVG Icon -->
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Staff Member
                    </label>
                    <select v-model="filters.staff_id" @change="loadAppointments" 
                        :class="[
                            'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                        ]">
                        <option value="">All Staff</option>
                        <option v-for="staff in staffList" :key="staff.id" :value="staff.id">{{ staff.name }}</option>
                    </select>
                </div>
                
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- Search SVG Icon -->
                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search
                    </label>
                    <div class="relative">
                        <!-- Search SVG Icon -->
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" v-model="filters.search" @input="searchAppointments" 
                            placeholder="Search by name, email, phone..." 
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
                    <!-- Filter SVG Icon -->
                    <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Active filters:
                </span>
                <button v-if="filters.status" @click="filters.status = ''; loadAppointments()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    Status: {{ filters.status }}
                    <!-- Close SVG Icon -->
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <button v-if="filters.date_from" @click="filters.date_from = ''; loadAppointments()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    From: {{ filters.date_from }}
                    <!-- Close SVG Icon -->
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <button v-if="filters.date_to" @click="filters.date_to = ''; loadAppointments()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    To: {{ filters.date_to }}
                    <!-- Close SVG Icon -->
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <button v-if="filters.staff_id" @click="filters.staff_id = ''; loadAppointments()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    Staff: {{ getStaffName(filters.staff_id) }}
                    <!-- Close SVG Icon -->
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <button v-if="filters.search" @click="filters.search = ''; loadAppointments()" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
                    Search: {{ filters.search }}
                    <!-- Close SVG Icon -->
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                
                <!-- Clear All Filters Button -->
                <button @click="clearAllFilters" 
                    class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition dark:bg-red-900/30 dark:text-red-400 ml-auto">
                    <!-- Clear Filters SVG Icon -->
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8"/>
                    </svg>
                    Clear All
                </button>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 px-1">
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <!-- List SVG Icon -->
                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of {{ pagination.total || 0 }} appointments
            </p>
            <div class="flex items-center gap-3 text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <span>
                    <!-- Check Circle SVG Icon -->
                    <svg class="inline-block w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ getStatusCount('completed') }}
                </span>
                <span>
                    <!-- Clock SVG Icon -->
                    <svg class="inline-block w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ getStatusCount('pending') }}
                </span>
                <span>
                    <!-- X Circle SVG Icon -->
                    <svg class="inline-block w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ getStatusCount('cancelled') }}
                </span>
            </div>
        </div>

        <!-- Calendar View -->
        <div v-if="showCalendarView" :class="[
            'rounded-2xl shadow-lg overflow-hidden border',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <div class="p-4 md:p-6">
                <!-- Calendar Header -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <button @click="previousMonth" :class="[
                            'inline-flex items-center justify-center w-10 h-10 border rounded-xl transition',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                        ]">
                            <!-- Chevron Left SVG Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <h3 class="text-xl font-bold min-w-[200px] text-center" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                            {{ currentMonthName }} {{ currentYear }}
                        </h3>
                        <button @click="nextMonth" :class="[
                            'inline-flex items-center justify-center w-10 h-10 border rounded-xl transition',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                        ]">
                            <!-- Chevron Right SVG Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                    <button @click="currentDate = new Date(); loadAppointments()" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl border transition" 
                        :class="isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'">
                        <!-- Calendar Day SVG Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Today
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-1 md:gap-2">
                    <div v-for="day in weekDays" :key="day" 
                        class="text-center font-semibold py-2 text-xs md:text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                        {{ day }}
                    </div>
                    <div v-for="day in calendarDays" :key="day.date" 
                        @click="selectDate(day)"
                        :class="[
                            'min-h-[80px] md:min-h-[120px] p-1 md:p-2 border-2 rounded-xl cursor-pointer transition-all',
                            day.isSelected ? 'ring-2 ring-primary-500 border-primary-500 shadow-lg' : '',
                            day.isToday && !day.isSelected ? (isDarkMode ? 'bg-gray-700 border-gray-500' : 'bg-gray-50 border-gray-300') : '',
                            !day.isSelected && !day.isToday ? (isDarkMode ? 'bg-gray-800/50 border-gray-700 hover:border-primary-500' : 'bg-white border-gray-200 hover:border-primary-300') : '',
                            day.appointments.length > 0 ? 'hover:shadow-md' : ''
                        ]">
                        <div class="flex justify-between items-start">
                            <span class="font-semibold text-sm" :class="{
                                'text-primary-600 dark:text-primary-400': day.isToday,
                                'text-gray-900 dark:text-white': !isDarkMode && !day.isToday,
                                'text-gray-400 dark:text-gray-500': isDarkMode && !day.isToday
                            }">
                                {{ day.day }}
                            </span>
                            <span v-if="day.appointments.length > 0" 
                                class="text-xs px-1.5 py-0.5 rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                {{ day.appointments.length }}
                            </span>
                        </div>
                        <div class="mt-1 space-y-1">
                            <div v-for="appt in day.appointments.slice(0, 2)" :key="appt.id" 
                                class="text-[10px] md:text-xs p-1 rounded-lg cursor-pointer transition-all hover:scale-105 truncate"
                                :class="getCalendarEventClass(appt.status)"
                                @click.stop="viewAppointment(appt)">
                                <span class="font-medium">{{ appt.time }}</span>
                                <span class="ml-1 truncate">{{ appt.customer?.name?.split(' ')[0] }}</span>
                            </div>
                            <div v-if="day.appointments.length > 2" class="text-[10px] text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                +{{ day.appointments.length - 2 }} more
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div v-else :class="[
            'rounded-2xl shadow-lg overflow-hidden border',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <!-- Mobile Card View -->
            <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                <div v-for="appointment in appointments" :key="appointment.id" 
                    class="p-4 transition-all hover:bg-opacity-50" :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                <!-- Hashtag SVG Icon -->
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                {{ appointment.booking_number }}
                            </p>
                            <p class="text-xs flex items-center gap-1 mt-0.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Clock SVG Icon -->
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ formatDateTime(appointment.start_time) }}
                            </p>
                        </div>
                        <select :value="appointment.status" @change="updateStatus(appointment.id, $event.target.value)" 
                            :class="[
                                'px-2 py-1 text-xs border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                            ]">
                            <option value="pending">⏳ Pending</option>
                            <option value="confirmed">✅ Confirmed</option>
                            <option value="in_progress">🔄 In Progress</option>
                            <option value="completed">🎯 Completed</option>
                            <option value="cancelled">❌ Cancelled</option>
                            <option value="no_show">🚫 No Show</option>
                        </select>
                    </div>
                    
                    <div class="space-y-3 mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-semibold text-primary-700 dark:text-primary-300">{{ appointment.customer?.name?.charAt(0) }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium truncate" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ appointment.customer?.name }}</p>
                                <p class="text-xs truncate flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <!-- Phone SVG Icon -->
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ appointment.customer?.phone }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2 text-sm bg-opacity-50 rounded-xl p-3" :class="isDarkMode ? 'bg-gray-700/30' : 'bg-gray-50'">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <!-- Concierge Bell SVG Icon -->
                                    <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Service
                                </p>
                                <p class="font-medium text-sm truncate" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ appointment.service?.name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <!-- User MD SVG Icon -->
                                    <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Staff
                                </p>
                                <p class="font-medium text-sm truncate" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ appointment.staff?.name || 'Any' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center pt-2">
                            <div>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Amount</p>
                                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">${{ appointment.total_amount }}</p>
                            </div>
                            <button @click="viewAppointment(appointment)" 
                                class="inline-flex items-center gap-1 px-4 py-2 bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 rounded-xl hover:bg-primary-100 dark:hover:bg-primary-900/50 transition text-sm font-medium">
                                View
                                <!-- Arrow Right SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State Mobile -->
                <div v-if="appointments.length === 0 && !isLoading" class="p-8 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <!-- Calendar Times SVG Icon -->
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15l6-6m0 0l-6 6"/>
                    </svg>
                    <p class="font-medium">No appointments found</p>
                    <p class="text-sm">Try adjusting your filters</p>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                    <thead :class="isDarkMode ? 'bg-gray-700/50' : 'bg-gray-50'">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Hashtag SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                Booking #
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- User SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Customer
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Concierge Bell SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Service
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- User MD SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Staff
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Clock SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Date & Time
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Circle SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                </svg>
                                Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Dollar Sign SVG Icon -->
                                <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"/>
                                </svg>
                                Amount
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
                        <tr v-for="appointment in appointments" :key="appointment.id" 
                            class="transition-colors hover:bg-opacity-50" :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                {{ appointment.booking_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                        <span class="text-xs font-semibold text-primary-700 dark:text-primary-300">{{ appointment.customer?.name?.charAt(0) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium truncate" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ appointment.customer?.name }}</p>
                                        <p class="text-xs truncate flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Phone SVG Icon -->
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ appointment.customer?.phone }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                {{ appointment.service?.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                {{ appointment.staff?.name || 'Not Assigned' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                                {{ formatDateTime(appointment.start_time) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select :value="appointment.status" @change="updateStatus(appointment.id, $event.target.value)" 
                                    :class="[
                                        'px-3 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                    ]">
                                    <option value="pending">⏳ Pending</option>
                                    <option value="confirmed">✅ Confirmed</option>
                                    <option value="in_progress">🔄 In Progress</option>
                                    <option value="completed">🎯 Completed</option>
                                    <option value="cancelled">❌ Cancelled</option>
                                    <option value="no_show">🚫 No Show</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary-600 dark:text-primary-400">
                                ${{ appointment.total_amount }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button @click="viewAppointment(appointment)" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition text-sm font-medium">
                                    <!-- Eye SVG Icon -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="hidden lg:inline">Details</span>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Empty State Desktop -->
                        <tr v-if="appointments.length === 0 && !isLoading">
                            <td colspan="8" class="px-6 py-16 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                <!-- Calendar Times SVG Icon -->
                                <svg class="w-20 h-20 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 15l6-6m0 0l-6 6"/>
                                </svg>
                                <p class="text-lg font-medium">No appointments found</p>
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
                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Loading appointments...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="pagination.last_page > 1 && !showCalendarView" :class="[
                'px-4 md:px-6 py-4 border-t',
                isDarkMode ? 'border-gray-700 bg-gray-800/50' : 'border-gray-200 bg-gray-50'
            ]">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm flex items-center gap-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        <!-- Info Circle SVG Icon -->
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
                            <!-- Chevron Left SVG Icon -->
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
                            <!-- Chevron Right SVG Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Details Modal -->
        <div v-if="showDetailsModal" class="fixed inset-0 z-50 overflow-y-auto" @click.self="showDetailsModal = false">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity"></div>
                <div :class="[
                    'rounded-2xl shadow-2xl max-w-2xl w-full z-50 transform transition-all max-h-[90vh] overflow-y-auto border',
                    isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
                ]">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl z-10" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
                        ]">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                                <!-- Calendar Check SVG Icon -->
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17l2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                    Appointment Details
                                </h3>
                                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    {{ selectedAppointment?.booking_number }}
                                </p>
                            </div>
                        </div>
                        <button @click="showDetailsModal = false" 
                            class="w-8 h-8 rounded-lg flex items-center justify-center transition" 
                            :class="isDarkMode ? 'hover:bg-gray-700 text-gray-400' : 'hover:bg-gray-100 text-gray-400'">
                            <!-- Close SVG Icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="px-6 py-4" v-if="selectedAppointment">
                        <div class="space-y-6">
                            <!-- Header Info -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p class="text-xs flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <!-- Hashtag SVG Icon -->
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                        Booking Number
                                    </p>
                                    <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedAppointment.booking_number }}</p>
                                </div>
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p class="text-xs flex items-center gap-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <!-- Circle SVG Icon -->
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        Status
                                    </p>
                                    <select v-model="selectedAppointment.status" @change="updateStatus(selectedAppointment.id, selectedAppointment.status)" 
                                        :class="[
                                            'w-full mt-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                                            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                        ]">
                                        <option value="pending">⏳ Pending</option>
                                        <option value="confirmed">✅ Confirmed</option>
                                        <option value="in_progress">🔄 In Progress</option>
                                        <option value="completed">🎯 Completed</option>
                                        <option value="cancelled">❌ Cancelled</option>
                                        <option value="no_show">🚫 No Show</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h4 class="font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                    <!-- User Circle SVG Icon -->
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Customer Information
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- User SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Name
                                        </p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedAppointment.customer?.name }}</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Mail SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Email
                                        </p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedAppointment.customer?.email }}</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border sm:col-span-2',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Phone SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            Phone
                                        </p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedAppointment.customer?.phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Service & Staff Info -->
                            <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h4 class="font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                    <!-- Concierge Bell SVG Icon -->
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Service Details
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Service</p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedAppointment.service?.name }}</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Clock SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Duration
                                        </p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedAppointment.service?.duration }} min</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Dollar Sign SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"/>
                                            </svg>
                                            Price
                                        </p>
                                        <p class="font-medium text-primary-600 dark:text-primary-400">${{ selectedAppointment.service?.price }}</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- User MD SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Staff Member
                                        </p>
                                        <select v-model="selectedAppointment.staff_id" @change="updateStaff" 
                                            :class="[
                                                'w-full mt-1 px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                                                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                            ]">
                                            <option :value="null">Assign staff</option>
                                            <option v-for="staff in staffList" :key="staff.id" :value="staff.id">{{ staff.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Time -->
                            <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h4 class="font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                    <!-- Calendar SVG Icon -->
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Schedule
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div :class="[
                                        'rounded-xl p-3 border sm:col-span-2',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Clock SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Date & Time
                                        </p>
                                        <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDateTime(selectedAppointment.start_time) }}</p>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Credit Card SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Payment Status
                                        </p>
                                        <span :class="getPaymentStatusColor(selectedAppointment.payment_status)" class="px-3 py-1 text-xs rounded-full inline-block mt-1">
                                            {{ selectedAppointment.payment_status }}
                                        </span>
                                    </div>
                                    <div :class="[
                                        'rounded-xl p-3 border',
                                        isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                    ]">
                                        <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Dollar Sign SVG Icon -->
                                            <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"/>
                                            </svg>
                                            Total Amount
                                        </p>
                                        <p class="text-xl font-bold text-primary-600 dark:text-primary-400">${{ selectedAppointment.total_amount }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Customer Notes -->
                            <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <h4 class="font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                    <!-- Comment Dots SVG Icon -->
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Customer Notes
                                </h4>
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">{{ selectedAppointment.customer_notes || 'No notes from customer' }}</p>
                                </div>
                            </div>
                            
                            <!-- Vendor Notes -->
                            <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                                <label class="block font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                                    <!-- Sticky Note SVG Icon -->
                                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Vendor Notes
                                </label>
                                <textarea v-model="vendorNotes" rows="3" 
                                    :class="[
                                        'w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                                    ]" 
                                    placeholder="Add internal notes about this appointment..."></textarea>
                                <button @click="saveNotes" class="mt-3 inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-xl hover:bg-primary-700 transition text-sm font-medium shadow-md hover:shadow-lg">
                                    <!-- Save SVG Icon -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Notes
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t flex flex-col sm:flex-row justify-end gap-3 rounded-b-2xl" 
                        :class="[
                            isDarkMode ? 'border-gray-700 bg-gray-800/50' : 'border-gray-200 bg-gray-50'
                        ]">
                        <button @click="showDetailsModal = false" :class="[
                            'inline-flex items-center gap-2 px-4 py-2.5 border rounded-xl transition text-sm font-medium',
                            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
                        ]">
                            <!-- Close SVG Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Close
                        </button>
                        <button @click="sendReminder" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition shadow-md hover:shadow-lg text-sm font-medium">
                            <!-- Bell SVG Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            Send Reminder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useVendorStore } from '../../stores/vendor';
import { useToast } from 'vue-toastification';
import { format, startOfMonth, endOfMonth, eachDayOfInterval, isSameMonth, isToday, addMonths, subMonths } from 'date-fns';
import axios from 'axios';

const vendorStore = useVendorStore();
const toast = useToast();

const appointments = ref([]);
const staffList = ref([]);
const isLoading = ref(false);
const showCalendarView = ref(false);
const showDetailsModal = ref(false);
const selectedAppointment = ref(null);
const vendorNotes = ref('');
const isDarkMode = ref(false);
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

const filters = ref({
    status: '',
    date_from: '',
    date_to: '',
    staff_id: '',
    search: ''
});

const pagination = ref({
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0
});

// Calendar state
const currentDate = ref(new Date());
const selectedDate = ref(null);
const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
let searchTimeout = null;

// Computed properties
const hasActiveFilters = computed(() => {
    return filters.value.status || filters.value.date_from || 
           filters.value.date_to || filters.value.staff_id || filters.value.search;
});

const currentMonthName = computed(() => format(currentDate.value, 'MMMM'));
const currentYear = computed(() => format(currentDate.value, 'yyyy'));

const calendarDays = computed(() => {
    const start = startOfMonth(currentDate.value);
    const end = endOfMonth(currentDate.value);
    const days = eachDayOfInterval({ start, end });
    
    const appointmentsByDate = {};
    appointments.value.forEach(app => {
        const dateKey = format(new Date(app.start_time), 'yyyy-MM-dd');
        if (!appointmentsByDate[dateKey]) {
            appointmentsByDate[dateKey] = [];
        }
        appointmentsByDate[dateKey].push({
            ...app,
            time: format(new Date(app.start_time), 'h:mm a')
        });
    });
    
    return days.map(day => ({
        date: day,
        day: format(day, 'd'),
        isToday: isToday(day),
        isSelected: selectedDate.value && format(selectedDate.value, 'yyyy-MM-dd') === format(day, 'yyyy-MM-dd'),
        appointments: appointmentsByDate[format(day, 'yyyy-MM-dd')] || []
    }));
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

// Methods
const clearAllFilters = () => {
    filters.value.status = '';
    filters.value.date_from = '';
    filters.value.date_to = '';
    filters.value.staff_id = '';
    filters.value.search = '';
    selectedDate.value = null;
    loadAppointments();
};

const formatDateTime = (datetime) => {
    if (!datetime) return 'N/A';
    return format(new Date(datetime), 'MMM dd, yyyy h:mm a');
};

const getStaffName = (id) => {
    const staff = staffList.value.find(s => s.id === id);
    return staff ? staff.name : 'Unknown';
};

const getStatusCount = (status) => {
    return appointments.value.filter(a => a.status === status).length;
};

const getCalendarEventClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900/50',
        confirmed: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900/50',
        in_progress: 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 hover:bg-purple-200 dark:hover:bg-purple-900/50',
        completed: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50',
        no_show: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
    };
    return classes[status] || 'bg-gray-100 dark:bg-gray-700';
};

const getPaymentStatusColor = (status) => {
    const classes = {
        paid: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
        refunded: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
    };
    return classes[status] || 'bg-gray-100 dark:bg-gray-700';
};

const loadStaff = async () => {
    try {
        const response = await vendorStore.fetchStaff();
        staffList.value = response.data.data || response;
    } catch (error) {
        console.error('Failed to load staff:', error);
    }
};

const loadAppointments = async (page = 1) => {
    isLoading.value = true;
    try {
        const params = { ...filters.value, page };
        const response = await axios.get('/api/vendor/appointments', { params });
        appointments.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            total: response.data.total,
            from: response.data.from,
            to: response.data.to
        };
    } catch (error) {
        toast.error('Failed to load appointments');
    } finally {
        isLoading.value = false;
    }
};

const searchAppointments = () => {
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadAppointments();
    }, 500);
};

const updateStatus = async (id, status) => {
    try {
        await axios.put(`/api/vendor/appointments/${id}/status`, { status });
        toast.success('Status updated successfully');
        await loadAppointments(pagination.value.current_page);
        if (showDetailsModal.value && selectedAppointment.value) {
            selectedAppointment.value.status = status;
        }
    } catch (error) {
        toast.error('Failed to update status');
    }
};

const updateStaff = async () => {
    try {
        await axios.put(`/api/vendor/appointments/${selectedAppointment.value.id}/staff`, { staff_id: selectedAppointment.value.staff_id });
        toast.success('Staff assigned successfully');
        await loadAppointments(pagination.value.current_page);
    } catch (error) {
        toast.error('Failed to assign staff');
    }
};

const saveNotes = async () => {
    try {
        await axios.put(`/api/vendor/appointments/${selectedAppointment.value.id}/notes`, {
            vendor_notes: vendorNotes.value
        });
        toast.success('Notes saved successfully');
        selectedAppointment.value.vendor_notes = vendorNotes.value;
    } catch (error) {
        toast.error('Failed to save notes');
    }
};

const viewAppointment = async (appointment) => {
    try {
        const response = await axios.get(`/api/vendor/appointments/${appointment.id}`);
        selectedAppointment.value = response.data;
        vendorNotes.value = selectedAppointment.value.vendor_notes || '';
        showDetailsModal.value = true;
    } catch (error) {
        toast.error('Failed to load appointment details');
    }
};

const sendReminder = async () => {
    try {
        await axios.post(`/api/vendor/appointments/${selectedAppointment.value.id}/remind`);
        toast.success('Reminder sent successfully');
    } catch (error) {
        toast.error('Failed to send reminder');
    }
};

const exportAppointments = async () => {
    try {
        const response = await axios.get('/api/vendor/appointments/export', {
            params: filters.value,
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `appointments_${Date.now()}.csv`);
        document.body.appendChild(link);
        link.click();
        link.remove();
        toast.success('Export started');
    } catch (error) {
        toast.error('Failed to export appointments');
    }
};

const goToPage = (page) => {
    if (page >= 1 && page <= pagination.value.last_page) {
        loadAppointments(page);
    }
};

// Calendar methods
const previousMonth = () => {
    currentDate.value = subMonths(currentDate.value, 1);
    loadAppointments();
};

const nextMonth = () => {
    currentDate.value = addMonths(currentDate.value, 1);
    loadAppointments();
};

const selectDate = (day) => {
    selectedDate.value = day.date;
    filters.value.date_from = format(day.date, 'yyyy-MM-dd');
    filters.value.date_to = format(day.date, 'yyyy-MM-dd');
    showCalendarView.value = false;
    loadAppointments();
};

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadStaff();
    loadAppointments();

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