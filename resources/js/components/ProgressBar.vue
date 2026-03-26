<template>
  <div class="h-2 bg-gray-200 rounded-full overflow-hidden transition-all duration-300">
    <div 
      class="h-full bg-blue-500 rounded-full transition-width duration-500" 
      :style="{ width: percent + '%' }"
    ></div>
  </div>
</template>

<script setup>
defineProps({
  percent: Number
});
</script>
