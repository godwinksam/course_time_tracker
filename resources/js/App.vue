<template>
  <div class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white shadow sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
          <!-- Brand -->
          <div class="flex-shrink-0 flex items-center">
            <span class="font-bold text-xl text-indigo-600">📚 Course Tracker</span>
          </div>

          <!-- Desktop Nav -->
          <div class="hidden sm:flex sm:space-x-6">
            <router-link to="/"       class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors" active-class="border-indigo-500 text-gray-900">Course</router-link>
            <router-link to="/admin"  class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors" active-class="border-indigo-500 text-gray-900">Manage</router-link>
            <router-link to="/report" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors" active-class="border-indigo-500 text-gray-900">Report</router-link>
          </div>

          <!-- Mobile Hamburger -->
          <button
            @click="mobileOpen = !mobileOpen"
            class="sm:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none"
          >
            <svg v-if="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-show="mobileOpen" class="sm:hidden bg-white border-t border-gray-200 px-4 pt-2 pb-4 space-y-1">
        <router-link @click="mobileOpen = false" to="/"       class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors" active-class="bg-indigo-50 text-indigo-700 font-semibold">📖 Course</router-link>
        <router-link @click="mobileOpen = false" to="/admin"  class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors" active-class="bg-indigo-50 text-indigo-700 font-semibold">⚙️ Manage</router-link>
        <router-link @click="mobileOpen = false" to="/report" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors" active-class="bg-indigo-50 text-indigo-700 font-semibold">📊 Report</router-link>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="py-6 px-4 sm:py-10 sm:px-6">
      <main>
        <div class="max-w-7xl mx-auto">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const mobileOpen = ref(false);
</script>
