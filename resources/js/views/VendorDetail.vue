<!-- resources/js/views/VendorDetail.vue -->
<template>
  <div
    :class="[
      'min-h-screen py-8 transition-colors duration-300',
      isDarkMode ? 'bg-gray-900' : 'bg-gray-50',
    ]"
  >
    <div class="container mx-auto px-4">
      <!-- Loading State -->
      <div v-if="isLoading" class="flex flex-col items-center justify-center py-16">
        <div class="relative">
          <div
            class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary-600"
          ></div>
          <div class="animate-pulse absolute inset-0 flex items-center justify-center">
            <div class="h-8 w-8 bg-primary-200 rounded-full opacity-50"></div>
          </div>
        </div>
        <p :class="['mt-4 font-medium', isDarkMode ? 'text-gray-400' : 'text-gray-600']">
          Loading vendor details...
        </p>
      </div>

      <!-- Vendor Content -->
      <div
        v-else-if="vendor"
        :class="[
          'rounded-lg shadow-sm overflow-hidden',
          isDarkMode ? 'bg-gray-800' : 'bg-white',
        ]"
      >
        <!-- Cover Image -->
        <div
          class="relative h-64 md:h-80 lg:h-96 bg-gradient-to-r from-gray-800 to-gray-900"
        >
          <img
            :src="getCoverImage()"
            :alt="vendor.business_name"
            @error="handleImageError"
            class="w-full h-full object-cover"
          />
          <div
            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"
          ></div>
          <div class="absolute bottom-0 left-0 right-0 p-6">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
              <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                  {{ vendor.business_name }}
                </h1>
                <div class="flex items-center gap-2 text-white/90">
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
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                    ></path>
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                    ></path>
                  </svg>
                  <span>{{ vendor.city }}, {{ vendor.state }}</span>
                </div>
              </div>
              <div
                class="flex items-center bg-white/20 backdrop-blur rounded-lg px-4 py-2"
              >
                <svg
                  class="w-5 h-5 text-yellow-400 mr-1"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  ></path>
                </svg>
                <span class="text-white font-semibold">{{ vendor.rating || 0 }}</span>
                <span class="text-white/80 ml-1"
                  >({{ vendor.total_reviews || 0 }} reviews)</span
                >
              </div>
            </div>
          </div>

          <div
            v-if="!hasValidCoverImage"
            class="absolute top-4 left-4 bg-black/50 backdrop-blur rounded-lg px-3 py-1 text-white text-sm"
          >
            <svg
              class="w-4 h-4 inline mr-1"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
              ></path>
            </svg>
            Image not available
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-8">
            <!-- About Section -->
            <div>
              <h2
                :class="[
                  'text-2xl font-bold mb-4 flex items-center',
                  isDarkMode ? 'text-white' : 'text-gray-900',
                ]"
              >
                <svg
                  class="w-6 h-6 mr-2 text-primary-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
                About
              </h2>
              <p
                :class="[
                  isDarkMode ? 'text-gray-300' : 'text-gray-600',
                  'leading-relaxed',
                ]"
              >
                {{ vendor.description }}
              </p>
            </div>

            <!-- Services Section -->
            <div>
              <h2
                :class="[
                  'text-2xl font-bold mb-4 flex items-center',
                  isDarkMode ? 'text-white' : 'text-gray-900',
                ]"
              >
                <svg
                  class="w-6 h-6 mr-2 text-primary-600"
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
                Our Services
              </h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                  v-for="service in vendor.services"
                  :key="service.id"
                  @click="selectService(service)"
                  :class="[
                    'border-2 rounded-lg p-4 transition-all cursor-pointer',
                    selectedService?.id === service.id
                      ? 'border-primary-500 bg-primary-50 dark:bg-primary-400/20 shadow-md'
                      : isDarkMode
                      ? 'border-gray-700 hover:border-primary-500'
                      : 'border-gray-200 hover:border-primary-300 hover:shadow-md',
                  ]"
                >
                  <div class="flex justify-between items-start mb-2">
                    <h3
                      :class="[
                        'font-semibold',
                        isDarkMode ? 'text-white' : 'text-gray-900',
                      ]"
                    >
                      {{ service.name }}
                    </h3>
                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400"
                      >${{ service.price }}</span
                    >
                  </div>
                  <p
                    :class="[
                      'text-sm mb-3',
                      isDarkMode ? 'text-gray-400' : 'text-gray-600',
                    ]"
                  >
                    {{ service.description }}
                  </p>
                  <div class="flex justify-between items-center text-sm">
                    <span
                      :class="[
                        'flex items-center',
                        isDarkMode ? 'text-gray-400' : 'text-gray-500',
                      ]"
                    >
                      <svg
                        class="w-4 h-4 mr-1"
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
                      {{ service.duration }} min
                    </span>
                    <span v-if="service.discount_price" class="text-red-500 line-through"
                      >${{ service.discount_price }}</span
                    >
                    <svg
                      v-if="selectedService?.id === service.id"
                      class="w-5 h-5 text-primary-600"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Staff Section -->
            <div v-if="vendor.staff && vendor.staff.length">
              <h2
                :class="[
                  'text-2xl font-bold mb-4 flex items-center',
                  isDarkMode ? 'text-white' : 'text-gray-900',
                ]"
              >
                <svg
                  class="w-6 h-6 mr-2 text-primary-600"
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
                Our Team
              </h2>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                  v-for="staff in vendor.staff"
                  :key="staff.id"
                  @click="selectStaff(staff)"
                  :class="[
                    'flex items-center p-3 border-2 rounded-lg cursor-pointer transition-all',
                    selectedStaff?.id === staff.id
                      ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20'
                      : isDarkMode
                      ? 'border-gray-700 hover:border-primary-500'
                      : 'border-gray-200 hover:border-primary-300',
                  ]"
                >
                  <div
                    :class="[
                      'w-12 h-12 rounded-full flex items-center justify-center mr-3',
                      selectedStaff?.id === staff.id
                        ? 'bg-primary-600 text-white'
                        : 'bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 text-primary-700 dark:text-primary-300',
                    ]"
                  >
                    <span class="font-semibold text-lg">{{ staff.name.charAt(0) }}</span>
                  </div>
                  <div class="flex-1">
                    <p
                      :class="[
                        'font-medium',
                        isDarkMode ? 'text-white' : 'text-gray-900',
                      ]"
                    >
                      {{ staff.name }}
                    </p>
                    <p
                      :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']"
                    >
                      {{ staff.position }}
                    </p>
                  </div>
                  <svg
                    v-if="selectedStaff?.id === staff.id"
                    class="w-5 h-5 text-primary-600"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Reviews Section -->
            <div>
              <h2
                :class="[
                  'text-2xl font-bold mb-4 flex items-center',
                  isDarkMode ? 'text-white' : 'text-gray-900',
                ]"
              >
                <svg
                  class="w-6 h-6 mr-2 text-primary-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                  ></path>
                </svg>
                Customer Reviews
              </h2>
              <div v-if="vendor.reviews && vendor.reviews.length" class="space-y-4">
                <div
                  v-for="review in vendor.reviews"
                  :key="review.id"
                  :class="[
                    'border-b pb-4 last:border-0',
                    isDarkMode ? 'border-gray-700' : 'border-gray-200',
                  ]"
                >
                  <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                      <div
                        :class="[
                          'w-10 h-10 rounded-full flex items-center justify-center mr-3',
                          isDarkMode ? 'bg-gray-700' : 'bg-gray-200',
                        ]"
                      >
                        <span
                          :class="[
                            isDarkMode ? 'text-gray-400' : 'text-gray-600',
                            'font-medium',
                          ]"
                          >{{ review.customer?.name?.charAt(0) || "U" }}</span
                        >
                      </div>
                      <div>
                        <p
                          :class="[
                            'font-medium',
                            isDarkMode ? 'text-white' : 'text-gray-900',
                          ]"
                        >
                          {{ review.customer?.name || "Anonymous" }}
                        </p>
                        <div class="flex items-center">
                          <div class="flex">
                            <svg
                              v-for="i in 5"
                              :key="i"
                              class="w-4 h-4"
                              :class="
                                i <= review.rating ? 'text-yellow-400' : 'text-gray-300'
                              "
                              fill="currentColor"
                              viewBox="0 0 20 20"
                            >
                              <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                              ></path>
                            </svg>
                          </div>
                          <span
                            :class="[
                              'text-sm ml-2',
                              isDarkMode ? 'text-gray-400' : 'text-gray-500',
                            ]"
                            >{{ review.rating }}</span
                          >
                        </div>
                      </div>
                    </div>
                    <span
                      :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-500']"
                      >{{ formatDate(review.created_at) }}</span
                    >
                  </div>
                  <p :class="[isDarkMode ? 'text-gray-300' : 'text-gray-600']">
                    {{ review.comment }}
                  </p>
                </div>
              </div>
              <div
                v-else
                :class="[
                  'text-center py-8 rounded-lg',
                  isDarkMode ? 'bg-gray-700' : 'bg-gray-50',
                ]"
              >
                <svg
                  class="w-12 h-12 text-gray-400 mx-auto mb-3"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                  ></path>
                </svg>
                <p :class="[isDarkMode ? 'text-gray-400' : 'text-gray-500']">
                  No reviews yet
                </p>
              </div>
            </div>
          </div>

          <!-- Sidebar - Booking -->
          <div class="lg:col-span-1">
            <div
              :class="[
                'rounded-lg p-6 sticky top-4 shadow-lg border',
                isDarkMode
                  ? 'bg-gray-700 border-gray-600'
                  : 'bg-gradient-to-br from-gray-50 to-white border-gray-200',
              ]"
            >
              <h3
                :class="[
                  'text-xl font-bold mb-4 flex items-center',
                  isDarkMode ? 'text-white' : 'text-gray-900',
                ]"
              >
                <svg
                  class="w-6 h-6 mr-2 text-primary-600"
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
                Book an Appointment
              </h3>

              <div class="space-y-4">
                <!-- Selected Service Display -->
                <div v-if="selectedService">
                  <div
                    :class="[
                      'rounded-lg p-3 mb-4 border',
                      isDarkMode
                        ? 'bg-primary-900/20 border-primary-700'
                        : 'bg-primary-50 border-primary-200',
                    ]"
                  >
                    <p
                      :class="[
                        'text-sm mb-1',
                        isDarkMode ? 'text-gray-400' : 'text-gray-600',
                      ]"
                    >
                      Selected Service:
                    </p>
                    <p
                      :class="[
                        'font-semibold',
                        isDarkMode ? 'text-white' : 'text-gray-900',
                      ]"
                    >
                      {{ selectedService.name }}
                    </p>
                    <p
                      :class="[
                        'text-sm',
                        isDarkMode ? 'text-primary-400' : 'text-primary-600',
                      ]"
                    >
                      ${{ selectedService.price }} · {{ selectedService.duration }} min
                    </p>
                  </div>
                </div>

                <!-- Selected Staff Display -->
                <div v-if="selectedStaff">
                  <div
                    :class="[
                      'rounded-lg p-3 mb-4',
                      isDarkMode ? 'bg-gray-600' : 'bg-gray-100',
                    ]"
                  >
                    <p
                      :class="[
                        'text-sm mb-1',
                        isDarkMode ? 'text-gray-400' : 'text-gray-600',
                      ]"
                    >
                      Selected Staff:
                    </p>
                    <p
                      :class="[
                        'font-semibold',
                        isDarkMode ? 'text-white' : 'text-gray-900',
                      ]"
                    >
                      {{ selectedStaff.name }}
                    </p>
                    <p
                      :class="['text-sm', isDarkMode ? 'text-gray-400' : 'text-gray-600']"
                    >
                      {{ selectedStaff.position }}
                    </p>
                  </div>
                </div>

                <!-- Slot Loading Indicator -->
                <div v-if="isLoadingSlots" class="flex items-center justify-center py-4">
                  <div
                    class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"
                  ></div>
                  <span
                    :class="[
                      'ml-2 text-sm',
                      isDarkMode ? 'text-gray-400' : 'text-gray-600',
                    ]"
                    >Loading available slots...</span
                  >
                </div>

                <div v-else>
                  <div>
                    <label
                      :class="[
                        'block text-sm font-medium mb-1',
                        isDarkMode ? 'text-gray-300' : 'text-gray-700',
                      ]"
                      >Select Date</label
                    >
                    <input
                      type="date"
                      v-model="selectedDate"
                      :min="minDate"
                      :disabled="!selectedService"
                      @change="loadAvailableSlots"
                      :class="[
                        'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                        !selectedService
                          ? 'bg-gray-100 cursor-not-allowed dark:bg-gray-600'
                          : isDarkMode
                          ? 'bg-gray-600 border-gray-500 text-white'
                          : 'border-gray-300',
                      ]"
                    />
                  </div>

                  <div v-if="availableSlots.length > 0">
                    <label
                      :class="[
                        'block text-sm font-medium mb-2',
                        isDarkMode ? 'text-gray-300' : 'text-gray-700',
                      ]"
                      >Available Time Slots</label
                    >
                    <div class="grid grid-cols-2 gap-2">
                      <button
                        v-for="slot in availableSlots"
                        :key="slot.start_time"
                        @click="selectSlot(slot)"
                        :class="[
                          selectedSlot === slot
                            ? 'bg-primary-600 text-white'
                            : isDarkMode
                            ? 'bg-gray-600 border-gray-500 text-gray-200 hover:bg-gray-500'
                            : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50',
                          'px-3 py-2 rounded-lg text-sm font-medium transition border',
                        ]"
                      >
                        {{ slot.formatted_time }}
                      </button>
                    </div>
                  </div>
                  <div
                    v-else-if="
                      selectedDate &&
                      selectedService &&
                      !isLoadingSlots &&
                      availableSlots.length === 0
                    "
                    :class="[
                      'text-center py-4 text-sm',
                      isDarkMode ? 'text-gray-400' : 'text-gray-500',
                    ]"
                  >
                    No available slots for this date
                  </div>
                </div>

                <!-- Payment Options -->
                <div v-if="selectedSlot" class="mt-4 space-y-3">
                  <!-- Pay Later Button -->
                  <button
                    @click="bookAppointment('later')"
                    class="w-full border-2 border-primary-600 text-primary-600 dark:text-primary-400 py-3 rounded-lg font-semibold hover:bg-primary-50 dark:hover:bg-primary-900/20 transition flex items-center justify-center gap-2"
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
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                      ></path>
                    </svg>
                    Pay Later
                  </button>

                  <!-- Pay Now Button - Toggle Payment Form -->
                  <button
                    @click="togglePaymentForm"
                    class="w-full bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition flex items-center justify-center gap-2"
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
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                      ></path>
                    </svg>
                    Pay Now - ${{ selectedService.price }}
                  </button>

                  <!-- Stripe Payment Form -->
                  <div
                    v-if="showPaymentForm"
                    class="mt-4 p-4 border rounded-lg"
                    :class="
                      isDarkMode
                        ? 'border-gray-600 bg-gray-800'
                        : 'border-gray-200 bg-white'
                    "
                  >
                    <div class="mb-3">
                      <label
                        :class="[
                          'block text-sm font-medium mb-2',
                          isDarkMode ? 'text-gray-300' : 'text-gray-700',
                        ]"
                      >
                        Card Details
                      </label>
                      <div
                        id="card-element"
                        class="p-3 border rounded-lg dark:bg-gray-700 dark:border-gray-600"
                      ></div>
                      <div
                        id="card-errors"
                        role="alert"
                        class="mt-2 text-sm text-red-600 dark:text-red-400"
                      ></div>
                    </div>

                    <button
                      @click="processStripePayment"
                      :disabled="isProcessingPayment"
                      class="w-full bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                      <svg
                        v-if="isProcessingPayment"
                        class="animate-spin h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                      >
                        <circle
                          class="opacity-25"
                          cx="12"
                          cy="12"
                          r="10"
                          stroke="currentColor"
                          stroke-width="4"
                        ></circle>
                        <path
                          class="opacity-75"
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                      </svg>
                      <span v-else>Pay ${{ selectedService?.price }}</span>
                    </button>

                    <p
                      :class="[
                        'text-xs text-center mt-2',
                        isDarkMode ? 'text-gray-400' : 'text-gray-500',
                      ]"
                    >
                      Your payment is secure and encrypted
                    </p>
                  </div>
                </div>

                <div
                  v-if="!selectedService"
                  :class="[
                    'text-center py-8',
                    isDarkMode ? 'text-gray-400' : 'text-gray-500',
                  ]"
                >
                  <svg
                    class="w-12 h-12 mx-auto mb-3 text-gray-400"
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
                  <p>Select a service to start booking</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div
        v-else-if="!isLoading && !vendor"
        :class="[
          'text-center py-16 rounded-xl shadow-sm',
          isDarkMode ? 'bg-gray-800' : 'bg-white',
        ]"
      >
        <svg
          class="w-20 h-20 text-gray-400 mx-auto mb-4"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
          ></path>
        </svg>
        <h3
          :class="[
            'text-xl font-semibold mb-2',
            isDarkMode ? 'text-gray-300' : 'text-gray-700',
          ]"
        >
          Vendor not found
        </h3>
        <p :class="[isDarkMode ? 'text-gray-400' : 'text-gray-500']">
          The vendor you're looking for doesn't exist or has been removed
        </p>
        <router-link
          to="/vendors"
          class="inline-block mt-4 text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
          >Browse all vendors</router-link
        >
      </div>
    </div>

    <!-- Login Modal -->
    <div
      v-if="showLoginModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click.self="showLoginModal = false"
    >
      <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div
          :class="[
            'rounded-lg shadow-xl max-w-md w-full z-50 p-6 transform transition-all',
            isDarkMode ? 'bg-gray-800' : 'bg-white',
          ]"
        >
          <div class="text-center">
            <div
              class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900/30 mb-4"
            >
              <svg
                class="h-6 w-6 text-primary-600 dark:text-primary-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                ></path>
              </svg>
            </div>
            <h3
              :class="[
                'text-lg font-semibold mb-2',
                isDarkMode ? 'text-white' : 'text-gray-900',
              ]"
            >
              Login Required
            </h3>
            <p :class="['mb-6', isDarkMode ? 'text-gray-400' : 'text-gray-600']">
              Please login to book an appointment
            </p>
            <div class="flex space-x-3">
              <button
                @click="showLoginModal = false"
                :class="[
                  'flex-1 px-4 py-2 border rounded-lg transition',
                  isDarkMode
                    ? 'border-gray-600 text-gray-300 hover:bg-gray-700'
                    : 'border-gray-300 text-gray-700 hover:bg-gray-50',
                ]"
              >
                Cancel
              </button>
              <router-link
                to="/login"
                class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg text-center hover:bg-primary-700 transition"
                >Login</router-link
              >
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, nextTick } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import { useAuthStore } from "../stores/auth";
import { useAppointmentStore } from "../stores/appointment";
import { useToast } from "vue-toastification";
import { format } from "date-fns";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const appointmentStore = useAppointmentStore();
const toast = useToast();

