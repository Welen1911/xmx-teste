<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { Post } from "@/types";

const props = defineProps<{
  posts: {
    data: Post[];
    links: { url: string | null; label: string; active: boolean }[];
  };
}>();

// helpers
const stripHtml = (s: string) => s.replace(/<\/?[^>]+(>|$)/g, "").trim();

// prev/next position-based
const prevUrl = computed(() => props.posts.links[0]?.url ?? null);
const nextUrl = computed(
  () => props.posts.links[props.posts.links.length - 1]?.url ?? null
);

// desktop page numbers: links[1] até links[length-2]
const pageLinks = computed(() =>
  props.posts.links.slice(1, props.posts.links.length - 1)
);

// página atual (para mobile)
const currentPageLabel = computed(() => {
  const active = props.posts.links.find((l) => l.active);
  return active ? stripHtml(active.label) : "";
});
</script>

<template>
  <nav aria-label="Pagination" class="w-full">
    <div class="flex items-center justify-center gap-3 py-4">
      <!-- Previous -->
      <div>
        <template v-if="prevUrl">
          <Link
            :href="prevUrl"
            preserve-scroll
            class="inline-flex items-center px-3 py-1 rounded-md border hover:bg-muted"
          >
            ‹
          </Link>
        </template>
        <template v-else>
          <span
            class="inline-flex items-center px-3 py-1 rounded-md border opacity-40 cursor-not-allowed"
          >
            ‹
          </span>
        </template>
      </div>

      <!-- MOBILE: só página atual -->
      <div class="sm:hidden">
        <span class="px-3 py-1 rounded-md bg-primary text-primary-foreground">
          {{ currentPageLabel }}
        </span>
      </div>

      <!-- DESKTOP: números reais apenas -->
      <div class="hidden sm:flex items-center gap-1">
        <template v-for="(link, i) in pageLinks" :key="i">
          <template v-if="link.url">
            <Link
              :href="link.url"
              preserve-scroll
              class="px-3 py-1 rounded-md border"
              :class="
                link.active ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'
              "
            >
              <span v-html="link.label"></span>
            </Link>
          </template>

          <template v-else>
            <!-- ellipsis -->
            <span class="px-3 py-1">…</span>
          </template>
        </template>
      </div>

      <!-- Next -->
      <div>
        <template v-if="nextUrl">
          <Link
            :href="nextUrl"
            preserve-scroll
            class="inline-flex items-center px-3 py-1 rounded-md border hover:bg-muted"
          >
            ›
          </Link>
        </template>
        <template v-else>
          <span
            class="inline-flex items-center px-3 py-1 rounded-md border opacity-40 cursor-not-allowed"
          >
            ›
          </span>
        </template>
      </div>
    </div>
  </nav>
</template>
