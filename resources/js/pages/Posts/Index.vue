<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import PostCard from '@/components/Posts/PostCard.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
    posts: Array<{
        data: Array<{
            id: number;
            title: string;
            excerpt: string;
            author: string;
            published_at: string;
            categories?: Array<{
                id: number;
                name: string;
            }>;
        }>;
    }>;
    searchQuery: string;
}>();
</script>

<template>
    <Head title="Posts" />
    <AppHeader :search-query="searchQuery" />

    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
        <ul v-if="posts.data.length > 0">
            <li v-for="post in posts.data" :key="post.id">
                <PostCard :post="post" />
            </li>
        </ul>
        <template v-else>
            <p class="text-lg">No posts found</p>
            <Link v-if="searchQuery" :href="route('posts.index')" class="flex items-center gap-2 pt-4">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Posts
            </Link>
        </template>
    </div>
</template>
