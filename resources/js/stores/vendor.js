// resources/js/stores/vendor.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useVendorStore = defineStore('vendor', {
    state: () => ({
        services: [],
        staff: [],
        schedules: [],
        dashboardStats: null,
        isLoading: false
    }),

    actions: {
        async getDashboardData() {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/vendor/dashboard');
                  return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async fetchServices(params = {}) {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/vendor/services', { params });
                this.services = response.data.data || response.data;
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async createService(data) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/vendor/services', data);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateService(id, data) {
            this.isLoading = true;
            try {
                const response = await axios.put(`/api/vendor/services/${id}`, data);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async deleteService(id) {
            this.isLoading = true;
            try {
                const response = await axios.delete(`/api/vendor/services/${id}`);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async fetchStaff(params = {}) {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/vendor/staff', { params });
                this.staff = response.data.data || response.data;
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async createStaff(data) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/vendor/staff', data);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateStaff(id, data) {
            this.isLoading = true;
            try {
                const response = await axios.put(`/api/vendor/staff/${id}`, data);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async deleteStaff(id) {
            this.isLoading = true;
            try {
                const response = await axios.delete(`/api/vendor/staff/${id}`);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateSchedule(schedules) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/vendor/schedule', { schedules });
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async getReports(filters = {}) {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/vendor/reports', { params: filters });
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        },
        

        async updateProfile(data) {
            this.isLoading = true;
            try {
                const response = await axios.put('/api/vendor/profile', data);
                return response.data;
            } catch (error) {
                throw error;
            } finally {
                this.isLoading = false;
            }
        }
    },

    getters: {
        activeServices: (state) => state.services.filter(s => s.is_active),
        activeStaff: (state) => state.staff.filter(s => s.is_active),
        totalServices: (state) => state.services.length,
        totalStaff: (state) => state.staff.length
    }
});