<template>
  <div class="space-y-4">
    <CourseHeader />
    <div class="mt-8 space-y-4">
      <SectionAccordion 
        v-for="section in store.sections" 
        :key="section.id"
        :section="section" 
      />
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useCourseStore } from '../stores/course';
import CourseHeader from '../components/CourseHeader.vue';
import SectionAccordion from '../components/SectionAccordion.vue';

const store = useCourseStore();

onMounted(() => {
  store.fetchCourse();
});
</script>
