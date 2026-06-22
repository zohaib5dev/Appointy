<!-- resources/js/views/vendor/Schedule.vue -->
<template>
    <div class="space-y-6 pb-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    <!-- Clock SVG Icon -->
                    <svg class="inline-block w-7 h-7 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Working Hours
                </h1>
                <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                    <!-- Calendar SVG Icon -->
                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Set your business operating hours and break times
                </p>
            </div>
            <div class="flex items-center gap-2 text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                <!-- Clock SVG Icon -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="hidden sm:inline">All times in 24-hour format</span>
                <span class="sm:hidden">24-hour format</span>
            </div>
        </div>

        <!-- Schedule Form -->
        <div :class="[
            'rounded-2xl shadow-lg overflow-hidden border',
            isDarkMode ? 'bg-gray-800/90 border-gray-700' : 'bg-white border-gray-100'
        ]">
            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center items-center py-16">
                <div class="flex flex-col items-center gap-3">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary-500 border-t-transparent"></div>
                    <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Loading schedule...</p>
                </div>
            </div>

            <!-- Schedule Content -->
            <template v-else>
                <!-- Mobile View (Cards) -->
                <div class="block md:hidden">
                    <div v-for="day in daysOfWeek" :key="day.value" class="border-b last:border-0" :class="isDarkMode ? 'border-gray-700' : 'border-gray-100'">
                        <div class="p-4 transition" :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'">
                            <!-- Day Header -->
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="font-semibold text-lg" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ day.label }}</h3>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        v-model="schedule[day.value].active" 
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600 dark:bg-gray-600 dark:peer-checked:bg-primary-600"></div>
                                    <span class="ml-3 text-sm font-medium" :class="schedule[day.value].active ? (isDarkMode ? 'text-green-400' : 'text-green-600') : (isDarkMode ? 'text-gray-400' : 'text-gray-500')">
                                        {{ schedule[day.value].active ? 'Open' : 'Closed' }}
                                    </span>
                                </label>
                            </div>

                            <!-- Time Settings -->
                            <div v-if="schedule[day.value].active" class="space-y-4">
                                <!-- Hours -->
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <label class="block text-xs font-medium mb-2 flex items-center gap-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <!-- Clock SVG Icon -->
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Operating Hours
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1">
                                            <input 
                                                type="time" 
                                                v-model="schedule[day.value].start" 
                                                :class="[
                                                    'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                ]"
                                            >
                                        </div>
                                        <span class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">to</span>
                                        <div class="flex-1">
                                            <input 
                                                type="time" 
                                                v-model="schedule[day.value].end" 
                                                :class="[
                                                    'w-full px-3 py-2.5 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition text-sm',
                                                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                ]"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <!-- Break Times -->
                                <div :class="[
                                    'rounded-xl p-4 border',
                                    isDarkMode ? 'bg-gray-700/30 border-gray-600' : 'bg-gray-50 border-gray-200'
                                ]">
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="text-xs font-medium flex items-center gap-1.5" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                            <!-- Coffee SVG Icon -->
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                            Break Times
                                        </label>
                                        <button 
                                            type="button" 
                                            @click="addBreak(day.value)" 
                                            class="inline-flex items-center gap-1 text-xs font-medium transform active:scale-95 transition"
                                            :class="isDarkMode ? 'text-primary-400 hover:text-primary-300' : 'text-primary-600 hover:text-primary-700'"
                                        >
                                            <!-- Plus SVG Icon -->
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Break
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div 
                                            v-for="(breakTime, index) in schedule[day.value].breaks" 
                                            :key="index" 
                                            class="flex items-center gap-2 rounded-lg p-2 border"
                                            :class="isDarkMode ? 'bg-gray-700 border-gray-600' : 'bg-white border-gray-200'"
                                        >
                                            <input 
                                                type="time" 
                                                v-model="breakTime.start_time" 
                                                :class="[
                                                    'flex-1 px-2 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                                                    isDarkMode ? 'bg-gray-600 border-gray-500 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                ]"
                                            >
                                            <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">to</span>
                                            <input 
                                                type="time" 
                                                v-model="breakTime.end_time" 
                                                :class="[
                                                    'flex-1 px-2 py-1.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 transition',
                                                    isDarkMode ? 'bg-gray-600 border-gray-500 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                ]"
                                            >
                                            <button 
                                                type="button" 
                                                @click="removeBreak(day.value, index)" 
                                                class="p-1.5 rounded-lg transition transform active:scale-95"
                                                :class="isDarkMode ? 'text-red-400 hover:text-red-300 hover:bg-red-900/20' : 'text-red-500 hover:text-red-700 hover:bg-red-50'"
                                            >
                                                <!-- Trash SVG Icon -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <p v-if="schedule[day.value].breaks.length === 0" class="text-xs text-center py-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">
                                        No breaks scheduled
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Save Button -->
                    <div class="p-4 border-t" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                        <button 
                            @click="saveSchedule" 
                            :disabled="isSaving"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg font-semibold transform active:scale-95"
                        >
                            <svg v-if="isSaving" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ isSaving ? 'Saving...' : 'Save Schedule' }}</span>
                        </button>
                    </div>
                </div>

                <!-- Desktop View (Table) -->
                <div class="hidden md:block overflow-x-auto">
                    <form @submit.prevent="saveSchedule">
                        <table class="min-w-full divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                            <thead :class="isDarkMode ? 'bg-gray-700/50' : 'bg-gray-50'">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <!-- Calendar SVG Icon -->
                                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Day
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <!-- Circle SVG Icon -->
                                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <!-- Clock SVG Icon -->
                                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Working Hours
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <!-- Coffee SVG Icon -->
                                        <svg class="inline-block w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                        Break Times
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" :class="[
                                isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
                            ]">
                                <tr v-for="day in daysOfWeek" :key="day.value" class="transition-colors" :class="isDarkMode ? 'hover:bg-gray-700/30' : 'hover:bg-gray-50'">
                                    <!-- Day -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ day.label }}</span>
                                    </td>
                                    
                                    <!-- Status Toggle -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                v-model="schedule[day.value].active" 
                                                class="sr-only peer"
                                            >
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600 dark:bg-gray-600 dark:peer-checked:bg-primary-600"></div>
                                            <span class="ml-3 text-sm font-medium" :class="schedule[day.value].active ? (isDarkMode ? 'text-green-400' : 'text-green-600') : (isDarkMode ? 'text-gray-400' : 'text-gray-500')">
                                                {{ schedule[day.value].active ? 'Open' : 'Closed' }}
                                            </span>
                                        </label>
                                    </td>
                                    
                                    <!-- Working Hours -->
                                    <td class="px-6 py-4">
                                        <div v-if="schedule[day.value].active" class="flex items-center gap-2">
                                            <input 
                                                type="time" 
                                                v-model="schedule[day.value].start" 
                                                :class="[
                                                    'px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm w-32',
                                                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                ]"
                                            >
                                            <span class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">—</span>
                                            <input 
                                                type="time" 
                                                v-model="schedule[day.value].end" 
                                                :class="[
                                                    'px-3 py-1.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent text-sm w-32',
                                                    isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                ]"
                                            >
                                        </div>
                                        <span v-else class="text-sm" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'">—</span>
                                    </td>
                                    
                                    <!-- Break Times -->
                                    <td class="px-6 py-4">
                                        <div v-if="schedule[day.value].active" class="space-y-2">
                                            <div v-for="(breakTime, index) in schedule[day.value].breaks" :key="index" 
                                                class="flex items-center gap-2">
                                                <input 
                                                    type="time" 
                                                    v-model="breakTime.start_time" 
                                                    :class="[
                                                        'px-2 py-1 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 w-28 transition',
                                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                    ]"
                                                >
                                                <span class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">to</span>
                                                <input 
                                                    type="time" 
                                                    v-model="breakTime.end_time" 
                                                    :class="[
                                                        'px-2 py-1 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 w-28 transition',
                                                        isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
                                                    ]"
                                                >
                                                <button 
                                                    type="button" 
                                                    @click="removeBreak(day.value, index)" 
                                                    class="p-1 rounded-lg transition transform active:scale-95"
                                                    :class="isDarkMode ? 'text-red-400 hover:text-red-300 hover:bg-red-900/20' : 'text-red-500 hover:text-red-700 hover:bg-red-50'"
                                                >
                                                    <!-- Trash SVG Icon -->
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <button 
                                                type="button" 
                                                @click="addBreak(day.value)" 
                                                class="text-xs font-medium inline-flex items-center gap-1 transform active:scale-95 transition"
                                                :class="isDarkMode ? 'text-primary-400 hover:text-primary-300' : 'text-primary-600 hover:text-primary-700'"
                                            >
                                                <!-- Plus SVG Icon -->
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Add Break
                                            </button>
                                        </div>
                                        <span v-else class="text-sm" :class="isDarkMode ? 'text-gray-500' : 'text-gray-400'">—</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <!-- Save Button (Desktop) -->
                        <div class="px-6 py-4 border-t" :class="isDarkMode ? 'border-gray-700 bg-gray-800/50' : 'border-gray-200 bg-gray-50'">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                                <div class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    All changes are saved when you click "Save Schedule"
                                </div>
                                <button 
                                    type="submit" 
                                    :disabled="isSaving"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-xl hover:from-primary-600 hover:to-primary-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg font-semibold transform active:scale-95"
                                >
                                    <svg v-if="isSaving" class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>{{ isSaving ? 'Saving...' : 'Save Schedule' }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </template>
        </div>

        <!-- Info Card -->
        <div :class="[
            'border rounded-xl p-4',
            isDarkMode ? 'bg-blue-900/20 border-blue-800' : 'bg-blue-50 border-blue-200'
        ]">
            <div class="flex gap-3">
                <div class="flex-shrink-0">
                    <!-- Info Circle SVG Icon -->
                    <svg class="w-5 h-5" :class="isDarkMode ? 'text-blue-400' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium flex items-center gap-2" :class="isDarkMode ? 'text-blue-400' : 'text-blue-800'">
                        <!-- Lightbulb SVG Icon -->
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                        Working Hours Guide
                    </p>
                    <p class="text-xs mt-1" :class="isDarkMode ? 'text-blue-300' : 'text-blue-700'">
                        Set your business hours for each day. Enable "Open" to activate working hours. 
                        Add break times for lunch or rest periods. Customers can only book during working hours, excluding break times.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';

