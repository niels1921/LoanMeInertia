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
          <inertia-link class="flex items-center group py-3" :href="route('reservations')">
              <icon name="office" class="w-4 h-4 mr-2" :class="isUrl('reservations') ? 'fill-white' : 'fill-white group-hover:fill-white'" />
              <div :class="isUrl('reservations') ? 'main-menu-color' : 'text-white group-hover:text-white'">My reservations</div>
          </inertia-link>
      </div>

    <!--<div class="mb-4">-->
      <!--<inertia-link class="flex items-center group py-3" :href="route('contacts')">-->
        <!--<icon name="users" class="w-4 h-4 mr-2" :class="isUrl('contacts') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />-->
        <!--<div :class="isUrl('contacts') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Contacts</div>-->
      <!--</inertia-link>-->
    <!--</div>-->
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('categories')">
        <icon name="users" class="w-4 h-4 mr-2" :class="isUrl('categories') ? 'fill-white' : 'fill-white group-hover:fill-white'" />
        <div :class="isUrl('categories') ? 'main-menu-color' : 'text-white group-hover:text-white'">Categories</div>
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
