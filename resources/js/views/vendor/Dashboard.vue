<!-- resources/js/views/vendor/Dashboard.vue -->
<template>
  <div class="space-y-6 pb-8">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex justify-center items-center min-h-[60vh]">
      <div class="flex flex-col items-center gap-4">
        <div
          class="animate-spin rounded-full h-16 w-16 border-4 border-primary-500 border-t-transparent"
        ></div>
        <p
          class="text-sm font-medium"
          :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
        >
          Loading dashboard...
        </p>
      </div>
    </div>

    <!-- Dashboard Content -->
    <template v-else>
      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1
            class="text-2xl md:text-3xl font-bold"
            :class="isDarkMode ? 'text-white' : 'text-gray-900'"
          >
            <!-- Dashboard SVG Icon -->
            <svg
              class="inline-block w-7 h-7 mr-3 text-primary-500"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
              />
            </svg>
            Dashboard
          </h1>
          <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
            <!-- Clock SVG Icon -->
            <svg
              class="inline-block w-4 h-4 mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Welcome back! Here's what's happening with your business today.
          </p>
        </div>
        <div class="flex items-center gap-3">
          <span
            class="text-xs px-3 py-1.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 inline-flex items-center gap-1.5"
          >
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            Live
          </span>
          <button
            @click="refreshDashboard"
            class="inline-flex items-center gap-2 px-3 py-2 border rounded-xl transition transform active:scale-95 text-sm"
            :class="
              isDarkMode
                ? 'border-gray-600 text-gray-300 hover:bg-gray-700'
                : 'border-gray-300 text-gray-600 hover:bg-gray-50'
            "
          >
            <!-- Refresh SVG Icon -->
            <svg
              class="w-4 h-4"
              :class="isRefreshing ? 'animate-spin' : ''"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 4v5h.582m15.418 0V4h-.582m-15.418 6a9 9 0 1118 0 9 9 0 01-18 0z"
              />
            </svg>
            <span class="hidden sm:inline">Refresh</span>
          </button>
        </div>
      </div>

      <!-- Stats Grid - Main Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Today's Appointments -->
        <div
          :class="[
            'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-5 md:p-6 border group',
            isDarkMode
              ? 'bg-gray-800 border-gray-700 hover:shadow-gray-700'
              : 'bg-white border-gray-100 hover:shadow-gray-200',
          ]"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <p
                class="text-xs font-medium uppercase tracking-wider"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Today's Appointments
              </p>
              <p
                class="text-2xl md:text-3xl font-bold mt-2 truncate"
                :class="isDarkMode ? 'text-white' : 'text-gray-900'"
              >
                {{ dashboardData.today_appointments || 0 }}
              </p>
            </div>
            <div
              class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600"
            >
              <!-- Calendar SVG Icon -->
              <svg
                class="w-6 h-6 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- Total Revenue -->
        <div
          :class="[
            'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-5 md:p-6 border group',
            isDarkMode
              ? 'bg-gray-800 border-gray-700 hover:shadow-gray-700'
              : 'bg-white border-gray-100 hover:shadow-gray-200',
          ]"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <p
                class="text-xs font-medium uppercase tracking-wider"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Total Revenue
              </p>
              <p
                class="text-2xl md:text-3xl font-bold mt-2 truncate"
                :class="isDarkMode ? 'text-white' : 'text-gray-900'"
              >
                ${{ Number(dashboardData.total_revenue || 0).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
}) }}
              </p>
            </div>
            <div
              class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-green-500 to-green-600"
            >
              <!-- Dollar Sign SVG Icon -->
              <svg
                class="w-6 h-6 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- Total Customers -->
        <div
          :class="[
            'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-5 md:p-6 border group',
            isDarkMode
              ? 'bg-gray-800 border-gray-700 hover:shadow-gray-700'
              : 'bg-white border-gray-100 hover:shadow-gray-200',
          ]"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <p
                class="text-xs font-medium uppercase tracking-wider"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Total Customers
              </p>
              <p
                class="text-2xl md:text-3xl font-bold mt-2 truncate"
                :class="isDarkMode ? 'text-white' : 'text-gray-900'"
              >
                {{ dashboardData.total_customers || 0 }}
              </p>
            </div>
            <div
              class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-purple-500 to-purple-600"
            >
              <!-- Users SVG Icon -->
              <svg
                class="w-6 h-6 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                />
              </svg>
            </div>
          </div>
        </div>

        <!-- Average Rating -->
        <div
          :class="[
            'rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-5 md:p-6 border group',
            isDarkMode
              ? 'bg-gray-800 border-gray-700 hover:shadow-gray-700'
              : 'bg-white border-gray-100 hover:shadow-gray-200',
          ]"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <p
                class="text-xs font-medium uppercase tracking-wider"
                :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
              >
                Average Rating
              </p>
              <p
                class="text-2xl md:text-3xl font-bold mt-2 truncate"
                :class="isDarkMode ? 'text-white' : 'text-gray-900'"
              >
                {{ (dashboardData.average_rating || 0).toFixed(1) }}
              </p>
            </div>
            <div
              class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-yellow-500 to-orange-500"
            >
              <!-- Star SVG Icon -->
              <svg
                class="w-6 h-6 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Stats Cards -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div
          class="rounded-2xl shadow-lg overflow-hidden border bg-gradient-to-br from-blue-500 to-blue-600 border-blue-400"
        >
          <div class="p-5 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium opacity-90">Total Appointments</p>
                <p class="text-2xl font-bold mt-1">
                  {{ dashboardData.total_appointments || 0 }}
                </p>
              </div>
              <div
                class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"
              >
                <!-- Calendar SVG Icon -->
                <svg
                  class="w-5 h-5 text-white"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
              </div>
            </div>
            <div class="mt-2 pt-2 border-t border-white/20">
              <p class="text-xs opacity-80">
                +{{ dashboardData.upcoming_appointments || 0 }} upcoming
              </p>
            </div>
          </div>
        </div>

        <div
          class="rounded-2xl shadow-lg overflow-hidden border bg-gradient-to-br from-green-500 to-green-600 border-green-400"
        >
          <div class="p-5 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium opacity-90">Revenue</p>
                <p class="text-2xl font-bold mt-1">
                  ${{
                    Number(dashboardData.total_revenue || 0).toLocaleString("en-US", {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2,
                    })
                  }}
                </p>
              </div>
              <div
                class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"
              >
                <!-- Dollar Sign SVG Icon -->
                <svg
                  class="w-5 h-5 text-white"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"
                  />
                </svg>
              </div>
            </div>
            <div class="mt-2 pt-2 border-t border-white/20">
              <p class="text-xs opacity-80">This month</p>
            </div>
          </div>
        </div>

        <div
          class="rounded-2xl shadow-lg overflow-hidden border bg-gradient-to-br from-purple-500 to-purple-600 border-purple-400"
        >
          <div class="p-5 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium opacity-90">Reviews</p>
                <p class="text-2xl font-bold mt-1">
                  {{ dashboardData.total_reviews || 0 }}
                </p>
              </div>
              <div
                class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"
              >
                <!-- Star SVG Icon -->
                <svg
                  class="w-5 h-5 text-white"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                  />
                </svg>
              </div>
            </div>
            <div class="mt-2 pt-2 border-t border-white/20">
              <p class="text-xs opacity-80">
                ⭐ {{ (dashboardData.average_rating || 0).toFixed(1) }} average
              </p>
            </div>
          </div>
        </div>

        <div
          class="rounded-2xl shadow-lg overflow-hidden border bg-gradient-to-br from-orange-500 to-orange-600 border-orange-400"
        >
          <div class="p-5 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-medium opacity-90">Completion Rate</p>
                <p class="text-2xl font-bold mt-1">
                  {{ dashboardData.completion_rate || 0 }}%
                </p>
              </div>
              <div
                class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center"
              >
                <!-- Check Circle SVG Icon -->
                <svg
                  class="w-5 h-5 text-white"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
            </div>
            <div class="mt-2 pt-2 border-t border-white/20">
              <p class="text-xs opacity-80">Last 30 days</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div
          :class="[
            'rounded-2xl shadow-lg p-5 md:p-6 border',
            isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100',
          ]"
        >
          <div class="flex items-center justify-between mb-4">
            <h3
              class="text-base font-semibold"
              :class="isDarkMode ? 'text-white' : 'text-gray-900'"
            >
              <!-- Chart SVG Icon -->
              <svg
                class="inline-block w-5 h-5 mr-2 text-primary-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                />
              </svg>
              Appointments Overview (Last 7 Days)
            </h3>
            <span
              class="text-xs px-2.5 py-1 rounded-full"
              :class="
                isDarkMode ? 'bg-gray-700 text-gray-400' : 'bg-gray-100 text-gray-500'
              "
            >
              Weekly
            </span>
          </div>
          <div class="relative" style="height: 260px">
            <canvas id="appointmentsChart"></canvas>
          </div>
        </div>

        <div
          :class="[
            'rounded-2xl shadow-lg p-5 md:p-6 border',
            isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100',
          ]"
        >
          <div class="flex items-center justify-between mb-4">
            <h3
              class="text-base font-semibold"
              :class="isDarkMode ? 'text-white' : 'text-gray-900'"
            >
              <!-- Dollar Sign SVG Icon -->
              <svg
                class="inline-block w-5 h-5 mr-2 text-primary-500"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"
                />
              </svg>
              Revenue Overview (This Month)
            </h3>
            <span
              class="text-xs px-2.5 py-1 rounded-full"
              :class="
                isDarkMode ? 'bg-gray-700 text-gray-400' : 'bg-gray-100 text-gray-500'
              "
            >
              Monthly
            </span>
          </div>
          <div class="relative" style="height: 260px">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Appointments Table -->
      <div
        :class="[
          'rounded-2xl shadow-lg overflow-hidden border',
          isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100',
        ]"
      >
        <div
          class="px-6 py-4 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3"
          :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'"
        >
          <h3
            class="text-base font-semibold flex items-center gap-2"
            :class="isDarkMode ? 'text-white' : 'text-gray-900'"
          >
            <!-- Clock SVG Icon -->
            <svg
              class="w-5 h-5 text-primary-500"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Recent Appointments
          </h3>
          <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
            Showing latest
            {{ dashboardData.recent_appointments?.length || 0 }} appointments
          </span>
        </div>

        <!-- Mobile Card View -->
        <div
          class="block md:hidden divide-y"
          :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'"
        >
          <div
            v-for="appointment in dashboardData.recent_appointments"
            :key="appointment.id"
            class="p-4 transition"
            :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'"
          >
            <div class="flex justify-between items-start mb-2">
              <div>
                <p
                  class="font-medium text-sm"
                  :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                >
                  #{{ appointment.booking_number }}
                </p>
                <p
                  class="text-xs"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  {{ appointment.customer?.name || "N/A" }}
                </p>
              </div>
              <span
                :class="getStatusClass(appointment.status)"
                class="px-2 py-0.5 text-xs font-semibold rounded-full"
              >
                {{ formatStatus(appointment.status) }}
              </span>
            </div>
            <div
              class="flex justify-between text-sm"
              :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'"
            >
              <span>{{ appointment.service?.name || "N/A" }}</span>
              <span class="font-medium">${{ appointment.total_amount }}</span>
            </div>
            <div
              class="text-xs mt-1"
              :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
            >
              {{ formatDateTime(appointment.start_time) }}
            </div>
          </div>

          <div
            v-if="!dashboardData.recent_appointments?.length"
            class="p-8 text-center"
            :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
          >
            <svg
              class="w-12 h-12 mx-auto mb-3 opacity-30"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
              />
            </svg>
            <p>No appointments found</p>
          </div>
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
          <table
            class="min-w-full divide-y"
            :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'"
          >
            <thead :class="isDarkMode ? 'bg-gray-700/50' : 'bg-gray-50'">
              <tr>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  <svg
                    class="inline-block w-3 h-3 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"
                    />
                  </svg>
                  Booking #
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  <svg
                    class="inline-block w-3 h-3 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                  Customer
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  <svg
                    class="inline-block w-3 h-3 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    />
                  </svg>
                  Service
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  <svg
                    class="inline-block w-3 h-3 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                  Date & Time
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  <svg
                    class="inline-block w-3 h-3 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <circle
                      cx="12"
                      cy="12"
                      r="10"
                      stroke="currentColor"
                      stroke-width="2"
                    />
                  </svg>
                  Status
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  <svg
                    class="inline-block w-3 h-3 mr-1"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 4v1m0 14v1m0-15a2 2 0 012 2h-4a2 2 0 012-2zm0 12a2 2 0 002-2h-4a2 2 0 002 2zm-2-6h4m-4 0v-1m0 1v1"
                    />
                  </svg>
                  Amount
                </th>
              </tr>
            </thead>
            <tbody
              class="divide-y"
              :class="[
                isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200',
              ]"
            >
              <tr
                v-for="appointment in dashboardData.recent_appointments"
                :key="appointment.id"
                class="transition-colors"
                :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'"
              >
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                  :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                >
                  {{ appointment.booking_number }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm"
                  :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                >
                  {{ appointment.customer?.name || "N/A" }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm"
                  :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                >
                  {{ appointment.service?.name || "N/A" }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm"
                  :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                >
                  {{ formatDateTime(appointment.start_time) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getStatusClass(appointment.status)"
                    class="px-2.5 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ formatStatus(appointment.status) }}
                  </span>
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-primary-600 dark:text-primary-400"
                >
                  ${{ appointment.total_amount }}
                </td>
              </tr>
              <tr v-if="!dashboardData.recent_appointments?.length">
                <td
                  colspan="6"
                  class="px-6 py-12 text-center"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  <svg
                    class="w-16 h-16 mx-auto mb-4 opacity-30"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                  </svg>
                  <p class="text-lg font-medium">No appointments found</p>
                  <p class="text-sm">Your recent appointments will appear here</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from "vue";
import { useVendorStore } from "../../stores/vendor";
import { format } from "date-fns";
import Chart from "chart.js/auto";

const vendorStore = useVendorStore();

const dashboardData = ref({
  today_appointments: 0,
  total_revenue: 0,
  total_customers: 0,
  average_rating: 0,
  total_appointments: 0,
  upcoming_appointments: 0,
  total_reviews: 0,
  completion_rate: 0,
  chart_data: {
    appointments: { labels: [], data: [] },
    revenue: { labels: [], data: [] },
  },
  recent_appointments: [],
});

const isLoading = ref(true);
const isRefreshing = ref(false);
const isDarkMode = ref(false);
let darkModeObserver = null;
let appointmentsChart = null;
let revenueChart = null;

// Check dark mode from localStorage or system preference
const checkDarkMode = () => {
  const savedTheme = localStorage.getItem("darkMode");
  if (savedTheme !== null) {
    isDarkMode.value = savedTheme === "true";
  } else {
    isDarkMode.value = window.matchMedia("(prefers-color-scheme: dark)").matches;
  }
};

// Watch for theme changes using MutationObserver
const setupDarkModeWatcher = () => {
  const htmlElement = document.documentElement;
  darkModeObserver = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.attributeName === "class") {
        const hasDarkClass = htmlElement.classList.contains("dark");
        if (hasDarkClass !== isDarkMode.value) {
          isDarkMode.value = hasDarkClass;
          localStorage.setItem("darkMode", String(hasDarkClass));
          setTimeout(() => {
            initCharts();
          }, 100);
        }
      }
    });
  });
  darkModeObserver.observe(htmlElement, { attributes: true });
};

