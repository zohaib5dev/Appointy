<!-- resources/js/views/vendor/Holidays.vue -->
<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <h1 class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Manage Holidays</h1>
      <button
        @click="openCreateModal"
        class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 flex items-center space-x-2 transition-all duration-200 shadow-sm hover:shadow-md w-full sm:w-auto justify-center"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          ></path>
        </svg>
        <span>Add Holiday</span>
      </button>
    </div>

    <!-- Filter Tabs -->
    <div :class="[
      'rounded-lg shadow-sm p-4',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
        <div class="flex flex-wrap gap-2">
          <button
            @click="filterType = 'all'"
            :class="
              filterType === 'all'
                ? 'bg-primary-600 text-white'
                : isDarkMode
                  ? 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            "
            class="px-4 py-2 rounded-lg transition"
          >
            All Holidays
          </button>
          <button
            @click="filterType = 'company'"
            :class="
              filterType === 'company'
                ? 'bg-primary-600 text-white'
                : isDarkMode
                  ? 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            "
            class="px-4 py-2 rounded-lg transition"
          >
            Company Holidays
          </button>
          <button
            @click="filterType = 'staff'"
            :class="
              filterType === 'staff'
                ? 'bg-primary-600 text-white'
                : isDarkMode
                  ? 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            "
            class="px-4 py-2 rounded-lg transition"
          >
            Staff Holidays
          </button>
        </div>

        <div class="flex-1"></div>

        <div class="flex gap-2 w-full md:w-auto">
          <button
            @click="viewMode = 'calendar'"
            :class="
              viewMode === 'calendar'
                ? 'bg-primary-600 text-white'
                : isDarkMode
                  ? 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            "
            class="px-4 py-2 rounded-lg transition flex-1 md:flex-none"
          >
            Calendar View
          </button>
          <button
            @click="viewMode = 'list'"
            :class="
              viewMode === 'list'
                ? 'bg-primary-600 text-white'
                : isDarkMode
                  ? 'bg-gray-700 text-gray-300 hover:bg-gray-600'
                  : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
            "
            class="px-4 py-2 rounded-lg transition flex-1 md:flex-none"
          >
            List View
          </button>
        </div>
      </div>
    </div>

    <!-- Calendar View -->
    <div v-if="viewMode === 'calendar'" :class="[
      'rounded-lg shadow-sm p-4 md:p-6',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <button @click="prevMonth" :class="[
          'p-2 rounded-lg transition',
          isDarkMode ? 'hover:bg-gray-700 text-gray-300' : 'hover:bg-gray-100 text-gray-700'
        ]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"
            ></path>
          </svg>
        </button>
        <h2 class="text-xl font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
          {{ currentMonthName }} {{ currentYear }}
        </h2>
        <button @click="nextMonth" :class="[
          'p-2 rounded-lg transition',
          isDarkMode ? 'hover:bg-gray-700 text-gray-300' : 'hover:bg-gray-100 text-gray-700'
        ]">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            ></path>
          </svg>
        </button>
      </div>

      <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
        <div
          v-for="day in weekDays"
          :key="day"
          class="text-center text-xs sm:text-sm font-medium py-2"
          :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'"
        >
          {{ day }}
        </div>
      </div>

      <div class="grid grid-cols-7 gap-1 sm:gap-2">
        <div
          v-for="(date, index) in calendarDays"
          :key="index"
          :class="[
            'min-h-[80px] sm:min-h-[120px] p-1 sm:p-2 rounded-lg relative transition-all',
            date.isCurrentMonth 
              ? isDarkMode ? 'bg-gray-700' : 'bg-white'
              : isDarkMode ? 'bg-gray-800/50' : 'bg-gray-50',
            isHolidayDate(date.date)
              ? getHolidaysForDate(date.date).some(h => h.staff_id)
                ? 'staff-holiday-border staff-holiday-bg'
                : 'holiday-date-border holiday-date-bg'
              : isDarkMode 
                ? 'border border-gray-600'
                : 'border border-gray-200',
            isToday(date.date) ? 'ring-2 ring-primary-500 ring-offset-2 dark:ring-offset-gray-800' : '',
          ]"
        >
          <div class="text-right">
            <span
              :class="[
                'text-xs sm:text-sm font-medium inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 rounded-full',
                isHolidayDate(date.date)
                  ? getHolidaysForDate(date.date).some(h => h.staff_id)
                    ? 'staff-day-number'
                    : 'holiday-day-number'
                  : isDarkMode ? 'text-gray-300' : 'text-gray-700',
                isToday(date.date) && !isHolidayDate(date.date)
                  ? isDarkMode ? 'bg-primary-900 text-primary-300' : 'bg-primary-100 text-primary-700'
                  : '',
              ]"
            >
              {{ date.day }}
            </span>
          </div>

          <!-- Holiday indicators -->
          <div class="mt-1 sm:mt-2 space-y-1 max-h-[60px] sm:max-h-[80px] overflow-y-auto">
            <div
              v-for="holiday in getHolidaysForDate(date.date).slice(0, 3)"
              :key="holiday.holiday_id"
              class="holiday-badge"
            >
              <div
                class="text-[10px] sm:text-xs holiday-badge-content rounded px-1 sm:px-1.5 py-0.5 sm:py-1 mb-0.5 sm:mb-1 truncate cursor-pointer"
                :class="
                  holiday.staff_id ? 'staff-holiday-badge' : 'company-holiday-badge'
                "
                @click="editHolidayById(holiday.holiday_id)"
              >
                <div class="flex items-center gap-0.5 sm:gap-1">
                  <span class="text-[8px] sm:text-xs">{{ holiday.staff_id ? '👤' : '🏢' }}</span>
                  <span class="font-medium truncate">{{ holiday.title }}</span>
                </div>
              </div>
            </div>
            <div v-if="getHolidaysForDate(date.date).length > 3" 
              class="text-[8px] sm:text-xs text-center" 
              :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
              +{{ getHolidaysForDate(date.date).length - 3 }} more
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- List View -->
    <div v-if="viewMode === 'list'" :class="[
      'rounded-lg shadow-sm overflow-hidden',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <!-- Filters -->
      <div class="p-4 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
          <div>
            <input
              type="text"
              v-model="filters.search"
              placeholder="Search holidays..."
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
            />
          </div>
          <div>
            <select
              v-model="filters.staff_id"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            >
              <option value="">All Staff</option>
              <option v-for="staff in staffList" :key="staff.id" :value="staff.id">
                {{ staff.name }}
              </option>
            </select>
          </div>
          <div>
            <input
              type="date"
              v-model="filters.from_date"
              placeholder="From Date"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            />
          </div>
          <div>
            <input
              type="date"
              v-model="filters.to_date"
              placeholder="To Date"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            />
          </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-4">
          <div class="flex flex-wrap gap-2">
            <button
              @click="applyFilters"
              class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition"
            >
              Apply Filters
            </button>
            <button
              @click="resetFilters"
              :class="[
                'px-4 py-2 rounded-lg transition',
                isDarkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
              ]"
            >
              Reset
            </button>
          </div>
          <div>
            <select
              v-model="filters.status"
              :class="[
                'px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition w-full sm:w-auto',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            >
              <option value="all">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Holidays Table - Mobile Card View -->
      <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
        <div v-for="holiday in holidays" :key="holiday.id" class="p-4" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
          <div class="flex justify-between items-start mb-2">
            <div>
              <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ holiday.title }}</p>
              <span
                :class="
                  holiday.staff_id
                    ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400'
                    : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400'
                "
                class="px-2 py-1 text-xs rounded-full inline-block mt-1"
              >
                {{ holiday.staff_id ? "Staff Holiday" : "Company Holiday" }}
              </span>
            </div>
            <div class="flex space-x-2">
              <button @click="editHoliday(holiday)" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
              </button>
              <button @click="confirmDelete(holiday)" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
              </button>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-2 text-sm mb-2">
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Duration</p>
              <p :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ holiday.duration }} day(s)</p>
            </div>
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Dates</p>
              <p :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDate(holiday.start_date) }}</p>
            </div>
          </div>
          <div class="flex justify-between items-center pt-2 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
            <button
              @click="toggleStatus(holiday)"
              :class="
                holiday.is_active
                  ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50'
                  : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
              "
              class="px-2 py-1 text-xs rounded-full transition-colors"
            >
              {{ holiday.is_active ? "Active" : "Inactive" }}
            </button>
            <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
              {{ holiday.staff_id ? '👤 ' + getStaffName(holiday.staff_id) : '🏢 Company' }}
            </span>
          </div>
        </div>
      </div>

      <!-- Holidays Table - Desktop View -->
      <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
          <thead :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Title</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Duration</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Dates</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Status</th>
              <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y" :class="[
            isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
          ]">
            <tr v-for="holiday in holidays" :key="holiday.id" class="transition-colors" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
              <td class="px-6 py-4">
                <div class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ holiday.title }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="
                    holiday.staff_id
                      ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400'
                      : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400'
                  "
                  class="px-2 py-1 text-xs rounded-full"
                >
                  {{ holiday.staff_id ? "Staff Holiday" : "Company Holiday" }}
                </span>
                <div v-if="holiday.staff_id" class="text-xs mt-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  👤 {{ getStaffName(holiday.staff_id) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ holiday.duration }} day(s)</div>
                <div v-if="holiday.is_multi_day" class="text-xs text-orange-600 dark:text-orange-400 font-medium">Multi-day</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ formatDate(holiday.start_date) }}</div>
                <div v-if="holiday.start_date !== holiday.end_date" class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  to {{ formatDate(holiday.end_date) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button
                  @click="toggleStatus(holiday)"
                  :class="
                    holiday.is_active
                      ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900/50'
                      : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                  "
                  class="px-2 py-1 text-xs rounded-full transition-colors"
                >
                  {{ holiday.is_active ? "Active" : "Inactive" }}
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="editHoliday(holiday)" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button @click="confirmDelete(holiday)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </td>
            </tr>
            <tr v-if="holidays.length === 0">
              <td colspan="6" class="px-6 py-12 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                No holidays found. Click "Add Holiday" to create one.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-4 sm:px-6 py-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
          <div class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-700'">
            Showing {{ holidays.length }} of {{ totalHolidays }} holidays
          </div>
          <div class="flex gap-2">
            <button
              @click="prevPage"
              :disabled="currentPage === 1"
              :class="[
                currentPage === 1 ? 'opacity-50 cursor-not-allowed' : isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100',
                'px-3 py-1 border rounded-lg transition-colors',
                isDarkMode ? 'border-gray-600 text-gray-300' : 'border-gray-300 text-gray-700'
              ]"
            >Previous</button>
            <span class="px-3 py-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Page {{ currentPage }} of {{ lastPage }}</span>
            <button
              @click="nextPage"
              :disabled="currentPage === lastPage"
              :class="[
                currentPage === lastPage ? 'opacity-50 cursor-not-allowed' : isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100',
                'px-3 py-1 border rounded-lg transition-colors',
                isDarkMode ? 'border-gray-600 text-gray-300' : 'border-gray-300 text-gray-700'
              ]"
            >Next</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-80 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4"
      @click.self="closeModal"
    >
      <div class="relative w-full max-w-md shadow-lg rounded-lg" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
        <div class="flex justify-between items-center p-4 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ modalTitle }}</h3>
          <button @click="closeModal" class="transition" :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveHoliday" class="p-4 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Holiday Type *</label>
            <div class="flex gap-4">
              <label class="flex items-center">
                <input type="radio" v-model="holidayType" value="company" class="mr-2" />
                <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Company Holiday</span>
              </label>
              <label class="flex items-center">
                <input type="radio" v-model="holidayType" value="staff" class="mr-2" />
                <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Staff Holiday</span>
              </label>
            </div>
          </div>

          <div v-if="holidayType === 'staff'">
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Select Staff *</label>
            <select
              v-model="form.staff_id"
              required
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            >
              <option value="">Select staff member</option>
              <option v-for="staff in staffList" :key="staff.id" :value="staff.id">{{ staff.name }}</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Holiday Title *</label>
            <input
              type="text"
              v-model="form.title"
              required
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
              placeholder="e.g., Christmas Break, Summer Vacation"
            />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Start Date *</label>
              <input
                type="date"
                v-model="form.start_date"
                required
                :class="[
                  'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                  isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                ]"
              />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">End Date *</label>
              <input
                type="date"
                v-model="form.end_date"
                required
                :class="[
                  'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                  isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                ]"
              />
            </div>
          </div>

          <div v-if="form.start_date && form.end_date" :class="[
            'text-sm p-2 rounded',
            isDarkMode ? 'bg-gray-700 text-gray-300' : 'bg-gray-50 text-gray-600'
          ]">
            Duration: {{ calculateDuration }} day(s)
          </div>

          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
              placeholder="Optional description..."
            ></textarea>
          </div>

          <div>
            <label class="flex items-center">
              <input type="checkbox" v-model="form.is_active" class="mr-2" />
              <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Active</span>
            </label>
          </div>

          <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
            <button type="button" @click="closeModal" :class="[
              'px-4 py-2 border rounded-lg transition-colors',
              isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
            ]">
              Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
              {{ editingHoliday ? "Update" : "Create" }} Holiday
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-80 overflow-y-auto h-full w-full z-50 flex items-center justify-center px-4">
      <div class="relative w-full max-w-md shadow-lg rounded-lg" :class="isDarkMode ? 'bg-gray-800' : 'bg-white'">
        <div class="p-6 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 mb-4">
            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium mb-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Delete Holiday</h3>
          <p class="text-sm mb-4" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
            Are you sure you want to delete "{{ deleteHoliday?.title }}"? This action cannot be undone.
          </p>
          <div class="flex flex-col sm:flex-row justify-center gap-3">
            <button @click="showDeleteModal = false" :class="[
              'px-4 py-2 border rounded-lg transition-colors',
              isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
            ]">
              Cancel
            </button>
            <button @click="deleteHolidayConfirm" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";