const vendor = ref(null);
const stripeKey = ref(null);
const selectedService = ref(null);
const selectedStaff = ref(null);
const selectedDate = ref("");
const selectedSlot = ref(null);
const availableSlots = ref([]);
const showLoginModal = ref(false);
const showPaymentForm = ref(false);
const isProcessingPayment = ref(false);
const isLoading = ref(true);
const isLoadingSlots = ref(false);
const hasValidCoverImage = ref(true);
const isDarkMode = ref(false);
const minDate = format(new Date(), "yyyy-MM-dd");
let darkModeObserver = null;
let stripe = null;
let elements = null;
let card = null;
let isStripeInitialized = false;

// Apply theme function
const applyTheme = (value) => {
  if (value) {
    document.documentElement.classList.add("dark");
    document.documentElement.style.colorScheme = "dark";
  } else {
    document.documentElement.classList.remove("dark");
    document.documentElement.style.colorScheme = "light";
  }
};

// Check dark mode from localStorage or system preference
const checkDarkMode = () => {
  const savedTheme = localStorage.getItem("darkMode");
  if (savedTheme !== null) {
    isDarkMode.value = savedTheme === "true";
  } else {
    isDarkMode.value = window.matchMedia("(prefers-color-scheme: dark)").matches;
  }
  applyTheme(isDarkMode.value);
};

