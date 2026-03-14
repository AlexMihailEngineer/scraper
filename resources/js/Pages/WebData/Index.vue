<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    extractedData: Object
});

// Helper to truncate text for the preview
const truncate = (text, length = 100) => {
    if (!text) return '';
    const str = typeof text === 'string' ? text : JSON.stringify(text);
    return str.length > length ? str.substring(0, length) + '...' : str;
};
</script>

<template>
  <MainLayout>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Web Scrape Executions</h1>
      <span class="text-sm font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
        {{ extractedData.total }} Total Records
      </span>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">ID</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Payload Preview</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-48">Scraped At</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          <tr v-for="item in extractedData.data" :key="item.id" class="hover:bg-gray-50 transition-colors align-top">

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-bold text-indigo-600">#{{ item.id }}</div>
              <div class="text-[10px] font-mono text-gray-400 mt-1 uppercase">{{ item.data_hash.substring(0, 8) }}</div>
            </td>

            <td class="px-6 py-4">
              <div class="flex flex-col gap-2">
                <div class="text-sm text-gray-900 font-medium">
                  {{ item.payload.title || item.payload.url || 'Scraped Content' }}
                </div>

                <div class="relative group">
                  <pre class="bg-gray-50 text-gray-600 p-3 rounded-lg text-xs font-mono overflow-x-auto max-h-40 border border-gray-100 group-hover:border-indigo-200 transition-colors">
{{ JSON.stringify(item.payload, null, 2) }}
                  </pre>
                </div>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              <div class="flex flex-col">
                <span>{{ new Date(item.created_at).toLocaleDateString() }}</span>
                <span class="text-xs text-gray-400">{{ new Date(item.created_at).toLocaleTimeString() }}</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="extractedData.data.length === 0" class="p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No data found</h3>
        <p class="mt-1 text-sm text-gray-500">Run a scraper to see extracted items here.</p>
      </div>
    </div>
  </MainLayout>
</template>

<style scoped>
/* Custom scrollbar for the JSON blocks */
pre::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}
pre::-webkit-scrollbar-thumb {
  background-color: #e2e8f0;
  border-radius: 10px;
}
pre::-webkit-scrollbar-track {
  background: transparent;
}
</style>