const toast = useToast();

// State
const viewMode = ref("calendar");
const filterType = ref("all");
const holidays = ref([]);
const calendarHolidays = ref([]);
const staffList = ref([]);
const totalHolidays = ref(0);
const currentPage = ref(1);
const lastPage = ref(1);
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingHoliday = ref(null);
const deleteHoliday = ref(null);
const holidayType = ref("company");
const isDarkMode = ref(false);
let darkModeObserver = null;

// Calendar state
const currentYear = ref(new Date().getFullYear());
const currentMonth = ref(new Date().getMonth());
const weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

// Filters
const filters = ref({
  search: "",
  staff_id: "",
  from_date: "",
  to_date: "",
  status: "all",
});

// Form
const form = ref({
  staff_id: "",
  title: "",
  start_date: "",
  end_date: "",
  description: "",
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

// Computed
const currentMonthName = computed(() => {
  return new Date(currentYear.value, currentMonth.value).toLocaleString("default", {
    month: "long",
  });
});

const calendarDays = computed(() => {
  const firstDayOfMonth = new Date(currentYear.value, currentMonth.value, 1);
  const lastDayOfMonth = new Date(currentYear.value, currentMonth.value + 1, 0);
  const startDay = firstDayOfMonth.getDay();
  const daysInMonth = lastDayOfMonth.getDate();

  const days = [];

  // Previous month days
  const prevMonthLastDay = new Date(currentYear.value, currentMonth.value, 0).getDate();
  for (let i = startDay - 1; i >= 0; i--) {
    const date = new Date(currentYear.value, currentMonth.value - 1, prevMonthLastDay - i);
    days.push({
      day: prevMonthLastDay - i,
      date: date,
      isCurrentMonth: false,
    });
  }

  // Current month days
  for (let i = 1; i <= daysInMonth; i++) {
    const date = new Date(currentYear.value, currentMonth.value, i);
    days.push({
      day: i,
      date: date,
      isCurrentMonth: true,
    });
  }

  // Next month days
  const remainingDays = 42 - days.length;
  for (let i = 1; i <= remainingDays; i++) {
    const date = new Date(currentYear.value, currentMonth.value + 1, i);
    days.push({
      day: i,
      date: date,
      isCurrentMonth: false,
    });
  }

  return days;
});

const calculateDuration = computed(() => {
  if (!form.value.start_date || !form.value.end_date) return 0;
  const start = new Date(form.value.start_date);
  const end = new Date(form.value.end_date);
  const diffTime = Math.abs(end - start);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
  return diffDays;
});

const modalTitle = computed(() => {
  return editingHoliday.value ? "Edit Holiday" : "Add New Holiday";
});

// Helper function to format date as YYYY-MM-DD
const formatDateYMD = (date) => {
  if (!date) return "";
  const d = new Date(date);
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
};

// Helper function to extract date from API response
const extractDate = (dateInput) => {
  if (!dateInput) return "";
  if (dateInput instanceof Date) {
    return formatDateYMD(dateInput);
  }
  const dateStr = String(dateInput);
  if (dateStr.includes("T")) {
    return dateStr.split("T")[0];
  }
  return dateStr;
};

// Methods
const isHolidayDate = (date) => {
  const dateStr = formatDateYMD(date);
  return calendarHolidays.value.some((h) => h && h.date === dateStr);
};

const getHolidaysForDate = (date) => {
  const dateStr = formatDateYMD(date);
  let holidaysList = calendarHolidays.value.filter((h) => h && h.date === dateStr);

  if (filterType.value === "staff") {
    holidaysList = holidaysList.filter((h) => h.staff_id);
  } else if (filterType.value === "company") {
    holidaysList = holidaysList.filter((h) => !h.staff_id);
  }

  return holidaysList;
};

const getStaffName = (staffId) => {
  const staff = staffList.value.find((s) => s.id === staffId);
  return staff ? staff.name : "Unknown";
};

const loadStaff = async () => {
  try {
    const response = await axios.get("/api/vendor/staff");
    staffList.value = response.data.data.data || response.data.data || response.data || [];
  } catch (error) {
    console.error("Error loading staff:", error);
    staffList.value = [];
  }
};

const loadHolidays = async () => {
  try {
    const params = {
      per_page: 15,
      page: currentPage.value,
    };

    if (filters.value.search) params.search = filters.value.search;
    if (filters.value.staff_id) params.staff_id = filters.value.staff_id;
    if (filters.value.from_date) params.from_date = filters.value.from_date;
    if (filters.value.to_date) params.to_date = filters.value.to_date;

    const response = await axios.get("/api/vendor/holidays", { params });

    let holidaysData = [];
    if (response.data.data) {
      holidaysData = response.data.data.data || response.data.data;
    } else if (response.data.holidays) {
      holidaysData = response.data.holidays;
    } else {
      holidaysData = response.data;
    }

    holidaysData = Array.isArray(holidaysData) ? holidaysData : [];

    if (filters.value.status !== "all") {
      holidaysData = holidaysData.filter((h) =>
        filters.value.status === "active" ? h.is_active : !h.is_active
      );
    }

    holidays.value = holidaysData;
    totalHolidays.value = response.data.total || holidaysData.length;
    lastPage.value = response.data.last_page || Math.ceil(totalHolidays.value / 15);
  } catch (error) {
    console.error("Error loading holidays:", error);
    holidays.value = [];
    toast.error("Failed to load holidays");
  }
};

const loadCalendarData = async () => {
  try {
    const response = await axios.get("/api/vendor/holidays/calendar", {
      params: {
        year: currentYear.value,
        month: currentMonth.value + 1,
      },
    });

    let calendarData = [];
    if (response.data.data) {
      calendarData = response.data.data.calendar_dates || response.data.data || [];
    } else if (response.data.calendar_dates) {
      calendarData = response.data.calendar_dates;
    } else {
      calendarData = response.data || [];
    }

    calendarData = calendarData.map(holiday => {
      if (holiday.staff_id) {
        holiday.staff_name = getStaffName(holiday.staff_id);
      }
      return holiday;
    });

    calendarHolidays.value = Array.isArray(calendarData) ? calendarData : [];
  } catch (error) {
    console.error("Error loading calendar:", error);
    calendarHolidays.value = [];
  }
};

const saveHoliday = async () => {
  try {
    const data = {
      title: form.value.title,
      start_date: form.value.start_date,
      end_date: form.value.end_date,
      description: form.value.description,
      is_active: form.value.is_active,
    };

    if (holidayType.value === "staff") {
      data.staff_id = form.value.staff_id;
    } else {
      data.staff_id = null;
    }

    if (editingHoliday.value) {
      await axios.put(`/api/vendor/holidays/${editingHoliday.value.id}`, data);
      toast.success("Holiday updated successfully");
    } else {
      await axios.post("/api/vendor/holidays", data);
      toast.success("Holiday created successfully");
    }

    closeModal();
    await loadHolidays();
    await loadCalendarData();
  } catch (error) {
    console.error("Error saving holiday:", error);
    toast.error(error.response?.data?.message || "Failed to save holiday");
  }
};

const editHoliday = (holiday) => {
  if (!holiday) return;
  editingHoliday.value = holiday;
  holidayType.value = holiday.staff_id ? "staff" : "company";
  form.value = {
    staff_id: holiday.staff_id || "",
    title: holiday.title || "",
    start_date: extractDate(holiday.start_date),
    end_date: extractDate(holiday.end_date),
    description: holiday.description || "",
    is_active: holiday.is_active !== undefined ? holiday.is_active : true,
  };
  showModal.value = true;
};

const editHolidayById = async (holidayId) => {
  if (!holidayId) return;
  try {
    const response = await axios.get(`/api/vendor/holidays/${holidayId}`);
    const holiday = response.data.data || response.data;
    if (holiday) {
      editHoliday(holiday);
    }
  } catch (error) {
    console.error("Error loading holiday:", error);
    toast.error("Failed to load holiday details");
  }
};

const toggleStatus = async (holiday) => {
  if (!holiday || !holiday.id) return;
  try {
    await axios.put(`/api/vendor/holidays/${holiday.id}`, {
      is_active: !holiday.is_active,
    });
    toast.success(`Holiday ${!holiday.is_active ? "activated" : "deactivated"} successfully`);
    await loadHolidays();
    await loadCalendarData();
  } catch (error) {
    console.error("Error toggling status:", error);
    toast.error("Failed to update holiday status");
  }
};

const confirmDelete = (holiday) => {
  if (!holiday) return;
  deleteHoliday.value = holiday;
  showDeleteModal.value = true;
};

const deleteHolidayConfirm = async () => {
  if (!deleteHoliday.value || !deleteHoliday.value.id) return;
  try {
    await axios.delete(`/api/vendor/holidays/${deleteHoliday.value.id}`);
    toast.success("Holiday deleted successfully");
    showDeleteModal.value = false;
    deleteHoliday.value = null;
    await loadHolidays();
    await loadCalendarData();
  } catch (error) {
    console.error("Error deleting holiday:", error);
    toast.error("Failed to delete holiday");
  }
};

const openCreateModal = () => {
  editingHoliday.value = null;
  holidayType.value = "company";
  form.value = {
    staff_id: "",
    title: "",
    start_date: "",
    end_date: "",
    description: "",
    is_active: true,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingHoliday.value = null;
};

const isToday = (date) => {
  const today = new Date();
  return date.toDateString() === today.toDateString();
};

const formatDate = (date) => {
  if (!date) return "";
  const dateStr = extractDate(date);
  return new Date(dateStr).toLocaleDateString();
};

const prevMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value--;
  } else {
    currentMonth.value--;
  }
  loadCalendarData();
};

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value++;
  } else {
    currentMonth.value++;
  }
  loadCalendarData();
};