// Setup dark mode watcher
const setupDarkModeWatcher = () => {
  const htmlElement = document.documentElement;
  darkModeObserver = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
      if (mutation.attributeName === "class") {
        const hasDarkClass = htmlElement.classList.contains("dark");
        if (hasDarkClass !== isDarkMode.value) {
          isDarkMode.value = hasDarkClass;
          localStorage.setItem("darkMode", String(hasDarkClass));
        }
      }
    });
  });
  darkModeObserver.observe(htmlElement, { attributes: true });
};

// Watch for changes to isDarkMode
watch(isDarkMode, (newVal) => {
  applyTheme(newVal);
});
 
const getCoverImage = () => { 
  if (vendor.value.cover_image && hasValidCoverImage.value) {
    const imagePath = `/storage/${vendor.value.cover_image.replace(/^\/storage\//, "")}`;
    return imagePath;
  }
   const fallbacks = [
        '/cover.png',
    ];
    return fallbacks[Math.floor(Math.random() * fallbacks.length)];
};

const handleImageError = () => {
  hasValidCoverImage.value = false;
};

const loadVendor = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(`/api/vendors/${route.params.slug}`);
    vendor.value = response.data.vendor;
    stripeKey.value = response.data.stripe_key;
    hasValidCoverImage.value = !!vendor.value.cover_image;
    console.log("Vendor loaded:", vendor.value);
  } catch (error) {
    console.error("Error loading vendor:", error);
    toast.error("Failed to load vendor details");
    router.push("/vendors");
  } finally {
    isLoading.value = false;
  }
};

