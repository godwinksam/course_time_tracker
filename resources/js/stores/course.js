import { defineStore } from 'pinia';
import axios from 'axios';

export const useCourseStore = defineStore('course', {
    state: () => ({
        sections: [],
        isLoading: false,
    }),
    getters: {
        totalMinutes: (state) => {
            return state.sections.reduce((total, section) => {
                return total + section.sub_sections.reduce((subTotal, sub) => subTotal + sub.duration_minutes, 0);
            }, 0);
        },
        completedMinutes: (state) => {
            return state.sections.reduce((total, section) => {
                return total + section.sub_sections.reduce((subTotal, sub) => {
                    return sub.is_completed ? subTotal + sub.duration_minutes : subTotal;
                }, 0);
            }, 0);
        },
        progressPercentage: (state) => {
            if (state.totalMinutes === 0) return 0;
            return Math.round((state.completedMinutes / state.totalMinutes) * 100);
        },
        lastStudied: (state) => {
            let lastSub = null;
            state.sections.forEach(section => {
                section.sub_sections.forEach(sub => {
                    if (sub.is_completed && (!lastSub || new Date(sub.completed_at) > new Date(lastSub.completed_at))) {
                        lastSub = sub;
                    }
                });
            });
            return lastSub;
        }
    },
    actions: {
        async fetchCourse() {
            this.isLoading = true;
            try {
                const response = await axios.get('/api/course');
                this.sections = response.data;
            } catch (error) {
                console.error('Error fetching course:', error);
            } finally {
                this.isLoading = false;
            }
        },
        async toggleSubSection(subSectionId, isCompleted) {
            // Optimistic update
            let targetSub = null;
            let targetSection = null;

            this.sections.forEach(section => {
                const sub = section.sub_sections.find(s => s.id === subSectionId);
                if (sub) {
                    targetSub = sub;
                    targetSection = section;
                }
            });

            if (targetSub) {
                targetSub.is_completed = isCompleted;
                targetSub.completed_at = isCompleted ? new Date().toISOString() : null;

                // Global Uncheck logic: uncheck everything with higher order
                if (!isCompleted) {
                    this.sections.forEach(sec => {
                        sec.sub_sections.forEach(sub => {
                            if (sub.id !== subSectionId && sub.order > targetSub.order && sub.is_completed) {
                                sub.is_completed = false;
                                sub.completed_at = null;
                            }
                        });
                    });
                }
            }

            // Sync with backend (fire and forget for responsiveness, or await if critical)
            try {
                await axios.put(`/api/sub-sections/${subSectionId}`, { is_completed: isCompleted });
                // Re-fetch to ensure sync (optional)
                // await this.fetchCourse(); 
            } catch (e) {
                console.error("Sync error", e);
                // Revert capabilities would go here
            }
        }
    }
});
