<template>
  <div
    class="bg-white border border-gray-300 overflow-y-auto rounded-md max-h-fit-screen"
  >
    <Bar
      v-if="months || loaded"
      :chart-data="months"
      :chart-options="chartOptions"
      cssClasses="px-6 py-4"
    />
    <Loader v-else />
  </div>
</template>

<script setup>
import Loader from '@/Components/Loader.vue'
import { ref } from '@vue/reactivity'
import { Bar } from 'vue-chartjs'
import { DateTime } from 'luxon'
const readablePeriod = (date) => {
  let localizedDate = DateTime.fromFormat(date, 'MM/yyyy')
    .setLocale('es')
    .toFormat(`MMMM 'de' yyyy`)
  return localizedDate.charAt(0).toUpperCase() + localizedDate.slice(1)
}
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
const months = ref(null)
const loaded = ref(false)
const chartOptions = {
  responsive: true,
  maintainAspectRatio: false
}
window.axios
  .get('/netEarningsBar', { params: { q: btoa(JSON.stringify(props.query)) } })
  .then((res) => {
    let data = res.data.data
    let sorted = { ...data }
    delete sorted['set_cost_median']
    sorted = Object.values(sorted).map((i) => Object.entries(i))
    let dataset = props.query.map((i) => ({
      label: i,
      data: [],
      type: 'bar',
      backgroundColor: getRandomColor()
    }))
    for (const month of sorted) {
      month.map((i) =>
        dataset.find((value) => value.label == i[0]).data.push(i[1])
      )
    }
    const median = {
      label: 'Media costo fijo',
      type: 'line',
      backgroundColor: '#FF5733',
      order: 1,
      data: Object.keys(data).map((i) => data.set_cost_median)
    }
    months.value = {
      labels: Object.keys(data)
        .filter((i) => i != 'set_cost_median')
        .map((month) => readablePeriod(month)),
      datasets: [...dataset, median]
    }
    loaded.value = true
  })
</script>

<style lang="scss" scoped></style>
