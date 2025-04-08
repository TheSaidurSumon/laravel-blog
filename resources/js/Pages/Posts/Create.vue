<template>
    <div class="max-w-lg mx-auto">
      <h2 class="text-xl font-bold mb-4">Create Post</h2>
      <form @submit.prevent="submit" enctype="multipart/form-data">
        <input v-model="form.title" type="text" placeholder="Title" class="w-full border p-2 mb-2" />
        <textarea v-model="form.content" placeholder="Content" class="w-full border p-2 mb-2"></textarea>
        <input type="file" @change="handleFile" class="w-full border p-2 mb-2" />
        <select v-model="form.visibility" class="w-full border p-2 mb-2">
          <option value="public">Public</option>
          <option value="private">Private</option>
        </select>
        <input v-model="tagInput" @keyup.enter.prevent="addTag" type="text" placeholder="Add Tag" class="w-full border p-2 mb-2" />
<div class="flex flex-wrap gap-2 mb-2">
  <span v-for="(tag, i) in form.tags" :key="i" class="bg-gray-300 px-2 py-1 rounded">
    {{ tag }}
    <button @click.prevent="removeTag(i)">✕</button>
  </span>
</div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2">Post</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/inertia-vue3';
  import { ref } from 'vue';
  const form = useForm({
    title: '',
    content: '',
    visibility: 'public',
    image: null,
    tags: [],

  });
  
  const handleFile = (e) => {
    form.image = e.target.files[0];
  };
  
  const submit = () => {
    form.post('/posts');
  };

 
const tagInput = ref('');

const addTag = () => {
  if (tagInput.value && !form.tags.includes(tagInput.value)) {
    form.tags.push(tagInput.value);
  }
  tagInput.value = '';
};

const removeTag = (index) => {
  form.tags.splice(index, 1);
};

  </script>
  