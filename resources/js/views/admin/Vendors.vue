<!-- resources/js/views/admin/Vendors.vue -->
<template>
  <div class="space-y-6 pb-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Manage Vendors</h1>
        <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Manage and monitor all vendors on the platform</p>
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
        <span>Add Vendor</span>
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
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Total Vendors</p>
            <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ stats.total }}</p>
          </div>
          <div
            class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center text-2xl"
          >
            🏪
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
            <p class="text-2xl font-bold text-green-600">{{ stats.active }}</p>
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
            <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ stats.pending }}</p>
          </div>
          <div
            class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center text-2xl"
          >
            ⏳
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
            <p class="text-2xl font-bold text-red-600">{{ stats.suspended }}</p>
          </div>
          <div
            class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-2xl"
          >
            ⚠️
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
            @change="loadVendors"
            :class="[
              'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
              isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
            ]"
          >
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <!-- Subscription Status Filter -->
        <div class="flex-1">
          <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Subscription</label>
          <select
            v-model="filters.subscription_status"
            @change="loadVendors"
            :class="[
              'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
              isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
            ]"
          >
            <option value="">All</option>
            <option value="trial">Trial</option>
            <option value="active">Active</option>
            <option value="cancelled">Cancelled</option>
            <option value="expired">Expired</option>
          </select>
        </div>

        <!-- Verification Filter -->
        <div class="flex-1">
          <label class="block text-xs font-medium mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Verification</label>
          <select
            v-model="filters.is_verified"
            @change="loadVendors"
            :class="[
              'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
              isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
            ]"
          >
            <option value="">All</option>
            <option value="1">Verified</option>
            <option value="0">Not Verified</option>
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
              @input="searchVendors"
              placeholder="Search by business name, email, owner..."
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
        v-if="
          filters.status ||
          filters.subscription_status ||
          filters.is_verified ||
          filters.search
        "
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
              loadVendors();
            "
            class="hover:text-primary-900"
          >
            ×
          </button>
        </span>
        <span
          v-if="filters.subscription_status"
          class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg"
          :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'"
        >
          Subscription: {{ filters.subscription_status }}
          <button
            @click="
              filters.subscription_status = '';
              loadVendors();
            "
            class="hover:text-primary-900"
          >
            ×
          </button>
        </span>
        <span
          v-if="filters.is_verified"
          class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-lg"
          :class="isDarkMode ? 'bg-gray-700 text-primary-400' : 'bg-primary-50 text-primary-700'"
        >
          {{ filters.is_verified === "1" ? "Verified" : "Not Verified" }}
          <button
            @click="
              filters.is_verified = '';
              loadVendors();
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
              loadVendors();
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
      <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Showing {{ vendors.length }} vendors</p>
      <div class="flex items-center gap-2">
        <label class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Sort by:</label>
        <select
          v-model="sortField"
          @change="loadVendors"
          :class="[
            'text-sm border rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-primary-500',
            isDarkMode ? 'border-gray-600 bg-gray-700 text-gray-200' : 'border-gray-300 bg-white text-gray-900'
          ]"
        >
          <option value="created_at">Newest</option>
          <option value="business_name">Business Name</option>
          <option value="status">Status</option>
          <option value="earnings">Earnings</option>
          <option value="rating">Rating</option>
        </select>
        <button
          @click="toggleSortDirection"
          class="p-1 rounded-lg transition"
          :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-100'"
        >
          <svg
            class="w-5 h-5"
            :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
            ></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Vendors List -->
    <div :class="[
      'rounded-2xl shadow-lg overflow-hidden',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <!-- Mobile Card View -->
      <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
        <div
          v-for="vendor in vendors"
          :key="vendor.id"
          class="p-4 transition"
          :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'"
        >
          <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
              <div
                class="w-12 h-12 bg-gradient-to-r from-primary-100 to-primary-200 rounded-xl flex items-center justify-center"
              >
                <span class="text-lg font-bold text-primary-700">{{
                  vendor.business_name?.charAt(0) || "V"
                }}</span>
              </div>
              <div>
                <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ vendor.business_name }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ vendor.user?.email }}</p>
              </div>
            </div>
            <span
              :class="getStatusClass(vendor.status)"
              class="px-2 py-1 text-xs font-semibold rounded-full capitalize"
            >
              {{ vendor.status }}
            </span>
          </div>

          <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Owner</p>
              <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ vendor.user?.name }}</p>
            </div>
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Phone</p>
              <p class="text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                {{ vendor.phone || vendor.user?.phone || "-" }}
              </p>
            </div>
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Subscription</p>
              <p v-if="vendor.plan" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'" class="text-sm">
                {{ vendor.plan.name }} ({{ capitalizeFirst(vendor.plan_price?.interval) }})
              </p>
              <p
                v-if="vendor.subscription_status == 'active'"
                :class="getSubscriptionStatusClass(vendor.subscription_status)"
                class="text-center text-sm py-1 font-semibold rounded-full capitalize"
              >
                {{ vendor.subscription_status }} till {{ vendor.subscription_ends_at }}
              </p>
              <p
                v-else-if="vendor.subscription_status == 'trial'"
                :class="getSubscriptionStatusClass(vendor.subscription_status)"
                class="text-center text-sm py-1 font-semibold rounded-full capitalize"
              >
                {{ vendor.subscription_status }} ends on {{ vendor.trial_ends_at }}
              </p>
            </div>
            <div>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Earnings</p>
              <p class="text-sm font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                ${{ formatPrice(vendor.earnings) }}
              </p>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                {{ vendor.rating }} ★ ({{ vendor.total_reviews }})
              </p>
            </div>
          </div>

          <div class="flex flex-wrap gap-2 pt-2 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
            <button
              @click="viewVendor(vendor)"
              class="flex-1 text-primary-600 hover:text-primary-700 text-sm font-medium py-1"
            >
              View
            </button>
            <button
              @click="editVendor(vendor)"
              class="flex-1 text-blue-600 hover:text-blue-700 text-sm font-medium py-1"
            >
              Edit
            </button>
            <button
              @click="deleteVendor(vendor.id)"
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
                Business
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Owner
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Contact
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Subscription
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium tracking-wider uppercase"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Earnings
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
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="divide-y" :class="[
            isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
          ]">
            <tr
              v-for="vendor in vendors"
              :key="vendor.id"
              class="transition-colors"
              :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div
                    class="w-10 h-10 bg-gradient-to-r from-primary-100 to-primary-200 rounded-xl flex items-center justify-center mr-3"
                  >
                    <span class="text-lg font-bold text-primary-700">{{
                      vendor.business_name?.charAt(0) || "V"
                    }}</span>
                  </div>
                  <div>
                    <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ vendor.business_name }}</p>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Slug: {{ vendor.slug }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <p class="text-sm" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ vendor.user?.name }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ vendor.user?.email }}</p>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ vendor.phone || "-" }}</p>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <p v-if="vendor.plan" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'" class="text-sm">
                  {{ vendor.plan.name }} ({{ capitalizeFirst(vendor.plan_price?.interval) }})
                </p>
                <p
                  v-if="vendor.subscription_status == 'active'"
                  :class="getSubscriptionStatusClass(vendor.subscription_status)"
                  class="text-center py-1 font-semibold rounded-full capitalize"
                >
                  {{ vendor.subscription_status }} till {{ vendor.subscription_ends_at }}
                </p>
                <p
                  v-else-if="vendor.subscription_status == 'trial'"
                  :class="getSubscriptionStatusClass(vendor.subscription_status)"
                  class="text-center py-1 font-semibold rounded-full capitalize"
                >
                  {{ vendor.subscription_status }} ends on {{ vendor.trial_ends_at }}
                </p>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <p class="text-sm font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  ${{ formatPrice(vendor.earnings) }}
                </p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  {{ vendor.rating }} ★ ({{ vendor.total_reviews }})
                </p>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(vendor.status)" class="px-2 py-1 text-xs font-semibold rounded-full capitalize">
                  {{ vendor.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <div class="flex items-center gap-2">
                  <button
                    title="View"
                    @click="viewVendor(vendor)"
                    class="inline-flex items-center gap-1.5 px-1 py-1.5 text-sm font-medium text-primary-600 hover:text-primary-800 rounded-lg transition-all duration-200"
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
                    @click="editVendor(vendor)"
                    class="inline-flex items-center gap-1.5 px-1 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 rounded-lg transition-all duration-200"
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
                    @click="deleteVendor(vendor.id)"
                    class="inline-flex items-center gap-1.5 px-1 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 rounded-lg transition-all duration-200"
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
            <tr v-if="vendors.length === 0 && !isLoading">
              <td colspan="9" class="px-6 py-12 text-center" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
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
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                  ></path>
                </svg>
                No vendors found
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

    <!-- View Vendor Modal -->
    <div
      v-if="showViewModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click.self="showViewModal = false"
    >
      <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <div
          :class="[
            'rounded-2xl shadow-2xl max-w-4xl w-full z-50 transform transition-all max-h-[90vh] overflow-y-auto',
            isDarkMode ? 'bg-gray-800' : 'bg-white'
          ]"
        >
          <div
            class="px-6 py-4 border-b flex justify-between items-center sticky top-0 rounded-t-2xl"
            :class="[
              isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'
            ]"
          >
            <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Vendor Details</h3>
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
          <div class="px-6 py-4" v-if="selectedVendor">
            <!-- Cover Image -->
            <div
              class="h-48 bg-gradient-to-r from-primary-500 to-primary-700 rounded-2xl mb-6 relative"
            >
              <div
                class="absolute -bottom-10 left-6 flex flex-col sm:flex-row items-start sm:items-end space-y-2 sm:space-y-0 sm:space-x-4"
              >
                <div
                  class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-lg"
                >
                  <span class="text-3xl font-bold text-primary-600">{{
                    selectedVendor.business_name?.charAt(0) || "V"
                  }}</span>
                </div>
                <div class="pb-2">
                  <h2 class="text-xl sm:text-2xl font-bold text-white">
                    {{ selectedVendor.business_name }}
                  </h2>
                  <p class="text-white/80 text-sm">
                    {{ selectedVendor.city }}, {{ selectedVendor.state }}
                  </p>
                </div>
              </div>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
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
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                    ></path>
                  </svg>
                  Business Information
                </h4>
                <div :class="[
                  'rounded-xl p-4 space-y-2 text-sm',
                  isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                ]">
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Business Name:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedVendor.business_name }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Description:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'">{{
                      selectedVendor.description || "N/A"
                    }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Website:</span>
                    <a
                      :href="selectedVendor.website"
                      target="_blank"
                      class="text-primary-600 font-medium"
                      >{{ selectedVendor.website || "Not provided" }}</a
                    >
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Timezone:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                      selectedVendor.timezone || "UTC"
                    }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Joined:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                      formatDate(selectedVendor.created_at)
                    }}</span>
                  </div>
                </div>
              </div>
              <div>
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
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    ></path>
                  </svg>
                  Contact Information
                </h4>
                <div :class="[
                  'rounded-xl p-4 space-y-2 text-sm',
                  isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                ]">
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Owner Name:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedVendor.user?.name }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Email:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedVendor.user?.email }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Phone:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                      selectedVendor.phone || selectedVendor.user?.phone || "N/A"
                    }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Address:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedVendor.address || "N/A" }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">City/State:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                      >{{ selectedVendor.city }}, {{ selectedVendor.state }}</span
                    >
                  </div>
                  <div class="flex justify-between">
                    <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Country:</span>
                    <span class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ selectedVendor.country || "N/A" }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Stats Grid -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
              <div
                class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-4 text-center"
                :class="isDarkMode ? 'from-primary-900/30 to-primary-800/30' : 'from-primary-50 to-primary-100'"
              >
                <p class="text-2xl font-bold text-primary-600">
                  {{ selectedVendorStats?.total_services || 0 }}
                </p>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Services</p>
              </div>
              <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center"
                :class="isDarkMode ? 'from-blue-900/30 to-blue-800/30' : 'from-blue-50 to-blue-100'"
              >
                <p class="text-2xl font-bold text-blue-600">
                  {{ selectedVendorStats?.total_staff || 0 }}
                </p>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Staff</p>
              </div>
              <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center"
                :class="isDarkMode ? 'from-purple-900/30 to-purple-800/30' : 'from-purple-50 to-purple-100'"
              >
                <p class="text-2xl font-bold text-purple-600">
                  {{ selectedVendorStats?.total_appointments || 0 }}
                </p>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Appointments</p>
              </div>
              <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center"
                :class="isDarkMode ? 'from-green-900/30 to-green-800/30' : 'from-green-50 to-green-100'"
              >
                <p class="text-2xl font-bold text-green-600">
                  ${{ formatPrice(selectedVendorStats?.total_earnings) }}
                </p>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Earnings</p>
              </div>
            </div>

            <!-- Subscription Details -->
            <div class="mt-6">
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
                    d="M13 10V3L4 14h7v7l9-11h-7z"
                  ></path>
                </svg>
                Subscription Details
              </h4>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div :class="[
                  'rounded-xl p-4',
                  isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                ]">
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Subscription Status</p>
                  <p class="font-semibold capitalize" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ selectedVendor.subscription_status || "N/A" }}
                  </p>
                </div>
                <div
                  v-if="selectedVendor.subscription_status == 'active'"
                  :class="[
                    'rounded-xl p-4',
                    isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                  ]"
                >
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Subscription Ends</p>
                  <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ formatDate(selectedVendor.subscription_ends_at) || "N/A" }}
                  </p>
                </div>
                <div
                  v-if="selectedVendor.subscription_status == 'trial'"
                  :class="[
                    'rounded-xl p-4',
                    isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                  ]"
                >
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Trial Ends</p>
                  <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ formatDate(selectedVendor.trial_ends_at) || "N/A" }}
                  </p>
                </div>
                <div :class="[
                  'rounded-xl p-4',
                  isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
                ]">
                  <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Stripe Account Status</p>
                  <p class="font-semibold capitalize" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ selectedVendor.stripe_account_status || "N/A" }}
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

    <!-- Create/Edit Vendor Modal -->
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
              {{ editingVendor ? "Edit Vendor" : "Add New Vendor" }}
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
          <form @submit.prevent="saveVendor" class="px-6 py-4">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Business Name *</label>
                  <input
                    type="text"
                    v-model="form.business_name"
                    required
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Owner Name *</label>
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
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Email *</label>
                  <input
                    type="email"
                    v-model="form.email"
                    required
                    :disabled="editingVendor"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition disabled:opacity-50',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 disabled:bg-gray-600' : 'bg-white border-gray-300 text-gray-900 disabled:bg-gray-100'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Phone *</label>
                  <input
                    type="text"
                    v-model="form.phone"
                    required
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div v-if="!editingVendor">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Password *</label>
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
                <div v-if="!editingVendor">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Confirm Password *</label>
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
                <div v-if="editingVendor">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">New Password</label>
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
                <div v-if="editingVendor">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Confirm New Password</label>
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
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Business Phone</label>
                  <input
                    type="text"
                    v-model="form.business_phone"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Website</label>
                  <input
                    type="url"
                    v-model="form.website"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                    ]"
                    placeholder="https://example.com"
                  />
                </div>
                <div class="md:col-span-2">
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Description</label>
                  <textarea
                    v-model="form.description"
                    rows="3"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                    ]"
                    placeholder="Describe the vendor's business..."
                  ></textarea>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Address</label>
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
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">State</label>
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
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Country</label>
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
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Postal Code</label>
                  <input
                    type="text"
                    v-model="form.postal_code"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Commission Rate (%)</label>
                  <input
                    type="number"
                    v-model="form.commission_rate"
                    min="0"
                    max="100"
                    step="0.01"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Status *</label>
                  <select
                    v-model="form.status"
                    required
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  >
                    <option value="pending">Pending</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                    <option value="rejected">Rejected</option>
                  </select>
                </div>
                <div>
                  <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Timezone</label>
                  <select
                    v-model="form.timezone"
                    :class="[
                      'w-full px-3 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition',
                      isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                    ]"
                  >
                    <option value="UTC">UTC</option>
                    <option value="America/New_York">Eastern Time</option>
                    <option value="America/Chicago">Central Time</option>
                    <option value="America/Denver">Mountain Time</option>
                    <option value="America/Los_Angeles">Pacific Time</option>
                    <option value="Europe/London">London</option>
                    <option value="Europe/Paris">Paris</option>
                    <option value="Asia/Dubai">Dubai</option>
                    <option value="Asia/Singapore">Singapore</option>
                    <option value="Australia/Sydney">Sydney</option>
                  </select>
                </div>
              </div>

              <div :class="[
                'flex flex-wrap gap-4 p-3 rounded-xl',
                isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
              ]">
                <label class="flex items-center cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="form.is_verified"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                  />
                  <span class="ml-2 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Mark as Verified</span>
                </label>
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
                    : editingVendor
                    ? "Update Vendor"
                    : "Create Vendor"
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