const selectService = (service) => {
  selectedService.value = service;
  selectedStaff.value = null;
  selectedDate.value = "";
  selectedSlot.value = null;
  availableSlots.value = [];
  showPaymentForm.value = false;
  console.log("Service selected:", service.name);
};

const selectStaff = (staff) => {
  if (selectedStaff.value?.id === staff.id) {
    selectedStaff.value = null;
    console.log("Staff deselected");
  } else {
    selectedStaff.value = staff;
    console.log("Staff selected:", staff.name);
  }
  if (selectedDate.value) {
    loadAvailableSlots();
  }
};

const loadAvailableSlots = async () => {
  if (!selectedService.value || !selectedDate.value) {
    console.log("Missing service or date");
    return;
  }

  isLoadingSlots.value = true;
  try {
    console.log("Loading slots for:", {
      vendor_id: vendor.value.id,
      service_id: selectedService.value.id,
      date: selectedDate.value,
      staff_id: selectedStaff.value?.id,
    });

    const slots = await appointmentStore.fetchAvailableSlots({
      vendor_id: vendor.value.id,
      service_id: selectedService.value.id,
      date: selectedDate.value,
      staff_id: selectedStaff.value?.id,
    });
    availableSlots.value = slots;
    selectedSlot.value = null;
    showPaymentForm.value = false;
    console.log("Slots loaded:", slots.length);
  } catch (error) {
    console.error("Error loading slots:", error);
    toast.error("Failed to load available slots");
    availableSlots.value = [];
  } finally {
    isLoadingSlots.value = false;
  }
};

