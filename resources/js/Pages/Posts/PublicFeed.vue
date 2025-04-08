<template>
    <div class="max-w-4xl mx-auto">
      <input v-model="search" @input="doSearch" type="text" placeholder="Search..." class="w-full border p-2 mb-4" />
      <div v-for="post in posts" :key="post.id" class="border p-4 mb-4">
        <h3 class="text-xl font-bold">{{ post.title }}</h3>
        <p>{{ post.content }}</p>
        <p>By: {{ post.user.username }}</p>
        <div class="flex gap-2 mt-2">
          <span v-for="tag in post.tags" :key="tag.id" class="bg-gray-200 px-2 py-1 rounded text-sm">{{ tag.name }}</span>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import { router } from '@inertiajs/vue3';
  defineProps(['posts']);
  
  const search = ref('');
  const doSearch = () => {
    router.get('/public-posts', { search: search.value }, { preserveState: true });
  };
  </script>
  