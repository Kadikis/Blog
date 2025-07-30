<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps<{
    category: {
        data: {
            id: number;
            name: string;
            created_at: string;
            updated_at: string;
            posts_count?: number;
            posts?: Array<{
                id: number;
                title: string;
                body: string;
                excerpt: string;
                author: string;
                published_at: string;
            }>;
        };
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '/admin/categories',
    },
    {
        title: props.category.data.name,
        href: `/admin/categories/${props.category.data.id}`,
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

const formatShortDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head :title="category.data.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col gap-6 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl font-bold tracking-tight">{{ category.data.name }}</h1>
                            <div class="mt-2 flex items-center gap-4 text-sm text-muted-foreground">
                                <span>Created {{ formatDate(category.data.created_at) }}</span>
                                <span v-if="category.data.posts_count !== undefined">•</span>
                                <span v-if="category.data.posts_count !== undefined">
                                    {{ category.data.posts_count }} {{ category.data.posts_count === 1 ? 'post' : 'posts' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <Button variant="outline" size="sm">
                                <Link :href="route('admin.categories.edit', category.data.id)" class="flex items-center gap-2">
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
                                <Link href="/admin/categories" class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Back to Categories
                                </Link>
                            </Button>
                        </div>
                    </div>

                    <div v-if="category.data.posts && category.data.posts.length > 0" class="border-t border-border pt-6">
                        <h2 class="mb-4 text-xl font-semibold">Posts in this Category</h2>
                        <div class="space-y-2">
                            <Link v-for="post in category.data.posts" :key="post.id" :href="route('admin.posts.show', post.id)" class="group block">
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
                                        <span class="text-xs">{{ formatShortDate(post.published_at) }}</span>
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

                    <div v-else-if="category.data.posts" class="border-t border-border pt-6">
                        <div class="py-12 text-center">
                            <p class="text-lg text-muted-foreground">No posts in this category</p>
                            <p class="text-sm text-muted-foreground">Posts assigned to this category will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
