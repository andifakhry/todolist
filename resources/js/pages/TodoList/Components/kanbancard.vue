<script setup lang="ts">
import { ref, computed } from 'vue'
import { ArrowRight, Delete } from 'lucide-vue-next'
import type { Task } from '@/types' // pastikan kamu punya interface Task di '@/types'

// Props dari parent
const props = defineProps<{
  task: Task
  columnKey: string
  isLastColumn: boolean
}>()

// Emit event ke parent (KanbanColumn)
const emit = defineEmits(['move', 'delete', 'update-task', 'detail'])

// Toggle expand (buka/tutup deskripsi)
function toggleExpand() {
  props.task.expanded = !props.task.expanded
  emit('update-task', props.task)
}

// Warna berdasarkan deadline
const taskClass = computed(() => {
  const d = props.task.deadline
  if (!d || isNaN(new Date(d).getTime())) return ''
  const deadlineDate = new Date(d)
  const currentDate = new Date()
  const daysDiff = Math.ceil((deadlineDate.getTime() - currentDate.getTime()) / (1000 * 3600 * 24))

  if (daysDiff <= 3) return 'bg-red-100 border-l-4 border-red-400'
  if (daysDiff <= 5) return 'bg-yellow-100 border-l-4 border-yellow-400'
  return 'bg-green-100 border-l-4 border-green-400'
})

// Aksi pindah & hapus
function moveTask() {
  emit('move', { column: props.columnKey, id: props.task.id })
}
function deleteTask() {
  emit('delete', { column: props.columnKey, id: props.task.id })
}

// Klik detail
function showDetail() {
  emit('detail', props.task) // ⬅ kirim langsung task ke KanbanColumn.vue
}
</script>

<template>
  <div class="p-2 mb-2 bg-white rounded-md shadow transition-all hover:scale-[1.01]">
    <div class="p-2 rounded-md shadow-sm" :class="taskClass">
      <div class="flex justify-between items-center">
        <span class="font-medium cursor-pointer" @click="toggleExpand">
          {{ task.title || '(Tanpa judul)' }}
        </span>
      </div>

      <div v-if="task.deadline" class="text-xs text-gray-500 mt-1">
        Deadline: {{ new Date(task.deadline).toLocaleDateString() }}
      </div>

      <!-- Detail konten saat expanded -->
      <div v-if="task.expanded" class="mt-2 text-sm text-gray-600">
        <div class="flex justify-between items-center mb-2">
          <label class="text-gray-500">Keterangan</label>
          <div class="flex items-center gap-2">
            <span class="text-blue-500 hover:text-blue-700 cursor-pointer text-sm font-medium" @click.stop="showDetail">
              Detail
            </span>
            <ArrowRight v-if="!isLastColumn" @click.stop="moveTask"
              class="text-blue-500 hover:text-blue-700 cursor-pointer" />
            <Delete @click.stop="deleteTask" class="text-red-500 hover:text-red-700 cursor-pointer" />
          </div>
        </div>

        <!-- Deskripsi -->
        <textarea v-model="task.description" @click.stop
          class="w-full p-2 border rounded-md resize-none focus:ring-blue-200"
          placeholder="Tambahkan keterangan di sini..."></textarea>

        <!-- Deadline -->
        <div class="mt-2">
          <label class="text-gray-500">Deadline</label>
          <input v-model="task.deadline" @click.stop type="date"
            class="w-full p-2 border rounded-md focus:ring-blue-200" />
        </div>
      </div>
    </div>
  </div>
</template>
