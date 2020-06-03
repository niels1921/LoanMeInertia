<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('equipment')">Equipment</inertia-link>
            <span class="text-indigo-400 font-medium">/</span> Create
        </h1>
        <div class="bg-white rounded shadow overflow-hidden w-full">
            <form @submit.prevent="submit">
                <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
                    <text-input v-model="form.name" :errors="$page.errors.name" class="pr-6 pb-8 w-full lg:w-1/2" label="Name" />
                    <textarea-input v-model="form.description" :errors="$page.errors.description" class="pr-6 pb-8 w-full lg:w-1/2" label="Description" />
                    <select-input v-model="form.category_id" :errors="$page.errors.category" class="pr-6 pb-8 w-full lg:w-1/2" label="Category">
                        <option v-for='category in categories' :value='category.id'>{{ category.name }}</option>
                    </select-input>
                    <text-input v-model="form.address" :errors="$page.errors.address" class="pr-6 pb-8 w-full lg:w-1/2" label="Address" />
                    <text-input v-model="form.postal_code" :errors="$page.errors.postal_code" class="pr-6 pb-8 w-full lg:w-1/2" label="Postal code" />
                    <text-input v-model="form.city" :errors="$page.errors.city" class="pr-6 pb-8 w-full lg:w-1/2" label="City" />
                    <text-input v-model="form.country" :errors="$page.errors.country" class="pr-6 pb-8 w-full lg:w-1/2" label="Country" />
                    <div class="pr-6 pb-8 w-full lg:w-1/2">
                        <label class="form-label">Images:</label>
                        <div class="form-input p-0" >
                            <input ref="files" type="file" multiple="multiple" class="hidden" @change="handleFileUploads">
                            <div v-if="form.files === null" class="p-2">
                                <button  type="button" class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white" @click="browse">
                                    Browse
                                </button>
                            </div>
                            <div v-else v-for="(item, index) in form.files" :key="index" class="flex items-center justify-between p-2">
                                <div  class="flex-1 pr-1">{{ item.name }} <span class="text-gray-500 text-xs">({{ filesize(item.size) }})</span></div>
                                <button type="button" class="px-4 py-1 bg-gray-500 hover:bg-gray-700 rounded-sm text-xs font-medium text-white" @click="remove(index)">
                                    Remove
                                </button>
                            </div>
                        </div>
<!--                        <div v-if="errors.length" class="form-error">{{ errors[0] }}</div>                   -->
                    </div>
                </div>

                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
                    <loading-button :loading="sending" class="btn-indigo button-green" type="submit">Create Equipment</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>



<script>
    import Layout from '@/Shared/Layout'
    import LoadingButton from '@/Shared/LoadingButton'
    import SelectInput from '@/Shared/SelectInput'
    import TextInput from '@/Shared/TextInput'
    import FileInput from '@/Shared/FileInput'
    import TextareaInput from '../../Shared/TextareaInput'

    export default {
        metaInfo: { title: 'Create Equipment' },
        layout: Layout,
        components: {
            TextareaInput,
            LoadingButton,
            SelectInput,
            TextInput,
            FileInput,
        },
      props: {
        categories: null,
      },
        remember: 'form',
        data() {
            return {
                sending: false,
                form: {
                    name: null,
                    description: null,
                    category_id: null,
                    postal_code: null,
                    address: null,
                    city: null,
                    country: null,
                    files: null,
                },
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
                console.log(app.form.files)
                app.form.files.splice(index, 1)

            },
            handleFileUploads(){
                this.form.files = Array.from(this.$refs.files.files);
            },
            submit() {
                var data = new FormData()
                data.append('name', this.form.name || '')
                data.append('description', this.form.description || '')
                data.append('category_id', this.form.category_id || '')
                data.append('postal_code', this.form.postal_code || '')
                data.append('address', this.form.address || '')
                data.append('city', this.form.city || '')
                data.append('country', this.form.country || '')
                for( var i = 0; i < this.form.files.length; i++ ){
                    let file = this.form.files[i];
                    data.append('files[' + i + ']', file);
                }

                this.sending = true
                this.$inertia.post(this.route('equipment.store'), data)
                    .then(() => this.sending = false)

                //
                // this.$inertia.post(this.route('equipment.store'), this.form)
                //     .then(() => this.sending = false)
            },

        },

    }
</script>
