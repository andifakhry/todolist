<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
    checklist: { id: number; text: string; done: boolean }[]
}>()

const emit = defineEmits(['update'])

const localChecklist = ref([...props.checklist])

function toggleItem(id: number) {
    const item = localChecklist.value.find(c => c.id === id)
    if (item) item.done = !item.done
}

function addItem() {
    localChecklist.value.push({
        id: Date.now() + Math.random(),
        text: '',
        done: false
    })
}

// kirim perubahan ke parent kalau data checklist berubah
watch(localChecklist, () => {
    emit('update', localChecklist.value)
}, { deep: true })
</script>

<template>
    <section class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Checklist</h3>

        <div v-if="localChecklist.length" class="space-y-2 mb-3">
            <div v-for="item in localChecklist" :key="item.id" class="flex items-center gap-2">
                <input type="checkbox" v-model="item.done" @change="toggleItem(item.id)" class="cursor-pointer" />
                <input v-model="item.text" type="text" placeholder="Tulis item..."
                    class="border rounded-md p-1 flex-1 text-sm" />
            </div>
        </div>

        <button @click="addItem" class="text-blue-600 hover:underline text-sm">
            + Tambah Checklist
        </button>
    </section>
</template>
