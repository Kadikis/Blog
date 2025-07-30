<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps<{
    categories: {
        data: Array<{
            id: number;
            name: string;
        }>;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '/admin/categories',
    },
];
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col gap-6 p-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-3xl font-bold tracking-tight">Categories</h1>
                        <Button variant="default">
                            <Link :href="route('admin.categories.create')" class="flex items-center gap-2">
                                <span class="text-sm">+</span>
                                New Category
                            </Link>
                        </Button>
                    </div>

                    <div v-if="categories.data.length === 0" class="py-12 text-center">
                        <p class="text-lg text-muted-foreground">No categories found</p>
                        <p class="text-sm text-muted-foreground">Create your first category to get started</p>
                    </div>

                    <div v-else class="space-y-2">
                        <Link
                            v-for="category in categories.data"
                            :key="category.id"
                            :href="route('admin.categories.show', category.id)"
                            class="group block"
                        >
                            <div
                                class="flex items-center gap-4 rounded-lg border bg-card p-4 transition-all hover:border-primary/30 hover:bg-accent hover:shadow-md"
                            >
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-lg font-semibold transition-colors group-hover:text-primary">
                                        {{ category.name }}
                                    </h3>
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
