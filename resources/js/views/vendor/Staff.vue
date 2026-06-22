<!-- resources/js/views/vendor/Staff.vue -->
<template>
  <div class="space-y-6 pb-8">
 
 <div v-if="staffLimitMessage" 
      class="rounded-2xl p-4 border border-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-600">
      <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
          <h4 class="font-semibold text-yellow-800 dark:text-yellow-300">Staff Limit Reached</h4>
          <p class="text-sm text-yellow-700 dark:text-yellow-400">{{ staffLimitMessage }}</p>
        </div>
      </div>
    </div>

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
          <!-- Users SVG Icon -->
          <svg class="inline-block w-7 h-7 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
          </svg>
          Staff Management
        </h1>
        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
          <!-- Briefcase SVG Icon -->
          <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          Manage your team members and their services
        </p>
      </div>
      <button v-if="!staffLimitMessage" @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm font-medium transform active:scale-95">
        <!-- Plus SVG Icon -->
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Add Staff</span>
      </button>
    </div>

    <!-- Search Filter Section -->
    <div :class="[
      'rounded-2xl shadow-lg p-4 md:p-6 border',
      isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
    ]">
      <div class="relative">
        <!-- Search SVG Icon -->
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" v-model="searchQuery" @input="filterStaff" 
          placeholder="Search by staff name, email, phone, or position..." 
          :class="[
            'w-full pl-10 pr-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
            isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
          ]">
      </div>
      
      <!-- Active Filters Display -->
      <div v-if="searchQuery" class="flex flex-wrap items-center gap-2 mt-4 pt-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
        <span class="text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
          <!-- Filter SVG Icon -->
          <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
          </svg>
          Active filters:
        </span>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200 transition dark:bg-primary-900/30 dark:text-primary-400">
          Search: "{{ searchQuery }}"
          <button @click="clearSearch" class="hover:text-primary-900">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </span>
      </div>
    </div>

    <!-- Results Count -->
    <div class="flex justify-between items-center">
      <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
        <!-- Users SVG Icon -->
        <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        Showing {{ filteredStaff.length }} of {{ staffList.length }} staff members
      </p>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center py-16">
      <div class="flex flex-col items-center gap-3">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent"></div>
        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Loading staff members...</p>
      </div>
    </div>

    <!-- Staff Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="staff in filteredStaff" :key="staff.id" 
        :class="[
          'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group border',
          isDarkMode ? 'bg-gray-800 border-gray-700 hover:shadow-gray-700' : 'bg-white border-gray-100 hover:shadow-gray-200'
        ]">
        <div class="relative">
          <!-- Status Badge -->
          <span :class="staff.is_active ? 'bg-green-500' : 'bg-gray-500'" 
            class="absolute top-3 right-3 px-2.5 py-1 text-xs font-semibold text-white rounded-lg shadow-md">
            {{ staff.is_active ? 'Active' : 'Inactive' }}
          </span>
        </div>
        
        <div class="p-5">
          <!-- Position Tag -->
          <div class="mb-2">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-full" :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'">
              <span class="w-1.5 h-1.5 bg-primary-500 rounded-full"></span>
              {{ staff.position || 'No Position' }}
            </span>
          </div>
          
          <!-- Staff Name -->
          <h3 class="font-bold text-lg mb-1 line-clamp-1" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ staff.name }}</h3>
          
          <div class="space-y-2 mb-4 mt-3">
            <div class="flex items-center gap-2 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
              <!-- Mail SVG Icon -->
              <svg class="w-4 h-4 flex-shrink-0" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              <span class="truncate">{{ staff.email || "Not provided" }}</span>
            </div>
            <div class="flex items-center gap-2 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
              <!-- Phone SVG Icon -->
              <svg class="w-4 h-4 flex-shrink-0" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
              <span class="truncate">{{ staff.phone || "Not provided" }}</span>
            </div>
          </div>
          
          <div v-if="staff.services && staff.services.length > 0" class="mb-4">
            <p class="text-xs font-medium mb-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
              <!-- Concierge Bell SVG Icon -->
              <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
              Services
            </p>
            <div class="flex flex-wrap gap-1.5">
              <span
                v-for="service in staff.services"
                :key="service.id"
                :class="[
                  'text-xs px-2.5 py-1 rounded-lg font-medium',
                  isDarkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-100 text-gray-600'
                ]"
              >
                {{ service.name }}
              </span>
            </div>
          </div>
          
          <!-- Actions -->
          <div class="flex gap-2 pt-3 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
            <button @click="editStaff(staff)"
              class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl transition font-medium text-sm transform active:scale-95"
              :class="isDarkMode ? 'text-primary-400 hover:bg-gray-700' : 'text-primary-600 hover:bg-primary-50'">
              <!-- Edit SVG Icon -->
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              Edit
            </button>
            <button @click="deleteStaff(staff.id)" 
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
    </div>

    <!-- Empty State -->
    <div v-if="filteredStaff.length === 0 && !isLoading" 
      :class="[
        'rounded-2xl shadow-lg p-12 text-center border',
        isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
      ]">
      <svg class="w-20 h-20 mx-auto mb-4 opacity-30" :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
      </svg>
      <h3 class="text-lg font-semibold mb-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
        {{ searchQuery ? 'No matching staff members' : 'No Staff Members' }}
      </h3>
      <p class="mb-4" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
        {{ searchQuery ? 'Try adjusting your search criteria' : 'Get started by adding your first staff member.' }}
      </p>
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
        <button v-if="searchQuery" @click="clearSearch" 
          class="inline-flex items-center gap-2 px-5 py-2.5 border rounded-xl transition text-sm font-medium transform active:scale-95"
          :class="isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50'">
          <!-- Clear SVG Icon -->
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
          Clear Search
        </button>
        <button @click="openModal()" 
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition shadow-md hover:shadow-lg text-sm font-medium transform active:scale-95">
          <!-- Plus SVG Icon -->
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Add Staff Member
        </button>
      </div>
    </div>

    <!-- Staff Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click.self="closeModal"
    >
      <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity"></div>
        <div :class="[
          'rounded-2xl shadow-2xl max-w-md w-full z-50 transform transition-all border',
          isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
        ]">
          <!-- Modal Header -->
          <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl" 
            :class="[
              isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
            ]">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                <!-- User Plus SVG Icon -->
                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
              </div>
              <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                {{ editing ? "Edit Staff" : "Add Staff" }}
              </h3>
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
          
          <form @submit.prevent="saveStaff" class="px-6 py-4">
            <div class="space-y-4">
              <!-- Name -->
              <div>
                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                  <!-- User SVG Icon -->
                  <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Name *
                </label>
                <input
                  type="text"
                  v-model="form.name"
                  required
                  :class="[
                    'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                  ]"
                  placeholder="Full name"
                />
              </div>
              
              <!-- Email -->
              <div>
                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                  <!-- Mail SVG Icon -->
                  <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  Email
                </label>
                <input
                  type="email"
                  v-model="form.email"
                  :class="[
                    'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                  ]"
                  placeholder="email@example.com"
                />
              </div>
              
              <!-- Phone -->
              <div>
                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                  <!-- Phone SVG Icon -->
                  <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                  </svg>
                  Phone
                </label>
                <input
                  type="text"
                  v-model="form.phone"
                  :class="[
                    'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                  ]"
                  placeholder="Phone number"
                />
              </div>
              
              <!-- Position -->
              <div>
                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                  <!-- Briefcase SVG Icon -->
                  <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  Position *
                </label>
                <input
                  type="text"
                  v-model="form.position"
                  required
                  :class="[
                    'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                  ]"
                  placeholder="e.g., Senior Stylist"
                />
              </div>
              
              <!-- Bio -->
              <div>
                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                  <!-- Document Text SVG Icon -->
                  <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  Bio
                </label>
                <textarea
                  v-model="form.bio"
                  rows="3"
                  :class="[
                    'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900'
                  ]"
                  placeholder="Brief description about the staff member..."
                ></textarea>
              </div>
              
              <!-- Services -->
              <div>
                <label class="block text-sm font-medium mb-1.5" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">
                  <!-- Concierge Bell SVG Icon -->
                  <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  Services
                </label>
                <div
                  :class="[
                    'space-y-2 max-h-40 overflow-y-auto border rounded-xl p-3',
                    isDarkMode ? 'border-gray-600 bg-gray-700' : 'border-gray-300 bg-white'
                  ]"
                >
                  <label
                    v-for="service in vendorServices"
                    :key="service.id"
                    class="flex items-center p-1.5 rounded-lg hover:bg-opacity-50 transition cursor-pointer"
                    :class="isDarkMode ? 'hover:bg-gray-600' : 'hover:bg-gray-50'"
                  >
                    <input
                      type="checkbox"
                      :value="service.id"
                      v-model="form.service_ids"
                      class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded cursor-pointer"
                    />
                    <span class="ml-2.5 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">{{ service.name }}</span>
                  </label>
                  <p v-if="vendorServices.length === 0" class="text-sm text-center py-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    No services available
                  </p>
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
                  <label class="text-sm font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Active Status</label>
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
              <button
                type="button"
                @click="closeModal"
                :class="[
                  'inline-flex items-center justify-center gap-2 px-4 py-2.5 border rounded-xl transition text-sm font-medium transform active:scale-95',
                  isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                ]"
              >
                <!-- Close SVG Icon -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancel
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition shadow-md hover:shadow-lg text-sm font-medium transform active:scale-95"
              >
                <!-- Save SVG Icon -->
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ editing ? 'Update Staff' : 'Create Staff' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useVendorStore } from "../../stores/vendor";
import { useToast } from "vue-toastification";

