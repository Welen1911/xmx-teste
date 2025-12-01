<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Post, type BreadcrumbItem } from '@/types'
import { dashboard } from '@/routes'
import PostCard from '@/components/PostCard.vue'
import Paginator from '@/components/Paginator.vue'



const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Home', href: dashboard().url }
]

const props = defineProps<{
  posts: {
    data: Post[],
    links: { url: string | null, label: string, active: boolean }[]
  }
}>()
</script>

<template>
  <Head title="Blog" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-6">
        <h2 class="text-2xl font-semibold mb-4">Posts</h2>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <PostCard v-for="post in props.posts.data" :key="post.id" :post="post" />
        </div>

        <div class="mt-8 flex justify-center">
          <Paginator :posts="props.posts" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
