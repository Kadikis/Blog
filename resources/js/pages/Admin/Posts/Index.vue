<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
    posts: {
        data: Array<{
            id: number;
            title: string;
            body: string;
            excerpt: string;
            author: string;
            published_at: string;
        }>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posts',
        href: '/admin/posts',
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Posts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col gap-6 p-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-bold tracking-tight">Posts</h1>
                        <Button variant="default">
                            <Link :href="route('admin.posts.create')" class="flex items-center gap-2">
                                <span class="text-sm">+</span>
                                New Post
                            </Link>
                        </Button>
                    </div>

                    <div v-if="posts.data.length === 0" class="py-12 text-center">
                        <p class="text-lg text-muted-foreground">No posts found</p>
                        <p class="text-sm text-muted-foreground">Create your first post to get started</p>
                    </div>

                    <div v-else class="space-y-2">
                        <Link v-for="post in posts.data" :key="post.id" :href="route('admin.posts.show', post.id)" class="group block">
                            <div
                                class="flex items-center gap-4 rounded-lg border bg-card p-4 transition-all hover:border-primary/30 hover:bg-accent hover:shadow-md"
                            >
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-lg font-semibold transition-colors group-hover:text-primary">
                                        {{ post.title }}
                                    </h3>
                                    <p class="mt-1 line-clamp-2 text-sm text-muted-foreground">
                                        {{ post.excerpt }}
                                    </p>
                                </div>
                                <div class="flex shrink-0 flex-col items-end text-right text-sm text-muted-foreground">
                                    <span class="font-medium">{{ post.author }}</span>
                                    <span class="text-xs">{{ formatDate(post.published_at) }}</span>
                                </div>
                                <div class="text-muted-foreground transition-colors group-hover:text-foreground">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
