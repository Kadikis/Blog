<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
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
        title: 'Posts',
        href: '/admin/posts',
    },
    {
        title: 'Create',
        href: `/admin/posts/create`,
    },
];

const form = useForm({
    title: '',
    body: '',
    published_at: new Date().toISOString().slice(0, 16),
    categories: [] as number[],
});

const submit = () => {
    form.post(route('admin.posts.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head :title="`Create Post`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col gap-6 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl font-bold tracking-tight">Create Post</h1>
                            <p class="mt-1 text-sm text-muted-foreground">Create a new post</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
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
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Title Field -->
                            <div class="space-y-2">
                                <Label for="title">Title</Label>
                                <Input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="w-full"
                                    :class="{ 'border-destructive': form.errors.title }"
                                />
                                <InputError :message="form.errors.title" />
                            </div>

                            <!-- Body Field -->
                            <div class="space-y-2">
                                <Label for="body">Content</Label>
                                <textarea
                                    id="body"
                                    v-model="form.body"
                                    rows="12"
                                    class="flex min-h-[200px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-base shadow-sm placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm"
                                    :class="{ 'border-destructive': form.errors.body }"
                                    placeholder="Write your post content here..."
                                />
                                <InputError :message="form.errors.body" />
                            </div>

                            <!-- Published At Field -->
                            <div class="space-y-2">
                                <Label for="published_at">Published Date & Time</Label>
                                <Input
                                    id="published_at"
                                    v-model="form.published_at"
                                    type="datetime-local"
                                    class="w-full"
                                    :class="{ 'border-destructive': form.errors.published_at }"
                                />
                                <InputError :message="form.errors.published_at" />
                            </div>

                            <!-- Categories Field -->
                            <div class="space-y-2">
                                <Label>Categories</Label>
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    <div v-for="category in categories.data" :key="category.id" class="flex items-center space-x-2">
                                        <Checkbox
                                            :id="`category-${category.id}`"
                                            :model-value="form.categories.includes(category.id)"
                                            @update:model-value="
                                                (checked: boolean) => {
                                                    if (checked) {
                                                        if (!form.categories.includes(category.id)) {
                                                            form.categories.push(category.id);
                                                        }
                                                    } else {
                                                        const index = form.categories.indexOf(category.id);
                                                        if (index > -1) {
                                                            form.categories.splice(index, 1);
                                                        }
                                                    }
                                                }
                                            "
                                        />
                                        <Label :for="`category-${category.id}`" class="cursor-pointer text-sm font-normal">
                                            {{ category.name }}
                                        </Label>
                                    </div>
                                </div>
                                <InputError :message="form.errors.categories" />
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between border-t border-border pt-4">
                                <div class="flex items-center gap-2">
                                    <Button type="button" variant="outline" :disabled="form.processing">
                                        <Link :href="route('admin.posts.index')"> Cancel </Link>
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
                                        {{ form.processing ? 'Creating...' : 'Create Post' }}
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
