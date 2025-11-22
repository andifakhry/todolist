<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';
import { computed } from 'vue';

interface Props {
    user: User | null;
    showEmail?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials } = useInitials();

// User fallback ketika props.user = null
const safeUser = computed(() => props.user ?? {
    name: 'Guest',
    email: '-',
    avatar: null,
});

// Kalau user tidak punya avatar → fallback avatar
const showAvatar = computed(() => {
    return safeUser.value.avatar && safeUser.value.avatar !== '';
});
</script>

<template>
    <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
        <AvatarImage
            v-if="showAvatar"
            :src="safeUser.avatar!"
            :alt="safeUser.name"
        />

        <AvatarFallback class="rounded-lg text-black dark:text-white">
            {{ getInitials(safeUser.name) }}
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">
            {{ safeUser.name }}
        </span>

        <span
            v-if="showEmail"
            class="truncate text-xs text-muted-foreground"
        >
            {{ safeUser.email }}
        </span>
    </div>
</template>
