<!-- resources/js/views/vendor/settings.vue -->
<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Business settings</h1>

    <!-- settings Overview -->
    <div :class="[
      'rounded-lg shadow-sm overflow-hidden',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <!-- Cover Image -->
      <div class="relative h-48 bg-gradient-to-r from-primary-500 to-primary-700">
        <img
          v-if="form.cover_image"
          :src="form.cover_image"
          class="w-full h-48 object-cover"
          alt="Cover"
        />
        <div
          class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity"
        >
          <label
            class="cursor-pointer bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-800 px-4 py-2 rounded-lg transition flex items-center space-x-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
              ></path>
            </svg>
            <span>Change Cover</span>
            <input
              type="file"
              class="hidden"
              accept="image/*"
              @change="uploadImage('cover_image', $event)"
            />
          </label>
        </div>
      </div>

      <!-- settings Header -->
      <div class="px-6 pb-6 relative">
        <div class="flex flex-col md:flex-row items-start md:items-end -mt-16 mb-4">
          <!-- Logo -->
          <div class="relative">
            <div
              class="w-32 h-32 rounded-full border-4 bg-gray-100 overflow-hidden shadow-lg"
              :class="isDarkMode ? 'border-gray-800' : 'border-white'"
            >
              <img
                v-if="form.logo"
                :src="form.logo"
                class="w-full h-full object-cover"
                alt="Logo"
              />
              <div
                v-else
                class="w-full h-full flex items-center justify-center"
                :class="isDarkMode ? 'bg-gray-700' : 'bg-primary-100'"
              >
                <span class="text-4xl font-bold" :class="isDarkMode ? 'text-primary-400' : 'text-primary-600'">
                  {{ form.business_name?.charAt(0) || "B" }}
                </span>
              </div>
            </div>
            <label
              class="absolute bottom-0 right-0 cursor-pointer rounded-full p-1 shadow-md transition"
              :class="isDarkMode ? 'bg-gray-700 hover:bg-gray-600' : 'bg-white hover:bg-gray-50'"
            >
              <svg
                class="w-5 h-5"
                :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                ></path>
              </svg>
              <input
                type="file"
                class="hidden"
                accept="image/*"
                @change="uploadImage('logo', $event)"
              />
            </label>
          </div>

          <!-- Business Info -->
          <div class="flex-1 md:ml-6 mt-4 md:mt-0">
            <div class="flex flex-wrap items-start justify-between">
              <div>
                <h2 class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ form.business_name }}</h2>
                <div class="flex items-center mt-1 space-x-2">
                  <span :class="statusClasses" class="px-2 py-0.5 text-xs rounded-full">
                    {{ vendor.status }}
                  </span>
                  <span
                    v-if="vendor.is_verified"
                    class="flex items-center text-green-600 text-sm"
                  >
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"
                      ></path>
                    </svg>
                    Verified
                  </span>
                </div>
              </div>
              <div class="flex items-center space-x-2">
                <div class="flex items-center">
                  <svg
                    class="w-5 h-5 text-yellow-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                    ></path>
                  </svg>
                  <span class="ml-1 text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                    vendor.rating
                  }}</span>
                  <span class="text-sm ml-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                    >({{ vendor.total_reviews }} reviews)</span
                  >
                </div>
              </div>
            </div>
            <p class="mt-2" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">{{ form.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
      <nav class="flex space-x-8">
        <button
          @click="activeTab = 'info'"
          :class="
            activeTab === 'info'
              ? 'border-primary-500 text-primary-600 dark:text-primary-400'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
          "
          class="py-4 px-1 border-b-2 font-medium text-sm transition"
        >
          Business Information
        </button>

        <button
          @click="activeTab = 'social'"
          :class="
            activeTab === 'social'
              ? 'border-primary-500 text-primary-600 dark:text-primary-400'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
          "
          class="py-4 px-1 border-b-2 font-medium text-sm transition"
        >
          Social Links
        </button>
        <button
          @click="activeTab = 'settings'"
          :class="
            activeTab === 'settings'
              ? 'border-primary-500 text-primary-600 dark:text-primary-400'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300'
          "
          class="py-4 px-1 border-b-2 font-medium text-sm transition"
        >
          Settings
        </button>
      </nav>
    </div>

    <!-- Business Information Tab -->
    <div v-if="activeTab === 'info'" :class="[
      'rounded-lg shadow-sm p-6',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <form @submit.prevent="savesettings">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
              >Business Name *</label
            >
            <input
              type="text"
              v-model="form.business_name"
              required
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Slug</label>
            <input
              type="text"
              v-model="form.slug"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 cursor-not-allowed',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-400' : 'bg-gray-50 border-gray-300 text-gray-500'
              ]"
              readonly
            />
            <p class="text-xs mt-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
              Unique URL identifier (auto-generated)
            </p>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
              >Description</label
            >
            <textarea
              v-model="form.description"
              rows="4"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
              placeholder="Tell customers about your business..."
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Phone</label>
            <input
              type="tel"
              v-model="form.phone"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
              placeholder="+1 234 567 8900"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Website</label>
            <input
              type="url"
              v-model="form.website"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
              placeholder="https://example.com"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Address</label>
            <input
              type="text"
              v-model="form.address"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
              ]"
              placeholder="Street address"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">City</label>
            <input
              type="text"
              v-model="form.city"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
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
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Country</label>
            <select
              v-model="form.country"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            >
              <option value="">Select Country</option>
              <option value="US">United States</option>
              <option value="UK">United Kingdom</option>
              <option value="CA">Canada</option>
              <option value="AU">Australia</option>
              <option value="IN">India</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
              >Postal Code</label
            >
            <input
              type="text"
              v-model="form.postal_code"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Time Zone</label>
            <select
              v-model="form.timezone"
              :class="[
                'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200' : 'bg-white border-gray-300 text-gray-900'
              ]"
            >
              <option value="UTC">UTC</option>
              <option value="America/New_York">Eastern Time (ET)</option>
              <option value="America/Chicago">Central Time (CT)</option>
              <option value="America/Denver">Mountain Time (MT)</option>
              <option value="America/Los_Angeles">Pacific Time (PT)</option>
            </select>
          </div>
        </div>
        <div class="flex justify-end mt-6">
          <button
            type="submit"
            class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition"
          >
           {{ loading ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Social Links Tab -->
    <div v-if="activeTab === 'social'" :class="[
      'rounded-lg shadow-sm p-6',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <form @submit.prevent="savesettings">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Facebook</label>
            <div class="flex items-center">
              <span
                class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 bg-gray-50"
                :class="isDarkMode ? 'border-gray-600 bg-gray-700 text-gray-400' : 'border-gray-300 bg-gray-50 text-gray-500'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                  />
                </svg>
              </span>
              <input
                type="url"
                v-model="form.social_links.facebook"
                placeholder="https://facebook.com/your-business"
                :class="[
                  'flex-1 px-3 py-2 border rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                  isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                ]"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
              >Twitter / X</label
            >
            <div class="flex items-center">
              <span
                class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 bg-gray-50"
                :class="isDarkMode ? 'border-gray-600 bg-gray-700 text-gray-400' : 'border-gray-300 bg-gray-50 text-gray-500'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"
                  />
                </svg>
              </span>
              <input
                type="url"
                v-model="form.social_links.twitter"
                placeholder="https://twitter.com/your-business"
                :class="[
                  'flex-1 px-3 py-2 border rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                  isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                ]"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Instagram</label>
            <div class="flex items-center">
              <span
                class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 bg-gray-50"
                :class="isDarkMode ? 'border-gray-600 bg-gray-700 text-gray-400' : 'border-gray-300 bg-gray-50 text-gray-500'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"
                  />
                </svg>
              </span>
              <input
                type="url"
                v-model="form.social_links.instagram"
                placeholder="https://instagram.com/your-business"
                :class="[
                  'flex-1 px-3 py-2 border rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                  isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                ]"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium mb-1" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">LinkedIn</label>
            <div class="flex items-center">
              <span
                class="inline-flex items-center px-3 py-2 rounded-l-lg border border-r-0 bg-gray-50"
                :class="isDarkMode ? 'border-gray-600 bg-gray-700 text-gray-400' : 'border-gray-300 bg-gray-50 text-gray-500'"
              >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451c.979 0 1.771-.773 1.771-1.729V1.729C24 .774 23.203 0 22.225 0z"
                  />
                </svg>
              </span>
              <input
                type="url"
                v-model="form.social_links.linkedin"
                placeholder="https://linkedin.com/company/your-business"
                :class="[
                  'flex-1 px-3 py-2 border rounded-r-lg focus:outline-none focus:ring-2 focus:ring-primary-500',
                  isDarkMode ? 'bg-gray-700 border-gray-600 text-gray-200 placeholder-gray-400' : 'bg-white border-gray-300 text-gray-900 placeholder-gray-500'
                ]"
              />
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-6">
          <button
            type="submit"
            class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition"
          >
            Save Social Links
          </button>
        </div>
      </form>
    </div>

    <!-- Settings Tab -->
    <div v-if="activeTab === 'settings'" :class="[
      'rounded-lg shadow-sm p-6',
      isDarkMode ? 'bg-gray-800' : 'bg-white'
    ]">
      <form @submit.prevent="savesettings">
        <div class="space-y-6">
          <div>
            <h3 class="text-lg font-medium mb-4" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Notification Settings</h3>
            <div class="space-y-3">
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.settings.email_notifications"
                  class="mr-3"
                />
                <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Email notifications for new bookings</span>
              </label>
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.settings.sms_notifications"
                  class="mr-3"
                />
                <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">SMS notifications for new bookings</span>
              </label>
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="form.settings.reminder_emails"
                  class="mr-3"
                />
                <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'">Send reminder emails to customers</span>
              </label>
            </div>
          </div>

          <div class="border-t pt-6" :class="isDarkMode ? 'border-gray-700' : ''">
            <h3 class="text-lg font-medium mb-4" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Earnings Summary</h3>
            <div :class="[
              'rounded-lg p-4',
              isDarkMode ? 'bg-gray-700' : 'bg-gray-50'
            ]">
              <div class="flex justify-between items-center mb-2">
                <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Total Earnings</span>
                <span class="text-2xl font-bold text-primary-600"
                  >${{ vendor.earnings?.toLocaleString() || 0 }}</span
                >
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'">Available for Payout</span>
                <span class="text-lg font-semibold text-green-600"
                  >${{ vendor.earnings?.toLocaleString() || 0 }}</span
                >
              </div>
              <button
                type="button"
                class="mt-4 w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
              >
                Request Payout
              </button>
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-6">
          <button
            type="submit"
            class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition"
          >
            Save Settings
          </button>
        </div>
      </form>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const toast = useToast();