const toast = useToast();
const isLoading = ref(false);
const isSaving = ref(false);
const isDarkMode = ref(false);
let darkModeObserver = null;

const daysOfWeek = [
    { value: 'monday', label: 'Monday' },
    { value: 'tuesday', label: 'Tuesday' },
    { value: 'wednesday', label: 'Wednesday' },
    { value: 'thursday', label: 'Thursday' },
    { value: 'friday', label: 'Friday' },
    { value: 'saturday', label: 'Saturday' },
    { value: 'sunday', label: 'Sunday' }
];

// Initialize schedule
const schedule = ref({});

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

const initializeSchedule = () => {
    daysOfWeek.forEach(day => {
        schedule.value[day.value] = {
            active: false,
            start: '09:00',
            end: '17:00',
            breaks: []
        };
    });
};

const addBreak = (day) => {
    schedule.value[day].breaks.push({
        start_time: '12:00',
        end_time: '13:00'
    });
};

const removeBreak = (day, index) => {
    schedule.value[day].breaks.splice(index, 1);
};

const loadSchedule = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/api/vendor/schedules');
        const schedules = response.data.data?.data || response.data.data || [];
        
        initializeSchedule();
        
        if (schedules && schedules.length) {
            schedules.forEach(sched => {
                const dayKey = sched.day.toLowerCase();
                if (schedule.value[dayKey]) {
                    schedule.value[dayKey].active = sched.is_active;
                    schedule.value[dayKey].start = sched.start_time;
                    schedule.value[dayKey].end = sched.end_time;
                    
                    if (sched.breaks && sched.breaks.length) {
                        schedule.value[dayKey].breaks = sched.breaks.map(breakItem => ({
                            start_time: breakItem.start_time,
                            end_time: breakItem.end_time,
                            id: breakItem.id
                        }));
                    }
                }
            });
        }
        
    } catch (error) {
        console.error('Failed to load schedule:', error);
        toast.error('Failed to load schedule');
    } finally {
        isLoading.value = false;
    }
};

const saveSchedule = async () => {
    isSaving.value = true;
    
    try {
        const scheduleData = {};
        
        for (const [day, data] of Object.entries(schedule.value)) {
            scheduleData[day] = {
                active: data.active,
                start: data.start,
                end: data.end,
                breaks: data.breaks.filter(b => b.start_time && b.end_time)
            };
        }
        
        await axios.post('/api/vendor/schedules', scheduleData);
        toast.success('Schedule saved successfully');
        await loadSchedule();
        
    } catch (error) {
        console.error('Save error:', error);
        toast.error(error.response?.data?.message || 'Failed to save schedule');
    } finally {
        isSaving.value = false;
    }
};

initializeSchedule();

onMounted(() => {
    checkDarkMode();
    setupDarkModeWatcher();
    loadSchedule();

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
</style>