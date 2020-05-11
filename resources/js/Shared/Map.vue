<template>
  <MglMap
    class="map"
    :access-token="accessToken"
    :map-style.sync="mapStyle"
    :zoom="12"
    :center="[23.130481,63.838491]"
    @load="onMapLoaded"
  />
</template>

<script>
import Mapbox from 'mapbox-gl'
import { MglMap } from 'vue-mapbox'

export default {
  components: {
    MglMap,
  },
  props: ['equipment'],
  data() {
    return {
      accessToken: 'pk.eyJ1IjoibmllbHMxOTIxIiwiYSI6ImNqdG90MHFrbzBwcmU0YW4yMTE1OWZsYTYifQ.DDwwyIVLf_fzq9187gWkAw', // your access token. Needed if you using Mapbox maps
      mapStyle: 'mapbox://styles/niels1921/ck7n7wfzb0u2y1irtxihv9f3t', // your map style
      map: null,
    }
  },

  created() {

    this.map = null
  },
  methods: {
    onMapLoaded(event) {
      this.map = event.map
      var app = this
      Object.keys(app.equipment).forEach(function (item) {
        console.log(item) // key
        console.log(app.equipment[item]) // value
      })
      // app.map.flyTo({ center: [5.80859,53.20139] })
    },
  },
}
</script>
