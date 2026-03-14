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

// Formatter for large numbers (e.g., 1.2M, 850K)
const formatNumber = (num) => {
    if (!num) return '0';
    return Intl.NumberFormat('en-US', {
        notation: "compact",
        maximumFractionDigits: 1
    }).format(num);
};
</script>

<template>
  <MainLayout>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">YouTube Gallery</h1>
      <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
        Sync Data
      </button>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <div v-for="video in videos.data" :key="video.id" class="bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 rounded-xl border border-gray-100 flex flex-col group">

        <a :href="`https://youtube.com/watch?v=${video.youtube_video_id}`" target="_blank" class="relative block overflow-hidden aspect-video bg-gray-200">
          <img
            :src="`https://img.youtube.com/vi/${video.youtube_video_id}/mqdefault.jpg`"
            :alt="video.title"
            class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300"
          />
          <div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded font-medium">
            {{ formatDuration(video.duration_seconds) }}
          </div>
        </a>

        <div class="p-4 flex-grow flex flex-col">
          <a :href="`https://youtube.com/watch?v=${video.youtube_video_id}`" target="_blank" class="text-base font-semibold text-gray-900 leading-tight hover:text-indigo-600 line-clamp-2 mb-2">
            {{ video.title }}
          </a>

          <div class="mt-auto pt-4 flex items-center justify-between text-sm text-gray-500">
            <div class="flex flex-col">
              <span class="font-medium text-gray-700">{{ video.channel?.title || 'Unknown Channel' }}</span>
              <span class="text-xs">{{ new Date(video.published_at).toLocaleDateString() }}</span>
            </div>
            <div v-if="video.latest_metric" class="flex flex-col items-end">
              <span class="font-semibold text-indigo-600">{{ formatNumber(video.latest_metric.view_count) }} views</span>
              <span class="text-xs">{{ formatNumber(video.latest_metric.like_count) }} likes</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </MainLayout>
</template>
