<template>
  <MglMap
      class="map"
      :accessToken="accessToken"
      :mapStyle.sync="mapStyle"
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
  data() {
    return {
      accessToken: 'pk.eyJ1IjoibmllbHMxOTIxIiwiYSI6ImNqdG90MHFrbzBwcmU0YW4yMTE1OWZsYTYifQ.DDwwyIVLf_fzq9187gWkAw', // your access token. Needed if you using Mapbox maps
      mapStyle: 'mapbox://styles/mapbox/streets-v11', // your map style
    }
  },

  created() {
    // We need to set mapbox-gl library here in order to use it in template
    this.mapbox = Mapbox

  },
  methods: {
    // onMapLoaded(event) {
    //
    //     this.mapbox.flyTo({ center: [53.20139,5.80859] });
    //
    // },
  },
}
</script>