const formatDateTime = (datetime) => {
  if (!datetime) return "N/A";
  return format(new Date(datetime), "MMM dd, yyyy h:mm a");
};

const formatStatus = (status) => {
  const statusMap = {
    pending: "Pending",
    confirmed: "Confirmed",
    in_progress: "In Progress",
    completed: "Completed",
    cancelled: "Cancelled",
    no_show: "No Show",
  };
  return statusMap[status] || status;
};

const getStatusClass = (status) => {
  const classes = {
    pending: "bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400",
    confirmed: "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400",
    in_progress:
      "bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400",
    completed: "bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400",
    cancelled: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400",
    no_show: "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300",
  };
  return (
    classes[status] || "bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300"
  );
};

const getChartColors = () => {
  return {
    gridColor: isDarkMode.value ? "rgba(255, 255, 255, 0.05)" : "rgba(0, 0, 0, 0.05)",
    textColor: isDarkMode.value ? "#9ca3af" : "#6b7280",
    borderColor: isDarkMode.value ? "#374151" : "#e5e7eb",
  };
};

const loadDashboard = async (showLoading = true) => {
  if (showLoading) {
    isLoading.value = true;
  } else {
    isRefreshing.value = true;
  }

  try {
    const data = await vendorStore.getDashboardData();
    dashboardData.value = data;

    // Initialize charts after data is loaded
    await nextTick();
    setTimeout(() => {
      initCharts();
    }, 100);
  } catch (error) {
    console.error("Failed to load dashboard:", error);
  } finally {
    isLoading.value = false;
    isRefreshing.value = false;
  }
};

