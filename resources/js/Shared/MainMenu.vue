<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('dashboard')">
        <icon name="dashboard" class="w-4 h-4 mr-2" :class="isUrl('') ? 'fill-white' : 'fill-white group-hover:fill-white'" />
        <div :class="isUrl('') ? 'main-menu-color' : 'text-white group-hover:text-white'">Dashboard</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('equipment')">
        <icon name="office" class="w-4 h-4 mr-2" :class="isUrl('equipment') ? 'fill-white' : 'fill-white group-hover:fill-white'" />
        <div :class="isUrl('equipment') ? 'main-menu-color' : 'text-white group-hover:text-white'">My equipment</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('categories')">
        <icon name="users" class="w-4 h-4 mr-2" :class="isUrl('categories') ? 'fill-white' : 'fill-white group-hover:fill-white'" />
        <div :class="isUrl('categories') ? 'main-menu-color' : 'text-white group-hover:text-white'">Categories</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('categories')">
        <icon name="location" class="w-4 h-4 mr-2" :class="isUrl('categories') ? 'fill-white' : 'fill-white group-hover:fill-white'" />
        <div :class="isUrl('categories') ? 'main-menu-color' : 'text-white group-hover:text-white'">Reservations</div>
      </inertia-link>
    </div>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
  },
  props: {
    url: String,
  },
  methods: {
    isUrl(...urls) {
      if (urls[0] === '') {
        return this.url === ''
      }

      return urls.filter(url => this.url.startsWith(url)).length
    },
  },
}
</script>
