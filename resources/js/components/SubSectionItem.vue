<template>
  <div class="border-b transition-all duration-200 hover:bg-gray-50">
    <div class="flex items-start px-3 py-3 sm:px-4 hover:bg-indigo-50 transition-colors gap-3">
      <!-- Checkbox -->
      <input
        type="checkbox"
        :checked="subSection.is_completed"
        @change="handleChange"
        @click.stop
        class="h-5 w-5 mt-0.5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer flex-shrink-0"
      >

      <!-- Title + duration -->
      <div class="flex-1 min-w-0 cursor-pointer select-none">
        <div class="flex items-start flex-wrap gap-x-1">
          <span class="text-gray-900 font-medium leading-snug">{{ subSection.order }}.</span>
          <span class="text-gray-900 font-medium leading-snug break-words">{{ subSection.title }}</span>
        </div>
        <div class="text-gray-500 text-sm mt-1 flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ formattedDuration }}
        </div>
      </div>

      <!-- Completed badge -->
      <div v-if="subSection.completed_at" class="flex-shrink-0">
        <span class="text-[10px] sm:text-xs text-green-700 font-semibold bg-green-100 px-2 py-1 rounded-lg leading-tight block text-center whitespace-nowrap">
          ✓ {{ formatDate(subSection.completed_at) }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCourseStore } from '../stores/course';
import { formatDate, formatDuration } from '../utils/formatters';

const props = defineProps({ subSection: Object });
const store = useCourseStore();

const formattedDuration = computed(() => formatDuration(props.subSection.duration_minutes));

const handleChange = (e) => {
  store.toggleSubSection(props.subSection.id, e.target.checked);
};
const toggle = () => {};
</script>
