<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { Post, type BreadcrumbItem } from '@/types'
import { dashboard } from '@/routes'
import PostCard from '@/components/PostCard.vue'
import Paginator from '@/components/Paginator.vue'
import PostFilters from '@/components/Post/PostFilters.vue'

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Home', href: dashboard().url }
]

const props = defineProps<{
  posts: {
    data: Post[]
    links: { url: string | null, label: string, active: boolean }[]
  },
  filters: {
    search?: string
    tag?: string
    likes?: string
  },
  tags: string[]
}>()

function updateFilters(filters: any) {
  router.get(
    window.location.pathname,
    filters,
    {
      preserveState: true,
      preserveScroll: true,
    }
  )
}
</script>

<template>
  <Head title="Blog" />
  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="rounded-xl p-4">
      <div class="rounded-xl border p-6 dark:border-sidebar-border">

        <h2 class="text-2xl font-semibold mb-4">Posts</h2>

        <PostFilters
          :filters="props.filters"
          :tags="props.tags"
          @update="updateFilters"
        />

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <PostCard
            v-for="post in props.posts.data"
            :key="post.id"
            :post="post"
          />
        </div>

        <div class="mt-8 flex justify-center">
          <Paginator :posts="props.posts" />
        </div>
      </div>
    </div>

  </AppLayout>
</template>
