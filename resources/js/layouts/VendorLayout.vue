<!-- resources/js/layouts/VendorLayout.vue -->
<template>
  <div
    :class="[
      'min-h-screen transition-colors duration-300',
      isDarkMode ? 'dark bg-gray-900' : 'bg-gradient-to-br from-gray-50 to-gray-100',
    ]"
  >
    <!-- Subscription Expired Banner -->
    <div
      v-if="!isSubscriptionValid && !isLoading && !isOnSubscriptionPage"
      class="fixed top-16 left-0 right-0 z-50 bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-3 text-center shadow-lg animate-slide-down"
    >
      <div class="flex items-center justify-center gap-3 flex-wrap">
        <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>
          <strong>⚠️ Subscription Inactive:</strong>
          {{ subscriptionMessage }}
        </span>
        <button
          @click="goToSubscription"
          class="bg-white text-red-600 px-4 py-1.5 rounded-full text-sm font-semibold hover:bg-red-50 transition-colors shadow-md"
        >
          Renew Now
        </button>
      </div>
    </div>

    <!-- Subscription Expiring Soon Banner -->
    <div
      v-if="isSubscriptionValid && isExpiringSoon && !isLoading && !isOnSubscriptionPage"
      class="fixed top-16 left-0 right-0 z-50 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-4 py-3 text-center shadow-lg animate-slide-down"
    >
      <div class="flex items-center justify-center gap-3 flex-wrap">
        <svg class="w-5 h-5 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span>
          <strong>⚠️ Subscription Expiring Soon:</strong>
          Your {{ subscriptionType }} will expire in <strong>{{ remainingDays }} days</strong>.
          {{ subscriptionStatus === 'trial' ? 'Upgrade to continue using our services.' : 'Renew now to avoid interruption.' }}
        </span>
        <button
          @click="goToSubscription"
          class="bg-white text-yellow-600 px-4 py-1.5 rounded-full text-sm font-semibold hover:bg-yellow-50 transition-colors shadow-md"
        >
          {{ subscriptionStatus === 'trial' ? 'Upgrade Now' : 'Renew Now' }}
        </button>
        <button
          @click="dismissBanner"
          class="text-white/80 hover:text-white transition-colors"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <transition name="fade">
      <div
        v-if="sidebarOpen && isMobile"
        class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/70 z-30"
        @click="sidebarOpen = false"
      ></div>
    </transition>

    <!-- Sidebar -->
    <aside
      class="fixed inset-y-0 left-0 z-40 w-72 shadow-2xl transition-all duration-300 transform overflow-y-auto"
      :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        isDarkMode ? 'bg-gray-800' : 'bg-white',
      ]"
    >
      <!-- Sidebar Header -->
      <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div
              class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm"
            >
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
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                ></path>
              </svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-white">Vendor Portal</h2>
              <p class="text-xs text-white/80 mt-0.5">Manage your business</p>
            </div>
          </div>

          <!-- Close Button - Mobile Only -->
          <button
            @click="sidebarOpen = false"
            class="lg:hidden p-2 rounded-lg text-white/80 hover:bg-white/20 transition-all duration-200"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="p-4 space-y-1">
        <router-link
          to="/vendor/dashboard"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/dashboard'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
            ></path>
          </svg>
          <span class="font-medium">Dashboard</span>
        </router-link>

        <router-link
          to="/vendor/appointments"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/appointments'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
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
          <span class="font-medium">Appointments</span>
        </router-link>

        <router-link
          to="/vendor/invoices"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/invoices'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform "
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"
            />
          </svg>
          <span class="font-medium">Invoices</span>
        </router-link>

        <router-link
          to="/vendor/services"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/services'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
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
          <span class="font-medium">Services</span>
        </router-link>

        <router-link
          to="/vendor/staff"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/staff'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
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
          <span class="font-medium">Staff Management</span>
        </router-link>

        <router-link
          to="/vendor/schedule"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/schedule'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
          <span class="font-medium">Working Hours</span>
        </router-link>

        <router-link
          to="/vendor/holidays"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/holidays'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
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
          <span class="font-medium">Holidays & Time Off</span>
        </router-link>

        <router-link
          to="/vendor/reports"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/reports'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
            ></path>
          </svg>
          <span class="font-medium">Reports & Analytics</span>
        </router-link>

        <router-link
          to="/vendor/subscription"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/subscription'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
            ></path>
          </svg>
          <span class="font-medium">Subscription</span>
        </router-link>
     
        <router-link
          to="/vendor/settings"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/vendor/settings'
              ? isDarkMode
                ? 'bg-gray-700 text-white'
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : '',
          ]"
          @click="closeSidebarOnMobile"
        >
          <svg
            class="w-5 h-5 group-hover:scale-110 transition-transform "
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
            />
          </svg>
          <span class="font-medium">Settings</span>
        </router-link>
      </nav>
    </aside>

    <!-- Main Content Area -->
    <div
      class="flex flex-col min-h-screen transition-all duration-300"
      :class="sidebarOpen ? 'lg:ml-72' : 'ml-0'"
    >
      <!-- Navbar -->
      <nav
        class="fixed top-0 right-0 left-0 z-30 transition-all duration-300"
        :class="[
          sidebarOpen ? 'lg:left-72' : 'left-0',
          isDarkMode
            ? 'bg-gray-800/90 backdrop-blur-md border-b border-gray-700'
            : 'bg-white/90 backdrop-blur-md shadow-lg',
        ]"
      >
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-16 md:h-20">
            <!-- Left side - Logo & Sidebar Toggle -->
            <div class="flex items-center space-x-3">
              <button
                @click="toggleSidebar"
                class="p-2 rounded-lg transition-all duration-200"
                :class="[
                  isDarkMode
                    ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
                    : 'text-gray-600 hover:bg-gray-100',
                  sidebarOpen && !isDarkMode ? 'bg-primary-50 text-primary-600' : '',
                  sidebarOpen && isDarkMode ? 'bg-gray-700 text-white' : '',
                ]"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  ></path>
                </svg>
              </button>

              <!-- Logo on mobile when sidebar closed -->
              <div v-if="!sidebarOpen" class="lg:hidden">
                <router-link to="/vendor/dashboard" class="flex items-center space-x-2">
                  <div
                    class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-700 rounded-lg flex items-center justify-center"
                  >
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
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                      ></path>
                    </svg>
                  </div>
                  <span
                    class="text-lg font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent"
                  >
                    Vendor
                  </span>
                </router-link>
              </div>
            </div>

            <!-- Right side - User Menu & Dark Mode Toggle -->
            <div class="flex items-center space-x-3">
            

              <!-- Dark Mode Toggle -->
              <button
                @click="toggleDarkMode"
                class="p-2 rounded-lg transition-all duration-200"
                :class="[
                  isDarkMode
                    ? 'text-yellow-400 hover:bg-gray-700 hover:text-yellow-300'
                    : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
                ]"
                :title="isDarkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
              >
                <svg
                  v-if="isDarkMode"
                  class="w-5 h-5"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                    clip-rule="evenodd"
                  />
                </svg>
                <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path
                    d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"
                  />
                </svg>
              </button>

              <div class="relative">
                <button
                  @click="showDropdown = !showDropdown"
                  class="flex items-center space-x-3 focus:outline-none group"
                >
                  <div
                    class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white font-semibold shadow-md group-hover:scale-105 transition-transform duration-300"
                  >
                    {{ authStore.user?.name?.charAt(0)?.toUpperCase() || "V" }}
                  </div>
                  <div class="hidden md:block text-left">
                    <p
                      class="text-sm font-medium"
                      :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'"
                    >
                      {{ authStore.user?.name }}
                    </p>
                    <p
                      class="text-xs"
                      :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                    >
                      {{ authStore.user?.email }}
                    </p>
                  </div>
                  <svg
                    class="hidden md:block w-4 h-4 transition-transform duration-200"
                    :class="[
                      isDarkMode ? 'text-gray-400' : 'text-gray-400',
                      { 'rotate-180': showDropdown },
                    ]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 9l-7 7-7-7"
                    ></path>
                  </svg>
                </button>

                <!-- Dropdown Menu -->
                <transition name="dropdown">
                  <div
                    v-if="showDropdown"
                    class="absolute right-0 mt-2 w-64 rounded-xl shadow-2xl py-2 z-50 border"
                    :class="[
                      isDarkMode
                        ? 'bg-gray-800 border-gray-700'
                        : 'bg-white border-gray-100',
                    ]"
                  >
                    <div
                      class="px-4 py-3 border-b"
                      :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'"
                    >
                      <p
                        class="text-sm font-medium"
                        :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'"
                      >
                        {{ authStore.user?.name }}
                      </p>
                      <p
                        class="text-xs"
                        :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                      >
                        {{ authStore.user?.email }}
                      </p>
                    </div>
                    <router-link
                      to="/vendor/profile"
                      class="flex items-center gap-3 px-4 py-2 transition-colors"
                      :class="[
                        isDarkMode
                          ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
                          : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
                      ]"
                      @click="showDropdown = false"
                    >
                      <svg
                        class="w-5 h-5"
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
                      Profile
                    </router-link>
                    <router-link
                      to="/vendor/subscription"
                      class="flex items-center gap-3 px-4 py-2 transition-colors"
                      :class="[
                        isDarkMode
                          ? 'text-gray-300 hover:bg-gray-700 hover:text-white'
                          : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
                      ]"
                      @click="showDropdown = false"
                    >
                      <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                        ></path>
                      </svg>
                      Subscription
                    </router-link>
                    <div
                      class="border-t my-1"
                      :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'"
                    ></div>
                    <button
                      @click="logout"
                      class="flex items-center gap-3 w-full text-left px-4 py-2 text-red-600 transition-colors"
                      :class="isDarkMode ? 'hover:bg-red-900/20' : 'hover:bg-red-50'"
                    >
                      <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                        ></path>
                      </svg>
                      Logout
                    </button>
                  </div>
                </transition>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <!-- Main Content -->
      <main class="flex-1 pt-16 md:pt-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <!-- Loading State -->
          <div v-if="isLoading" class="flex justify-center items-center min-h-[400px]">
            <div class="flex flex-col items-center gap-3">
              <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent"></div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Checking subscription status...</p>
            </div>
          </div>

          <!-- Content (only shown if subscription is valid or on subscription page) -->
          <div v-else-if="isSubscriptionValid || isOnSubscriptionPage">
            <router-view v-slot="{ Component }">
              <transition name="fade" mode="out-in">
                <component :is="Component" />
              </transition>
            </router-view>
          </div>

          <!-- Subscription Expired - Full Page Message -->
          <div v-else class="flex justify-center items-center min-h-[400px]">
            <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
              <div class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                Subscription Required
              </h3>
              <p class="text-gray-600 dark:text-gray-400 mb-6">
                {{ subscriptionMessage || 'Your subscription has expired or is inactive. Please renew your subscription to continue using the vendor portal.' }}
              </p>
              <button
                @click="goToSubscription"
                class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-medium py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl"
              >
                Renew Subscription
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

