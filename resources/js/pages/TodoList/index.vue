<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

import KanbanBoard from '@/pages/TodoList/Components/KanbanBoard.vue'
</script>

<template>
  <Head title="To-doList"></Head>
  <div class="grid grid-cols-5 grid-rows-5 gap-4">
    <div class="col-span-4 col-start-2 row-start-1 text-center text-2xl font-semibold">
      To-Do List
    </div>

    <div class="col-span-4 row-span-3 col-start-2 row-start-2 flex-justify-center items-center place-items-center">
      <KanbanBoard />
    </div>

    <div class="col-span-4 col-start-2 row-start-5 text-right">
      Andi Nur Muhammad Fakhry 2025
    </div>

    <div class="row-span-5 col-start-1 row-start-1">
      side bar
    </div>
  </div>
</template>
