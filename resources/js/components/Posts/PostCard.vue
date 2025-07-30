<template>
    <div class="mb-4 flex flex-1 flex-col gap-3 rounded-lg bg-white p-6 shadow">
        <h2 class="mb-1 text-xl font-bold text-gray-900">
            <Link :href="route('posts.show', post.id)" class="transition-colors hover:text-blue-600">
                {{ post.title }}
            </Link>
        </h2>
        <p class="mb-2 line-clamp-3 text-sm text-gray-700">{{ post.excerpt }}</p>
        <div class="mt-auto flex items-center justify-between text-xs text-gray-500">
            <span>By {{ post.author }}</span>
            <span>{{ post.published_at }}</span>
        </div>
        <slot name="actions" />
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
    post: {
        id: number;
        title: string;
        excerpt: string;
        author: string | { name: string };
        published_at: string | Date;
    };
}>();
</script>
