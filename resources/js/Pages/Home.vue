<template>
    <div>
      <h2 class="text-2xl font-semibold mb-4">Public Posts</h2>
      <div v-for="post in posts" :key="post.id" class="bg-white p-4 rounded shadow mb-4">
        <h3 class="text-xl font-bold">{{ post.title }}</h3>
        <p class="text-gray-700">{{ post.content }}</p>
        <small>By {{ post.user.username }}</small>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    posts: Array
  })
  </script>
  