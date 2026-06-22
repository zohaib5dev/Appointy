<!-- resources/js/views/admin/Appointments.vue -->
<template>
  <div class="space-y-6 pb-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">All Appointments</h1>
        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Manage and monitor all appointments across the platform</p>
      </div>
      <div class="flex items-center gap-2 text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
        <span class="inline-flex items-center gap-1">
          <span class="w-2 h-2 bg-green-500 rounded-full"></span>
          {{ activeAppointments }} Active
        </span>
        <span class="inline-flex items-center gap-1">
          <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
          {{ pendingAppointments }} Pending
        </span>
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
          <select
            v-model="filters.status"
            @change="loadAppointments"
            :class="[
              'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
              isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
            ]"
          >
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="no_show">No Show</option>
          </select>
        </div>

        <!-- Date From -->
        <div class="flex-1">
          <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">From Date</label>
          <input
            type="date"
            v-model="filters.date_from"
            @change="loadAppointments"
            :class="[
              'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
              isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
            ]"
          />
        </div>

        <!-- Date To -->
        <div class="flex-1">
          <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">To Date</label>
          <input
            type="date"
            v-model="filters.date_to"
            @change="loadAppointments"
            :class="[
              'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
              isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
            ]"
          />
        </div>

        <!-- Search -->
        <div class="flex-[2]">
          <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Search</label>
          <div class="relative">
            <svg
              class="absolute left-3 top-2.5 w-5 h-5"
              :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              ></path>
            </svg>
            <input
              type="text"
              v-model="filters.search"
              @input="searchAppointments"
              placeholder="Search by booking #, customer, vendor..."
              :class="[
                'w-full pl-10 pr-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
            />
          </div>
        </div>
      </div>

      <!-- Active Filters Display -->
      <div
        v-if="filters.status || filters.date_from || filters.date_to || filters.search"
        class="flex flex-wrap gap-2 mt-4 pt-3 border-t"
        :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'"
      >
        <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Active filters:</span>
        <span
          v-if="filters.status"
          class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg"
          :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'"
        >
          Status: {{ filters.status }}
          <button
            @click="
              filters.status = '';
              loadAppointments();
            "
            class="hover:text-primary-900"
          >
            ×
          </button>
        </span>
        <span
          v-if="filters.date_from"
          class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg"
          :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'"
        >
          From: {{ filters.date_from }}
          <button
            @click="
              filters.date_from = '';
              loadAppointments();
            "
            class="hover:text-primary-900"
          >
            ×
          </button>
        </span>
        <span
          v-if="filters.date_to"
          class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg"
          :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'"
        >
          To: {{ filters.date_to }}
          <button
            @click="
              filters.date_to = '';
              loadAppointments();
            "
            class="hover:text-primary-900"
          >
            ×
          </button>
        </span>
        <span
          v-if="filters.search"
          class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg"
          :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'"
        >
          Search: "{{ filters.search }}"
          <button
            @click="
              filters.search = '';
              loadAppointments();
            "
            class="hover:text-primary-900"
          >
            ×
          </button>
        </span>
        <button
          @click="clearFilters"
          class="text-xs font-medium"
          :class="isDarkMode ? 'text-primary-400 hover:text-primary-300' : 'text-primary-600 hover:text-primary-700'"
        >
          Clear all
        </button>
      </div>
    </div>

    <!-- Results Count -->
    <div class="flex justify-between items-center">
      <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
        Showing {{ pagination.from || 0 }} to {{ pagination.to || 0 }} of
        {{ pagination.total || 0 }} appointments
      </p>
    </div>

    <!-- Appointments List -->
    <div :class="[
      'rounded-2xl shadow-lg overflow-hidden',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <!-- Mobile Card View -->
      <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
        <div
          v-for="appointment in appointments"
          :key="appointment.id"
          class="p-4 transition"
          :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'"
        >
          <div class="flex justify-between items-start mb-3">
            <div>
              <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ appointment.booking_number }}</p>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                {{ formatDateTime(appointment.start_time) }}
              </p>
            </div>
            <span
              :class="getStatusClass(appointment.status)"
              class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
            >
              {{ appointment.status }}
            </span>
          </div>

          <div class="space-y-2 mb-3">
            <div class="flex items-center gap-2">
              <div
                class="w-8 h-8 bg-gradient-to-r from-primary-100 to-primary-200 rounded-full flex items-center justify-center"
              >
                <span class="text-xs font-semibold text-primary-700">{{
                  appointment.customer?.name?.charAt(0) || "C"
                }}</span>
              </div>
              <div class="flex-1">
                <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ appointment.customer?.name }}
                </p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  {{ appointment.vendor?.business_name }}
                </p>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
              <div>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Service</p>
                <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ appointment.service?.name }}</p>
              </div>
              <div>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Amount</p>
                <p class="font-bold text-primary-600">${{ appointment.total_amount }}</p>
              </div>
            </div>
          </div>

          <div class="flex gap-2 pt-2 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
            <button
              @click="viewDetails(appointment)"
              class="flex-1 text-primary-600 hover:text-primary-700 text-sm font-medium py-1"
            >
              View 
            </button>
          </div>
        </div>
      </div>

      <!-- Desktop Table View -->
      <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
          <thead :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Booking #
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Customer
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Vendor
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Service
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Date & Time
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Status
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Amount
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="divide-y" :class="[
            isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
          ]">
            <tr
              v-for="appointment in appointments"
              :key="appointment.id"
              class="transition-colors"
              :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                {{ appointment.booking_number }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div
                    class="w-8 h-8 bg-gradient-to-r from-primary-100 to-primary-200 rounded-full flex items-center justify-center mr-3"
                  >
                    <span class="text-xs font-semibold text-primary-700">{{
                      appointment.customer?.name?.charAt(0) || "C"
                    }}</span>
                  </div>
                  <div>
                    <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ appointment.customer?.name }}
                    </p>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ appointment.customer?.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                {{ appointment.vendor?.business_name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                {{ appointment.service?.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                {{ formatDateTime(appointment.start_time) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="getStatusClass(appointment.status)"
                  class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
                >
                  {{ appointment.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary-600">
                ${{ appointment.total_amount }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <button title="View"
                  @click="viewDetails(appointment)"
                  class="text-primary-600 hover:text-primary-800 font-medium transition"
                >
                  <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    ></path>
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    ></path>
                  </svg>
                </button>
              </td>
            </tr>
            <tr v-if="appointments.length === 0 && !isLoading">
              <td colspan="8" class="px-6 py-12 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <svg
                  class="w-12 h-12 mx-auto mb-3"
                  :class="isDarkMode ? 'text-gray-600' : 'text-gray-300'"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  ></path>
                </svg>
                No appointments found
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center items-center py-12">
        <div
          class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
        ></div>
      </div>
    </div>

    <!-- Pagination -->
    <div
      v-if="pagination.last_page > 1"
      class="flex flex-col sm:flex-row justify-between items-center gap-4"
    >
      <div class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
        Page {{ pagination.current_page }} of {{ pagination.last_page }}
      </div>
      <div class="flex flex-wrap justify-center gap-2">
        <button
          @click="goToPage(pagination.current_page - 1)"
          :disabled="pagination.current_page === 1"
          :class="[
            'px-3 py-1 border rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed',
            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
          ]"
        >
          Previous
        </button>
        <button
          v-for="page in displayedPages"
          :key="page"
          @click="goToPage(page)"
          :class="[
            page === pagination.current_page
              ? 'bg-primary-600 text-white'
              : isDarkMode 
                ? 'bg-gray-700 text-gray-300 border-gray-600 hover:bg-gray-600' 
                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100',
            'px-3 py-1 border rounded-lg transition'
          ]"
        >
          {{ page }}
        </button>
        <button
          @click="goToPage(pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          :class="[
            'px-3 py-1 border rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed',
            isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-100'
          ]"
        >
          Next
        </button>
      </div>
    </div>

    <!-- Details Modal -->
    <div
      v-if="showDetailsModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click.self="showDetailsModal = false"
    >
      <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <div
          :class="[
            'rounded-2xl shadow-2xl max-w-2xl w-full z-50 transform transition-all max-h-[90vh] overflow-y-auto',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
          ]"
        >
          <div
            class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl"
            :class="[
              isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
            ]"
          >
            <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Appointment Details</h3>
            <button
              @click="showDetailsModal = false"
              :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                ></path>
              </svg>
            </button>
          </div>
          <div class="px-6 py-4" v-if="selectedAppointment">
            <div class="space-y-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div :class="[
                  'rounded-xl p-4',
                  isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                ]">
                  <p class="text-xs mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Booking Number</p>
                  <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ selectedAppointment.booking_number }}
                  </p>
                </div>
                <div :class="[
                  'rounded-xl p-4',
                  isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                ]">
                  <p class="text-xs mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Status</p>
                  <span
                    :class="getStatusClass(selectedAppointment.status)"
                    class="px-2 py-1 text-sm font-semibold rounded-full capitalize"
                  >
                    {{ selectedAppointment.status }}
                  </span>
                </div>
              </div>

              <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : ''">
                <h4 class="font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  <svg
                    class="w-5 h-5 text-primary-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    ></path>
                  </svg>
                  Customer Information
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Name</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ selectedAppointment.customer?.name }}
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Email</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ selectedAppointment.customer?.email }}
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Phone</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ selectedAppointment.customer?.phone }}
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Vendor</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ selectedAppointment.vendor?.business_name }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="border-t pt-4" :class="isDarkMode ? 'border-gray-700' : ''">
                <h4 class="font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  <svg
                    class="w-5 h-5 text-primary-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    ></path>
                  </svg>
                  Service Details
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Service</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ selectedAppointment.service?.name }}
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Duration</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ selectedAppointment.service?.duration }} minutes
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Staff</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ selectedAppointment.staff?.name || "Not Assigned" }}
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Date & Time</p>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ formatDateTime(selectedAppointment.start_time) }}
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Amount</p>
                    <p class="text-2xl font-bold text-primary-600">
                      ${{ selectedAppointment.total_amount }}
                    </p>
                  </div>
                  <div>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Payment Status</p>
                    <span
                      :class="getPaymentStatusClass(selectedAppointment.payment_status)"
                      class="px-2 py-1 text-xs rounded-full"
                    >
                      {{ selectedAppointment.payment_status || "N/A" }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Notes -->
              <div
                v-if="
                  selectedAppointment.customer_notes || selectedAppointment.vendor_notes
                "
                class="border-t pt-4 space-y-3"
                :class="isDarkMode ? 'border-gray-700' : ''"
              >
                <div v-if="selectedAppointment.customer_notes">
                  <h4 class="font-semibold mb-2 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    <svg
                      class="w-5 h-5 text-primary-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                      ></path>
                    </svg>
                    Customer Notes
                  </h4>
                  <div :class="[
                    'rounded-xl p-4',
                    isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                  ]">
                    <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">{{ selectedAppointment.customer_notes }}</p>
                  </div>
                </div>

                <div v-if="selectedAppointment.vendor_notes">
                  <h4 class="font-semibold mb-2 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    <svg
                      class="w-5 h-5 text-primary-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                      ></path>
                    </svg>
                    Vendor Notes
                  </h4>
                  <div :class="[
                    'rounded-xl p-4',
                    isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                  ]">
                    <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">{{ selectedAppointment.vendor_notes }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div
            class="px-6 py-4 border-t flex justify-end rounded-b-2xl"
            :class="[
              isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-gray-50'
            ]"
          >
            <button
              @click="showDetailsModal = false"
              class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { format } from "date-fns";

