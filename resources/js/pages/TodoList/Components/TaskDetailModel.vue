<script setup lang="ts">
import { ref, watch } from 'vue'
import ChecklistSection from './ChecklistSection.vue'
import CommentSection from './CommentSection.vue'
import AttachmentSection from './AttachmentSection.vue'
import type { Task } from '@/types'

// Props
const props = defineProps<{
  task: Task | null
}>()

// Emit ke parent (KanbanBoard)
const emit = defineEmits(['close', 'update-task'])

// Local copy agar bisa edit
const localTask = ref<Task | null>(null)

// State animasi
const isVisible = ref(false)
const isFlipped = ref(false)

// Sync dengan props.task
watch(
  () => props.task,
  (newVal) => {
    if (newVal) {
      localTask.value = JSON.parse(JSON.stringify(newVal))
      isVisible.value = true
      isFlipped.value = false // mulai dari sisi belakang
    } else {
      isVisible.value = false
    }
  },
  { immediate: true }
)

// Simpan perubahan
function saveChanges() {
  if (localTask.value) emit('update-task', localTask.value)
}

// Tutup modal
function closeModal() {
  isVisible.value = false
  emit('close')
}

// Flip kartu
function flipCard() {
  isFlipped.value = true
}
</script>

<template>
  <Transition name="fade">
    <div v-if="isVisible && localTask"
      class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50" @click.self="closeModal">
      <div class="relative w-[600px] h-[900px] aspect -[1/1.56] perspective" @click="flipCard">
        <div class="relative w-full h-full duration-700 transform-style-preserve-3d"
          :class="{ 'rotate-y-180': isFlipped }">
          <!-- BACK SIDE -->
          <div
            class="absolute inset-0 bg-gradient-to-br from-blue-200 to-gray-700 rounded-2xl shadow-l flex flex-col items-center justify-center text-gray-700 font-semibold backface-hidden cursor-pointer ">
            <p>Flip this card</p>
          </div>

          <!-- FRONT SIDE -->
          <div class="absolute inset-0 bg-gradient-to-br from-gray-700 to-blue-200 rounded-2xl shadow-lg overflow-y-auto rotate-y-180 backface-hidden
            px-4
            py-4">
            <!-- HEADER -->
            <header class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold">{{ localTask.title }}</h2>
              <button @click.stop="closeModal" class="text-gray-600 hover:text-red-600 text-lg">
                ✕
              </button>
            </header>

            <!-- DESCRIPTION -->
            <textarea v-model="localTask.description" class="w-full border rounded-md p-2 mb-4"
              placeholder="Tambahkan deskripsi..."></textarea>

            <!-- DEADLINE -->
            <div class="mb-4">
              <label class="block text-gray-600 text-sm mb-1">Deadline</label>
              <input v-model="localTask.deadline" type="date" class="border rounded-md p-2 w-full" />
            </div>

            <!-- CHECKLIST -->
            <ChecklistSection :checklist="(localTask.checklist || []).map((item, index) => ({
              id: (item as any).id ?? index,
              text: item.text,
              done: item.done
            }))" @update="saveChanges" />

            <!-- COMMENT -->
            <CommentSection :comments="(localTask.comments || []).map((item, index) => ({
              id: (item as any).id ?? index,
              author: (item as any).author ?? 'Unknown',
              text: item.text,
              date: (item as any).date ?? new Date().toISOString()
            }))" @update="saveChanges" />


            <!-- ATTACHMENTS -->
            <AttachmentSection :attachments="(localTask.attachments || []).map((item, index) => ({
              id: (item as any).id ?? index,
              name: item.name,
              url: item.url
            }))" @update="saveChanges" />

            <!-- SAVE BUTTON -->
            <footer class="mt-6 flex justify-end">
              <button @click="saveChanges" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Simpan Perubahan
              </button>
            </footer>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.perspective {
  perspective: 1200px;
}

.transform-style-preserve-3d {
  transform-style: preserve-3d;
}

.backface-hidden {
  backface-visibility: hidden;
}

.rotate-y-180 {
  transform: rotateY(180deg);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
