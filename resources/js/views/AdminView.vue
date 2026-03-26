<template>
  <div class="space-y-4">
    <!-- Page Header -->
    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
      <h2 class="text-lg sm:text-xl font-bold text-gray-800">⚙️ Manage Course</h2>
      <button @click="showAddSection = true" class="bg-indigo-600 text-white px-3 py-2 sm:px-4 rounded-lg hover:bg-indigo-700 text-sm font-medium">
        + Add Section
      </button>
    </div>

    <!-- Add Section Form -->
    <div v-if="showAddSection" class="bg-white p-4 border rounded-xl shadow">
      <div class="flex flex-col sm:flex-row gap-2 mb-3">
        <input v-model="newSectionTitle" placeholder="Section Title" class="border p-2 flex-grow rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
        <input v-model="newSectionOrder" type="number" placeholder="Order" class="border p-2 w-full sm:w-24 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
      </div>
      <div class="flex justify-end gap-2">
        <button @click="showAddSection = false" class="text-gray-500 hover:text-gray-700 px-3 py-1.5 rounded-lg text-sm">Cancel</button>
        <button @click="addSection" class="bg-green-600 text-white px-4 py-1.5 rounded-lg hover:bg-green-700 text-sm font-medium">Save</button>
      </div>
    </div>

    <!-- Sections List -->
    <div v-for="section in store.sections" :key="section.id" class="border rounded-xl bg-white overflow-hidden shadow-sm">
      <!-- Section Header -->
      <div class="flex justify-between items-start p-4 bg-gray-100 gap-3">
        <div v-if="editingSectionId === section.id" class="flex-1 flex gap-2 mr-2 flex-wrap">
           <input v-model="editSectionTitle" class="border p-2 flex-grow rounded-lg text-sm min-w-0" />
           <button @click="updateSection(section)" class="text-green-600 font-bold text-lg px-1">✓</button>
           <button @click="cancelEditSection" class="text-red-500 font-bold text-lg px-1">✕</button>
        </div>
        <div v-else class="flex-1 min-w-0">
            <h3 class="text-base sm:text-lg font-semibold truncate">{{ section.title }}</h3>
            <div class="text-xs text-gray-500 mt-0.5">Total: {{ totalSectionDuration(section) }}</div>
        </div>

        <div class="flex gap-3 flex-shrink-0 text-sm">
            <button v-if="editingSectionId !== section.id" @click="startEditSection(section)" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
            <button @click="deleteSection(section.id)" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
        </div>
      </div>

      <!-- Add SubSection -->
      <div class="p-3 sm:p-4 border-t bg-gray-50">
          <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_auto_auto] gap-2 items-center">
              <input v-model="newSubSectionTitles[section.id]" placeholder="Video Title" class="border p-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 w-full" />
              <input v-model="newSubSectionDurations[section.id]" type="number" placeholder="Mins" class="border p-2 w-full sm:w-20 rounded-lg text-sm" />
              <input v-model="newSubSectionOrders[section.id]" type="number" placeholder="Order" class="border p-2 w-full sm:w-20 rounded-lg text-sm" />
              <button @click="addSubSection(section.id)" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium w-full sm:w-auto">
                  Add Video
              </button>
          </div>
      </div>

      <!-- SubSections List -->
      <div class="divide-y">
        <div v-for="sub in section.sub_sections" :key="sub.id" class="px-3 py-3 sm:px-4 flex justify-between items-start hover:bg-gray-50 gap-3">
            <!-- Edit Mode -->
            <div v-if="editingSubSectionId === sub.id" class="flex-1 flex gap-2 flex-wrap mr-2">
                <input v-model="editSubSectionTitle" class="border p-1.5 flex-grow rounded-lg text-sm min-w-0" />
                <input v-model="editSubSectionDuration" type="number" class="border p-1.5 w-full sm:w-20 rounded-lg text-sm" />
                <input v-model="editSubSectionOrder" type="number" class="border p-1.5 w-full sm:w-16 rounded-lg text-sm" />
                <button @click="updateSubSection(sub)" class="text-green-600 font-bold text-lg">✓</button>
                <button @click="cancelEditSubSection" class="text-red-500 font-bold text-lg">✕</button>
            </div>
            <!-- Display Mode -->
            <div v-else class="flex-1 min-w-0">
                <div class="flex items-start gap-1 flex-wrap">
                    <span class="font-medium text-gray-900 leading-snug">{{ sub.order }}.</span>
                    <span class="font-medium text-gray-900 leading-snug break-words">{{ sub.title }}</span>
                </div>
                <div class="text-gray-500 text-sm mt-0.5 ml-4 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ formatDuration(sub.duration_minutes) }}
                </div>
            </div>

            <div class="flex gap-3 text-sm flex-shrink-0">
                <button v-if="editingSubSectionId !== sub.id" @click="startEditSubSection(sub)" class="text-blue-500 font-medium">Edit</button>
                <button @click="deleteSubSection(sub.id)" class="text-red-500 font-medium">Delete</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted, reactive, watch } from 'vue';