const vendors = ref([]);
const isLoading = ref(false);
const isSaving = ref(false);
const showViewModal = ref(false);
const showFormModal = ref(false);
const editingVendor = ref(false);
const selectedVendor = ref(null);
const selectedVendorStats = ref(null);
const sortField = ref("created_at");
const sortDirection = ref("desc");
const isDarkMode = ref(false);
let darkModeObserver = null;

const stats = ref({
  total: 0,
  active: 0,
  pending: 0,
  suspended: 0,
});

const filters = ref({
  status: "",
  subscription_status: "",
  is_verified: "",
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
  business_name: "",
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  business_phone: "",
  description: "",
  website: "",
  timezone: "UTC",
  address: "",
  city: "",
  state: "",
  country: "",
  postal_code: "",
  latitude: "",
  longitude: "",
  commission_rate: 10.0,
  status: "pending",
  is_verified: false,
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

const capitalizeFirst = (str) => {
  if (!str) return "";
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const getStatusClass = (status) => {
  const classes = {
    active: "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    pending: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400",
    suspended: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400",
    rejected: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
  };
  return classes[status] || "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";
};

const getSubscriptionStatusClass = (status) => {
  const classes = {
    trial: "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400",
    active: "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    cancelled: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400",
    expired: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
  };
  return classes[status] || "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300";
};

const formatDate = (date) => {
  if (!date) return "N/A";
  return format(new Date(date), "MMM dd, yyyy");
};

const formatPrice = (price) => {
  if (!price && price !== 0) return "0.00";
  return Number(price).toFixed(2);
};

const toggleSortDirection = () => {
  sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
  loadVendors();
};

const loadVendors = async (page = 1) => {
  isLoading.value = true;
  try {
    const params = {
      ...filters.value,
      page,
      sort: sortField.value,
      direction: sortDirection.value,
    };
    // Remove empty filters
    Object.keys(params).forEach((key) => {
      if (params[key] === "" || params[key] === null || params[key] === undefined) {
        delete params[key];
      }
    });

    const response = await axios.get("/api/admin/vendors", { params });
    vendors.value = response.data.data || [];
    pagination.value = {
      current_page: response.data.current_page || 1,
      last_page: response.data.last_page || 1,
      total: response.data.total || 0,
      from: response.data.from || 0,
      to: response.data.to || 0,
      per_page: response.data.per_page || 20,
    };

    stats.value = response.data.stats;
  } catch (error) {
    toast.error("Failed to load vendors");
  } finally {
    isLoading.value = false;
  }
};

const searchVendors = () => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    loadVendors();
  }, 500);
};

