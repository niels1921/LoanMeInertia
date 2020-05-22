import Vue from 'vue'
import VueMeta from 'vue-meta'
import PortalVue from 'portal-vue'
import { InertiaApp } from '@inertiajs/inertia-vue'
import VueMapbox from "vue-mapbox";
import Mapbox from "mapbox-gl";
import VModal from 'vue-js-modal'


Vue.config.productionTip = false
Vue.mixin({ methods: { route: window.route } })
Vue.use(InertiaApp)
Vue.use(PortalVue)
Vue.use(VueMeta)
Vue.use(VueMapbox, { mapboxgl: Mapbox });
Vue.use(VModal)

let app = document.getElementById('app')

new Vue({
  metaInfo: {
    titleTemplate: (title) => title ? `${title} - Ping CRM` : 'Ping CRM'
  },
  render: h => h(InertiaApp, {
    props: {
      initialPage: JSON.parse(app.dataset.page),
      resolveComponent: name => import(`@/Pages/${name}`).then(module => module.default),
    },
  }),
}).$mount(app)
