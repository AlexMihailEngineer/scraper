<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    extractedData: Object // This contains our paginated WebExtractedData
});
</script>

<template>
  <MainLayout>
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Web Scraped Data</h1>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
      <ul class="divide-y divide-gray-200">
        <li v-for="item in extractedData.data" :key="item.id" class="p-6">
          <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-indigo-600 truncate">
              ID: {{ item.id }} | Hash: {{ item.data_hash.substring(0, 8) }}
            </p>
            <div class="ml-2 flex-shrink-0 flex">
              <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                Extracted {{ new Date(item.created_at).toLocaleDateString() }}
              </p>
            </div>
          </div>
          <div class="mt-4 bg-gray-50 p-4 rounded text-xs font-mono text-gray-700 overflow-x-auto">
            <pre>{{ JSON.stringify(item.payload, null, 2) }}</pre>
          </div>
        </li>
      </ul>
    </div>
  </MainLayout>
</template>
