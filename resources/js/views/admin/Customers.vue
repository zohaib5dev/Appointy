<!-- resources/js/views/admin/Customers.vue -->
<template>
  <div class="space-y-6 pb-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Manage Customers</h1>
        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Manage and monitor all customers on the platform</p>
      </div>
      <button
        @click="openCreateModal"
        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-md"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 4v16m8-8H4"
          ></path>
        </svg>
        <span>Add Customer</span>
      </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div :class="[
        'rounded-2xl shadow-lg p-5 border-l-4 border-primary-500',
        isDarkMode ? 'bg-gray-800' : 'bg-white'
      ]">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Customers</p>
            <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ stats.total || 0 }}</p>
          </div>
          <div
            class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center"
          >
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
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
              ></path>
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
            <p class="text-2xl font-bold text-green-600">{{ stats.active || 0 }}</p>
          </div>
          <div
            class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-2xl"
          >
            ✅
          </div>
        </div>
      </div>
      <div :class="[
        'rounded-2xl shadow-lg p-5 border-l-4 border-yellow-500',
        isDarkMode ? 'bg-gray-800' : 'bg-white'
      ]">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Inactive</p>
            <p class="text-2xl font-bold text-yellow-600">{{ stats.inactive || 0 }}</p>
          </div>
          <div
            class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-2xl"
          >
            ⏸️
          </div>
        </div>
      </div>
      <div :class="[
        'rounded-2xl shadow-lg p-5 border-l-4 border-red-500',
        isDarkMode ? 'bg-gray-800' : 'bg-white'
      ]">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Suspended</p>
            <p class="text-2xl font-bold text-red-600">{{ stats.suspended || 0 }}</p>
          </div>
          <div
            class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-2xl"
          >
            🚫
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
          <select
            v-model="filters.status"
            @change="loadCustomers"
            :class="[
              'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
              isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
            ]"
          >
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="suspended">Suspended</option>
          </select>
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
              @input="searchCustomers"
              placeholder="Search by name, email, phone..."
              :class="[
                'w-full pl-10 pr-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
            />
          </div>
        </div>

        <!-- Reset Filters -->
        <div class="flex items-end">
          <button
            @click="resetFilters"
            :class="[
              'px-4 py-2 border rounded-xl transition',
              isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-900'
            ]"
          >
            Reset Filters
          </button>
        </div>
      </div>

      <!-- Active Filters Display -->
      <div
        v-if="filters.status || filters.search"
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
              loadCustomers();
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
              loadCustomers();
            "
            class="hover:text-primary-900"
          >
            ×
          </button>
        </span>
        <button
          @click="resetFilters"
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
        {{ pagination.total || 0 }} customers
      </p>
    </div>

    <!-- Customers List -->
    <div :class="[
      'rounded-2xl shadow-lg overflow-hidden',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <!-- Mobile Card View -->
      <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
        <div
          v-for="customer in customers"
          :key="customer.id"
          class="p-4 transition"
          :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'"
        >
          <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
              <div
                class="w-12 h-12 bg-gradient-to-r from-primary-100 to-primary-200 rounded-full flex items-center justify-center"
              >
                <span class="text-lg font-semibold text-primary-700">{{
                  customer.name?.charAt(0) || "C"
                }}</span>
              </div>
              <div>
                <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ customer.name }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ customer.email }}</p>
              </div>
            </div>
            <span
              :class="getStatusClass(customer.status)"
              class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
            >
              {{ customer.status }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Phone</p>
              <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ customer.phone || "-" }}</p>
            </div>
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Appointments</p>
              <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                {{ customer.appointments_count || 0 }}
              </p>
            </div>
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Joined</p>
              <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                {{ formatDate(customer.created_at) }}
              </p>
            </div>
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Location</p>
              <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                {{ customer.city || "N/A" }}
              </p>
            </div>
          </div>

          <div class="flex gap-2 pt-2 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
            <button
              @click="viewCustomer(customer)"
              class="flex-1 text-primary-600 hover:text-primary-700 text-sm font-medium py-1"
            >
              View
            </button>
            <button
              @click="editCustomer(customer)"
              class="flex-1 text-primary-600 hover:text-primary-700 text-sm font-medium py-1"
            >
              Edit
            </button>
            <button
              @click="deleteCustomer(customer.id)"
              class="flex-1 text-red-600 hover:text-red-700 text-sm font-medium py-1"
            >
              Delete
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
                Customer
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Email
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Phone
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Location
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
                Joined
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
              v-for="customer in customers"
              :key="customer.id"
              class="transition-colors"
              :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div
                    class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 rounded-full flex items-center justify-center mr-3"
                  >
                    <span class="text-sm font-semibold text-primary-700">{{
                      customer.name?.charAt(0) || "C"
                    }}</span>
                  </div>
                  <div>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ customer.name }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                {{ customer.email }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                {{ customer.phone || "-" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">
                {{ customer.city || "N/A" }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="getStatusClass(customer.status)"
                  class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
                >
                  {{ customer.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                {{ formatDate(customer.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="flex items-center gap-2">
                  <button
                    title="View"
                    @click="viewCustomer(customer)"
                    class="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-800 hover:bg-primary-50 rounded-lg transition-all duration-200"
                    :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-primary-50'"
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
                  <button
                    title="Edit"
                    @click="editCustomer(customer)"
                    class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all duration-200"
                    :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-blue-50'"
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
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                      ></path>
                    </svg>
                  </button>
                  <button
                    title="Delete"
                    @click="deleteCustomer(customer.id)"
                    class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-all duration-200"
                    :class="isDarkMode ? 'hover:bg-red-900/20' : 'hover:bg-red-50'"
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
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      ></path>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="customers.length === 0 && !isLoading">
              <td colspan="7" class="px-6 py-12 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
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
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                  ></path>
                </svg>
                No customers found
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

    <!-- View Customer Modal -->
    <div
      v-if="showViewModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click.self="showViewModal = false"
    >
      <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <div
          :class="[
            'rounded-2xl shadow-2xl max-w-3xl w-full z-50 transform transition-all max-h-[90vh] overflow-y-auto',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
          ]"
        >
          <div
            class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl"
            :class="[
              isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
            ]"
          >
            <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Customer Details</h3>
            <button
              @click="showViewModal = false"
              class="transition"
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
          <div class="px-6 py-4" v-if="selectedCustomer">
            <!-- Profile Header -->
            <div
              class="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-4 bg-gradient-to-r from-primary-50 to-primary-100 rounded-2xl mb-6"
              :class="isDarkMode ? 'from-gray-700 to-gray-600' : 'from-primary-50 to-primary-100'"
            >
              <div
                class="w-20 h-20 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-3xl font-bold text-white shadow-md"
              >
                {{ selectedCustomer.name?.charAt(0) || "C" }}
              </div>
              <div class="flex-1 text-center sm:text-left">
                <h4 class="text-xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ selectedCustomer.name }}
                </h4>
                <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ selectedCustomer.email }}</p>
                <div class="flex flex-wrap gap-2 mt-2 justify-center sm:justify-start">
                  <span
                    :class="getStatusClass(selectedCustomer.status)"
                    class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
                  >
                    {{ selectedCustomer.status }}
                  </span>
                  <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                    >Joined {{ formatDate(selectedCustomer.created_at) }}</span
                  >
                </div>
              </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
              <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center"
                :class="isDarkMode ? 'from-blue-900/30 to-blue-800/30' : 'from-blue-50 to-blue-100'"
              >
                <p class="text-2xl font-bold text-blue-600">
                  {{ selectedCustomer.stats?.total_appointments || 0 }}
                </p>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Appointments</p>
              </div>
              <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center"
                :class="isDarkMode ? 'from-green-900/30 to-green-800/30' : 'from-green-50 to-green-100'"
              >
                <p class="text-2xl font-bold text-green-600">
                  ${{ formatPrice(selectedCustomer.stats?.total_spent || 0) }}
                </p>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Total Spent</p>
              </div>
              <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center"
                :class="isDarkMode ? 'from-purple-900/30 to-purple-800/30' : 'from-purple-50 to-purple-100'"
              >
                <p class="text-2xl font-bold text-purple-600">
                  {{ selectedCustomer.stats?.total_reviews || 0 }}
                </p>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Reviews</p>
              </div>
            </div>

            <!-- Contact Information -->
            <div>
              <h5 class="font-semibold mb-3 flex items-center gap-2" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
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
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  ></path>
                </svg>
                Contact Information
              </h5>
              <div :class="[
                'grid grid-cols-1 sm:grid-cols-2 gap-4 rounded-xl p-4',
                isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
              ]">
                <div>
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Phone</p>
                  <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ selectedCustomer.phone || "Not provided" }}
                  </p>
                </div>
                <div>
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Email</p>
                  <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedCustomer.email }}</p>
                </div>
                <div>
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Address</p>
                  <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ selectedCustomer.address || "Not provided" }}
                  </p>
                </div>
                <div>
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Location</p>
                  <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ selectedCustomer.city || "N/A"
                    }}{{ selectedCustomer.state ? ", " + selectedCustomer.state : "" }}
                  </p>
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
              @click="showViewModal = false"
              class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Customer Modal -->
    <div
      v-if="showFormModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click.self="closeFormModal"
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
            <h3 class="text-xl font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
              {{ editingCustomer ? "Edit Customer" : "Add New Customer" }}
            </h3>
            <button
              @click="closeFormModal"
              class="transition"
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
          <form @submit.prevent="saveCustomer" class="px-6 py-4">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Full Name *</label
                  >
                  <input
                    type="text"
                    v-model="form.name"
                    required
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Email *</label
                  >
                  <input
                    type="email"
                    v-model="form.email"
                    required
                    :disabled="editingCustomer"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition disabled:opacity-50',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 disabled:bg-gray-600' : 'bg-white border-gray-300 text-gray-900 disabled:bg-gray-100'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Phone</label
                  >
                  <input
                    type="text"
                    v-model="form.phone"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Status</label
                  >
                  <select
                    v-model="form.status"
                    required
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                  </select>
                </div>
                <div v-if="!editingCustomer">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Password *</label
                  >
                  <input
                    type="password"
                    v-model="form.password"
                    required
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div v-if="!editingCustomer">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Confirm Password *</label
                  >
                  <input
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div v-if="editingCustomer">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >New Password</label
                  >
                  <input
                    type="password"
                    v-model="form.password"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                    ]"
                    placeholder="Leave blank to keep current"
                  />
                </div>
                <div v-if="editingCustomer">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Confirm New Password</label
                  >
                  <input
                    type="password"
                    v-model="form.password_confirmation"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Address</label
                  >
                  <input
                    type="text"
                    v-model="form.address"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">City</label>
                  <input
                    type="text"
                    v-model="form.city"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >State</label
                  >
                  <input
                    type="text"
                    v-model="form.state"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >ZIP Code</label
                  >
                  <input
                    type="text"
                    v-model="form.zip"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                    >Country</label
                  >
                  <select
                    v-model="form.country"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  >
                    <option value="">Select Country</option>
                    <option value="US">United States</option>
                    <option value="CA">Canada</option>
                    <option value="GB">United Kingdom</option>
                    <option value="DE">Germany</option>
                    <option value="FR">France</option>
                    <option value="AU">Australia</option>
                    <option value="IN">India</option>
                    <option value="AE">UAE</option>
                    <option value="SG">Singapore</option>
                  </select>
                </div>
              </div>
            </div>

            <div
              class="mt-6 flex flex-col sm:flex-row justify-end gap-3 sticky bottom-0 py-4 border-t -mx-6 px-6 rounded-b-2xl"
              :class="[
                isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'
              ]"
            >
              <button
                type="button"
                @click="closeFormModal"
                :class="[
                  'px-4 py-2 border rounded-lg transition',
                  isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                ]"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="isSaving"
                class="px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md"
              >
                {{
                  isSaving
                    ? "Saving..."
                    : editingCustomer
                    ? "Update Customer"
                    : "Create Customer"
                }}
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
import axios from "axios";
import { useToast } from "vue-toastification";
import { format } from "date-fns";

