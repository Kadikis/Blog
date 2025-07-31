<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface DashboardStats {
    totals: {
        posts: number;
        comments: number;
        users: number;
    };
    averages: {
        posts_per_user: number;
        comments_per_post: number;
    };
    recent: {
        posts: number;
        comments: number;
    };
    top_authors: Array<{
        name: string;
        posts_count: number;
    }>;
    most_commented_posts: Array<{
        title: string;
        author: string;
        comments_count: number;
    }>;
}

defineProps<{
    stats: DashboardStats;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
            <!-- Overview Stats -->
            <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Total Posts</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.totals.posts }}</CardTitle>
                    </CardHeader>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Total Comments</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.totals.comments }}</CardTitle>
                    </CardHeader>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Total Users</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.totals.users }}</CardTitle>
                    </CardHeader>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Posts per User</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.averages.posts_per_user }}</CardTitle>
                    </CardHeader>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardDescription>Comments per Post</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.averages.comments_per_post }}</CardTitle>
                    </CardHeader>
                </Card>
            </div>

            <!-- Recent Activity -->
            <div class="grid gap-4 md:grid-cols-1">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle>Recent Activity (30 days)</CardTitle>
                        <CardDescription>Posts and comments created in the last month</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">{{ stats.recent.posts }}</div>
                                <div class="text-sm text-muted-foreground">New Posts</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-primary">{{ stats.recent.comments }}</div>
                                <div class="text-sm text-muted-foreground">New Comments</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Top Authors and Most Commented Posts -->
            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle>Top Authors</CardTitle>
                        <CardDescription>Users with the most published posts</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div v-for="(author, index) in stats.top_authors" :key="author.name" class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-muted text-sm font-medium text-muted-foreground"
                                    >
                                        {{ index + 1 }}
                                    </div>
                                    <span class="font-medium">{{ author.name }}</span>
                                </div>
                                <div class="text-sm text-muted-foreground">{{ author.posts_count }} posts</div>
                            </div>
                            <div v-if="stats.top_authors.length === 0" class="text-center text-muted-foreground">No authors yet</div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle>Most Commented Posts</CardTitle>
                        <CardDescription>Posts generating the most discussion</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div v-for="post in stats.most_commented_posts" :key="post.title" class="space-y-1">
                                <div class="flex items-start justify-between">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate font-medium">{{ post.title }}</p>
                                        <p class="text-sm text-muted-foreground">by {{ post.author }}</p>
                                    </div>
                                    <div class="ml-2 text-sm text-muted-foreground">{{ post.comments_count }} comments</div>
                                </div>
                            </div>
                            <div v-if="stats.most_commented_posts.length === 0" class="text-center text-muted-foreground">No posts yet</div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
