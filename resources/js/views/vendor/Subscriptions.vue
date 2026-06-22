<!-- resources/js/views/vendor/Subscriptions.vue -->
<template>
  <div class="space-y-6 pb-8">
    <!-- Full Page Spinner -->
    <div v-if="loading.initial" class="flex items-center justify-center min-h-[60vh]">
      <div class="text-center">
        <div
          class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"
        ></div>
      </div>
    </div>

    <!-- Main Content - Only shown when not loading -->
    <template v-else>
      <!-- Page Header -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Subscription Plans</h1>
          <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Manage your plan and billing</p>
        </div>
        <button v-if="stripeAccountStatus !== 'active'"
          @click="goToStripeOnboarding"
          class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg transition-all duration-200 transform hover:scale-105"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 10V3L4 14h7v7l9-11h-7z"
            ></path>
          </svg>
          Stripe Onboarding
        </button>
      </div>

      <!-- Current Subscription Card -->
      <div
        v-if="currentSubscription"
        class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-lg overflow-hidden"
      >
        <div class="p-6 md:p-8">
          <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
            <div class="flex-1 w-full">
              <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4"
              >
                <div>
                  <h2 class="text-xl md:text-2xl font-bold text-white">
                    Current Plan: {{ currentSubscription.plan_name }}
                  </h2>
                  <p class="text-white/80 text-sm mt-1 capitalize">
                    {{ currentSubscription.interval }} plan - ${{
                      currentSubscription.price
                    }}/{{ currentSubscription.interval }}

                    <span
                      class="text-sm font-semibold text-white bg-green-500 px-3 py-1 rounded-full"
                    >
                      {{ currentSubscription.status }}
                    </span>
                  </p>
                </div>

                <div
                  class="flex flex-col sm:flex-row sm:items-center sm:justify-between item-center gap-3 mb-4"
                >
                  <div
                    v-if="currentSubscription.end_date"
                    class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2"
                  >
                    <p class="text-sm font-semibold text-white text-center">
                      Renews On: {{ formatDate(currentSubscription.end_date) }}
                    </p>
                  </div>
                  <button
                    v-if="currentSubscription.status === 'active'"
                    @click="cancelCurrentSubscription"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-200 text-sm font-semibold shadow-md"
                  >
                    Cancel Subscription
                  </button>
                </div>
              </div>

              <!-- Usage Metrics -->
              <div
                v-if="currentSubscription.usage"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4"
              >
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                  <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-white/90">Services</p>
                    <svg
                      class="w-4 h-4 text-white/70"
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
                  </div>
                  <p class="text-xl font-bold text-white">
                    {{ currentSubscription.usage.services.used }} /
                    <span v-if="currentSubscription.usage.services.limit === -1">∞</span
                    ><span v-else>{{ currentSubscription.usage.services.limit }}</span>
                  </p>
                  <div class="mt-2 bg-white/30 rounded-full h-1.5 overflow-hidden">
                    <div
                      class="bg-white rounded-full h-1.5 transition-all duration-500"
                      :style="{
                        width: currentSubscription.usage.services.percentage + '%',
                      }"
                    ></div>
                  </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                  <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-white/90">Staff</p>
                    <svg
                      class="w-4 h-4 text-white/70"
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
                  <p class="text-xl font-bold text-white">
                    {{ currentSubscription.usage.staff.used }} /
                    <span v-if="currentSubscription.usage.staff.limit === -1">∞</span
                    ><span v-else>{{ currentSubscription.usage.staff.limit }}</span>
                  </p>
                  <div class="mt-2 bg-white/30 rounded-full h-1.5 overflow-hidden">
                    <div
                      class="bg-white rounded-full h-1.5 transition-all duration-500"
                      :style="{ width: currentSubscription.usage.staff.percentage + '%' }"
                    ></div>
                  </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                  <div class="flex items-center justify-between mb-2">
                    <p class="text-sm text-white/90">Monthly Appointments</p>
                    <svg
                      class="w-4 h-4 text-white/70"
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
                  <p class="text-xl font-bold text-white">
                    {{ currentSubscription.usage.appointments.used }} /
                    <span v-if="currentSubscription.usage.appointments.limit === -1"
                      >∞</span
                    ><span v-else>{{
                      currentSubscription.usage.appointments.limit
                    }}</span>
                  </p>
                  <div class="mt-2 bg-white/30 rounded-full h-1.5 overflow-hidden">
                    <div
                      class="bg-white rounded-full h-1.5 transition-all duration-500"
                      :style="{
                        width: currentSubscription.usage.appointments.percentage + '%',
                      }"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- No Subscription Warning -->
      <div
        v-else
        :class="[
          'border-l-4 rounded-xl p-4 shadow-sm',
          isDarkMode ? 'bg-yellow-900/20 border-yellow-600' : 'bg-gradient-to-r from-yellow-50 to-orange-50 border-yellow-400'
        ]"
      >
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
          <div class="flex-shrink-0">
            <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <div class="flex-1">
            <p class="text-sm font-medium" :class="isDarkMode ? 'text-yellow-400' : 'text-yellow-800'">No Active Subscription</p>
            <p class="text-xs" :class="isDarkMode ? 'text-yellow-300' : 'text-yellow-700'">
              Please choose a plan below to get started with your vendor account.
            </p>
          </div>
        </div>
      </div>

      <!-- Available Plans - Only shown when no active subscription -->
      <div v-if="currentSubscription?.status !== 'active'">
        <div
          class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6"
        >
          <h2 class="text-xl md:text-2xl font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Available Plans</h2>
          <div class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">* Prices are in USD</div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="plan in plans"
            :key="plan.id"
            :class="[
              'rounded-2xl shadow-lg border overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1',
              isDarkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'
            ]"
          >
            <div class="p-6">
              <!-- Plan Header -->
              <div class="text-center mb-6">
                <div
                  class="w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-3"
                  :class="isDarkMode ? 'bg-gray-700' : 'bg-gradient-to-br from-primary-100 to-primary-200'"
                >
                  <svg
                    class="w-8 h-8 text-primary-600"
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
                <h3 class="text-xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ plan.name }}</h3>
                <p class="text-xs mt-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  {{ plan.description || "Perfect for growing business" }}
                </p>
              </div>

              <!-- Pricing Options -->
              <div class="space-y-3 mb-6">
                <div
                  v-for="price in plan.prices"
                  :key="price.id"
                  class="flex justify-between items-center p-3 rounded-xl cursor-pointer transition-all duration-200"
                  :class="
                    selectedPrice && selectedPrice.id === price.id
                      ? isDarkMode
                        ? 'bg-gray-700 border-2 border-primary-500 shadow-sm'
                        : 'bg-primary-50 border-2 border-primary-500 shadow-sm'
                      : isDarkMode
                        ? 'bg-gray-700 border-2 border-transparent hover:bg-gray-600'
                        : 'bg-gray-50 border-2 border-transparent hover:bg-gray-100'
                  "
                  @click="selectPrice(price, plan)"
                >
                  <div>
                    <p class="font-semibold capitalize" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      {{ price.interval }}
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="text-2xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                      ${{ formatPrice(price.price) }}
                    </p>
                    <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">/{{ price.interval }}</p>
                  </div>
                </div>
              </div>

              <!-- Features/Limits -->
              <div class="space-y-3 border-t pt-4" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-600" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Services</span>
                  <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                    plan.services_limit === -1 ? "Unlimited" : plan.services_limit
                  }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-600" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Staff Members</span>
                  <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                    plan.staff_limit === -1 ? "Unlimited" : plan.staff_limit
                  }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-600" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Monthly Appointments</span>
                  <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                    plan.appointments_limit === -1
                      ? "Unlimited"
                      : plan.appointments_limit.toLocaleString()
                  }}</span>
                </div>
              </div>

              <!-- Subscribe Button -->
              <button
                @click="openPaymentModal(plan)"
                :disabled="currentSubscription?.status === 'active'"
                class="w-full mt-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white py-2.5 rounded-xl hover:from-primary-700 hover:to-primary-800 disabled:from-gray-300 disabled:to-gray-400 disabled:cursor-not-allowed transition-all duration-200 font-semibold shadow-md"
              >
                {{
                  currentSubscription?.status === "active"
                    ? "Already Subscribed"
                    : "Subscribe Now"
                }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Invoice History Section -->
      <div :class="[
        'rounded-2xl shadow-lg overflow-hidden',
        isDarkMode ? 'bg-gray-800' : 'bg-white'
      ]">
        <div class="px-4 sm:px-6 py-4 border-b" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
              <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Invoice History</h3>
              <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">View and download your past invoices</p>
            </div>
            <div class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-400'">
              Total: {{ pagination.total }} invoices
            </div>
          </div>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
          <div v-for="invoice in invoices" :key="invoice.id" class="p-4 transition" :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'">
            <div class="flex justify-between items-start mb-2">
              <div>
                <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">#{{ invoice.invoice_number }}</p>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  {{ formatDate(invoice.billing_period_start) }} -
                  {{ formatDate(invoice.billing_period_end) }}
                </p>
              </div>
              <span
                :class="getPaymentStatusClass(invoice.status)"
                class="px-2 py-1 text-xs font-semibold rounded-full"
              >
                {{ invoice.status }}
              </span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
              <div>
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Plan</p>
                <p class="font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{ invoice.plan?.name || "N/A" }}</p>
              </div>
              <div class="text-right">
                <p class="text-xs" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">Amount</p>
                <p class="font-bold text-primary-600">
                  ${{ formatPrice(invoice.total) }}
                </p>
              </div>
            </div>
            <div class="flex gap-2">
              <button
                @click="viewInvoice(invoice.id)"
                class="flex-1 text-primary-600 hover:text-primary-700 text-sm font-medium py-1"
              >
                View Details
              </button>
              <button
                @click="downloadInvoice(invoice.id)"
                class="flex-1 text-primary-600 hover:text-primary-700 text-sm font-medium py-1"
              >
                Download
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
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  Invoice #
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  Date
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  Billing Period
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  Plan
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  Amount
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                  :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'"
                >
                  Status
                </th>
                <th
                  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
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
                v-for="invoice in invoices"
                :key="invoice.id"
                class="transition-colors"
                :class="isDarkMode ? 'hover:bg-gray-700' : 'hover:bg-gray-50'"
              >
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                  {{ invoice.invoice_number }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  {{ formatDate(invoice.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                  {{ formatDate(invoice.billing_period_start) }} -
                  {{ formatDate(invoice.billing_period_end) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-500'">
                  {{ invoice.plan?.name || "N/A" }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                >
                  ${{ formatPrice(invoice.total) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getPaymentStatusClass(invoice.status)"
                    class="px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ invoice.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                 <button
    @click="viewInvoice(invoice.id)"
    class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-800 font-medium transition"
>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
    View
</button>
<button
    @click="downloadInvoice(invoice.id)"
    class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-800 font-medium transition"
>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
    </svg>
    Download
</button>
                </td>
              </tr>
              <tr v-if="invoices.length === 0">
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
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                  </svg>
                  No invoices found
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div
          v-if="pagination.last_page > 1"
          class="px-4 sm:px-6 py-4 border-t"
          :class="isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-gray-50'"
        >
          <div class="flex justify-center gap-2">
            <button
              @click="loadInvoices(pagination.current_page - 1)"
              :disabled="!pagination.prev_page_url"
              class="px-3 py-1 rounded-lg text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
              :class="isDarkMode ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-600 hover:bg-gray-200'"
            >
              Previous
            </button>
            <span class="px-3 py-1 text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
              Page {{ pagination.current_page }} of {{ pagination.last_page }}
            </span>
            <button
              @click="loadInvoices(pagination.current_page + 1)"
              :disabled="!pagination.next_page_url"
              class="px-3 py-1 rounded-lg text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed"
              :class="isDarkMode ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-600 hover:bg-gray-200'"
            >
              Next
            </button>
          </div>
        </div>
      </div>

      <!-- Payment Modal -->
      <div
        v-if="showPaymentModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="closePaymentModal"
      >
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
          <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
          <div
            :class="[
              'rounded-2xl shadow-2xl max-w-md w-full z-50 transform transition-all',
              isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]"
          >
            <div
              class="px-6 py-4 border-b flex justify-between items-center"
              :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'"
            >
              <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Complete Subscription</h3>
              <button
                @click="closePaymentModal"
                class="transition"
                :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'"
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
                    d="M6 18L18 6M6 6l12 12"
                  ></path>
                </svg>
              </button>
            </div>
            <div class="px-6 py-4">
              <div class="mb-6 space-y-3 rounded-xl p-4" :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
                <div class="flex justify-between">
                  <span class="text-gray-600" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Plan:</span>
                  <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                    selectedPlan?.name
                  }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Billing Cycle:</span>
                  <span class="font-semibold capitalize" :class="isDarkMode ? 'text-white' : 'text-gray-900'">{{
                    selectedPrice?.interval
                  }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-600" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Subtotal:</span>
                  <span class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'"
                    >${{ formatPrice(selectedPrice?.price) }}</span
                  >
                </div>
              
                <div class="border-t pt-3 mt-2" :class="isDarkMode ? 'border-gray-600' : 'border-gray-200'">
                  <div class="flex justify-between">
                    <span class="text-lg font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Total:</span>
                    <span class="text-lg font-bold text-primary-600"
                      >${{ formatPrice(selectedPrice?.price * 1.1) }}</span
                    >
                  </div>
                </div>
              </div>

              <!-- Stripe Card Element -->
              <div class="mb-4">
                <label class="block text-sm font-medium mb-2" :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'"
                  >Card Details</label
                >
                <div
                  id="card-element"
                  :class="[
                    'border rounded-xl p-3 shadow-sm focus:ring-2 focus:ring-primary-500',
                    isDarkMode ? 'bg-gray-700 border-gray-600 text-white' : 'bg-white border-gray-300'
                  ]"
                ></div>
                <div
                  id="card-errors"
                  class="text-red-600 text-xs mt-2"
                  role="alert"
                ></div>
              </div>

              <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button
                  @click="closePaymentModal"
                  :class="[
                    'px-4 py-2 border rounded-lg transition',
                    isDarkMode ? 'border-gray-600 text-gray-300 hover:bg-gray-700' : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                  ]"
                >
                  Cancel
                </button>
                <button
                  @click="processPayment"
                  :disabled="processing"
                  class="px-4 py-2 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed transition font-semibold"
                >
                  <span v-if="processing" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                      <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                        fill="none"
                      ></circle>
                      <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                      ></path>
                    </svg>
                    Processing...
                  </span>
                  <span v-else>Pay ${{ formatPrice(selectedPrice?.price * 1.1) }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Invoice Modal -->
      <div
        v-if="showInvoiceModal"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="closeInvoiceModal"
      >
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
          <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
          <div
            :class="[
              'rounded-2xl shadow-2xl max-w-3xl w-full z-50 max-h-[90vh] overflow-y-auto',
              isDarkMode ? 'bg-gray-800' : 'bg-white'
            ]"
          >
            <div
              class="px-6 py-4 border-b sticky top-0 flex justify-between items-center"
              :class="isDarkMode ? 'border-gray-700 bg-gray-800' : 'border-gray-200 bg-white'"
            >
              <h3 class="text-lg font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">Invoice Details</h3>
              <button
                @click="closeInvoiceModal"
                class="transition"
                :class="isDarkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'"
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
                    d="M6 18L18 6M6 6l12 12"
                  ></path>
                </svg>
              </button>
            </div>
            <div class="px-6 py-6" v-if="selectedInvoice">
              <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold" :class="isDarkMode ? 'text-white' : 'text-gray-800'">INVOICE</h1>
                <p class="mt-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">{{ selectedInvoice.invoice_number }}</p>
              </div>

              <div class="flex flex-col sm:flex-row justify-between gap-6 mb-8">
                <div>
                  <p class="text-sm mb-1" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Bill To:</p>
                  <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ vendorStore.vendor?.business_name || "Business Name" }}
                  </p>
                  <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">
                    {{ vendorStore.vendor?.email || "vendor@example.com" }}
                  </p>
                </div>
                <div class="text-left sm:text-right">
                  <p class="text-sm" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Invoice Date:</p>
                  <p class="font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                    {{ formatDate(selectedInvoice.created_at) }}
                  </p>
                  <p class="text-sm mt-2" :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'">Billing Period:</p>
                  <p class="text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'">
                    {{ formatDate(selectedInvoice.billing_period_start) }} -
                    {{ formatDate(selectedInvoice.billing_period_end) }}
                  </p>
                </div>
              </div>

              <div class="overflow-x-auto">
                <table class="min-w-full divide-y mb-6" :class="isDarkMode ? 'divide-gray-700' : 'divide-gray-200'">
                  <thead :class="isDarkMode ? 'bg-gray-700' : 'bg-gray-50'">
                    <tr>
                      <th class="px-4 py-3 text-left text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        Description
                      </th>
                      <th class="px-4 py-3 text-right text-xs font-medium" :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'">
                        Amount
                      </th>
                    </tr>
                  </thead>
                  <tbody class="divide-y" :class="[
                    isDarkMode ? 'bg-gray-800 divide-gray-700' : 'bg-white divide-gray-200'
                  ]">
                    <tr v-for="(item, index) in selectedInvoice.items" :key="index">
                      <td class="px-4 py-3 text-sm" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'">
                        {{ item.description }}
                      </td>
                      <td class="px-4 py-3 text-sm text-right" :class="isDarkMode ? 'text-gray-300' : 'text-gray-900'">
                        ${{ formatPrice(item.total) }}
                      </td>
                    </tr>
                  </tbody>
                  <tfoot class="border-t-2" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
                    <tr>
                      <td class="px-4 py-3 text-right font-semibold" :class="isDarkMode ? 'text-white' : 'text-gray-900'">
                        Total:
                      </td>
                      <td class="px-4 py-3 text-right font-bold text-primary-600 text-lg">
                        ${{ formatPrice(selectedInvoice.total) }}
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="px-6 py-4 border-t flex justify-end" :class="isDarkMode ? 'border-gray-700' : 'border-gray-200'">
              <button
                @click="closeInvoiceModal"
                :class="[
                  'px-4 py-2 rounded-lg transition',
                  isDarkMode ? 'bg-gray-700 text-gray-300 hover:bg-gray-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                ]"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { useVendorStore } from "../../stores/vendor";

const toast = useToast();
const vendorStore = useVendorStore();

// Loading states
const loading = ref({
  initial: true, // Main loading state
});

// Data properties
const plans = ref([]);
const currentSubscription = ref(null);
const invoices = ref([]);
const stripeKey = ref("");
const stripeAccountStatus = ref("");
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
  from: 0,
  to: 0,
  prev_page_url: null,
  next_page_url: null,
});

// Modal states
const showPaymentModal = ref(false);
const showInvoiceModal = ref(false);
const selectedPlan = ref(null);
const selectedPrice = ref(null);
const selectedInvoice = ref(null);
const processing = ref(false);
const isDarkMode = ref(false);
let darkModeObserver = null;

let stripe = null;
let cardElement = null;

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

// Stripe Onboarding
const goToStripeOnboarding = async () => {
  try {
    const response = await axios.get("/api/vendor/onboarding");
    window.location.href = response.data.account_link_url;
  } catch (error) {
    console.error(error);
    toast.error("Failed to start onboarding");
  }
};

// Load Stripe
const loadStripe = () => {
  return new Promise((resolve) => {
    if (!stripeKey.value) {
      console.error("Stripe key not available");
      resolve();
      return;
    }

    if (window.Stripe) {
      stripe = window.Stripe(stripeKey.value);
      resolve();
      return;
    }

    const script = document.createElement("script");
    script.src = "https://js.stripe.com/v3/";
    script.onload = () => {
      stripe = window.Stripe(stripeKey.value);
      resolve();
    };
    script.onerror = () => {
      toast.error("Failed to load Stripe");
      resolve();
    };
    document.head.appendChild(script);
  });
};

// Initialize Stripe Elements
const initStripeElements = () => {
  if (!stripe) {
    console.error("Stripe not initialized");
    return;
  }

  const elements = stripe.elements();
  const style = {
    base: {
      color: isDarkMode.value ? "#e5e7eb" : "#32325d",
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
  };

  cardElement = elements.create("card", { style: style });
  cardElement.mount("#card-element");

  cardElement.addEventListener("change", (event) => {
    const displayError = document.getElementById("card-errors");
    if (displayError) {
      displayError.textContent = event.error ? event.error.message : "";
    }
  });
};

// Load available plans
const loadPlans = async () => {
  try {
    const response = await axios.get("/api/vendor/subscriptions/plans");
    stripeKey.value = response.data.stripe_key;
    stripeAccountStatus.value = response.data.stripe_account_status;
    
    plans.value = response.data.plans;

    if (
      plans.value.length > 0 &&
      plans.value[0].prices.length > 0 &&
      !selectedPrice.value
    ) {
      selectedPrice.value = plans.value[0].prices[0];
      selectedPlan.value = plans.value[0];
    }

    await loadStripe();
  } catch (error) {
    console.error("Failed to load plans:", error);
    toast.error("Failed to load plans");
  }
};

// Load current subscription
const loadCurrentSubscription = async () => {
  try {
    const response = await axios.get("/api/vendor/subscriptions/current");
    currentSubscription.value = response.data.current_subscription;
  } catch (error) {
    console.error("Failed to load subscription:", error);
  }
};

// Load invoice history
const loadInvoices = async (page = 1) => {
  try {
    const response = await axios.get(`/api/vendor/subscriptions/invoices?page=${page}`);
    invoices.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      per_page: response.data.per_page,
      total: response.data.total,
      from: response.data.from,
      to: response.data.to,
      prev_page_url: response.data.prev_page_url,
      next_page_url: response.data.next_page_url,
    };
  } catch (error) {
    console.error("Failed to load invoices:", error);
    toast.error("Failed to load invoice history");
  }
};

// Select a price
const selectPrice = (price, plan) => {
  selectedPrice.value = price;
  selectedPlan.value = plan;
};

// Open payment modal
const openPaymentModal = async (plan) => {
  if (currentSubscription.value && currentSubscription.value.status === "active") {
    toast.warning("You already have an active subscription");
    return;
  }

  let priceToUse = selectedPrice.value;
  if (!priceToUse || priceToUse.plan_id !== plan.id) {
    priceToUse = plan.prices[0];
    selectedPrice.value = priceToUse;
  }

  selectedPlan.value = plan;
  showPaymentModal.value = true;

  setTimeout(() => {
    if (document.getElementById("card-element")) {
      initStripeElements();
    }
  }, 100);
};

// Close payment modal
const closePaymentModal = () => {
  showPaymentModal.value = false;
  selectedPlan.value = null;
  if (cardElement) {
    cardElement.unmount();
    cardElement = null;
  }
};

// Process payment with Stripe
const processPayment = async () => {
  if (!stripe || !cardElement) {
    toast.error("Payment system not initialized. Please refresh and try again.");
    return;
  }

  processing.value = true;

  try {
    const { paymentMethod, error: paymentMethodError } = await stripe.createPaymentMethod(
      {
        type: "card",
        card: cardElement,
      }
    );

    if (paymentMethodError) {
      toast.error(paymentMethodError.message);
      processing.value = false;
      return;
    }

    const response = await axios.post("/api/vendor/subscriptions/subscribe", {
      plan_id: selectedPlan.value.id,
      price_id: selectedPrice.value.id,
      payment_method_id: paymentMethod.id,
    });

    if (response.data.requires_action && response.data.payment_intent_client_secret) {
      const { error: confirmError } = await stripe.confirmCardPayment(
        response.data.payment_intent_client_secret
      );

      if (confirmError) {
        toast.error(confirmError.message);
        processing.value = false;
        return;
      }
    }

    if (response.data.success) {
      toast.success(response.data.message);
      closePaymentModal();
      await loadCurrentSubscription();
      await loadInvoices();
    } else {
      toast.error(response.data.message || "Payment failed");
    }
  } catch (error) {
    console.error("Payment failed:", error);
    const errorMessage =
      error.response?.data?.message || "Payment failed. Please try again.";
    toast.error(errorMessage);
  } finally {
    processing.value = false;
  }
};

// Cancel current subscription
const cancelCurrentSubscription = async () => {
  if (
    confirm(
      "Are you sure you want to cancel your subscription? This will take effect at the end of your billing period."
    )
  ) {
    try {
      const response = await axios.post("/api/vendor/subscriptions/cancel");
      toast.success(response.data.message);
      await loadCurrentSubscription();
    } catch (error) {
      console.error("Failed to cancel subscription:", error);
      toast.error("Failed to cancel subscription");
    }
  }
};

// View invoice details
const viewInvoice = async (invoiceId) => {
  try {
    const response = await axios.get(`/api/vendor/subscriptions/invoices/${invoiceId}`);
    selectedInvoice.value = response.data;
    showInvoiceModal.value = true;
  } catch (error) {
    console.error("Failed to load invoice:", error);
    toast.error("Failed to load invoice details");
  }
};

// Close invoice modal
const closeInvoiceModal = () => {
  showInvoiceModal.value = false;
  selectedInvoice.value = null;
};

// Download invoice
const downloadInvoice = async (invoiceId) => {
  if (!invoiceId) return;

  try {
    const response = await axios.get(
      `/api/vendor/subscriptions/invoices/${invoiceId}/download`
    );
    if (response.data.download_url) {
      window.open(response.data.download_url, "_blank");
    } else {
      toast.info("PDF download will be available soon");
    }
  } catch (error) {
    console.error("Failed to download invoice:", error);
    toast.error("Failed to download invoice");
  }
};

// Helper functions
const formatPrice = (price) => {
  if (!price && price !== 0) return "0.00";
  return Number(price).toFixed(2);
};

const formatDate = (date) => {
  if (!date) return "N/A";
  return new Date(date).toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
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

// Initialize component - Load everything with a single spinner
onMounted(async () => {
  checkDarkMode();
  setupDarkModeWatcher();
  
  try {
    // Load all data
    await Promise.all([loadCurrentSubscription(), loadPlans(), loadInvoices()]);
  } catch (error) {
    console.error("Failed to load data:", error);
    toast.error("Failed to load subscription data");
  } finally {
    // Hide spinner once everything is loaded
    loading.value.initial = false;
  }

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
/* Spinner animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>