<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import CommentSection from '@/components/Comments/CommentSection.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Calendar, User } from 'lucide-vue-next';
import { computed, defineProps } from 'vue';

const props = defineProps<{
    post: {
        data: {
            id: number;
            title: string;
            body: string;
            excerpt: string;
            author: string;
            published_at: string;
            categories?: Array<{
                id: number;
                name: string;
            }>;
            comments?: Array<{
                id: number;
                content: string;
                user: {
                    id: number;
                    name: string;
                    is_admin: boolean;
                };
                created_at: string;
            }>;
        };
    };
}>();

// Format the published date
const formattedDate = computed(() => {
    return new Date(props.post.data.published_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
});
</script>

<template>
    <Head :title="post.data.title" />
    <AppHeader />
    <div class="min-h-screen bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8 lg:py-12">
            <article class="mx-auto max-w-3xl">
                <div class="mb-6">
                    <Link
                        :href="route('posts.index')"
                        class="inline-flex items-center gap-2 text-sm text-gray-600 transition-colors hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        <ArrowLeft class="h-4 w-4" />
                        Back to Posts
                    </Link>
                </div>

                <header class="mb-8 border-b border-gray-200 pb-8 dark:border-gray-700">
                    <h1 class="mb-4 text-3xl font-bold text-gray-900 lg:text-4xl dark:text-gray-100">
                        {{ post.data.title }}
                    </h1>

                    <div v-if="post.data.categories && post.data.categories.length > 0" class="mb-4">
                        <div class="flex flex-wrap gap-1">
                            <span
                                v-for="category in post.data.categories"
                                :key="category.id"
                                class="inline-flex items-center rounded bg-gray-50 px-1.5 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-400/10 dark:text-gray-400"
                            >
                                {{ category.name }}
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-1">
                            <User class="h-4 w-4" />
                            <span>{{ post.data.author }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <Calendar class="h-4 w-4" />
                            <span>{{ formattedDate }}</span>
                        </div>
                    </div>
                </header>

                <div class="prose prose-lg dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                    <div class="leading-relaxed whitespace-pre-wrap">
                        {{ post.data.body }}
                    </div>
                </div>

                <CommentSection :post-id="post.data.id" :comments="post.data.comments" />
            </article>
        </div>
    </div>
</template>
