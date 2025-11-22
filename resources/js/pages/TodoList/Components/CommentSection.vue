<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
    comments: { id: number; author: string; text: string; date: string }[]
}>()

const emit = defineEmits(['update'])
const localComments = ref([...props.comments])

const newComment = ref('')

function addComment() {
    if (!newComment.value.trim()) return
    localComments.value.push({
        id: Date.now() + Math.random(),
        author: 'Kamu', // bisa diganti sesuai user login nanti
        text: newComment.value,
        date: new Date().toISOString()
    })
    newComment.value = ''
}

// Emit update ke parent kalau data berubah
watch(localComments, () => {
    emit('update', localComments.value)
}, { deep: true })
</script>

<template>
    <section class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Komentar</h3>

        <div v-if="localComments.length" class="space-y-3 mb-3">
            <div v-for="comment in localComments" :key="comment.id" class="border rounded-md p-2 bg-gray-50">
                <p class="text-sm text-gray-700">{{ comment.text }}</p>
                <small class="text-xs text-gray-500">
                    {{ comment.author }} • {{ new Date(comment.date).toLocaleString() }}
                </small>
            </div>
        </div>

        <div class="flex gap-2">
            <input v-model="newComment" type="text" placeholder="Tulis komentar..."
                class="flex-1 border rounded-md p-2 text-sm" />
            <button @click="addComment" class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 text-sm">
                Kirim
            </button>
        </div>
    </section>
</template>
