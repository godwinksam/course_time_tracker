<template>
  <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md mb-6">
    <!-- Top row -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
      <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Course Progress</h1>
        <p class="text-gray-500 text-sm mt-1 leading-relaxed">
          Last Studied:
          <span v-if="store.lastStudied" class="font-medium text-blue-600 break-words">
            {{ store.lastStudied.title }}
            <span class="text-gray-400 text-xs">({{ formatDate(store.lastStudied.completed_at) }})</span>
          </span>
          <span v-else class="text-gray-400">No progress yet</span>
        </p>
      </div>
      <div class="flex items-center gap-4 sm:flex-col sm:text-right sm:items-end">
        <div class="text-3xl sm:text-4xl font-bold text-blue-600">{{ store.progressPercentage }}%</div>
        <div class="text-sm text-gray-500">
          {{ formatDuration(store.completedMinutes) }} / {{ formatDuration(store.totalMinutes) }}
        </div>
      </div>
    </div>
    <!-- Progress bar -->
    <div class="w-full bg-gray-200 rounded-full h-3">
      <div
        class="bg-blue-600 h-3 rounded-full transition-all duration-700 ease-out"
        :style="{ width: store.progressPercentage + '%' }"
      ></div>
    </div>
  </div>
</template>

<script setup>
import { useCourseStore } from '../stores/course';
import { formatDate, formatDuration } from '../utils/formatters';
const store = useCourseStore();
</script>