const toast = useToast();

const customers = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const showViewModal = ref(false);
const showFormModal = ref(false);
const editingCustomer = ref(false);
const selectedCustomer = ref(null);
const sortField = ref("created_at");
const sortDirection = ref("desc");
const isDarkMode = ref(false);
let darkModeObserver = null;

const stats = ref({
  total: 0,
  active: 0,
  inactive: 0,
  suspended: 0,
});

const filters = ref({
  status: "",
  search: "",
});

const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
  from: 0,
  to: 0,
  per_page: 20,
});

const form = ref({
  id: null,
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  address: "",
  city: "",
  state: "",
  zip: "",
  country: "",
  status: "active",
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

const formatDate = (date) => {
  if (!date) return "N/A";
  return format(new Date(date), "MMM dd, yyyy");
};

const formatPrice = (price) => {
  if (!price && price !== 0) return "0.00";
  return Number(price).toFixed(2);
};

const getStatusClass = (status) => {
  const classes = {
    active: "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    inactive: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400",
    suspended: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400",
  };
  return classes[status] || "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";
};

// LOAD CUSTOMERS
const loadCustomers = async (page = 1) => {
  isLoading.value = true;
  try {
    const params = {
      ...filters.value,
      page,
      sort: sortField.value,
      direction: sortDirection.value,
    };
    Object.keys(params).forEach((key) => {
      if (params[key] === "" || params[key] === null || params[key] === undefined) {
        delete params[key];
      }
    });

    const response = await axios.get("/api/admin/customers", { params });
    customers.value = response.data.data || [];
    pagination.value = {
      current_page: response.data.current_page || 1,
      last_page: response.data.last_page || 1,
      total: response.data.total || 0,
      from: response.data.from || 0,
      to: response.data.to || 0,
      per_page: response.data.per_page || 20,
    };
    stats.value = response.data.stats || {
      total: 0,
      active: 0,
      inactive: 0,
      suspended: 0,
    };
  } catch (error) {
    toast.error("Failed to load customers");
    console.error(error);
  } finally {
    isLoading.value = false;
  }
};

const searchCustomers = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    loadCustomers();
  }, 500);
};

