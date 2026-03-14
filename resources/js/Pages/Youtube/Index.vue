<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    videos: Object
});

const formatDuration = (seconds) => {
    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${minutes}:${secs.toString().padStart(2, '0')}`;
};
</script>

<template>
  <MainLayout>
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">YouTube Scrape Gallery</h1>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
      <div v-for="video in videos.data" :key="video.id" class="bg-white overflow-hidden shadow rounded-lg flex flex-col">
        <div class="px-4 py-5 sm:p-6 flex-grow">
          <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ video.title }}
          </h3>
          <p class="mt-1 text-sm text-gray-500 line-clamp-2">
            {{ video.description }}
          </p>
          <div class="mt-4 flex justify-between text-xs text-gray-400">
            <span>Channel ID: {{ video.youtube_channel_id }}</span>
            <span>Published: {{ new Date(video.published_at).toLocaleDateString() }}</span>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between items-center">
          <span class="text-sm font-semibold text-gray-700">
            {{ formatDuration(video.duration_seconds) }}
          </span>
          <div v-if="video.latest_metric" class="text-xs text-indigo-600 font-bold">
            {{ video.latest_metric.view_count?.toLocaleString() }} views
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>
