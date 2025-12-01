<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import type { BreadcrumbItem } from '@/types'

import UserHeader from '@/components/User/UserHeader.vue'
import UserInfo from '@/components/User/UserInfo.vue'
import UserStats from '@/components/User/UserStats.vue'

const props = defineProps<{
  user: {
    id: number
    external_id: string
    first_name: string
    last_name: string
    email: string
    phone: string | null
    image: string | null
    birth_date: string | null
    address: string | null
    posts_count: number
    comments_count: number
  }
}>()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Home', href: '/' },
  { title: 'Autor', href: `/user/${props.user.id}` }
]
</script>

<template>
  <Head :title="`${props.user.first_name} ${props.user.last_name}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 p-4">

      <UserHeader :user="props.user" />

      <UserInfo :user="props.user" />

      <UserStats :user="props.user" />

    </div>
  </AppLayout>
</template>