const refreshDashboard = () => {
  loadDashboard(false);
};

const initCharts = () => {
  const colors = getChartColors();

  // Appointments Chart
  const appointmentsCtx = document.getElementById("appointmentsChart")?.getContext("2d");
  if (appointmentsCtx) {
    if (appointmentsChart) appointmentsChart.destroy();

    appointmentsChart = new Chart(appointmentsCtx, {
      type: "line",
      data: {
        labels: dashboardData.value.chart_data?.appointments?.labels || [
          "Mon",
          "Tue",
          "Wed",
          "Thu",
          "Fri",
          "Sat",
          "Sun",
        ],
        datasets: [
          {
            label: "Appointments",
            data: dashboardData.value.chart_data?.appointments?.data || [
              0,
              0,
              0,
              0,
              0,
              0,
              0,
            ],
            borderColor: "#3b82f6",
            backgroundColor: isDarkMode.value
              ? "rgba(59, 130, 246, 0.15)"
              : "rgba(59, 130, 246, 0.05)",
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: "#3b82f6",
            pointBorderColor: isDarkMode.value ? "#1f2937" : "#fff",
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            mode: "index",
            intersect: false,
            backgroundColor: isDarkMode.value ? "#1f2937" : "#ffffff",
            titleColor: isDarkMode.value ? "#f9fafb" : "#111827",
            bodyColor: isDarkMode.value ? "#d1d5db" : "#374151",
            borderColor: isDarkMode.value ? "#374151" : "#e5e7eb",
            borderWidth: 1,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
              color: colors.textColor,
            },
            grid: {
              color: colors.gridColor,
              drawBorder: false,
            },
          },
          x: {
            ticks: {
              color: colors.textColor,
            },
            grid: {
              color: colors.gridColor,
              drawBorder: false,
            },
          },
        },
      },
    });
  }

  // Revenue Chart
  const revenueCtx = document.getElementById("revenueChart")?.getContext("2d");
  if (revenueCtx) {
    if (revenueChart) revenueChart.destroy();

    revenueChart = new Chart(revenueCtx, {
      type: "bar",
      data: {
        labels: dashboardData.value.chart_data?.revenue?.labels || [
          "Jan",
          "Feb",
          "Mar",
          "Apr",
          "May",
          "Jun",
        ],
        datasets: [
          {
            label: "Revenue ($)",
            data: dashboardData.value.chart_data?.revenue?.data || [0, 0, 0, 0, 0, 0],
            backgroundColor: isDarkMode.value ? "rgba(16, 185, 129, 0.7)" : "#10b981",
            borderRadius: 6,
            borderWidth: 0,
            hoverBackgroundColor: isDarkMode.value
              ? "rgba(16, 185, 129, 0.9)"
              : "#059669",
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            backgroundColor: isDarkMode.value ? "#1f2937" : "#ffffff",
            titleColor: isDarkMode.value ? "#f9fafb" : "#111827",
            bodyColor: isDarkMode.value ? "#d1d5db" : "#374151",
            borderColor: isDarkMode.value ? "#374151" : "#e5e7eb",
            borderWidth: 1,
            callbacks: {
              label: function (context) {
                return `$${context.raw.toLocaleString()}`;
              },
            },
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function (value) {
                return "$" + value.toLocaleString();
              },
              color: colors.textColor,
            },
            grid: {
              color: colors.gridColor,
              drawBorder: false,
            },
          },
          x: {
            ticks: {
              color: colors.textColor,
            },
            grid: {
              color: colors.gridColor,
              drawBorder: false,
            },
          },
        },
      },
    });
  }
};

onMounted(() => {
  checkDarkMode();
  setupDarkModeWatcher();
  loadDashboard();

  // Listen for storage changes from other tabs
  window.addEventListener("storage", (e) => {
    if (e.key === "darkMode") {
      isDarkMode.value = e.newValue === "true";
      setTimeout(() => {
        initCharts();
      }, 100);
    }
  });
});

onUnmounted(() => {
  if (darkModeObserver) {
    darkModeObserver.disconnect();
  }
  if (appointmentsChart) {
    appointmentsChart.destroy();
    appointmentsChart = null;
  }
  if (revenueChart) {
    revenueChart.destroy();
    revenueChart = null;
  }
});
</script>