const selectSlot = (slot) => {
  selectedSlot.value = slot;
  showPaymentForm.value = false;
  console.log("Slot selected:", slot.formatted_time);
};

const togglePaymentForm = async () => {
  if (!authStore.isAuthenticated) {
    showLoginModal.value = true;
    return;
  }

  showPaymentForm.value = !showPaymentForm.value;

  if (showPaymentForm.value) {
    await nextTick();
    initializeStripe();
  }
};

const initializeStripe = () => {
  if (isStripeInitialized || !stripeKey.value) return;

  try {
    if (!window.Stripe) {
      toast.error("Payment system not loaded. Please refresh the page.");
      return;
    }

    stripe = Stripe(stripeKey.value);
    elements = stripe.elements({
      appearance: {
        theme: isDarkMode.value ? "night" : "stripe",
      },
    });

    card = elements.create("card", {
      style: {
        base: {
          color: isDarkMode.value ? "#fff" : "#32325d",
          fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
          fontSmoothing: "antialiased",
          fontSize: "16px",
          "::placeholder": {
            color: isDarkMode.value ? "#6b7280" : "#aab7c4",
          },
        },
        invalid: {
          color: "#fa755a",
          iconColor: "#fa755a",
        },
      },
    });

    const cardElement = document.getElementById("card-element");
    if (cardElement) {
      card.mount("#card-element");

      // Handle real-time validation errors
      card.on("change", function (event) {
        const displayError = document.getElementById("card-errors");
        if (event.error) {
          displayError.textContent = event.error.message;
        } else {
          displayError.textContent = "";
        }
      });

      isStripeInitialized = true;
    }
  } catch (error) {
    console.error("Error initializing Stripe:", error);
    toast.error("Failed to initialize payment system");
    showPaymentForm.value = false;
  }
};


