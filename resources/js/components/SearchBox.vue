<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/vue3';
import { Search, X } from 'lucide-vue-next';
import { nextTick, ref } from 'vue';

const props = defineProps<{
    searchQuery: string;
}>();

const isExpanded = ref(props.searchQuery ? true : false);
const searchQuery = ref(props.searchQuery ?? '');
const inputRef = ref<HTMLInputElement | null>(null);

const toggleSearch = async () => {
    isExpanded.value = !isExpanded.value;

    if (isExpanded.value) {
        await nextTick();
        inputRef.value?.focus();
    } else {
        //if has search value and is expanded, clear the search query and redirect to the index page
        if (searchQuery.value.trim() !== '') {
            router.get(route('posts.index'));
        }
        searchQuery.value = '';
    }
};

const handleSubmit = () => {
    if (searchQuery.value.trim()) {
        router.get(route('posts.index'), {
            search_query: searchQuery.value.trim(),
        });
    }
};

const handleKeydown = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        isExpanded.value = false;
        searchQuery.value = '';
    }
};
</script>

<template>
    <div class="relative flex items-center">
        <!-- Search Input (Expandable) -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="w-0 opacity-0"
            enter-to-class="w-64 opacity-100"
            leave-active-class="transition-all duration-300 ease-in"
            leave-from-class="w-64 opacity-100"
            leave-to-class="w-0 opacity-0"
        >
            <div v-if="isExpanded" class="absolute top-1/2 right-12 -translate-y-1/2 overflow-hidden">
                <form @submit.prevent="handleSubmit" class="flex items-center">
                    <Input
                        ref="inputRef"
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search..."
                        class="h-9 w-64 pr-10 text-sm focus:ring-2 focus:ring-primary"
                        @keydown="handleKeydown"
                    />
                    <Button
                        type="submit"
                        variant="ghost"
                        size="icon"
                        class="absolute right-1 h-7 w-7 hover:bg-transparent"
                        :disabled="!searchQuery.trim()"
                    >
                        <Search class="size-4" />
                    </Button>
                </form>
            </div>
        </Transition>

        <!-- Search Icon / Close Button -->
        <Button variant="ghost" size="icon" class="group relative z-10 h-9 w-9 cursor-pointer" @click="toggleSearch">
            <Transition
                enter-active-class="transition-all duration-200"
                enter-from-class="scale-0 rotate-90"
                enter-to-class="scale-100 rotate-0"
                leave-active-class="transition-all duration-200"
                leave-from-class="scale-100 rotate-0"
                leave-to-class="scale-0 rotate-90"
                mode="out-in"
            >
                <X v-if="isExpanded" class="size-5 opacity-80 group-hover:opacity-100" />
                <Search v-else class="size-5 opacity-80 group-hover:opacity-100" />
            </Transition>
        </Button>
    </div>
</template>
