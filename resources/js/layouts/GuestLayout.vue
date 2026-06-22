<!-- resources/js/layouts/CustomerLayout.vue -->
<template>
  <div :class="['min-h-screen transition-colors duration-300', isDarkMode ? 'dark bg-gray-900' : 'bg-gradient-to-br from-gray-50 to-gray-100']">
    <!-- Navbar -->
    <nav
      class="fixed top-0 w-full z-50 transition-all duration-300"
      :class="[
        isDarkMode ? 'bg-gray-800/90 backdrop-blur-md border-b border-gray-700' : 'bg-white/90 backdrop-blur-md shadow-lg'
      ]"
    >
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
          <!-- Logo -->
          <div class="flex items-center">
            <router-link to="/" class="flex items-center space-x-2 group">
              <div
                class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-700 rounded-lg flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300"
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
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  ></path>
                </svg>
              </div>
              <span
                class="text-xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent"
              >
                Appointy
              </span>
            </router-link>
          </div>

          <!-- Desktop Navigation - Always visible -->
          <div class="hidden lg:flex items-center space-x-1">
            <router-link
              to="/"
              class="px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2"
              :class="[
                isDarkMode 
                  ? 'text-gray-300 hover:text-white hover:bg-gray-700' 
                  : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50',
                $route.path === '/' 
                  ? isDarkMode 
                    ? 'text-white bg-gray-700 font-semibold' 
                    : 'text-primary-600 bg-primary-50 font-semibold'
                  : ''
              ]"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                ></path>
              </svg>
              Home
            </router-link>
            <router-link
              to="/vendors"
              class="px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2"
              :class="[
                isDarkMode 
                  ? 'text-gray-300 hover:text-white hover:bg-gray-700' 
                  : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50',
                $route.path === '/vendors' 
                  ? isDarkMode 
                    ? 'text-white bg-gray-700 font-semibold' 
                    : 'text-primary-600 bg-primary-50 font-semibold'
                  : ''
              ]"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                ></path>
              </svg>
              Vendors
            </router-link>
          </div>

          <!-- Right side - Changes based on auth status -->
          <div class="flex items-center space-x-3">
            <!-- Dark Mode Toggle -->
            <button
              @click="toggleDarkMode"
              class="p-2 rounded-lg transition-all duration-200"
              :class="[
                isDarkMode 
                  ? 'text-yellow-400 hover:bg-gray-700 hover:text-yellow-300' 
                  : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'
              ]"
              :title="isDarkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
            >
              <svg
                v-if="isDarkMode"
                class="w-5 h-5"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
              </svg>
              <svg
                v-else
                class="w-5 h-5"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
              </svg>
            </button>

            <!-- Show Login/Sign Up when NOT authenticated -->
            <template v-if="!isAuthenticated">
              <router-link
                to="/login"
                class="hidden md:block transition"
                :class="isDarkMode ? 'text-gray-300 hover:text-white' : 'text-gray-700 hover:text-primary-600'"
              >
                Login
              </router-link>
              <router-link
                to="/register"
                class="hidden md:block bg-primary-600 text-white px-5 py-2 rounded-lg hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 shadow-md"
              >
                Sign Up
              </router-link>
            </template>

            <!-- Show User Menu when authenticated -->
            <template v-else>
              <!-- User Dropdown -->
              <div class="relative">
                <button
                  @click="showDropdown = !showDropdown"
                  class="flex items-center space-x-3 focus:outline-none group"
                >
                  <div
                    class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white font-semibold shadow-md group-hover:scale-105 transition-transform duration-300"
                  >
                    {{ authStore.user?.name?.charAt(0)?.toUpperCase() || "U" }}
                  </div>
                  <div class="hidden md:block text-left">
                    <p class="text-sm font-medium" :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'">
                      {{ authStore.user?.name }}
                    </p>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ authStore.user?.email }}</p>
                  </div>
                  <svg
                    class="hidden md:block w-4 h-4 transition-transform duration-200"
                    :class="[
                      isDarkMode ? 'text-gray-400' : 'text-gray-400',
                      { 'rotate-180': showDropdown }
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
                        : 'bg-white border-gray-100'
                    ]"
                  >
                    <div class="px-4 py-3 border-b md:hidden" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                      <p class="text-sm font-medium" :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'">
                        {{ authStore.user?.name }}
                      </p>
                      <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ authStore.user?.email }}</p>
                    </div>
                    <router-link
                      to="/customer/dashboard"
                      class="flex items-center gap-3 px-4 py-2 transition-colors"
                      :class="[
                        isDarkMode 
                          ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
                          : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600'
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
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                        ></path>
                      </svg>
                      Dashboard
                    </router-link>
                    <router-link
                      to="/customer/appointments"
                      class="flex items-center gap-3 px-4 py-2 transition-colors"
                      :class="[
                        isDarkMode 
                          ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
                          : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600'
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
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        ></path>
                      </svg>
                      Appointments
                    </router-link>
                    <router-link
                      to="/customer/profile"
                      class="flex items-center gap-3 px-4 py-2 transition-colors"
                      :class="[
                        isDarkMode 
                          ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
                          : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600'
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
                    <div class="border-t my-1" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'"></div>
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
            </template>

            <!-- Mobile Menu Button -->
            <button
              @click="showMobileMenu = !showMobileMenu"
              class="lg:hidden p-2 rounded-lg focus:outline-none"
              :class="[
                isDarkMode 
                  ? 'text-gray-300 hover:bg-gray-700' 
                  : 'text-gray-600 hover:bg-gray-100'
              ]"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  v-if="!showMobileMenu"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                ></path>
                <path
                  v-else
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                ></path>
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Navigation Menu -->
      <transition name="mobile-menu">
        <div
          v-if="showMobileMenu"
          class="lg:hidden border-t shadow-lg"
          :class="[
            isDarkMode 
              ? 'bg-gray-800 border-gray-700' 
              : 'bg-white border-gray-100'
          ]"
        >
          <div class="container mx-auto px-4 py-3 space-y-1">
            <router-link
              to="/"
              class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all"
              :class="[
                isDarkMode 
                  ? 'text-gray-300 hover:text-white hover:bg-gray-700' 
                  : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
              ]"
              @click="showMobileMenu = false"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                ></path>
              </svg>
              Home
            </router-link>
            <router-link
              to="/vendors"
              class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all"
              :class="[
                isDarkMode 
                  ? 'text-gray-300 hover:text-white hover:bg-gray-700' 
                  : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
              ]"
              @click="showMobileMenu = false"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                ></path>
              </svg>
              Vendors
            </router-link>

            <div class="border-t my-2" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'"></div>

            <!-- Show different mobile menu items based on auth status -->
            <template v-if="!isAuthenticated">
              <router-link
                to="/login"
                class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all"
                :class="[
                  isDarkMode 
                    ? 'text-gray-300 hover:text-white hover:bg-gray-700' 
                    : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
                ]"
                @click="showMobileMenu = false"
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
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                  ></path>
                </svg>
                Login
              </router-link>
              <router-link
                to="/register"
                class="flex items-center gap-3 px-3 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-all"
                @click="showMobileMenu = false"
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
                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                  ></path>
                </svg>
                Sign Up
              </router-link>
            </template>

            <template v-else>
              <router-link
                to="/customer/dashboard"
                class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all"
                :class="[
                  isDarkMode 
                    ? 'text-gray-300 hover:text-white hover:bg-gray-700' 
                    : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
                ]"
                @click="showMobileMenu = false"
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
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                  ></path>
                </svg>
                Dashboard
              </router-link>
              <router-link
                to="/customer/appointments"
                class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all"
                :class="[
                  isDarkMode 
                    ? 'text-gray-300 hover:text-white hover:bg-gray-700' 
                    : 'text-gray-700 hover:text-primary-600 hover:bg-primary-50'
                ]"
                @click="showMobileMenu = false"
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
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  ></path>
                </svg>
                Appointments
              </router-link>
              <button
                @click="logout"
                class="flex items-center gap-3 w-full px-3 py-2 text-red-600 rounded-lg transition-all"
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
            </template>
          </div>
        </div>
      </transition>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <router-view v-slot="{ Component }">
          <transition name="fade" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </div>
    </main>

    <!-- Footer -->
    <footer :class="[
      'border-t mt-16 transition-colors duration-300',
      isDarkMode 
        ? 'bg-gray-800/50 border-gray-700' 
        : 'bg-gradient-to-b from-white to-gray-50 border-gray-200'
    ]">
      <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Brand Section -->
          <div class="col-span-2 lg:col-span-1">
            <div class="flex items-center space-x-2 mb-4">
              <div
                class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-700 rounded-xl flex items-center justify-center"
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
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  ></path>
                </svg>
              </div>
              <h3
                class="text-xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent"
              >
                Appointy
              </h3>
            </div>
            <p class="text-sm leading-relaxed mb-4" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
              Book appointments with top service providers easily and securely. Your
              trusted platform for quality services.
            </p>
            <div class="flex space-x-3">
              <a
                href="#"
                class="transition transform hover:scale-110"
                :class="isDarkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                  />
                </svg>
              </a>
              <a
                href="#"
                class="transition transform hover:scale-110"
                :class="isDarkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.642-11.9c0-.21-.005-.424-.015-.636A9.936 9.936 0 0024 4.59z"
                  />
                </svg>
              </a>
              <a
                href="#"
                class="transition transform hover:scale-110"
                :class="isDarkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
                  />
                </svg>
              </a>
              <a
                href="#"
                class="transition transform hover:scale-110"
                :class="isDarkMode ? 'text-gray-400 hover:text-primary-400' : 'text-gray-400 hover:text-primary-600'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"
                  />
                </svg>
              </a>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4 col-span-2 lg:col-span-2">
            <div>
              <h4 class="font-semibold mb-4 flex items-center gap-2" :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'">
                <svg
                  class="w-4 h-4 text-primary-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                Quick Links
              </h4>
              <ul class="space-y-2">
                <li>
                  <router-link
                    to="/"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    Home
                  </router-link>
                </li>
                <li>
                  <router-link
                    to="/vendors"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    Vendors
                  </router-link>
                </li>
                <li>
                  <router-link
                    to="/about"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    About Us
                  </router-link>
                </li>
                <li>
                  <router-link
                    to="/contact"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    Contact
                  </router-link>
                </li>
              </ul>
            </div>

            <!-- Support -->
            <div>
              <h4 class="font-semibold mb-4 flex items-center gap-2" :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'">
                <svg
                  class="w-4 h-4 text-primary-500"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m0 5.656l3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                  ></path>
                </svg>
                Support
              </h4>
              <ul class="space-y-2">
                <li>
                  <a
                    href="#"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    FAQ
                  </a>
                </li>
                <li>
                  <a
                    href="#"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    Terms of Service
                  </a>
                </li>
                <li>
                  <a
                    href="#"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    Privacy Policy
                  </a>
                </li>
                <li>
                  <a
                    href="#"
                    class="text-sm transition flex items-center gap-2 group"
                    :class="isDarkMode ? 'text-gray-400 hover:text-gray-200' : 'text-gray-600 hover:text-primary-600'"
                  >
                    <span
                      class="w-1 h-1 rounded-full group-hover:bg-primary-500"
                      :class="isDarkMode ? 'bg-gray-600' : 'bg-gray-300'"
                    ></span>
                    Help Center
                  </a>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-span-2 lg:col-span-1">
            <div
              class="rounded-xl p-5 border h-full"
              :class="[
                isDarkMode 
                  ? 'bg-primary-900/20 border-primary-800' 
                  : 'bg-gradient-to-br from-primary-50 to-primary-100 border-primary-200'
              ]"
            >
              <div class="flex items-center gap-2 mb-3">
                <svg
                  class="w-6 h-6 text-primary-600"
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
                <h4 class="font-bold" :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'">Become a Vendor</h4>
              </div>
              <p class="text-sm mb-4" :class="isDarkMode ? 'text-gray-400' : 'text-gray-700'">
                Join our platform and reach thousands of customers. Grow your business
                with Appointy.
              </p>
              <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                  <svg
                    class="w-4 h-4 text-green-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    ></path>
                  </svg>
                  <span>Reach more customers</span>
                </div>
                <div class="flex items-center gap-2 text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                  <svg
                    class="w-4 h-4 text-green-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    ></path>
                  </svg>
                  <span>Easy booking management</span>
                </div>
                <div class="flex items-center gap-2 text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                  <svg
                    class="w-4 h-4 text-green-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    ></path>
                  </svg>
                  <span>Secure payments</span>
                </div>
              </div>
              <router-link
                to="/register?role=vendor"
                class="w-full bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 text-sm font-semibold shadow-md"
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
                    d="M12 4v16m8-8H4"
                  ></path>
                </svg>
                Get Started
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";
import { formatDistanceToNow } from "date-fns";