const bookAppointment = async (paymentType = "later") => {
  if (!authStore.isAuthenticated) {
    showLoginModal.value = true;
    return;
  }

  try {
    let paymentMethodId = null;

    if (paymentType === "now") {
      const { paymentMethod, error } = await stripe.createPaymentMethod({
        type: "card",
        card: card,
      });

      if (error) {
        toast.error(error.message);
        return;
      }

      paymentMethodId = paymentMethod.id;
    }

    const appointmentData = {
      vendor_id: vendor.value.id,
      service_id: selectedService.value.id,
      staff_id: selectedStaff.value?.id,
      start_time: selectedSlot.value.start_time,
      payment_type: paymentType,
      payment_status: paymentType === "later" ? "pending" : "processing",
      paymentMethodId,
    };

    const response = await appointmentStore.createAppointment(appointmentData);

    if (paymentType === "later") {
      toast.success("Appointment booked successfully! You can pay later.");
    } else {
      toast.success("Payment successful! Appointment confirmed.");
    }

    router.push("/customer/appointments");

    return response;
  } catch (error) {
    console.error("Booking error:", error);
    toast.error(error.response?.data?.message || "Failed to book appointment");
    throw error;
  }
};

const processStripePayment = async () => {
  if (!stripe || !card) {
    toast.error("Payment system not initialized");
    return;
  }

  isProcessingPayment.value = true;

  try {
    // First, create the appointment with payment status 'processing'
    const appointmentResponse = await bookAppointment("now");

    if (!appointmentResponse) {
      throw new Error("Failed to create appointment");
    }

    // Create payment intent on the server
    const { data } = await axios.post("/api/create-payment-intent", {
      appointment_id: appointmentResponse.data.id,
      amount: Math.round(selectedService.value.price * 100), // Convert to cents
      currency: "usd",
    });

    // Confirm the payment with Stripe
    const { error, paymentIntent } = await stripe.confirmCardPayment(data.client_secret, {
      payment_method: {
        card: card,
        billing_details: {
          name: authStore.user?.name || "Customer",
          email: authStore.user?.email || "",
        },
      },
    });

    if (error) {
      console.error("Payment error:", error);
      toast.error(error.message || "Payment failed");

      // Update appointment status to failed
      await axios.patch(`/api/appointments/${appointmentResponse.data.id}`, {
        payment_status: "failed",
      });
    } else if (paymentIntent.status === "succeeded") {
      toast.success("Payment successful! Appointment confirmed.");

      // Update appointment with successful payment
      await axios.patch(`/api/appointments/${appointmentResponse.data.id}`, {
        payment_status: "paid",
        payment_intent_id: paymentIntent.id,
      });

      showPaymentForm.value = false;
      router.push("/customer/appointments");
    }
  } catch (error) {
    console.error("Payment processing error:", error);
    toast.error(error.response?.data?.message || "Payment processing failed");
  } finally {
    isProcessingPayment.value = false;
  }
};

