// resources/js/stores/appointment.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useAppointmentStore = defineStore('appointment', {
    state: () => ({
        appointments: [],
        currentAppointment: null,
        availableSlots: [],
        isLoading: false,
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 20,
            total: 0
        }
    }),

    getters: {
        upcomingAppointments: (state) => {
            return state.appointments.filter(a => 
                a.status === 'pending' || a.status === 'confirmed' || a.status === 'in_progress'
            );
        },
        completedAppointments: (state) => {
            return state.appointments.filter(a => a.status === 'completed');
        },
        cancelledAppointments: (state) => {
            return state.appointments.filter(a => a.status === 'cancelled');
        },
        getAppointmentById: (state) => (id) => {
            return state.appointments.find(a => a.id === id);
        }
    },

    actions: {
        async fetchAppointments(params = {}) {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/appointments', { params });
                this.appointments = response.data.data || response.data;
                if (response.data.pagination) {
                    this.pagination = response.data.pagination;
                }
                return response.data;
            } catch (error) {
                console.error('Failed to fetch appointments:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async fetchAvailableSlots(params) {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/appointments/available-slots', { params });
                this.availableSlots = response.data.slots;
                return response.data.slots;
            } catch (error) {
                console.error('Failed to fetch available slots:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async createAppointment(data) {
            this.isLoading = true;
            try {
                const response = await axios.post('/api/appointments', data);
                this.appointments.unshift(response.data.appointment);
                return response.data;
            } catch (error) {
                console.error('Failed to create appointment:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async getAppointment(id) {
            this.isLoading = true;
            try {
                const response = await axios.get(`/api/appointments/${id}`);
                this.currentAppointment = response.data;
                return response.data;
            } catch (error) {
                console.error('Failed to fetch appointment:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateAppointment(id, data) {
            this.isLoading = true;
            try {
                const response = await axios.put(`/api/appointments/${id}`, data);
                const index = this.appointments.findIndex(a => a.id === id);
                if (index !== -1) {
                    this.appointments[index] = response.data;
                }
                return response.data;
            } catch (error) {
                console.error('Failed to update appointment:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async cancelAppointment(id) {
            this.isLoading = true;
            try {
                const response = await axios.post(`/api/appointments/${id}/cancel`);
                const index = this.appointments.findIndex(a => a.id === id);
                if (index !== -1) {
                    this.appointments[index].status = 'cancelled';
                }
                return response.data;
            } catch (error) {
                console.error('Failed to cancel appointment:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async rescheduleAppointment(id, startTime) {
            this.isLoading = true;
            try {
                const response = await axios.post(`/api/appointments/${id}/reschedule`, { 
                    start_time: startTime 
                });
                const index = this.appointments.findIndex(a => a.id === id);
                if (index !== -1) {
                    this.appointments[index] = response.data.appointment;
                }
                return response.data;
            } catch (error) {
                console.error('Failed to reschedule appointment:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async updateAppointmentStatus(id, status) {
            this.isLoading = true;
            try {
                const response = await axios.put(`/api/appointments/${id}/status`, { status });
                const index = this.appointments.findIndex(a => a.id === id);
                if (index !== -1) {
                    this.appointments[index].status = status;
                }
                return response.data;
            } catch (error) {
                console.error('Failed to update appointment status:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async addVendorNotes(id, notes) {
            this.isLoading = true;
            try {
                const response = await axios.put(`/api/appointments/${id}/notes`, { vendor_notes: notes });
                const index = this.appointments.findIndex(a => a.id === id);
                if (index !== -1) {
                    this.appointments[index].vendor_notes = notes;
                }
                return response.data;
            } catch (error) {
                console.error('Failed to add notes:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        async sendReminder(id) {
            this.isLoading = true;
            try {
                const response = await axios.post(`/api/appointments/${id}/remind`);
                return response.data;
            } catch (error) {
                console.error('Failed to send reminder:', error);
                throw error;
            } finally {
                this.isLoading = false;
            }
        },

        clearAppointments() {
            this.appointments = [];
            this.currentAppointment = null;
            this.availableSlots = [];
            this.pagination = {
                current_page: 1,
                last_page: 1,
                per_page: 20,
                total: 0
            };
        }
    }
});