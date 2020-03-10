<template>
  <div>
      <Map></Map>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import Map from '../../Shared/Map'

export default {
  metaInfo: { title: 'Dashboard' },
  layout: Layout,
  components: {
    Map,
  },
}
</script>
