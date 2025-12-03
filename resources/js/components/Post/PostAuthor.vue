<script setup lang="ts">
import { User } from '@/types';

const props = defineProps<{
  user: User;
}>();
</script>

<template>
  <div class="flex items-center gap-4 mb-6">
    <div class="h-14 w-14 rounded-full overflow-hidden border">
      <a :href="`/user/${props.user.id}`">
        <img
          v-if="props.user.image"
          :src="props.user.image"
          class="h-full w-full object-cover"
          alt="Foto do autor"
        />

        <div
          v-else
          class="h-full w-full flex items-center justify-center bg-muted text-muted-foreground"
        >
          {{ props.user.first_name[0] }}{{ props.user.last_name[0] }}
        </div>
      </a>
    </div>

    <div>
      <a :href="`/user/${props.user.id}`">
        <p class="text-lg font-semibold">{{ props.user.first_name }} {{ props.user.last_name }}</p>
        <p class="text-sm text-muted-foreground">Autor do post</p>
      </a>
    </div>
  </div>
</template>
