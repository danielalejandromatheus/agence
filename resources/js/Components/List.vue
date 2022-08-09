<script setup>
import Loader from '@/Components/Loader.vue'
const props = defineProps({ users: Object })
const selectUser = (user) => {
  user.selected = !user.selected
}
const c = [
  'bg-green-500',
  'bg-blue-500',
  'bg-yellow-500',
  'bg-gray-500',
  'bg-red-500',
  'bg-purple-500'
]
const changeStatus = (state) => {
  Object.entries(props.users).map((set) => {
    set[1].map((user) => {
      user.selected = state
    })
  })
}
const selectByLetter = (letter) => {
  let state =
    props.users[letter].filter((i) => i.selected).length <
    props.users[letter].length
  //If all items are selected, clear state
  props.users[letter].map((user) => {
    user.selected = state
  })
}
Object.entries(props.users).map((set) => {
  set[1].map((user) => {
    user.className = c.sort(() => 0.5 - Math.random())[0]
  })
})
const getInitials = (name) =>
  name
    .match(/(^\S\S?|\s\S)?/g)
    .map((v) => v.trim())
    .join('')
    .match(/(^\S|\S$)?/g)
    .join('')
    .toLocaleUpperCase()
</script>

<template>
  <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6 s">
    <h3 class="text-lg leading-6 font-medium text-gray-900">
      Lista de consultores
    </h3>
  </div>
  <span class="relative z-0 grid grid-cols-2 shadow-sm rounded-md mb-3">
    <button
      type="button"
      class="relative items-center px-4 py-2 border border-blue-400 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
      @click="changeStatus(true)"
    >
      Seleccionar todos
    </button>
    <button
      type="button"
      class="-ml-px relative items-center px-4 py-2 border border-blue-400 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
      @click="changeStatus(false)"
    >
      Limpiar selección
    </button>
  </span>
  <Loader v-if="users.length == 0" />
  <nav class="h-fit-screen overflow-y-auto" aria-label="Directory" v-else>
    <div v-for="letter in Object.keys(users)" :key="letter" class="relative">
      <div
        class="z-10 sticky top-0 border-t border-b border-gray-200 bg-gray-50 px-6 py-1 text-sm font-medium text-gray-500 cursor-pointer hover:bg-blue-300"
        @click="selectByLetter(letter)"
      >
        <h3>{{ letter }}</h3>
      </div>
      <ul role="list" class="relative z-0 divide-y divide-gray-200">
        <li
          v-for="person in users[letter]"
          :key="person.co_usuario"
          class="cursor-pointer"
          :class="person.selected ? 'bg-blue-200' : 'bg-white'"
          @click="selectUser(person)"
        >
          <div
            class="relative px-6 py-5 flex items-center space-x-3 focus-within:ring-2 hover:bg-blue-300 focus-within:ring-inset focus-within:ring-indigo-500"
          >
            <div class="flex-shrink-0">
              <span
                class="inline-flex items-center justify-center h-10 w-10 rounded-full"
                :class="person.className"
              >
                <span class="text-sm font-medium leading-none text-white">{{
                  getInitials(person.no_usuario)
                }}</span>
              </span>
            </div>
            <div class="flex-1 min-w-0">
              <span class="absolute inset-0" aria-hidden="true" />
              <p class="text-sm font-medium text-gray-900">
                {{ person.no_usuario }}
              </p>
              <p class="text-sm text-gray-500 truncate">
                {{ person.co_usuario }}
              </p>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>
<style scoped>
.h-fit-screen {
  height: calc(100vh - 385px);
}
</style>
