<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem, Post } from '@/types'

import PostHeader from '@/components/Post/PostHeader.vue'
import PostBody from '@/components/Post/PostBody.vue'
import PostReactions from '@/components/Post/PostReactions.vue'
import PostComments from '@/components/Post/PostComments.vue'
import PostAuthor from '@/components/Post/PostAuthor.vue'

const props = defineProps<{
  post: Post
}>()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Home', href: '/' },
  { title: 'Post', href: `/post/${props.post.id}` },
]

</script>

<template>
  <Head :title="props.post.title" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6">
        <PostAuthor :user="props.post.user" />
        
        <PostHeader
          :title="props.post.title"
          :tags="props.post.tags"
          :views="props.post.views"
        />


        <PostBody :body="props.post.body" />

        <PostReactions
          :likes="props.post.likes"
          :dislikes="props.post.dislikes"
        />
      </div>

      <PostComments :comments="props.post.comments" />

    </div>
  </AppLayout>
</template>