// State
const vendor = ref({});
const activeTab = ref('info');
const loading = ref(false);
const isDarkMode = ref(false);
let darkModeObserver = null;

// Form data - single source of truth
const form = ref({
  business_name: '',
  slug: '',
  description: '',
  phone: '',
  website: '',
  address: '',
  city: '',
  state: '',
  country: '',
  postal_code: '',
  timezone: 'UTC',
  logo: '',
  cover_image: '',
  social_links: {
    facebook: '',
    twitter: '',
    instagram: '',
    linkedin: ''
  },
  settings: {
    email_notifications: true,
    sms_notifications: false,
    reminder_emails: true
  }
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
const statusClasses = computed(() => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
    active: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
    suspended: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
    rejected: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
  };
  return classes[vendor.value.status] || classes.pending;
});

// Methods
const loadsettings = async () => {
  try {
    loading.value = true;
    const response = await axios.get('/api/vendor/settings');
    vendor.value = response.data.data || response.data;
    
    // Initialize form with vendor data
    form.value = {
      business_name: vendor.value.business_name || '',
      slug: vendor.value.slug || '',
      description: vendor.value.description || '',
      phone: vendor.value.phone || '',
      website: vendor.value.website || '',
      address: vendor.value.address || '',
      city: vendor.value.city || '',
      state: vendor.value.state || '',
      country: vendor.value.country || '',
      postal_code: vendor.value.postal_code || '',
      timezone: vendor.value.timezone || 'UTC',
      logo: vendor.value.logo || '',
      cover_image: vendor.value.cover_image || '',
      social_links: vendor.value.social_links && !Array.isArray(vendor.value.social_links) 
        ? vendor.value.social_links 
        : {
            facebook: '',
            twitter: '',
            instagram: '',
            linkedin: ''
          },
      settings: vendor.value.settings || {
            email_notifications: true,
            sms_notifications: false,
            reminder_emails: true
          }
    };
    
    // Ensure social_links has all required fields
    if (!form.value.social_links.facebook) form.value.social_links.facebook = '';
    if (!form.value.social_links.twitter) form.value.social_links.twitter = '';
    if (!form.value.social_links.instagram) form.value.social_links.instagram = '';
    if (!form.value.social_links.linkedin) form.value.social_links.linkedin = '';
    
  } catch (error) {
    console.error('Error loading settings:', error);
    toast.error('Failed to load settings');
  } finally {
    loading.value = false;
  }
};