const resetFilters = () => {
  filters.value = { status: "", search: "" };
  loadCustomers();
};

const updateStatus = async (id, status) => {
  try {
    await axios.put(`/api/admin/customers/${id}/status`, { status });
    toast.success("Customer status updated");
    await loadCustomers(pagination.value.current_page);
  } catch (error) {
    toast.error("Failed to update status");
  }
};

// VIEW CUSTOMER
const viewCustomer = async (customer) => {
  try {
    const response = await axios.get(`/api/admin/customers/${customer.id}`);
    selectedCustomer.value = response.data.customer || response.data;
    showViewModal.value = true;
  } catch (error) {
    toast.error("Failed to load customer details");
    console.error(error);
  }
};

// OPEN CREATE MODAL
const openCreateModal = () => {
  editingCustomer.value = false;
  form.value = {
    id: null,
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
    address: "",
    city: "",
    state: "",
    zip: "",
    country: "",
    status: "active",
  };
  showFormModal.value = true;
};

// EDIT CUSTOMER
const editCustomer = (customer) => {
  editingCustomer.value = true;
  form.value = {
    id: customer.id,
    name: customer.name || "",
    email: customer.email || "",
    phone: customer.phone || "",
    password: "",
    password_confirmation: "",
    address: customer.address || "",
    city: customer.city || "",
    state: customer.state || "",
    zip: customer.zip || "",
    country: customer.country || "",
    status: customer.status || "active",
  };
  showFormModal.value = true;
};

