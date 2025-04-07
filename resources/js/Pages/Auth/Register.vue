<template>
    <div class="max-w-md mx-auto mt-10">
      <h2 class="text-2xl font-bold mb-4">Register</h2>
      <form @submit.prevent="submit">
        <input v-model="form.username" type="text" placeholder="Username" class="w-full p-2 border mb-2" />
        <input v-model="form.email" type="email" placeholder="Email" class="w-full p-2 border mb-2" />
        <input v-model="form.password" type="password" placeholder="Password" class="w-full p-2 border mb-2" />
        <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="w-full p-2 border mb-2" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2">Register</button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { useForm } from '@inertiajs/inertia-vue3';
  
  const form = useForm({
    username: '',
    email: '',
    password: '',
    password_confirmation: ''
  });
  
  const submit = () => {
    form.post('/register');
  };
  </script>
  