const savesettings = async () => {
  try {
    loading.value = true;
    
    // Create a copy of form data to send
    const dataToSend = { ...form.value };
    
    // Remove fields that shouldn't be sent in this request
    delete dataToSend.logo;
    delete dataToSend.cover_image;
    delete dataToSend.slug;
    
    // Ensure social_links is an object with proper structure
    if (dataToSend.social_links && !Array.isArray(dataToSend.social_links)) {
      // Make sure all fields exist
      dataToSend.social_links = {
        facebook: dataToSend.social_links.facebook || '',
        twitter: dataToSend.social_links.twitter || '',
        instagram: dataToSend.social_links.instagram || '',
        linkedin: dataToSend.social_links.linkedin || ''
      };
    } else {
      // If it's an array or empty, set default object
      dataToSend.social_links = {
        facebook: '',
        twitter: '',
        instagram: '',
        linkedin: ''
      };
    }
    
    // Debug: Log what's being sent
    console.log('Saving Settings with data:', JSON.stringify(dataToSend, null, 2));
    
    const response = await axios.put('/api/vendor/settings', dataToSend);
    console.log('Save response:', response.data);
    
    toast.success('Settings updated successfully');
    await loadsettings(); // Reload to get updated data
  } catch (error) {
    console.error('Error saving settings:', error);
    console.error('Error response:', error.response?.data);
    toast.error(error.response?.data?.message || 'Failed to save settings');
  } finally {
    loading.value = false;
  }
};

