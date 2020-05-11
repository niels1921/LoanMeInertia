<template>
  <div>
    <Map :equipment="equipment" />
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
  props: {
    equipment: Array,
  },
}
</script>
