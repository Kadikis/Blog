<template>
    <div class="mb-4 flex flex-1 flex-col gap-3 rounded-lg bg-white p-6 shadow">
        <h2 class="mb-1 text-xl font-bold text-gray-900">
            <Link :href="route('posts.show', post.id)" class="transition-colors hover:text-blue-600">
                {{ post.title }}
            </Link>
        </h2>

        <!-- Categories -->
        <div v-if="post.categories && post.categories.length > 0" class="mb-2">
            <div class="flex flex-wrap gap-1">
                <span
                    v-for="category in post.categories"
                    :key="category.id"
                    class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700"
                >
                    {{ category.name }}
                </span>
            </div>
        </div>

        <p class="mb-2 line-clamp-3 text-sm text-gray-700">{{ post.excerpt }}</p>
        <div class="mt-auto flex items-center justify-between text-xs text-gray-500">
            <span>
                <span class="flex items-center gap-1">
                    By {{ post.author }}
                    <span class="text-gray-500">|</span>
                    {{ formattedDate }}
                </span>
            </span>
            <span>
                <span class="flex items-center gap-1">
                    <MessageCircle class="h-4 w-4 text-gray-600 dark:text-gray-400" />
                    {{ post.comment_count }}
                </span>
            </span>
        </div>
        <slot name="actions" />
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { MessageCircle } from 'lucide-vue-next';
import { computed, defineProps } from 'vue';

const props = defineProps<{
    post: {
        id: number;
        title: string;
        excerpt: string;
        author: string | { name: string };
        published_at: string | Date;
        categories?: Array<{
            id: number;
            name: string;
        }>;
        comment_count: number;
    };
}>();

// Format the published date
const formattedDate = computed(() => {
    return new Date(props.post.published_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
});
</script>
