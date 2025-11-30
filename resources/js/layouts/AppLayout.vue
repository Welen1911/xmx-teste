<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import AppHeaderLayout from './app/AppHeaderLayout.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppHeaderLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppHeaderLayout>
</template>