import { useCourseStore } from '../stores/course';
import { formatDuration } from '../utils/formatters';
import axios from 'axios';

const store = useCourseStore();
const showAddSection = ref(false);
const newSectionTitle = ref('');
const newSectionOrder = ref(1);

const editingSectionId = ref(null);
const editSectionTitle = ref('');

const newSubSectionTitles = reactive({});
const newSubSectionDurations = reactive({});
const newSubSectionOrders = reactive({});

const editingSubSectionId = ref(null);
const editSubSectionTitle = ref('');
const editSubSectionDuration = ref(0);
const editSubSectionOrder = ref(0);

// Update section order and sub-section orders
watch(() => store.sections, (sections) => {
    // Sections order
    if (sections.length >= 0) {
        const maxSectionOrder = sections.length > 0 
            ? Math.max(...sections.map(s => s.order)) 
            : 0;
        newSectionOrder.value = maxSectionOrder + 1;
    }

    // Sub-sections global order
    let globalMaxSubOrder = 0;
    sections.forEach(sec => {
        sec.sub_sections.forEach(sub => {
            if (sub.order > globalMaxSubOrder) globalMaxSubOrder = sub.order;
        });
    });

    sections.forEach(sec => {
        // We set it if it's not already being typed or if we just added/deleted something
        // To be safe, we'll update it if it's currently at the previous predicted value or if it's null
        newSubSectionOrders[sec.id] = globalMaxSubOrder + 1;
    });
}, { deep: true, immediate: true });


onMounted(() => {
    store.fetchCourse();
});

const addSection = async () => {
    if (!newSectionTitle.value) return;
    try {
        await axios.post('/api/sections', { 
            title: newSectionTitle.value,
            order: newSectionOrder.value 
        });
        newSectionTitle.value = '';
        showAddSection.value = false;
        await store.fetchCourse();
    } catch (e) {
        alert("Failed to add section: " + e.message);
    }
};

const deleteSection = async (id) => {
    if (!confirm('Delete this section and all its videos?')) return;
    try {
        await axios.delete(`/api/sections/${id}`);
        await store.fetchCourse();
    } catch (e) {
        alert("Failed to delete section");
    }
};

const startEditSection = (section) => {
    editingSectionId.value = section.id;
    editSectionTitle.value = section.title;
};

const cancelEditSection = () => {
    editingSectionId.value = null;
    editSectionTitle.value = '';
};

const updateSection = async (section) => {
    try {
        await axios.put(`/api/sections/${section.id}`, { title: editSectionTitle.value });
        editingSectionId.value = null;
        await store.fetchCourse();
    } catch (e) {
        alert("Failed to update");
    }
};

const addSubSection = async (sectionId) => {
    const title = newSubSectionTitles[sectionId];
    const duration = newSubSectionDurations[sectionId];
    const order = newSubSectionOrders[sectionId];
    if (!title || !duration || !order) return;

    try {
        await axios.post('/api/sub-sections', {
            section_id: sectionId,
            title: title,
            duration_minutes: duration,
            order: order
        });
        newSubSectionTitles[sectionId] = '';
        newSubSectionDurations[sectionId] = '';
        delete newSubSectionOrders[sectionId];
        await store.fetchCourse();
    } catch (e) {
        alert("Failed to add video");
    }
};

const deleteSubSection = async (id) => {
     if (!confirm('Delete this video?')) return;
     try {
         await axios.delete(`/api/sub-sections/${id}`);
         await store.fetchCourse();
     } catch (e) {
         alert("Failed to delete video");
     }
};

const startEditSubSection = (sub) => {
    editingSubSectionId.value = sub.id;
    editSubSectionTitle.value = sub.title;
    editSubSectionDuration.value = sub.duration_minutes;
    editSubSectionOrder.value = sub.order;
};

const cancelEditSubSection = () => {
    editingSubSectionId.value = null;
};

const updateSubSection = async (sub) => {
    try {
        await axios.put(`/api/sub-sections/${sub.id}`, {
            title: editSubSectionTitle.value,
            duration_minutes: editSubSectionDuration.value,
            order: editSubSectionOrder.value,
            section_id: sub.section_id // Backend validation usually requires checking existence, but update should be partial or safe
        });
        editingSubSectionId.value = null;
        await store.fetchCourse();
    } catch (e) {
        alert("Failed to update video");
    }
};

const totalSectionDuration = (section) => {
    const totalMinutes = section.sub_sections.reduce((sum, s) => sum + s.duration_minutes, 0);
    return formatDuration(totalMinutes);
};

</script>