const authStore = useAuthStore();
const router = useRouter();
const showDropdown = ref(false);
const showMobileMenu = ref(false);
const showNotifications = ref(false);
const unreadNotifications = ref(3);
const isDarkMode = ref(false);
const notifications = ref([
  {
    id: 1,
    message: "Your appointment is confirmed for tomorrow",
    created_at: new Date(),
  },
  {
    id: 2,
    message: "New service added to Wellness Medical Clinic",
    created_at: new Date(Date.now() - 86400000),
  },
]);

const isAuthenticated = computed(() => authStore.isAuthenticated);

// Check for saved theme preference
const loadThemePreference = () => {
  const savedTheme = localStorage.getItem('darkMode');
  if (savedTheme !== null) {
    isDarkMode.value = savedTheme === 'true';
  } else {
    // Check system preference
    isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
  applyTheme();
};

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
  applyTheme();
  localStorage.setItem('darkMode', isDarkMode.value.toString());
};

const applyTheme = () => {
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

const logout = async () => {
  await authStore.logout();
  showDropdown.value = false;
  showMobileMenu.value = false;
  router.push("/");
};

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) {
    unreadNotifications.value = 0;
  }
};

const formatRelativeTime = (date) => {
  return formatDistanceToNow(new Date(date), { addSuffix: true });
};

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest(".relative")) {
    showDropdown.value = false;
    showNotifications.value = false;
  }
};

// Close mobile menu on route change
const handleRouteChange = () => {
  showMobileMenu.value = false;
  showDropdown.value = false;
  showNotifications.value = false;
};

onMounted(() => {
  loadThemePreference();
  document.addEventListener("click", handleClickOutside);
  router.afterEach(handleRouteChange);

  // Watch for system theme changes
  const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
  const handleSystemThemeChange = (e) => {
    if (localStorage.getItem('darkMode') === null) {
      isDarkMode.value = e.matches;
      applyTheme();
    }
  };
  darkModeMediaQuery.addEventListener('change', handleSystemThemeChange);
});

onUnmounted(() => {
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

/* Mobile menu animations */
.mobile-menu-enter-active,
.mobile-menu-leave-active {
  transition: all 0.3s ease;
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
  opacity: 0;
  transform: translateY(-20px);
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

/* Scrollbar styling */
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