// UI State
const sidebarOpen = ref(true);
const showDropdown = ref(false);
const isMobile = ref(false);
const isDarkMode = ref(false);

// Subscription State
const isLoading = ref(true);
const subscriptionData = ref(null);
const bannerDismissed = ref(false);

// Check for saved theme preference
const loadThemePreference = () => {
  const savedTheme = localStorage.getItem("darkMode");
  if (savedTheme !== null) {
    isDarkMode.value = savedTheme === "true";
  } else {
    isDarkMode.value = window.matchMedia("(prefers-color-scheme: dark)").matches;
  }
  applyTheme();
};

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  applyTheme();
  localStorage.setItem("darkMode", isDarkMode.value.toString());
};

const applyTheme = () => {
  if (isDarkMode.value) {
    document.documentElement.classList.add("dark");
  } else {
    document.documentElement.classList.remove("dark");
  }
};

const checkMobile = () => {
  isMobile.value = window.innerWidth < 1024;
  if (isMobile.value) {
    sidebarOpen.value = false;
  } else {
    sidebarOpen.value = true;
  }
};

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebarOnMobile = () => {
  if (isMobile.value) {
    sidebarOpen.value = false;
  }
};

const logout = async () => {
  await authStore.logout();
  router.push("/login");
};

// Close dropdown when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest(".relative")) {
    showDropdown.value = false;
  }
};