// CLOSE FORM MODAL
const closeFormModal = () => {
  showFormModal.value = false;
  editingCustomer.value = false;
  form.value = {
    id: null,
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
    address: "",
    city: "",
    state: "",
    zip: "",
    country: "",
    status: "active",
  };
};

// SAVE CUSTOMER
const saveCustomer = async () => {
  // Validate
  if (!editingCustomer.value) {
    if (!form.value.password || form.value.password.length < 8) {
      toast.error("Password must be at least 8 characters");
      return;
    }
    if (form.value.password !== form.value.password_confirmation) {
      toast.error("Passwords do not match");
      return;
    }
  } else {
    if (form.value.password && form.value.password.length > 0) {
      if (form.value.password.length < 8) {
        toast.error("Password must be at least 8 characters");
        return;
      }
      if (form.value.password !== form.value.password_confirmation) {
        toast.error("Passwords do not match");
        return;
      }
    }
  }

  isSaving.value = true;
  try {
    const payload = { ...form.value };
    delete payload.id;

    if (editingCustomer.value && !payload.password) {
      delete payload.password;
      delete payload.password_confirmation;
    }

    let response;
    if (editingCustomer.value) {
      response = await axios.put(`/api/admin/customers/${form.value.id}`, payload);
    } else {
      response = await axios.post("/api/admin/customers", payload);
    }

    if (response.data.success) {
      toast.success(response.data.message);
      closeFormModal();
      await loadCustomers(pagination.value.current_page);
    } else {
      toast.error(response.data.message || "Failed to save customer");
    }
  } catch (error) {
    console.error("Save error:", error);
    const errors = error.response?.data?.errors;
    if (errors) {
      Object.values(errors).forEach((err) => {
        toast.error(err[0]);
      });
    } else {
      toast.error(error.response?.data?.message || "Failed to save customer");
    }
  } finally {
    isSaving.value = false;
  }
};

// DELETE CUSTOMER
const deleteCustomer = async (id) => {
  if (
    confirm(
      "Are you sure you want to delete this customer? This action cannot be undone."
    )
  ) {
    try {
      await axios.delete(`/api/admin/customers/${id}`);
      toast.success("Customer deleted successfully");
      await loadCustomers(pagination.value.current_page);
    } catch (error) {
      toast.error("Failed to delete customer");
    }
  }
};

const goToPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    loadCustomers(page);
  }
};

onMounted(() => {
  checkDarkMode();
  setupDarkModeWatcher();
  loadCustomers();

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