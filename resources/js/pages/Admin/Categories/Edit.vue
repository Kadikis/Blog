<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps<{
    category: {
        data: {
            id: number;
            name: string;
            created_at: string;
            updated_at: string;
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
    {
        title: 'Edit',
        href: `/admin/categories/${props.category.data.id}/edit`,
    },
];

const form = useForm({
    name: props.category.data.name,
});

const submit = () => {
    form.put(route('admin.categories.update', props.category.data.id), {
        preserveScroll: true,
    });
};

const deleteCategory = () => {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        form.delete(route('admin.categories.destroy', props.category.data.id));
    }
};
</script>

<template>
    <Head :title="`Edit ${category.data.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <div class="flex flex-col gap-6 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl font-bold tracking-tight">Edit Category</h1>
                            <p class="mt-1 text-sm text-muted-foreground">Update the category details below</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <Button variant="outline" size="sm">
                                <Link :href="route('admin.categories.show', category.data.id)" class="flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        ></path>
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        ></path>
                                    </svg>
                                    View Category
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
                            <div class="flex items-center justify-between border-t border-border pt-4">
                                <Button
                                    type="button"
                                    variant="destructive"
                                    @click="deleteCategory"
                                    :disabled="form.processing"
                                    class="flex items-center gap-2"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        ></path>
                                    </svg>
                                    Delete Category
                                </Button>

                                <div class="flex items-center gap-2">
                                    <Button type="button" variant="outline" :disabled="form.processing">
                                        <Link :href="route('admin.categories.show', category.data.id)"> Cancel </Link>
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
                                        {{ form.processing ? 'Updating...' : 'Update Category' }}
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
