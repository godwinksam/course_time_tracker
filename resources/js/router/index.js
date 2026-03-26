import { createRouter, createWebHistory } from 'vue-router';
import StudentView from '../views/StudentView.vue';
import AdminView from '../views/AdminView.vue';
import ReportView from '../views/ReportView.vue';

const routes = [
    {
        path: '/',
        name: 'student',
        component: StudentView
    },
    {
        path: '/admin',
        name: 'admin',
        component: AdminView
    },
    {
        path: '/report',
        name: 'report',
        component: ReportView
    }
];

const router = createRouter({
    history: createWebHistory('/course_hour_tracker/'),
    routes
});

export default router;
