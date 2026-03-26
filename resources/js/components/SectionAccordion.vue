<template>
  <div class="border rounded-xl mb-4 overflow-hidden shadow-sm transition-all duration-300">
    <!-- Section Header Button -->
    <button
      @click="isOpen = !isOpen"
      class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 hover:bg-gray-200 focus:outline-none transition-colors"
      :class="{ 'bg-blue-50 text-blue-800': isOpen }"
    >
      <div class="flex items-center gap-3 min-w-0">
        <svg
          class="w-5 h-5 flex-shrink-0 transition-transform duration-200 transform"
          :class="{ 'rotate-180': isOpen }"
          xmlns="http://www.w3.org/2000/svg"
          fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
        <div class="text-left min-w-0">
          <span class="text-base sm:text-lg font-semibold block truncate">{{ section.title }}</span>
          <span class="text-xs text-gray-500 font-normal">Total: {{ totalSectionDuration }}</span>
        </div>
      </div>
      <div class="text-xs sm:text-sm text-gray-600 flex-shrink-0 ml-3 text-right">
        <span class="whitespace-nowrap">{{ completedCount }}/{{ section.sub_sections.length }}</span>
        <span class="hidden sm:inline"> Completed</span>
      </div>
    </button>

    <!-- Sub-sections panel -->
    <div
      v-show="isOpen"
      class="bg-white border-t border-gray-200 transition-all duration-300 ease-in-out"
    >
      <div v-if="section.sub_sections.length === 0" class="p-4 text-gray-500 italic text-sm">
        No videos in this section.
      </div>
      <SubSectionItem
        v-for="sub in section.sub_sections"
        :key="sub.id"
        :subSection="sub"
        class="border-b last:border-b-0"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useCourseStore } from '../stores/course';
import SubSectionItem from './SubSectionItem.vue';
import { formatDuration } from '../utils/formatters';

const props = defineProps({
  section: Object
});

const store = useCourseStore();
const isOpen = ref(false);

const completedCount = computed(() => {
  return props.section.sub_sections.filter(s => s.is_completed).length;
});

const totalSectionDuration = computed(() => {
    const totalMinutes = props.section.sub_sections.reduce((sum, s) => sum + s.duration_minutes, 0);
    return formatDuration(totalMinutes);
});


const checkActive = () => {
    if (store.lastStudied && store.lastStudied.section_id === props.section.id) {
        isOpen.value = true;
    } else if (!store.lastStudied && props.section.order === 1) {
        isOpen.value = true;
    }
};

onMounted(() => { checkActive(); });

watch(() => store.lastStudied, (newVal) => {
    if (newVal && newVal.section_id === props.section.id) {
       // isOpen.value = true;
    }
});
</script>