const toast = useToast();
const appointments = ref([]);
const isLoading = ref(false);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
  from: 0,
  to: 0,
});
const filters = ref({
  status: "",
  date_from: "",
  date_to: "",
  search: "",
});
const showDetailsModal = ref(false);
const selectedAppointment = ref(null);
const isDarkMode = ref(false);
let searchTimeout = null;
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
  // Watch for changes to localStorage
  const originalSetItem = localStorage.setItem;
  localStorage.setItem = function(key, value) {
    originalSetItem.apply(this, arguments);
    if (key === 'darkMode') {
      isDarkMode.value = value === 'true';
    }
  };

  // Also watch for class changes on html element (in case theme is toggled via class)
  const htmlElement = document.documentElement;
  darkModeObserver = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.attributeName === 'class') {
        const hasDarkClass = htmlElement.classList.contains('dark');
        // Only update if it matches our current state
        if (hasDarkClass !== isDarkMode.value) {
          isDarkMode.value = hasDarkClass;
          // Update localStorage to keep it in sync
          localStorage.setItem('darkMode', String(hasDarkClass));
        }
      }
    });
  });
  darkModeObserver.observe(htmlElement, { attributes: true });
};

// Computed stats
const activeAppointments = computed(() => {
  return appointments.value.filter(
    (a) => a.status === "confirmed" || a.status === "in_progress"
  ).length;
});

