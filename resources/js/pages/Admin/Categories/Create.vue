<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: '/admin/categories',
    },
    {
        title: 'Create',
        href: `/admin/categories/create`,
    },
];

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('admin.categories.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col gap-6 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl font-bold tracking-tight">Create Category</h1>
                            <p class="mt-1 text-sm text-muted-foreground">Create a new category</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
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

                    <div class="border-t border-border pt-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name Field -->
                            <div class="space-y-2">
                                <Label for="name">Category Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="w-full"
                                    :class="{ 'border-destructive': form.errors.name }"
                                    placeholder="Enter category name..."
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end border-t border-border pt-4">
                                <div class="flex items-center gap-2">
                                    <Button type="button" variant="outline" :disabled="form.processing">
                                        <Link :href="route('admin.categories.index')"> Cancel </Link>
                                    </Button>
                                    <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                                        <svg
                                            v-if="form.processing"
                                            class="h-4 w-4 animate-spin"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                            ></path>
                                        </svg>
                                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ form.processing ? 'Creating...' : 'Create Category' }}
                                    </Button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
