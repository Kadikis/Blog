<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { MessageCircle, Trash2, User } from 'lucide-vue-next';
import { computed } from 'vue';

interface Comment {
    id: number;
    content: string;
    user: {
        id: number;
        name: string;
        is_admin: boolean;
    };
    created_at: string;
}

interface Props {
    postId: number;
    comments?: Comment[];
}

const props = withDefaults(defineProps<Props>(), {
    comments: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

// Comment form
const commentForm = useForm({
    content: '',
    post_id: props.postId,
});

const submitComment = () => {
    commentForm.post(route('comments.store', props.postId), {
        preserveState: true,
        onSuccess: () => {
            commentForm.reset('content');
        },
    });
};

const deleteComment = (commentId: number) => {
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(route('comments.destroy', commentId), {
            preserveState: true,
        });
    }
};

// Format comment date
const formatCommentDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <div class="mt-12 border-t border-gray-200 pt-8 dark:border-gray-700">
        <div class="mb-6 flex items-center gap-2">
            <MessageCircle class="h-5 w-5 text-gray-600 dark:text-gray-400" />
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Comments ({{ comments?.length || 0 }})</h2>
        </div>

        <div v-if="auth.user" class="mb-8">
            <Card>
                <CardHeader>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Add a Comment</h3>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submitComment" class="space-y-4">
                        <div>
                            <textarea
                                v-model="commentForm.content"
                                rows="4"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 placeholder-gray-500 focus:border-gray-500 focus:ring-gray-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400"
                                placeholder="Write your comment..."
                                required
                            ></textarea>
                            <div v-if="commentForm.errors.content" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ commentForm.errors.content }}
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <Button
                                type="submit"
                                :disabled="commentForm.processing || !commentForm.content.trim()"
                                class="bg-gray-900 text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"
                            >
                                {{ commentForm.processing ? 'Posting...' : 'Post Comment' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>

        <div v-else class="mb-8">
            <Card>
                <CardContent class="py-6 text-center">
                    <p class="mb-4 text-gray-600 dark:text-gray-400">Please log in to leave a comment.</p>
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-gray-200"
                    >
                        Log In
                    </Link>
                </CardContent>
            </Card>
        </div>

        <!-- Comments List -->
        <div class="space-y-4">
            <div v-if="!comments || comments.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
                <MessageCircle class="mx-auto h-12 w-12 opacity-50" />
                <p class="mt-2">No comments yet. Be the first to comment!</p>
            </div>

            <Card v-for="comment in comments" :key="comment.id" class="relative">
                <CardContent class="pt-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="mb-2 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <User class="h-4 w-4" />
                                <span class="font-medium text-gray-500 dark:text-gray-100">
                                    {{ comment.user.name }}
                                </span>
                                <span>•</span>
                                <span>{{ formatCommentDate(comment.created_at) }}</span>
                            </div>
                            <div class="leading-relaxed whitespace-pre-wrap text-gray-800 dark:text-gray-200">
                                {{ comment.content }}
                            </div>
                        </div>

                        <div v-if="comment.user.id === auth.user.id || auth.user.is_admin" class="ml-4">
                            <Button
                                @click="deleteComment(comment.id)"
                                variant="ghost"
                                size="sm"
                                class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400"
                            >
                                <Trash2 class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>
