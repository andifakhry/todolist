<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem, Task } from '@/types'
import KanbanColumn from '@/pages/TodoList/Components/KanbanColumn.vue'
import TaskDetailModel from './TaskDetailModel.vue'


//State untuk modal detail
const selectedTask = ref<Task | null>(null)
const showDetailModal = ref(false)

// Buka modal detail
function openDetailModel(task: Task) {
  selectedTask.value = task
  showDetailModal.value = true
}

//Tutup modal
function closeDetailModel() {
  selectedTask.value = null
  showDetailModal.value = false
}

//Update task
function updateTask(updatedTask: Task) {
  // logika mengganti data task di kolom yang sesuai
  const allColumns = [todoTasks, inProgressTasks, inRenewTasks, doneTasks]
  for (const col of allColumns) {
    const idx = col.value.findIndex(t => t.id === updatedTask.id)
    if (idx !== -1) {
      col.value[idx] = updatedTask
      break
    }
  }
}


// Breadcrumb
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'To-Do List', href: '/todolist' },
]

// State tiap kolom
const todoTasks = ref<Task[]>([
  {
    id: 1,
    title: 'Testing 1',
    description: 'Contoh deskripsi',
    deadline: '2025-11-05',
    expanded: false,
    checklist: [
      { text: 'Buat struktur folder', done: true },
      { text: 'Buat component card', done: false }
    ],
    comments: [{ user: 'Andi', text: 'Sudah dicek ya?' }],
    attachments: []
  }
])
const inProgressTasks = ref<Task[]>([])
const inRenewTasks = ref<Task[]>([])
const doneTasks = ref<Task[]>([])

// Tambah task baru
function addTask(task: { title: string; deadline?: string }) {
  todoTasks.value.push({
    id: Date.now(),
    title: task.title,
    description: '',
    deadline: task.deadline || '',
    expanded: false,
    checklist: [],
    comments: [],
    attachments: [],
  })
}

// Hapus task
function deleteTask({ column, id }: { column: string; id: number }) {
  const map = {
    todo: todoTasks.value,
    inProgress: inProgressTasks.value,
    inRenew: inRenewTasks.value,
    done: doneTasks.value,
  }
  const list = map[column as keyof typeof map]
  const index = list.findIndex(t => t.id === id)
  if (index !== -1) list.splice(index, 1)
}

// Pindahkan task
function moveTask({ column, id }: { column: string; id: number }) {
  const map = {
    todo: todoTasks.value,
    inProgress: inProgressTasks.value,
    inRenew: inRenewTasks.value,
    done: doneTasks.value,
  }
  const order = ['todo', 'inProgress', 'inRenew', 'done']
  const current = map[column as keyof typeof map]
  const nextIndex = order.indexOf(column) + 1
  const next = map[order[nextIndex] as keyof typeof map]
  if (!next) return

  const idx = current.findIndex(t => t.id === id)
  if (idx !== -1) next.push(JSON.parse(JSON.stringify(current.splice(idx, 1)[0])))
}

</script>

<template>

  <Head title="To-Do List" />

  <div class="p-6 flex gap-4 overflow-x-auto">
    <KanbanColumn title="To Do" color="gray" column-key="todo" :tasks="todoTasks" is-addable @add-task="addTask"
      @move="moveTask" @delete="deleteTask" @update-task="updateTask" @detail="openDetailModel" />
    <div class="flex flex-col gap-2 bg-yellow-50 p-12 rounded-lg shadow-sm">
      <h2 class="text-lg font-bild text-center mb-2 text-yellow-800">Process</ h2>
        <div class="flex gap-4">
          <KanbanColumn title="In Progress" color="yellow" column-key="inProgress" :tasks="inProgressTasks"
            @move="moveTask" @delete="deleteTask" @update-task="updateTask" @detail="openDetailModel" />
          <KanbanColumn title="In Renew" color="blue" column-key="inRenew" :tasks="inRenewTasks" @move="moveTask"
            @delete="deleteTask" @update-task="updateTask" @detail="openDetailModel" />
        </div>
    </div>
    <div class="flex gap-4">
      <KanbanColumn title="Done" color="green" column-key="done" :tasks="doneTasks" is-last-column @delete="deleteTask"
        @update-task="updateTask" @detail="openDetailModel" />
    </div>
  </div>
  <Transition name="slide-fade">
    <!-- Detail Modal -->
    <TaskDetailModel v-if="showDetailModal" :task="selectedTask" @close="closeDetailModel" @update-task="updateTask" />

  </Transition>

</template>

<style>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
  transition: all 0.9s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.4s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}
</style>