<!-- resources/js/views/Home.vue -->
<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 text-white overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse delay-1000"></div>
            </div>
            
            <div class="container mx-auto px-4 py-20 md:py-28 relative z-10">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur rounded-full px-4 py-2 mb-6 animate-fade-in">
                        <span class="text-sm font-semibold">✨ Trusted by 10,000+ customers</span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-slide-up">
                        Book Appointments with 
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                            Top Professionals
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 opacity-90 animate-slide-up-delayed">
                        Find and book appointments with doctors, salons, trainers, and more. 
                        Easy scheduling, secure payments.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4 animate-slide-up-delayed-2">
                        <router-link 
                            to="/register" 
                            class="group bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center gap-2"
                        >
                            Get Started
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </router-link>
                        <router-link 
                            to="/vendors" 
                            class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2"
                        >
                            Browse Vendors
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </router-link>
                    </div>
                </div>
            </div>
            
            <!-- Wave Divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg class="w-full h-16 text-gray-50" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="currentColor"></path>
                </svg>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-white py-12 border-b border-gray-100">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600 mb-2">500+</div>
                        <div class="text-gray-600">Verified Vendors</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600 mb-2">10K+</div>
                        <div class="text-gray-600">Happy Customers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600 mb-2">50K+</div>
                        <div class="text-gray-600">Appointments Booked</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600 mb-2">24/7</div>
                        <div class="text-gray-600">Customer Support</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="container mx-auto px-4 py-20">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Why Choose <span class="text-primary-600">Us?</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    We make booking appointments simple, fast, and reliable
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group text-center p-6 rounded-xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Easy Discovery</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Find the best service providers in your area with authentic reviews and ratings from real customers
                    </p>
                </div>
                
                <div class="group text-center p-6 rounded-xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Instant Booking</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Book appointments instantly with real-time availability and get immediate confirmation
                    </p>
                </div>
                
                <div class="group text-center p-6 rounded-xl hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 bg-white">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-primary-200 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900">Secure Payments</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pay online securely with multiple payment options or conveniently pay at the venue
                    </p>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div class="bg-gradient-to-b from-white to-gray-50 py-20">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Popular <span class="text-primary-600">Categories</span>
                    </h2>
                    <p class="text-xl text-gray-600">Browse through our most sought-after service categories</p>
                </div>
                
                <!-- Loading State -->
                <div v-if="isLoading" class="flex justify-center items-center py-20">
                    <div class="relative">
                        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-primary-600"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-8 h-8 bg-primary-100 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Categories Grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <router-link 
                        v-for="category in categories" 
                        :key="category.id" 
                        :to="`/vendors?category=${category.id}`" 
                        class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden"
                    >
                        <div class="p-6 text-center">
                            <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">
                                {{ category.icon || '📁' }}
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition">
                                {{ category.name }}
                            </h3>
                            <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>{{ category.services_count || category.services?.length || 0 }} services</span>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-1 w-0 group-hover:w-full transition-all duration-300"></div>
                    </router-link>
                </div>
                
                <!-- View All Categories Button -->
                <div class="text-center mt-12">
                    <router-link 
                        to="/vendors" 
                        class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:text-primary-700 transition-colors group"
                    >
                        View All Categories
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </router-link>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                    Join thousands of satisfied customers and book your next appointment today
                </p>
                <router-link 
                    to="/register" 
                    class="inline-flex items-center gap-2 bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                    Create Free Account
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const categories = ref([]);
const isLoading = ref(false);

const loadCategories = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/api/categories');
        // Take first 4 categories
        categories.value = response.data.slice(0, 4);
        
        // If services_count is missing, fetch counts separately
        for (const category of categories.value) {
            if (!category.services_count && category.id) {
                try {
                    const countResponse = await axios.get(`/api/categories/${category.id}/services-count`);
                    category.services_count = countResponse.data.count;
                } catch (err) {
                    category.services_count = 0;
                }
            }
        }
    } catch (error) {
        console.error('Failed to load categories:', error);
        // Fallback categories if API fails
        categories.value = [
            { id: 1, name: 'Medical & Healthcare', icon: '🏥', services_count: 124 },
            { id: 2, name: 'Beauty & Salons', icon: '💇', services_count: 89 },
            { id: 3, name: 'Spa & Wellness', icon: '🧖', services_count: 67 },
            { id: 4, name: 'Fitness & Training', icon: '💪', services_count: 45 }
        ];
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    loadCategories();
});
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.6s ease-out;
}

.animate-slide-up-delayed {
    animation: slide-up 0.6s ease-out 0.2s both;
}

.animate-slide-up-delayed-2 {
    animation: slide-up 0.6s ease-out 0.4s both;
}

.delay-1000 {
    animation-delay: 1s;
}

/* Smooth hover transitions */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

.group:hover .group-hover\:translate-x-1 {
    transform: translateX(0.25rem);
}

/* Custom animations */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .animate-slide-up-delayed-2 {
        animation-delay: 0.3s;
    }
}
</style>