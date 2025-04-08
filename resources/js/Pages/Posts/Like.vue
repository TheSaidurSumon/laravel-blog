<template>
    <button @click="toggleLike">
      <span v-if="liked">💔 Unlike</span>
      <span v-else>❤️ Like</span>
      <span>({{ likeCount }})</span>
    </button>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'

  import Pusher from 'pusher-js'
  import axios from 'axios'
  
  const props = defineProps({ postId: Number, initialLiked: Boolean, initialCount: Number })
  
  const liked = ref(props.initialLiked)
  const likeCount = ref(props.initialCount)
  
  const toggleLike = async () => {
    const res = await axios.post(`/api/posts/${props.postId}/like`)
    liked.value = !res.data.liked
    likeCount.value = res.data.like_count
  }
  
  // Real-time update
  onMounted(() => {
    window.Pusher = Pusher
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: import.meta.env.VITE_PUSHER_APP_KEY,
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
      forceTLS: true
    })
  
    window.Echo.channel('posts')
      .listen('PostLiked', e => {
        if (e.post_id === props.postId) {
          likeCount.value = e.like_count
        }
      })
  })
  </script>
  