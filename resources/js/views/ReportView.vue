<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">📊 Progress Report</h2>
        <p class="text-sm text-gray-500 mt-1">View completed study time within a date range</p>
      </div>
      <div class="flex flex-col sm:flex-row items-center gap-3">
        <!-- From Date -->
        <div class="flex flex-col">
          <label class="text-xs font-semibold text-gray-500 mb-1">From</label>
          <input
            v-model="fromDate"
            type="date"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
          />
        </div>
        <!-- To Date -->
        <div class="flex flex-col">
          <label class="text-xs font-semibold text-gray-500 mb-1">To</label>
          <input
            v-model="toDate"
            type="date"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
          />
        </div>
        <button
          @click="fetchReport"
          :disabled="loading"
          class="mt-4 sm:mt-5 bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition text-sm font-semibold disabled:opacity-50"
        >
          {{ loading ? 'Loading…' : 'Generate Report' }}
        </button>
      </div>
    </div>

    <!-- Error -->
    <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
      {{ error }}
    </div>

    <!-- No data -->
    <div v-if="!loading && fetched && dailyData.length === 0" class="text-center py-16 text-gray-400">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <p class="text-lg font-semibold">No completed videos found in this date range.</p>
    </div>

    <!-- Summary Cards -->
    <div v-if="dailyData.length > 0" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="bg-indigo-100 p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <div class="text-xs text-gray-500 uppercase font-semibold">Total Time</div>
          <div class="text-xl font-bold text-gray-800">{{ formatDuration(totalMinutes) }}</div>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="bg-green-100 p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806A3.42 3.42 0 0119.5 8.13a3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.13 3.5..." />
          </svg>
        </div>
        <div>
          <div class="text-xs text-gray-500 uppercase font-semibold">Videos Completed</div>
          <div class="text-xl font-bold text-gray-800">{{ totalItems }}</div>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="bg-blue-100 p-3 rounded-lg">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <div>
          <div class="text-xs text-gray-500 uppercase font-semibold">Active Days</div>
          <div class="text-xl font-bold text-gray-800">{{ dailyData.length }}</div>
        </div>
      </div>
    </div>

    <!-- Chart -->
    <div v-if="dailyData.length > 0" class="bg-white rounded-xl shadow p-6">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">Daily Study Time</h3>
      <div class="relative" style="height: 320px;">
        <canvas ref="chartCanvas"></canvas>
      </div>
    </div>

    <!-- Detail Table -->
    <div v-if="completedItems.length > 0" class="bg-white rounded-xl shadow overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-700">Completed Videos Detail</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
            <tr>
              <th class="px-6 py-3 text-left">#</th>
              <th class="px-6 py-3 text-left">Section</th>
              <th class="px-6 py-3 text-left">Video Title</th>
              <th class="px-6 py-3 text-left">Duration</th>
              <th class="px-6 py-3 text-left">Completed At</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr
              v-for="(item, idx) in completedItems"
              :key="item.id"
              class="hover:bg-indigo-50 transition-colors"
            >
              <td class="px-6 py-3 text-gray-400 font-mono">{{ idx + 1 }}</td>
              <td class="px-6 py-3 font-medium text-indigo-700">{{ item.section?.title ?? '—' }}</td>
              <td class="px-6 py-3 text-gray-800">{{ item.order }}. {{ item.title }}</td>
              <td class="px-6 py-3 text-gray-600">
                <div class="flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ formatDuration(item.duration_minutes) }}
                </div>
              </td>
              <td class="px-6 py-3 text-gray-500">{{ formatDate(item.completed_at) }}</td>
            </tr>
          </tbody>
          <!-- Per-day subtotals -->
          <tbody class="border-t-2 border-gray-200">
            <tr
              v-for="day in dailyData"
              :key="'sub-' + day.date"
              class="bg-indigo-50"
            >
              <td colspan="3" class="px-6 py-2 text-xs font-bold text-indigo-700 uppercase">
                {{ formatDisplayDate(day.date) }} — Subtotal
              </td>
              <td class="px-6 py-2 text-xs font-bold text-indigo-700">{{ formatDuration(day.total_minutes) }}</td>
              <td class="px-6 py-2 text-xs text-indigo-500">{{ day.total_items }} video{{ day.total_items == 1 ? '' : 's' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from 'vue';
import axios from 'axios';
import { formatDate, formatDuration } from '../utils/formatters';
import {
  Chart,
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
  Title,
} from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title);

// ── Helpers ──────────────────────────────────────────────────────────
const formatDisplayDate = (dateStr) => {
  // dateStr from DB is "YYYY-MM-DD"
  const [y, m, d] = dateStr.split('-');
  return `${d}-${m}-${y}`;
};

// Default date range: last 7 days
const today = new Date();
const weekAgo = new Date();
weekAgo.setDate(today.getDate() - 6);
const toISODate = (d) => d.toISOString().split('T')[0];

const fromDate = ref(toISODate(weekAgo));
const toDate   = ref(toISODate(today));

const loading       = ref(false);
const fetched       = ref(false);
const error         = ref('');
const dailyData     = ref([]);
const completedItems = ref([]);

// ── Chart ─────────────────────────────────────────────────────────────
const chartCanvas = ref(null);
let chartInstance = null;

const buildChart = () => {
  if (chartInstance) {
    chartInstance.destroy();
    chartInstance = null;
  }

  const labels = dailyData.value.map(d => formatDisplayDate(d.date));
  const dataMinutes = dailyData.value.map(d => Number(d.total_minutes));
  // Convert to hours (decimal) for a nicer Y-axis
  const dataHours = dataMinutes.map(m => parseFloat((m / 60).toFixed(2)));

  chartInstance = new Chart(chartCanvas.value, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Study Time (hours)',
        data: dataHours,
        backgroundColor: 'rgba(99, 102, 241, 0.75)',
        borderColor: 'rgba(99, 102, 241, 1)',
        borderWidth: 2,
        borderRadius: 6,
        borderSkipped: false,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              const minutes = dataMinutes[ctx.dataIndex];
              return ` ${formatDuration(minutes)}`;
            },
          },
        },
        title: {
          display: false,
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { size: 12 } },
          title: { display: true, text: 'Date', font: { size: 13 } },
        },
        y: {
          beginAtZero: true,
          grid: { color: 'rgba(0,0,0,0.05)' },
          title: { display: true, text: 'Time (hours)', font: { size: 13 } },
          ticks: {
            font: { size: 12 },
            callback: (val) => `${val} hr`,
          },
        },
      },
    },
  });
};