const resetFilters = () => {
  filters.value = {
    status: "",
    subscription_status: "",
    is_verified: "",
    search: "",
  };
  loadVendors();
};

const viewVendor = async (vendor) => {
  try {
    const response = await axios.get(`/api/admin/vendors/${vendor.id}`);
    if (response.data.vendor) {
      selectedVendor.value = response.data.vendor;
      selectedVendorStats.value = response.data.stats;
    } else {
      selectedVendor.value = response.data;
    }
    showViewModal.value = true;
  } catch (error) {
    toast.error("Failed to load vendor details");
  }
};

const editVendor = (vendor) => {
  editingVendor.value = true;
  form.value = {
    id: vendor.id,
    business_name: vendor.business_name,
    name: vendor.user?.name || "",
    email: vendor.user?.email || "",
    phone: vendor.user?.phone || "",
    password: "",
    password_confirmation: "",
    business_phone: vendor.phone || "",
    description: vendor.description || "",
    website: vendor.website || "",
    timezone: vendor.timezone || "UTC",
    address: vendor.address || "",
    city: vendor.city || "",
    state: vendor.state || "",
    country: vendor.country || "",
    postal_code: vendor.postal_code || "",
    latitude: vendor.latitude || "",
    longitude: vendor.longitude || "",
    commission_rate: vendor.commission_rate || 10.0,
    status: vendor.status || "pending",
    is_verified: vendor.is_verified || false,
  };
  showFormModal.value = true;
};

