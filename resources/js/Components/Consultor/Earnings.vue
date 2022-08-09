<script setup>
import Loader from '@/Components/Loader.vue'
import { ref } from '@vue/reactivity'
import { DateTime } from 'luxon'
import { StatusOfflineIcon } from '@heroicons/vue/solid'
const props = defineProps({ query: Array })
const consultors = ref(null)
window.axios
  .get('/earnings', { params: { q: btoa(JSON.stringify(props.query)) } })
  .then((res) => {
    consultors.value = res.data.data
  })
const monetaryValue = (value) =>
  isNaN(value) ? value : `R$ ${Number(value).toLocaleString('pt-BR')}`
const readablePeriod = (date) => {
  let localizedDate = DateTime.fromFormat(date, 'MM/yyyy')
    .setLocale('es')
    .toFormat(`MMMM 'de' yyyy`)
  return localizedDate.charAt(0).toUpperCase() + localizedDate.slice(1)
}
const hasAny = (object) => Object.entries(object).length > 0
</script>

<template>
  <div
    class="bg-white border border-gray-300 overflow-y-auto rounded-md max-h-fit-screen"
  >
    <ul v-if="consultors" role="list" class="divide-y divide-gray-300">
      <li
        class="px-6 py-4"
        v-for="consultor in consultors"
        :key="consultor.co_usuario"
      >
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
              <h1 class="text-xl font-semibold text-gray-900">
                {{ consultor.no_usuario }}
              </h1>
              <div class="grid grid-cols-2">
                <p class="mt-2 text-sm text-gray-700">Costo Fijo</p>
                <p class="mt-2 text-sm text-gray-700 text-right">
                  {{ monetaryValue(consultor.set_cost) }}
                </p>
              </div>
            </div>
          </div>
          <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div
                class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"
              >
                <div
                  class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"
                >
                  <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                      <tr>
                        <th
                          scope="col"
                          class="whitespace-nowrap py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                        >
                          Periodo
                        </th>
                        <th
                          scope="col"
                          class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Ganancias netas
                        </th>
                        <th
                          scope="col"
                          class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Comision
                        </th>
                        <th
                          scope="col"
                          class="whitespace-nowrap px-2 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Lucro
                        </th>
                      </tr>
                    </thead>
                    <tbody
                      class="divide-y divide-gray-200 bg-white"
                      v-if="hasAny(consultor.invoices)"
                    >
                      <tr
                        v-for="(salary, period) in consultor.invoices"
                        :key="`${consultor.co_usuario}-${period}`"
                      >
                        <td
                          class="whitespace-nowrap py-2 pl-4 pr-3 text-sm text-gray-500 sm:pl-6"
                        >
                          {{ readablePeriod(period) }}
                        </td>
                        <td
                          class="whitespace-nowrap px-2 py-2 text-sm font-medium text-gray-900"
                        >
                          {{ monetaryValue(salary.net_earning) }}
                        </td>
                        <td
                          class="whitespace-nowrap px-2 py-2 text-sm text-gray-900"
                        >
                          {{ monetaryValue(salary.commission) }}
                        </td>
                        <td
                          class="whitespace-nowrap px-2 py-2 text-sm text-gray-500"
                        >
                          {{ monetaryValue(salary.profit) }}
                        </td>
                      </tr>
                    </tbody>
                    <tbody class="divide-y divide-gray-200 bg-white" v-else>
                      <tr>
                        <td
                          scope="row"
                          colspan="4"
                          class="whitespace-nowrap center-items py-2 pl-4 pr-3 text-xl text-gray-500 sm:pl-6 text-center"
                        >
                          <div class="inline-flex center-items">
                            <StatusOfflineIcon class="-ml-1 mr-2 h-6 w-6" /> No
                            se ha encontrado ningún ingreso relacionado a este
                            consultor
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
    <Loader v-else />
  </div>
</template>
<style scoped></style>
