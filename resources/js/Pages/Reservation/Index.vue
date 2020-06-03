<template>
    <div>
        <h1 class="mb-8 font-bold text-3xl">My reservations</h1>
        <div class="mb-6 flex justify-between items-center">
            <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
                <label class="block text-gray-700">Trashed:</label>
                <select v-model="form.trashed" class="mt-1 w-full form-select">
                    <option :value="null" />
                    <option value="with">With Trashed</option>
                    <option value="only">Only Trashed</option>
                </select>
            </search-filter>

        </div>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4">Name</th>
                    <th class="px-6 pt-6 pb-4">From</th>
                    <th class="px-6 pt-6 pb-4">Till</th>
                    <th class="px-6 pt-6 pb-4" colspan="2"></th>
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
                            <button v-on:click="cancelReservation(reservation.id)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
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
    import mapValues from 'lodash/mapValues'
    import Pagination from '@/Shared/Pagination'
    import pickBy from 'lodash/pickBy'
    import SearchFilter from '@/Shared/SearchFilter'
    import throttle from 'lodash/throttle'

    export default {
        metaInfo: { title: 'Reservations' },
        layout: Layout,
        components: {
            Icon,
            Pagination,
            SearchFilter,
        },
        props: {
            reservations: Array,
            filters: Object,
        },
        data() {
            return {
                form: {
                    search: this.filters.search,
                    trashed: this.filters.trashed,
                },
            }
        },
        watch: {
            form: {
                handler: throttle(function() {
                    let query = pickBy(this.form)
                    this.$inertia.replace(this.route('reservations', Object.keys(query).length ? query : { remember: 'forget' }))
                }, 150),
                deep: true,
            },
        },
        methods: {
            reset() {
                this.form = mapValues(this.form, () => null)
            },
            cancelReservation(id){
                var data = new FormData()
                data.append('id', id || '')
                this.$inertia.post(this.route('reservations.cancel'), data)
                    .then(() => this.sending = false)
            }
        },
    }
</script>