const vendorStore = useVendorStore();
const toast = useToast();

const staffList = ref([]);
const staffLimitMessage = ref('');
const staffLimitReached = ref(false);
const vendorServices = ref([]);
const showModal = ref(false);
const editing = ref(false);
const isLoading = ref(false);
const isDarkMode = ref(false);
const searchQuery = ref('');
let darkModeObserver = null;

// Computed property for filtered staff
const filteredStaff = computed(() => {
  if (!searchQuery.value.trim()) {
    return staffList.value;
  }
  
  const query = searchQuery.value.toLowerCase().trim();
  return staffList.value.filter(staff => {
    return (
      staff.name?.toLowerCase().includes(query) ||
      staff.email?.toLowerCase().includes(query) ||
      staff.phone?.toLowerCase().includes(query) ||
      staff.position?.toLowerCase().includes(query) ||
      staff.bio?.toLowerCase().includes(query)
    );
  });
});

const form = ref({
  name: "",
  email: "",
  phone: "",
  position: "",
  bio: "",
  service_ids: [],
  is_active: true,
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

const filterStaff = () => {
  // The computed property handles the filtering automatically
  // This method is kept for the @input event binding
};

const clearSearch = () => {
  searchQuery.value = '';
};

const loadStaff = async () => {
  isLoading.value = true;
  try {
    const response = await vendorStore.fetchStaff();
    staffList.value = response.data.data || response;
      if (response.limit) {
      staffLimitMessage.value = response.limit.message || '';
      staffLimitReached.value = response.limit.limitReached || false;
    } else {
      staffLimitMessage.value = '';
      staffLimitReached.value = false;
    }
    
  } catch (error) {
    toast.error("Failed to load staff");
  } finally {
    isLoading.value = false;
  }
};

const loadServices = async () => {
  try {
    const response = await vendorStore.fetchServices();
    vendorServices.value = response.data.data || response;
  } catch (error) {
    console.error("Failed to load services:", error);
  }
};

const openModal = () => {
  editing.value = false;
  form.value = {
    name: "",
    email: "",
    phone: "",
    position: "",
    bio: "",
    service_ids: [],
    is_active: true,
  };
  showModal.value = true;
};

const editStaff = (staff) => {
  editing.value = true;
  form.value = {
    ...staff,
    service_ids: staff.services?.map((s) => s.id) || [],
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const toggleStaffStatus = async (staff) => {
  try {
    const newStatus = !staff.is_active;
    await vendorStore.updateStaff(staff.id, { is_active: newStatus });
    toast.success(`Staff ${newStatus ? 'activated' : 'deactivated'} successfully`);
    await loadStaff();
  } catch (error) {
    toast.error("Failed to update staff status");
  }
};

const saveStaff = async () => {
  try {
    if (editing.value) {
      await vendorStore.updateStaff(form.value.id, form.value);
      toast.success("Staff updated successfully");
    } else {
      await vendorStore.createStaff(form.value);
      toast.success("Staff created successfully");
    }
    closeModal();
    await loadStaff();
  } catch (error) {
    toast.error(error.response?.data?.message || "Failed to save staff");
  }
};

const deleteStaff = async (id) => {
  if (confirm("Are you sure you want to delete this staff member? This action cannot be undone.")) {
    try {
      await vendorStore.deleteStaff(id);
      toast.success("Staff deleted successfully");
      await loadStaff();
    } catch (error) {
      toast.error("Failed to delete staff");
    }
  }
};

onMounted(() => {
  checkDarkMode();
  setupDarkModeWatcher();
  loadStaff();
  loadServices();

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
/* Smooth transitions */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>