const formatDate = (date) => {
  return format(new Date(date), "MMM dd, yyyy");
};

// Watch for changes to reset slots
watch([selectedService, selectedStaff], () => {
  if (selectedDate.value) {
    loadAvailableSlots();
  }
});

// Load Stripe.js dynamically
const loadStripeScript = () => {
  return new Promise((resolve, reject) => {
    if (window.Stripe) {
      resolve();
      return;
    }

    const script = document.createElement("script");
    script.src = "https://js.stripe.com/v3/";
    script.onload = resolve;
    script.onerror = reject;
    document.head.appendChild(script);
  });
};

onMounted(async () => {
  checkDarkMode();
  setupDarkModeWatcher();
  await loadVendor();

  // Load Stripe.js
  try {
    await loadStripeScript();
    console.log("Stripe.js loaded successfully");
  } catch (error) {
    console.error("Failed to load Stripe.js:", error);
  }

  // Listen for storage changes from other tabs
  window.addEventListener("storage", (e) => {
    if (e.key === "darkMode") {
      isDarkMode.value = e.newValue === "true";
      applyTheme(isDarkMode.value);
    }
  });

  // Watch for system theme changes
  const darkModeMediaQuery = window.matchMedia("(prefers-color-scheme: dark)");
  const handleSystemThemeChange = (e) => {
    if (localStorage.getItem("darkMode") === null) {
      isDarkMode.value = e.matches;
      applyTheme(isDarkMode.value);
    }
  };
  darkModeMediaQuery.addEventListener("change", handleSystemThemeChange);
});

onUnmounted(() => {
  if (darkModeObserver) {
    darkModeObserver.disconnect();
  }
  if (card) {
    card.destroy();
    isStripeInitialized = false;
  }
  window.removeEventListener("storage", () => {});
});
</script>

<style scoped>
.transition-all {
  transition: all 0.2s ease-in-out;
}

/* Loading animations */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%,
  100% {
    opacity: 0.5;
  }
  50% {
    opacity: 0.3;
  }
}

/* Stripe card element dark mode fix */
.StripeElement {
  background-color: transparent !important;
}

.StripeElement--focus {
  box-shadow: 0 0 0 2px #3b82f6 !important;
}
</style>
