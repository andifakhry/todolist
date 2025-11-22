<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
    attachments: { id: number; name: string; url: string }[]
}>()

const emit = defineEmits(['update'])
const localAttachments = ref([...props.attachments])

function handleFileUpload(event: Event) {
    const files = (event.target as HTMLInputElement).files
    if (!files) return
    for (const file of files) {
        const url = URL.createObjectURL(file)
        localAttachments.value.push({
            id: Date.now() + Math.random(),
            name: file.name,
            url
        })
    }
}

// Kirim update ke parent jika attachments berubah
watch(localAttachments, () => {
    emit('update', localAttachments.value)
}, { deep: true })
</script>

<template>
    <section class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Lampiran</h3>

        <div v-if="localAttachments.length" class="space-y-2 mb-3">
            <div v-for="file in localAttachments" :key="file.id"
                class="flex justify-between items-center p-2 bg-gray-50 rounded-md">
                <a :href="file.url" target="_blank" class="text-blue-600 hover:underline truncate">
                    {{ file.name }}
                </a>
            </div>
        </div>

        <label
            class="flex items-center justify-center border-dashed border-2 border-gray-300 rounded-md p-4 cursor-pointer hover:bg-gray-50">
            <input type="file" multiple class="hidden" @change="handleFileUpload" />
            <span class="text-sm text-gray-600">Tambah File</span>
        </label>
    </section>
</template>
