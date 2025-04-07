<template>
    <header class="bg-gray-800 text-white p-4 flex justify-between items-center">
      <h1 class="text-xl font-bold">Blog System</h1>
      <div v-if="$page.props.auth.user">
        Hello, {{ $page.props.auth.user.username }}
        <form method="POST" :action="route('logout')" class="inline ml-2">
          <button class="bg-red-500 px-2 py-1 rounded">Logout</button>
        </form>
      </div>
      <div v-else>
        <a href="/login" class="mr-4">Login</a>
        <a href="/register">Register</a>
      </div>
    </header>
    <main class="p-4">
      <slot />
    </main>
  </template>
  