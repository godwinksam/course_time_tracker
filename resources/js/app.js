require('./bootstrap');

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import CourseHeader from './components/CourseHeader.vue';
import SectionAccordion from './components/SectionAccordion.vue';
import SubSectionItem from './components/SubSectionItem.vue';
import ProgressBar from './components/ProgressBar.vue';

const app = createApp(App);
const pinia = createPinia();

import router from './router';

app.use(pinia);
app.use(router);
app.component('course-header', CourseHeader);
app.component('section-accordion', SectionAccordion);
app.component('sub-section-item', SubSectionItem);
app.component('progress-bar', ProgressBar);


app.mount('#app');