// ── Fetch ─────────────────────────────────────────────────────────────
const fetchReport = async () => {
  if (!fromDate.value || !toDate.value) {
    error.value = 'Please select both a From and To date.';
    return;
  }
  error.value = '';
  loading.value = true;
  fetched.value = false;
  dailyData.value = [];
  completedItems.value = [];

  try {
    const params = { from: fromDate.value, to: toDate.value };
    const [daily, items] = await Promise.all([
      axios.get('/api/report/daily-progress', { params }),
      axios.get('/api/report/completed-items',  { params }),
    ]);
    dailyData.value     = daily.data;
    completedItems.value = items.data;
    fetched.value = true;

    if (dailyData.value.length > 0) {
      await nextTick();
      buildChart();
    }
  } catch (e) {
    error.value = 'Failed to fetch report. Please check your date range and try again.';
    console.error(e);
  } finally {
    loading.value = false;
  }
};

// ── Computed Totals ───────────────────────────────────────────────────
const totalMinutes = computed(() =>
  dailyData.value.reduce((sum, d) => sum + Number(d.total_minutes), 0)
);

const totalItems = computed(() =>
  dailyData.value.reduce((sum, d) => sum + Number(d.total_items), 0)
);

// Auto-load on mount with default range
onMounted(() => fetchReport());
</script>
