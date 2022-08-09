<script setup>
import Earnings from '@/Components/Consultor/Earnings.vue'
import BarChart from '@/Components/Consultor/BarChart.vue'
import PieChart from '@/Components/Consultor/PieChart.vue'
import DashboardLayout from '@/Layouts/Dashboard.vue'
import swal from 'sweetalert2'
import {
  CurrencyDollarIcon,
  ChartBarIcon,
  ChartPieIcon
} from '@heroicons/vue/solid'
import List from '@/Components/List.vue'
import { Head } from '@inertiajs/inertia-vue3'
import { ref, shallowRef } from '@vue/reactivity'
const props = defineProps({
  consultors: Object
})

const currentComponent = shallowRef(null)
const selected = ref(null)
const changeComponent = (component) => {
  selected.value = Object.entries(props.consultors)
    .map((set) => set[1].filter((i) => i.selected).map((i) => i.co_usuario))
    .flat()
  if (selected.value.length > 0) {
    switch (component) {
      case 'earnings':
        currentComponent.value = Earnings
        break
      case 'barchart':
        currentComponent.value = BarChart
        break
      case 'piechart':
        currentComponent.value = PieChart
        break
      default:
        currentComponent.value = null
        break
    }
  } else {
    swal.fire({
      title: 'Error',
      text: 'No ha seleccionado usuarios aún',
      icon: 'error'
    })
  }
}
</script>
<script>
export default {
  layout: DashboardLayout
}
</script>

<template>
  <Head title="Consultors" />
  <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
    <div class="grid grid-cols-1 gap-4">
      <section aria-labelledby="section-1-title">
        <div class="rounded-lg bg-white overflow-hidden shadow">
          <List :users="consultors" />
        </div>
      </section>
    </div>
    <div class="grid grid-cols-1 gap-4 lg:col-span-2">
      <section aria-labelledby="section-2-title">
        <div class="rounded-lg bg-white overflow-hidden shadow">
          <div class="p-6">
            <span
              class="relative z-0 grid grid-cols-3 shadow-sm rounded-md mb-5"
            >
              <button
                type="button"
                class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-blue-400 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                @click="changeComponent('earnings')"
              >
                <CurrencyDollarIcon
                  class="-ml-1 mr-2 h-5 w-5 text-white"
                  aria-hidden="true"
                />
                Ganancias
              </button>
              <button
                type="button"
                class="relative inline-flex items-center px-4 py-2 border border-blue-400 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                @click="changeComponent('barchart')"
              >
                <ChartBarIcon
                  class="-ml-1 mr-2 h-5 w-5 text-white"
                  aria-hidden="true"
                />
                Gráfico
              </button>
              <button
                type="button"
                class="-ml-px relative inline-flex items-center px-3 py-2 rounded-r-md border border-blue-400 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                @click="changeComponent('piechart')"
              >
                <ChartPieIcon
                  class="-ml-1 mr-2 h-5 w-5 text-white"
                  aria-hidden="true"
                />
                Pizza
              </button>
            </span>
            <component :is="currentComponent" :query="selected" />
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<style lang="scss" scoped></style>
