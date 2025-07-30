<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
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
</script>

<template>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-between gap-4">
                <Link
                    :href="route('posts.index')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    ← Back to Posts
                </Link>
                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('admin.dashboard')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <article class="w-full max-w-[335px] lg:max-w-4xl">
            <div class="prose prose-lg max-w-none text-gray-800 dark:text-gray-200">
                <h1 class="mb-4 text-3xl font-bold text-gray-900 lg:text-4xl dark:text-gray-100">
                    {{ post.data.title }}
                </h1>

                <!-- Categories -->
                <div v-if="post.data.categories && post.data.categories.length > 0" class="not-prose mb-6">
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="category in post.data.categories"
                            :key="category.id"
                            class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        >
                            {{ category.name }}
                        </span>
                    </div>
                </div>

                <div class="leading-relaxed whitespace-pre-wrap">
                    {{ post.data.body }}
                </div>
            </div>

            <div class="mt-10 flex items-center justify-between text-xs text-gray-500">
                <span>By {{ post.data.author }}</span>
                <span>{{ post.data.published_at }}</span>
            </div>
        </article>
    </div>
</template>