const applyFilters = () => {
  currentPage.value = 1;
  loadHolidays();
};

const resetFilters = () => {
  filters.value = {
    search: "",
    staff_id: "",
    from_date: "",
    to_date: "",
    status: "all",
  };
  applyFilters();
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    loadHolidays();
  }
};

const nextPage = () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++;
    loadHolidays();
  }
};

// Watch for month/year changes to reload calendar
watch([currentYear, currentMonth], () => {
  loadCalendarData();
});

onMounted(() => {
  checkDarkMode();
  setupDarkModeWatcher();
  loadStaff();
  loadHolidays();
  loadCalendarData();

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
/* Company Holiday date styling */
.holiday-date-border {
  border: 2px solid #ef4444 !important;
  box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2);
}

.holiday-date-bg {
  background: linear-gradient(135deg, #fff5f5 0%, #fee2e2 100%);
}

.holiday-day-number {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white !important;
  font-weight: bold;
  box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
}

/* Staff Holiday date styling */
.staff-holiday-border {
  border: 2px solid #f59e0b !important;
  box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
}

.staff-holiday-bg {
  background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}

.staff-day-number {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white !important;
  font-weight: bold;
  box-shadow: 0 2px 4px rgba(245, 158, 11, 0.3);
}

.company-holiday-badge {
  background: linear-gradient(135deg, #dc2626, #b91c1c);
  color: white;
  border-left: 3px solid #fbbf24;
}

.staff-holiday-badge {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
  border-left: 3px solid #ef4444;
}

.holiday-badge-content {
  transition: all 0.2s ease;
  font-weight: 500;
}

.holiday-badge-content:hover {
  transform: translateX(2px);
  filter: brightness(1.05);
}

.holiday-badge {
  animation: fadeInSlide 0.3s ease-out;
}

@keyframes fadeInSlide {
  from {
    opacity: 0;
    transform: translateY(-5px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Dark mode overrides for holiday styles */
.dark .holiday-date-bg {
  background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(220, 38, 38, 0.25) 100%) !important;
}

.dark .staff-holiday-bg {
  background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, rgba(217, 119, 6, 0.25) 100%) !important;
}

.dark .holiday-date-border {
  border-color: #ef4444 !important;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.15) !important;
}

.dark .staff-holiday-border {
  border-color: #f59e0b !important;
  box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15) !important;
}

/* Custom scrollbar for table and calendar */
.overflow-x-auto::-webkit-scrollbar,
.max-h-\[60px\]::-webkit-scrollbar,
.max-h-\[80px\]::-webkit-scrollbar {
  height: 6px;
  width: 4px;
}

.overflow-x-auto::-webkit-scrollbar-track,
.max-h-\[60px\]::-webkit-scrollbar-track,
.max-h-\[80px\]::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.dark .overflow-x-auto::-webkit-scrollbar-track,
.dark .max-h-\[60px\]::-webkit-scrollbar-track,
.dark .max-h-\[80px\]::-webkit-scrollbar-track {
  background: #374151;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb,
.max-h-\[60px\]::-webkit-scrollbar-thumb,
.max-h-\[80px\]::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover,
.max-h-\[60px\]::-webkit-scrollbar-thumb:hover,
.max-h-\[80px\]::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb,
.dark .max-h-\[60px\]::-webkit-scrollbar-thumb,
.dark .max-h-\[80px\]::-webkit-scrollbar-thumb {
  background: #6b7280;
}

.dark .overflow-x-auto::-webkit-scrollbar-thumb:hover,
.dark .max-h-\[60px\]::-webkit-scrollbar-thumb:hover,
.dark .max-h-\[80px\]::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>