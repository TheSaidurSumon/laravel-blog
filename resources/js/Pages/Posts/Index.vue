<template>
    <div class="max-w-4xl mx-auto">
      <h2 class="text-2xl font-bold mb-4">Your Posts</h2>
      <a href="/posts/create" class="bg-green-500 text-white px-4 py-2 mb-4 inline-block">Create New</a>
      <div v-for="post in posts" :key="post.id" class="border p-4 mb-4">
        <h3 class="text-xl font-bold">{{ post.title }}</h3>
        <p class="text-gray-600">{{ post.content }}</p>
        <p><strong>Visibility:</strong> {{ post.visibility }}</p>
        <div v-if="post.image">
          <img :src="`/storage/${post.image}`" alt="Post Image" class="w-1/2 mt-2" />
        </div>
        <div class="mt-2">
          <a :href="`/posts/${post.id}/edit`" class="text-blue-600 mr-4">Edit</a>
          <button @click="deletePost(post.id)" class="text-red-600">Delete</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { Inertia } from '@inertiajs/inertia';
  defineProps(['posts']);
  
  const deletePost = (id) => {
    if (confirm('Are you sure?')) {
      Inertia.delete(`/posts/${id}`);
    }
  };
  </script>
  