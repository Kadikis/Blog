<script setup lang="ts">
import AppHeader from '@/components/AppHeader.vue';
import PostCard from '@/components/Posts/PostCard.vue';
import { Head } from '@inertiajs/vue3';
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
}>();
</script>

<template>
    <Head title="Posts" />
    <AppHeader />

    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
        <ul>
            <li v-for="post in posts.data" :key="post.id">
                <PostCard :post="post" />
            </li>
        </ul>
    </div>
</template>