const uploadImage = async (field, event) => {
  const file = event.target.files[0];
  if (!file) return;
  
  // Validate file type
  const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
  if (!validTypes.includes(file.type)) {
    toast.error('Please upload a valid image file (JPEG, PNG, JPG, or GIF)');
    event.target.value = '';
    return;
  }
  
  // Validate file size (2MB for logo, 5MB for cover)
  const maxSize = field === 'logo' ? 2 * 1024 * 1024 : 5 * 1024 * 1024;
  if (file.size > maxSize) {
    toast.error(`File size should be less than ${field === 'logo' ? '2MB' : '5MB'}`);
    event.target.value = '';
    return;
  }
  
  const formData = new FormData();
  formData.append(field, file);
  
  try {
    loading.value = true;
    const response = await axios.post('/api/vendor/settings/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    
    if (response.data.data[field]) {
      form.value[field] = response.data.data[field];
      toast.success(`${field === 'logo' ? 'Logo' : 'Cover image'} updated successfully`);
    }
  } catch (error) {
    console.error('Error uploading image:', error);
    toast.error(error.response?.data?.message || 'Failed to upload image');
  } finally {
    loading.value = false;
    event.target.value = '';
  }
};

onMounted(() => {
  checkDarkMode();
  setupDarkModeWatcher();
  loadsettings();

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
/* Custom styles */
</style>