const openCreateModal = () => {
  editingVendor.value = false;
  form.value = {
    id: null,
    business_name: "",
    name: "",
    email: "",
    phone: "",
    password: "",
    password_confirmation: "",
    business_phone: "",
    description: "",
    website: "",
    timezone: "UTC",
    address: "",
    city: "",
    state: "",
    country: "",
    postal_code: "",
    latitude: "",
    longitude: "",
    commission_rate: 10.0,
    status: "pending",
    is_verified: false,
  };
  showFormModal.value = true;
};

const closeFormModal = () => {
  showFormModal.value = false;
  editingVendor.value = false;
};

const saveVendor = async () => {
  // Validate password for new vendor
  if (!editingVendor.value) {
    if (!form.value.password || form.value.password.length < 8) {
      toast.error("Password must be at least 8 characters");
      return;
    }
    if (form.value.password !== form.value.password_confirmation) {
      toast.error("Passwords do not match");
      return;
    }
  } else if (form.value.password && form.value.password.length > 0) {
    // If editing and password is provided, validate it
    if (form.value.password.length < 8) {
      toast.error("Password must be at least 8 characters");
      return;
    }
    if (form.value.password !== form.value.password_confirmation) {
      toast.error("Passwords do not match");
      return;
    }
  }

  isSaving.value = true;
  try {
    // Remove empty password if not changing it
    if (editingVendor.value && !form.value.password) {
      delete form.value.password;
      delete form.value.password_confirmation;
    }

    const payload = { ...form.value };
    // Remove id from payload for update
    if (editingVendor.value) {
      const id = payload.id;
      delete payload.id;
      await axios.put(`/api/admin/vendors/${id}`, payload);
      toast.success("Vendor updated successfully");
    } else {
      await axios.post("/api/admin/vendors", payload);
      toast.success("Vendor created successfully");
    }
    closeFormModal();
    await loadVendors();
  } catch (error) {
    const errors = error.response?.data?.errors;
    if (errors) {
      Object.values(errors).forEach((err) => {
        toast.error(err[0]);
      });
    } else {
      toast.error(error.response?.data?.message || "Failed to save vendor");
    }
  } finally {
    isSaving.value = false;
  }
};

const deleteVendor = async (id) => {
  if (
    confirm("Are you sure you want to delete this vendor? This action cannot be undone.")
  ) {
    try {
      await axios.delete(`/api/admin/vendors/${id}`);
      toast.success("Vendor deleted successfully");
      await loadVendors(pagination.value.current_page);
    } catch (error) {
      toast.error("Failed to delete vendor");
    }
  }
};

const goToPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    loadVendors(page);
  }
};

onMounted(() => {
  checkDarkMode();
  setupDarkModeWatcher();
  loadVendors();

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