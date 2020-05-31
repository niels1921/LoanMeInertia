<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Available equipment</h1>
    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="mt-1 w-full form-select">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
    </div>
    <div v-for="eq in equipment.data" :key="eq.id" class="max-w-sm rounded overflow-hidden shadow-lg card">
      <img class="w-full" :src="getImage(eq)">
      <div class="px-6 py-4">
        <div class="font-bold text-xl mb-2">{{ eq.name }}</div>
        <p class="text-gray-700 text-base">{{ eq.description }}</p>
      </div>
      <div class="px-6 py-4 reservation-button">
        <p class="text-gray-900 leading-none">{{ eq.city }}</p>
        <p class="text-gray-600">{{ eq.postal_code }}</p>
      </div>
      <inertia-link class="btn-indigo button-green " :href="route('reservation.create')">
        <span>Make</span>
        <span class="hidden md:inline">Reservation</span>
      </inertia-link>
    </div>
    <pagination :links="equipment .links" />
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import pickBy from 'lodash/pickBy'
import SearchFilter from '@/Shared/SearchFilter'
import throttle from 'lodash/throttle'

export default {
  metaInfo: { title: 'equipment' },
  layout: Layout,
  components: {
    Icon,
    Pagination,
    SearchFilter,
  },
  props: {
    equipment: Object,
    filters: Object,
    media: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      handler: throttle(function() {
        let query = pickBy(this.form)
        this.$inertia.replace(this.route('equipment', Object.keys(query).length ? query : { remember: 'forget' }))
      }, 150),
      deep: true,
    },
  },
  methods: {
    getImage (eq) {
      if (typeof eq.media !== 'undefined' && eq.media.length > 0) {
        return eq.media[0].url
      }
      return 'https://checkyeti.imgix.net/images/optimized/private-snowboarding-lessons-for-kids-and-adults-all-levels-scuola-di-sci-e-snowboard-alpe-cimbra1.jpg'
    },
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
