<template>
  <div
    class="bg-white border border-gray-300 overflow-y-auto rounded-md max-h-fit-screen"
  >
    <Pie
      v-if="loaded || earnings"
      :chart-data="earnings"
      :chartOptions="chartOptions"
      cssClasses="px-6 py-4"
    />
    <Loader v-else />
  </div>
</template>

<script setup>
import Loader from '@/Components/Loader.vue'
import { ref } from '@vue/reactivity'
import { Pie } from 'vue-chartjs'
import Chart from 'chart.js/auto'
const getRandomColor = () => {
  var letters = '0123456789ABCDEF'.split('')
  var color = '#'
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)]
  }
  return color
}

const props = defineProps({
  query: Array
})
const earnings = ref(null)

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    tooltip: {
      enabled: true,
      callbacks: {
        label: (tooltipItems, data) => {
          console.log(tooltipItems)
          return `R$ ${Number(tooltipItems.raw).toLocaleString('pt-BR')}`
        }
      }
    },
    title: {
      display: true,
      text: 'Gráfica de ganancia neta(pizza)'
    }
  },
  hover: {
    mode: 'index',
    intersec: false
  }
}
const loaded = ref(false)
window.axios
  .get('/netEarningsPie', { params: { q: btoa(JSON.stringify(props.query)) } })
  .then((res) => {
    let data = res.data.data
    let dataset = {
      data: [],
      backgroundColor: []
    }
    for (const user of data) {
      dataset.data.push(Object.values(user)[0]),
        dataset.backgroundColor.push(getRandomColor())
    }
    let labels = data.map((i) => Object.keys(i)[0])
    earnings.value = {
      labels: labels,
      datasets: [dataset],
      type: 'pie'
    }
    loaded.value = true
  })
</script>

<style lang="scss" scoped></style>
