<!-- resources/js/views/auth/Register.vue -->
<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">
          Create Your Account
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Already have an account?
          <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-500 transition-colors">
            Sign in
          </router-link>
        </p>
      </div>
 
      <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
          <form @submit.prevent="handleRegister" class="space-y-6">
            <!-- Common Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">
                  Full Name <span class="text-red-500">*</span>
                </label>
                <input
                  id="name"
                  type="text"
                  v-model="form.name"
                  required
                  placeholder="John Doe"
                  class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                />
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                  Email Address <span class="text-red-500">*</span>
                </label>
                <input
                  id="email"
                  type="email"
                  v-model="form.email"
                  required
                  placeholder="john@example.com"
                  class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                />
              </div>
            </div>

            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700">
                Phone Number <span class="text-red-500">*</span>
              </label>
              <input
                id="phone"
                type="tel"
                v-model="form.phone"
                required
                placeholder="+1 (555) 000-0000"
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                  Password <span class="text-red-500">*</span>
                </label>
                <input
                  id="password"
                  type="password"
                  v-model="form.password"
                  required
                  placeholder="••••••••"
                  minlength="8"
                  class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                />
              </div>

              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                  Confirm Password <span class="text-red-500">*</span>
                </label>
                <input
                  id="password_confirmation"
                  type="password"
                  v-model="form.password_confirmation"
                  required
                  placeholder="••••••••"
                  class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                />
              </div>
            </div>

            <!-- Vendor Specific Fields -->
            <transition name="slide-fade">
              <div v-if="form.role === 'vendor'" class="space-y-4 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                  <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  Business Information
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="business_name" class="block text-sm font-medium text-gray-700">
                      Business Name <span class="text-red-500">*</span>
                    </label>
                    <input
                      id="business_name"
                      type="text"
                      v-model="form.business_name"
                      required
                      placeholder="My Awesome Business"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                    />
                  </div>

                  <div>
                    <label for="phone_business" class="block text-sm font-medium text-gray-700">
                      Business Phone
                    </label>
                    <input
                      id="phone_business"
                      type="tel"
                      v-model="form.business_phone"
                      placeholder="+1 (555) 000-0000"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                    />
                  </div>
                </div>

                <div>
                  <label for="description" class="block text-sm font-medium text-gray-700">
                    Business Description <span class="text-red-500">*</span>
                  </label>
                  <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    required
                    placeholder="Tell customers about your business..."
                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none"
                  ></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="website" class="block text-sm font-medium text-gray-700">
                      Website
                    </label>
                    <input
                      id="website"
                      type="url"
                      v-model="form.website"
                      placeholder="https://example.com"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                    />
                  </div>

                  <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700">
                      Timezone
                    </label>
                    <select
                      id="timezone"
                      v-model="form.timezone"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">
                      Address
                    </label>
                    <input
                      id="address"
                      type="text"
                      v-model="form.address"
                      placeholder="123 Main St"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                    />
                  </div>

                  <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">
                      City
                    </label>
                    <input
                      id="city"
                      type="text"
                      v-model="form.city"
                      placeholder="New York"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                    />
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <div>
                    <label for="state" class="block text-sm font-medium text-gray-700">
                      State/Province
                    </label>
                    <input
                      id="state"
                      type="text"
                      v-model="form.state"
                      placeholder="NY"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                    />
                  </div>

                  <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">
                      Country
                    </label>
                    <select
                      id="country"
                      v-model="form.country"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
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
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">
                      Postal Code
                    </label>
                    <input
                      id="postal_code"
                      type="text"
                      v-model="form.postal_code"
                      placeholder="10001"
                      class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                    />
                  </div>
                </div>
              </div>
            </transition>

            <!-- Submit Button -->
            <button
              type="submit"
              :disabled="authStore.isLoading"
              class="w-full flex justify-center items-center gap-2 py-4 px-6 border border-transparent rounded-lg shadow-md text-base font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-[1.02]"
            >
              <svg v-if="authStore.isLoading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span v-else>{{ form.role === 'vendor' ? 'Create Business Account' : 'Create Account' }}</span>
            </button>

            <!-- Terms -->
            <p class="text-xs text-center text-gray-500">
              By signing up, you agree to our
              <a href="#" class="text-primary-600 hover:text-primary-500">Terms of Service</a>
              and
              <a href="#" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useAuthStore } from "../../stores/auth";
import { useRouter, useRoute } from "vue-router";
import { useToast } from "vue-toastification";

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();
const toast = useToast();

const form = ref({
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: "",
  role: "customer",
  // Vendor fields
  business_name: "",
  business_phone: "",
  description: "",
  website: "",
  timezone: "UTC",
  address: "",
  city: "",
  state: "",
  country: "",
  postal_code: "",
});

// Check URL params for role switching
onMounted(() => {
  if (route.query.role === 'vendor') {
    form.value.role = 'vendor';
  }
});

// Watch for role changes in URL
watch(() => route.query.role, (newRole) => {
  if (newRole === 'vendor') {
    form.value.role = 'vendor';
  }
});

const handleRegister = async () => {
  try {
    const payload = { ...form.value };
    
    // Clean vendor fields if customer
    if (payload.role === 'customer') {
      delete payload.business_name;
      delete payload.business_phone;
      delete payload.description;
      delete payload.website;
      delete payload.timezone;
      delete payload.address;
      delete payload.city;
      delete payload.state;
      delete payload.country;
      delete payload.postal_code;
    }

    const response = await authStore.register(payload);
    
    toast.success(`Welcome ${form.value.name}! Your account has been created.`);
    
    // Redirect based on role
    if (form.value.role === 'vendor') {
      router.push('/vendor/dashboard');
    } else {
      router.push('/dashboard');
    }
  } catch (error) {
    const errors = error.response?.data?.errors;
    if (errors) {
      Object.values(errors).forEach((err) => {
        toast.error(err[0]);
      });
    } else {
      toast.error(error.response?.data?.message || "Registration failed. Please try again.");
    }
  }
};
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.2s ease-in;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(-10px);
  opacity: 0;
}
</style>