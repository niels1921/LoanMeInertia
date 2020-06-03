<template>
  <div>
      <h1 class="mb-8 font-bold text-3xl">
          <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('dashboard')">Equipment</inertia-link>
          <span class="text-indigo-400 font-medium">/</span> Reservation
      </h1>
    <h4 v-if="equipment !== null" class="p-4 text-center text-white text-lg bg-main-bold">Make a reservation for {{ equipment.name }}</h4>
    <div class="flex text-white">
      <div class="w-1/2 bg-main-light p-4">
        <p class="p-4 text-lg">Information:</p>

        <div class="flex mb-4 text-white">
          <div class="w-1/2 bg-main-light p-4">
            <ul>
              <li>Address: </li>
              <li>City - postcode: </li>
              <li>Country: </li>
            </ul>
          </div>
          <div class="w-1/2 bg-main-light p-4">
            <ul class="p-4 pt-0 text-white">
              <li v-if="equipment !== null">{{ equipment.address }}</li>
              <li v-if="equipment !== null">{{ equipment.city }} {{ equipment.postal_code }}</li>
              <li v-if="equipment !== null">{{ equipment.country }} </li>
            </ul>
          </div>
        </div>

        <h4 class="pb-0 p-4 text-md text-white">Description: </h4>
        <p v-if="equipment !== null" class="p-4 text-white text-md">{{ equipment.description }}</p>
      </div>
      <div class="w-1/2 bg-main-bold p-4">
        <h4 class="text-center text-md p-4 text-white ">Please pick a date:</h4>
        <vc-date-picker
          v-model="date"
          :disabled-dates="unavailableDates"
          is-required
          mode="range"
          :min-date="new Date()"
          is-inline
          class="m-auto "
        />
      </div>
    </div>
      <button class="w-full bg-main-light text-white p-4 hover:bg-main-bold" @click="submitReservation">
        Submit
      </button>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'


export default {
  metaInfo: { title: 'Make a reservation' },
  layout: Layout,
  components: {

  },
  props: {
    equipment: null,
    disabledDates: null,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      date: null,
      attrs: [
        {
          key: 'today',
          dot: true,
          dates: new Date(),
          mode: 'range',
        },
      ],
    }
  },
  computed: {
    unavailableDates: function () {
      let dates = this.disabledDates
      let disabled = []
      dates.forEach(function(date) {
        let item =     {
          start: new Date(date.start.year, date.start.month, date.start.day),
          end: new Date(date.end.year, date.end.month, date.end.day),
        }
        disabled.push(item)
      })
      return disabled
    },
  },
  methods: {
      submitReservation() {
          if (this.date !== null) {
              var data = new FormData()
              let date = {start: this.date.start, end: this.date.end}
              data.append('id', this.equipment.id || '')
              data.append('date', JSON.stringify(date) || '')
              this.$inertia.post(this.route('reservations.store'), data)
                  .then(() => this.$modal.hide('reservation'))
          }
      },

  },

}
</script>
