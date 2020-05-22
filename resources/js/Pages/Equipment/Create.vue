<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">
            <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('equipment')">Equipment</inertia-link>
            <span class="text-indigo-400 font-medium">/</span> Create
        </h1>
        <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
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
                </div>
                <label>
                    <input type="file" id="files" ref="files" multiple v-on:change="handleFileUploads()"/>
                </label>
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
            handleFileUploads(){
                this.files = this.$refs.files.files;
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
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
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