const pendingAppointments = computed(() => {
  return appointments.value.filter((a) => a.status === "pending").length;
});

const completedCount = computed(() => {
  return appointments.value.filter((a) => a.status === "completed").length;
});

const pendingCount = computed(() => {
  return appointments.value.filter((a) => a.status === "pending").length;
});

const cancelledCount = computed(() => {
  return appointments.value.filter(
    (a) => a.status === "cancelled" || a.status === "no_show"
  ).length;
});

const displayedPages = computed(() => {
  const total = pagination.value.last_page;
  const current = pagination.value.current_page;
  const delta = 2;
  const range = [];
  for (
    let i = Math.max(2, current - delta);
    i <= Math.min(total - 1, current + delta);
    i++
  ) {
    range.push(i);
  }
  if (current - delta > 2) range.unshift("...");
  if (current + delta < total - 1) range.push("...");
  range.unshift(1);
  if (total !== 1) range.push(total);
  return range;
});

const formatDateTime = (datetime) => {
  if (!datetime) return "N/A";
  return format(new Date(datetime), "MMM dd, yyyy h:mm a");
};

const getStatusClass = (status) => {
  const classes = {
    pending: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400",
    confirmed: "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400",
    in_progress: "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400",
    completed: "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    cancelled: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400",
    no_show: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
  };
  return classes[status] || "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";
};