// Computed properties for subscription
const isOnSubscriptionPage = computed(() => {
  return route.path === '/vendor/subscription';
});

const isSubscriptionValid = computed(() => {
  return subscriptionData.value?.subscription?.is_valid ?? false;
});

const subscriptionStatus = computed(() => {
  return subscriptionData.value?.subscription?.status ?? 'unknown';
});

const subscriptionMessage = computed(() => {
  return subscriptionData.value?.subscription?.message ?? '';
});

const remainingDays = computed(() => {
  return subscriptionData.value?.subscription?.remaining_days ?? null;
});

const isExpiringSoon = computed(() => {
  return subscriptionData.value?.subscription?.is_expiring_soon ?? false;
});

const subscriptionType = computed(() => {
  return subscriptionStatus.value === 'trial' ? 'trial' : 'subscription';
});

const subscriptionStatusText = computed(() => {
  if (!subscriptionData.value) return 'Checking...';
  if (!isSubscriptionValid.value) return 'Inactive';
  if (isExpiringSoon.value) return 'Expiring Soon';
  return 'Active';
});

// Check subscription status
const checkSubscriptionStatus = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/vendor/subscription/status');
    subscriptionData.value = response.data.data;
    
    // If subscription is not valid and not on subscription page, redirect
    if (!isSubscriptionValid.value && !isOnSubscriptionPage.value) {
      // Show a brief message before redirect
      setTimeout(() => {
        router.push('/vendor/subscription');
      }, 2000);
    }
  } catch (error) {
    console.error('Error checking subscription status:', error);
    // If there's an error, default to invalid subscription
    subscriptionData.value = {
      subscription: {
        is_valid: false,
        message: 'Unable to verify subscription status. Please contact support.',
        status: 'error'
      }
    };
  } finally {
    isLoading.value = false;
  }
};

