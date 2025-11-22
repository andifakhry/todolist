<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import  draggable from 'vuedraggable'
import KanbanCard from './kanbancard.vue'
import type { Task } from '@/types'

const props = defineProps<{
  title: string
  color: string
  columnKey: string
  tasks: Task[]
  isLastColumn?: boolean
  isAddable?: boolean
}>()

const emit = defineEmits([
  'move',
  'delete',
  'update-task-order',
  'update-task',
  'add-task',
  'detail'
])

// Local tasks
const localTasks = ref([...props.tasks])

watch(
  () => props.tasks,
  (newVal) => {
    localTasks.value = [...newVal]
  },
  { deep: true }
)

// Background color
const bgClass = computed(() => {
  return {
    gray: "bg-gray-100",
    yellow: "bg-yellow-100",
    blue: "bg-blue-100",
    green: "bg-green-100"
  }[props.color] || "bg-gray-100"
})

// Add task
const newTask = ref('')
const newDeadline = ref('')

function addTask() {
  if (!newTask.value.trim()) return
  emit('add-task', {
    title: newTask.value,
    deadline: newDeadline.value
  })
  newTask.value = ''
  newDeadline.value = ''
}

function onDragEnd() {
  emit('update-task-order', localTasks.value)
}

function showTaskDetail(task: Task) {
  emit('detail', task)
}
</script>

<template>
  <div :class="`${bgClass} rounded-md w-auto p-7 flex-shrink-0`">
    <h2 class="font-bold mb-2">{{ title }}</h2>

    <div v-if="isAddable" class="flex gap-2 mb-4">
      <input
        v-model="newTask"
        @keyup.enter="addTask"
        type="text"
        placeholder="Tambah task baru..."
        class="flex-1 p-2 border rounded-md"
      />

      <input
        v-model="newDeadline"
        @keyup.enter="addTask"
        type="date"
        class="p-2 border rounded-md"
      />

      <button
        @click="addTask"
        class="px-3 py-2 bg-blue-500 text-white rounded-md"
      >
        Tambah
      </button>
    </div>

    <draggable
      v-model="localTasks"
      :group="'tasks'"
      item-key="id"
      class="min-h-[200px]"
      @end="onDragEnd"
    >
      <template #item="{ element }">
        <KanbanCard
          :task="element"
          :column-key="props.columnKey"
          :is-last-column="props.isLastColumn ?? false"
          @move="emit('move', $event)"
          @delete="emit('delete', $event)"
          @update-task="emit('update-task', $event)"
          @detail="showTaskDetail"
        />
      </template>
    </draggable>
  </div>
</template>
