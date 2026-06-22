<!-- resources/js/layouts/AdminLayout.vue -->
<template>
  <div :class="['min-h-screen transition-colors duration-300', isDarkMode ? 'dark bg-gray-900' : 'bg-gradient-to-br from-gray-50 to-gray-100']">
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
        isDarkMode ? 'bg-gray-800' : 'bg-white'
      ]"
    >
      <!-- Sidebar Header -->
      <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-6">
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
                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
              ></path>
            </svg>
          </div>
          <div>
            <h2 class="text-xl font-bold text-white">Admin Panel</h2>
            <p class="text-xs text-white/80 mt-0.5">Manage everything</p>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="p-4 space-y-1">
        <router-link
          to="/admin/dashboard"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode 
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/admin/dashboard' 
              ? isDarkMode 
                ? 'bg-gray-700 text-white' 
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : ''
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
          to="/admin/plans"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode 
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/admin/plans' 
              ? isDarkMode 
                ? 'bg-gray-700 text-white' 
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : ''
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
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
            ></path>
          </svg>
          <span class="font-medium">Subscription Plans</span>
        </router-link>

        <router-link
          to="/admin/vendors"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode 
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/admin/vendors' 
              ? isDarkMode 
                ? 'bg-gray-700 text-white' 
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : ''
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
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
            ></path>
          </svg>
          <span class="font-medium">Vendors</span>
        </router-link>

        <router-link
          to="/admin/invoices"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode 
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/admin/invoices' 
              ? isDarkMode 
                ? 'bg-gray-700 text-white' 
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : ''
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
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            ></path>
          </svg>
          <span class="font-medium">Invoices</span>
        </router-link>

        <router-link
          to="/admin/customers"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode 
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/admin/customers' 
              ? isDarkMode 
                ? 'bg-gray-700 text-white' 
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : ''
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
          <span class="font-medium">Customers</span>
        </router-link>

        <router-link
          to="/admin/categories"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode 
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/admin/categories' 
              ? isDarkMode 
                ? 'bg-gray-700 text-white' 
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : ''
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
              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"
            ></path>
          </svg>
          <span class="font-medium">Categories</span>
        </router-link>

        <router-link
          to="/admin/appointments"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
          :class="[
            isDarkMode 
              ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
              : 'text-gray-700 hover:bg-primary-50 hover:text-primary-600',
            $route.path === '/admin/appointments' 
              ? isDarkMode 
                ? 'bg-gray-700 text-white' 
                : 'bg-primary-50 text-primary-600 shadow-sm'
              : ''
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
          isDarkMode ? 'bg-gray-800/90 backdrop-blur-md border-b border-gray-700' : 'bg-white/90 backdrop-blur-md shadow-lg'
        ]"
      >
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center h-16 md:h-20">
            <!-- Left side - Sidebar Toggle -->
            <div class="flex items-center">
              <button
                @click="toggleSidebar"
                class="p-2 rounded-lg transition-all duration-200"
                :class="[
                  isDarkMode 
                    ? 'text-gray-300 hover:bg-gray-700 hover:text-white' 
                    : 'text-gray-600 hover:bg-gray-100',
                  sidebarOpen && !isDarkMode ? 'bg-primary-50 text-primary-600' : '',
                  sidebarOpen && isDarkMode ? 'bg-gray-700 text-white' : ''
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
              <div v-if="!sidebarOpen" class="ml-3 lg:hidden">
                <div class="flex items-center space-x-2">
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
                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                      ></path>
                    </svg>
                  </div>
                  <span
                    class="text-lg font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent"
                  >
                    Admin
                  </span>
                </div>
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

              <div class="relative">
                <button
                  @click="showDropdown = !showDropdown"
                  class="flex items-center space-x-3 focus:outline-none group"
                >
                  <div
                    class="w-10 h-10 bg-gradient-to-r from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white font-semibold shadow-md group-hover:scale-105 transition-transform duration-300"
                  >
                    {{ authStore.user?.name?.charAt(0)?.toUpperCase() || "A" }}
                  </div>
                  <div class="hidden md:block text-left">
                    <p class="text-sm font-medium" :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'">
                      {{ authStore.user?.name }}
                    </p>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Administrator</p>
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
                    <div class="px-4 py-3 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                      <p class="text-sm font-medium" :class="isDarkMode ? 'text-gray-200' : 'text-gray-900'">
                        {{ authStore.user?.name }}
                      </p>
                      <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Administrator</p>
                    </div>
                    <router-link
                      to="/admin/profile"
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
                      Profile Settings
                    </router-link>
                    <div class="border-t my-1" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'"></div>
                    <button
                      @click="logout"
                      class="flex items-center gap-3 w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors"
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
          <router-view v-slot="{ Component }">
            <transition name="fade" mode="out-in">
              <component :is="Component" />
            </transition>
          </router-view>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();
const sidebarOpen = ref(true);
const showDropdown = ref(false);
const isMobile = ref(false);
const isDarkMode = ref(false);

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

onMounted(() => {
  loadThemePreference();
  checkMobile();
  window.addEventListener("resize", checkMobile);
  document.addEventListener("click", handleClickOutside);
  
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