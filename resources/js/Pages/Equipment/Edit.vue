<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('equipment')">Equipment</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="equipment.deleted_at" class="mb-6" @restore="restore">
      This organization has been deleted.
    </trashed-message>
    <div class="bg-white rounded shadow overflow-hidden w-full">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.name" :errors="$page.errors.name" class="pr-6 pb-8 w-full lg:w-1/2" label="Name" />
          <textarea-input v-model="form.description" :errors="$page.errors.description" class="pr-6 pb-8 w-full lg:w-1/2" label="Description" />
          <select-input v-model="form.category_id" :value="equipment.category_id" :errors="$page.errors.category" class="pr-6 pb-8 w-full lg:w-1/2" label="Category">
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select-input>
          <text-input v-model="form.address" :errors="$page.errors.address" class="pr-6 pb-8 w-full lg:w-1/2" label="Address" />
          <text-input v-model="form.postal_code" :errors="$page.errors.postal_code" class="pr-6 pb-8 w-full lg:w-1/2" label="Postal code" />
          <text-input v-model="form.city" :errors="$page.errors.city" class="pr-6 pb-8 w-full lg:w-1/2" label="City" />
          <text-input v-model="form.country" :errors="$page.errors.country" class="pr-6 pb-8 w-full lg:w-1/2" label="Country" />
          <div class="pr-6 pb-8 w-full lg:w-1/2">
            <label class="form-label">Images:</label>
            <div class="form-input p-0">
              <input ref="files" type="file" multiple="multiple" class="hidden" @change="handleFileUploads">
              <div class="p-2">
                <button type="button" class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white" @click="browse">
                  Browse
                </button>
              </div>
              <draggable v-model="form.files">
                <transition-group>
                  <div v-for="(item, index) in form.files" :key="index" class="flex items-center justify-between p-2">
                    <div class="flex-1 pr-1">{{ item.name }} <span class="text-gray-500 text-xs">({{ filesize(item.size) }})</span></div>
                    <button type="button" class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white" @click="remove(index)">
                      Remove
                    </button>
                  </div>
                </transition-group>
              </draggable>
            </div>
            <!--                        <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>                   -->
          </div>
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
          <button v-if="!equipment.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete equipment</button>
          <loading-button :loading="sending" class="btn-indigo button-green ml-auto" type="submit">Update equipment</loading-button>
        </div>
      </form>
    </div>
    <h1 class="mb-8 mt-8 font-bold text-xl text-indigo-400 hover:text-indigo-600">
      Reservations
    </h1>
    <div class="bg-white rounded shadow overflow-x-auto mt-5">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left font-bold">
          <th class="px-6 pt-6 pb-4">Name</th>
          <th class="px-6 pt-6 pb-4">From</th>
          <th class="px-6 pt-6 pb-4">till</th>
          <th class="px-6 pt-6 pb-4" colspan="2" />
        </tr>
        <tr v-for="reservation in reservations" :key="reservation.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 text-blue-500 flex items-center focus:text-indigo-500" :href="route('reservations.create', reservation.equipment_id)">
              {{ reservation.name }}
            </inertia-link>
          </td>
          <td class="border-t">
            <div class="px-6 py-4 flex items-center">
              {{ reservation.from }}
            </div>
          </td>
          <td class="border-t">
            <div class="px-6 py-4 flex items-center">
              {{ reservation.till }}
            </div>
          </td>
          <td class="border-t">
            <div class="px-6 py-4 flex justify-end">
              <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="cancelReservation(reservation.id)">
                Cancel
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="reservations.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No reservations found.</td>
        </tr>
      </table>
    </div>
    <pagination :links="reservations.links" />
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import TrashedMessage from '@/Shared/TrashedMessage'
import TextareaInput from '../../Shared/TextareaInput'
import Pagination from '@/Shared/Pagination'
import draggable from 'vuedraggable'


export default {
  metaInfo() {
    return { title: this.form.name }
  },
  layout: Layout,
  components: {
    TextareaInput,
    Icon,
    LoadingButton,
    SelectInput,
    TextInput,
    Pagination,
    TrashedMessage,
    draggable,

  },
  props: {
    equipment: Object,
    categories: Array,
    reservations: Array,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        name: this.equipment.name,
        category_id: this.equipment.category_id,
        description: this.equipment.description,
        address: this.equipment.address,
        city: this.equipment.city,
        country: this.equipment.country,
        postal_code: this.equipment.postal_code,
        files: this.equipment.files,
      },
      removedMedia: [],
    }
  },
  methods: {
    filesize(size) {
      var i = Math.floor(Math.log(size) / Math.log(1024))
      return (size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i]
    },
    browse() {
      this.$refs.files.click()
    },
    remove(index) {
      let app = this
      if(app.form.files[index].hasOwnProperty('id')){
        this.removedMedia.push(app.form.files[index].id)
      }
      app.form.files.splice(index, 1)

    },
    handleFileUploads(){
      this.form.files = this.form.files.concat(Array.from(this.$refs.files.files))
    },
    submit() {
      this.sending = true
      var data = new FormData()
      data.append('id', this.equipment.id || '')
      data.append('name', this.form.name || '')
      data.append('description', this.form.description || '')
      data.append('category_id', this.form.category_id || '')
      data.append('postal_code', this.form.postal_code || '')
      data.append('address', this.form.address || '')
      data.append('city', this.form.city || '')
      data.append('country', this.form.country || '')
      data.append('removedMedia', JSON.stringify(this.removedMedia) || '')
      for( var i = 0; i < this.form.files.length; i++ ){
        let file
        if(this.form.files[i].hasOwnProperty('id')){
          file = JSON.stringify(this.form.files[i])
        } else {
          file = this.form.files[i]
        }
        data.append('files[' + i + ']', file)
      }

      this.$inertia.post(this.route('equipment.update'), data)
        .then(() => this.sending = false)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this equipment?')) {
        this.$inertia.delete(this.route('equipment.destroy', this.equipment.id))
      }
    },
    cancelReservation(id){
      var data = new FormData()
      data.append('id', id || '')
      data.append('owner', true)
      this.$inertia.post(this.route('reservations.cancel'), data)
        .then(() => this.sending = false)
    },
  },
}
</script>
