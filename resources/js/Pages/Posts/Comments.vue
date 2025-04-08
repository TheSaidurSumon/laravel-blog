<template>
    <div>
      <h3 class="text-lg font-bold">Comments</h3>
      <form @submit.prevent="submitComment">
        <textarea v-model="newComment" placeholder="Add a comment..." class="w-full border p-2"></textarea>
        <button type="submit" class="btn">Post</button>
      </form>
  
      <div v-for="comment in comments" :key="comment.id" class="border mt-4 p-2">
        <p><strong>{{ comment.user.username }}</strong>: {{ comment.content }}</p>
        <button @click="replyTo(comment)">Reply</button>
  
        <div v-for="reply in comment.replies" :key="reply.id" class="ml-6 text-sm">
          <p><strong>{{ reply.user.username }}</strong>: {{ reply.content }}</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'

  
  const props = defineProps(['postId', 'initialComments'])
  
  const comments = ref(props.initialComments)
  const newComment = ref('')
  const replyParentId = ref(null)
  
  const submitComment = async () => {
    const res = await axios.post('/comments', {
      post_id: props.postId,
      content: newComment.value,
      parent_id: replyParentId.value,
    })
  
    newComment.value = ''
    replyParentId.value = null
  }
  
  const replyTo = (comment) => {
    replyParentId.value = comment.id
    newComment.value = `@${comment.user.username} `
  }
  
  // Real-time listener
  onMounted(() => {
    window.Echo.channel(`post.${props.postId}`)
      .listen('CommentPosted', (e) => {
        const parentId = e.parent_id
        if (!parentId) {
          comments.value.unshift({ ...e, replies: [] })
        } else {
          const parent = comments.value.find(c => c.id === parentId)
          if (parent) {
            parent.replies = parent.replies || []
            parent.replies.push(e)
          }
        }
      })
  })
  </script>
  