const getPaymentStatusClass = (status) => {
  const classes = {
    paid: "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    pending: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400",
    failed: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400",
    refunded: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
  };
  return classes[status] || "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";
};

const loadAppointments = async (page = 1) => {
  isLoading.value = true;
  try {
    const response = await axios.get("/api/admin/appointments", {
      params: { ...filters.value, page },
    });
    appointments.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total,
      from: response.data.from,
      to: response.data.to,
    };
  } catch (error) {
    toast.error("Failed to load appointments");
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

const clearFilters = () => {
  filters.value = { status: "", date_from: "", date_to: "", search: "" };
  loadAppointments();
};

const updateStatus = async (id, status) => {
  try {
    await axios.put(`/api/admin/appointments/${id}/status`, { status });
    toast.success("Appointment status updated");
    await loadAppointments(pagination.value.current_page);
  } catch (error) {
    toast.error("Failed to update status");
  }
};

const viewDetails = async (appointment) => {
  try {
    const response = await axios.get(`/api/admin/appointments/${appointment.id}`);
    selectedAppointment.value = response.data;
    showDetailsModal.value = true;
  } catch (error) {
    toast.error("Failed to load appointment details");
  }
};

const goToPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    loadAppointments(page);
  }
};

onMounted(() => {
  checkDarkMode();
  setupDarkModeWatcher();
  loadAppointments();

  // Also listen for storage changes from other tabs
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
  window.removeEventListener('storage', () => {});
});
</script>