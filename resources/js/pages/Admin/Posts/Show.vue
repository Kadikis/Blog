<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

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
        };
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/admin/posts',
    },
    {
        title: props.post.data.title,
        href: `/admin/posts/${props.post.data.id}`,
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="post.data.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col gap-6 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl font-bold tracking-tight">{{ post.data.title }}</h1>

                            <!-- Categories -->
                            <div v-if="post.data.categories && post.data.categories.length > 0" class="mt-3 mb-2">
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="category in post.data.categories"
                                        :key="category.id"
                                        class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-gray-700/10 ring-inset dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/30"
                                    >
                                        {{ category.name }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-2 flex items-center gap-4 text-sm text-muted-foreground">
                                <span class="font-medium">By {{ post.data.author }}</span>
                                <span>•</span>
                                <span>{{ formatDate(post.data.published_at) }}</span>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <Button variant="outline" size="sm">
                                <Link :href="route('admin.posts.edit', post.data.id)" class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                        ></path>
                                    </svg>
                                    Edit
                                </Link>
                            </Button>
                            <Button variant="outline" size="sm">
                                <Link href="/admin/posts" class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Back to Posts
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <div class="border-t border-border pt-6">
                        <div class="prose prose-slate dark:prose-invert max-w-none">
                            <div class="leading-relaxed whitespace-pre-wrap text-foreground">
                                {{ post.data.body }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