// Go to subscription page
const goToSubscription = () => {
  router.push('/vendor/subscription');
};

// Dismiss banner
const dismissBanner = () => {
  bannerDismissed.value = true;
};

// Watch for route changes to check subscription
watch(
  () => route.path,
  (newPath) => {
    // If user navigates to subscription page, allow access
    if (newPath === '/vendor/subscription') {
      return;
    }
    
    // If subscription is invalid, redirect
    if (!isSubscriptionValid.value && !isLoading.value) {
      router.push('/vendor/subscription');
    }
  }
);

// Watch subscription validity
watch(
  () => isSubscriptionValid.value,
  (newVal) => {
    if (!newVal && !isOnSubscriptionPage.value && !isLoading.value) {
      router.push('/vendor/subscription');
    }
  }
);

// Check subscription on mount
onMounted(() => {
  loadThemePreference();
  checkMobile();
  window.addEventListener("resize", checkMobile);
  document.addEventListener("click", handleClickOutside);

  // Check subscription status
  checkSubscriptionStatus();

  // Check subscription every 5 minutes
  const interval = setInterval(() => {
    if (!isOnSubscriptionPage.value) {
      checkSubscriptionStatus();
    }
  }, 5 * 60 * 1000);

  // Watch for system theme changes
  const darkModeMediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
  const handleSystemThemeChange = (e) => {
    if (localStorage.getItem("darkMode") === null) {
      isDarkMode.value = e.matches;
      applyTheme();
    }
  };
  darkModeMediaQuery.addEventListener("change", handleSystemThemeChange);

  // Cleanup interval
  onUnmounted(() => {
    clearInterval(interval);
  });
});

onUnmounted(() => {
  window.removeEventListener("resize", checkMobile);
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
/* Dropdown animations */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Fade animation for overlay */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Page transition */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Banner slide down animation */
.animate-slide-down {
  animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
  from {
    transform: translateY(-100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Scrollbar styling for dark mode */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.dark ::-webkit-scrollbar-track {
  background: #1f2937;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.dark ::-webkit-scrollbar-thumb {
  background: #4b5563;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}
</style>