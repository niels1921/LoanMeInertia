import Vue from 'vue'
import VueMeta from 'vue-meta'
import PortalVue from 'portal-vue'
import { InertiaApp } from '@inertiajs/inertia-vue'
import VueMapbox from "vue-mapbox";
import Mapbox from "mapbox-gl";
import VModal from 'vue-js-modal'
import VCalendar from 'v-calendar';


Vue.config.productionTip = false
Vue.mixin({ methods: { route: window.route } })
Vue.use(InertiaApp)
Vue.use(PortalVue)
Vue.use(VueMeta)
Vue.use(VueMapbox, { mapboxgl: Mapbox });
Vue.use(VModal)
Vue.use(VCalendar, {
    componentPrefix: 'vc',  // Use <vc-calendar /> instead of <v-calendar />
});
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
