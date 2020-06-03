<template>
  <div class="login-page p-6 min-h-screen flex justify-center items-center">
    <div class="w-full max-w-md">
      <h1 class="title-login">LoanMe</h1>
      <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="submit">
        <div class="px-10 py-12">
          <h1 class="text-center font-bold text-3xl">Register</h1>
          <div class="mx-auto mt-6 w-24 border-b-2" />
            <text-input v-model="form.name" :errors="$page.errors.name" class="mt-10" label="Name" type="text" autofocus autocapitalize="off" />
            <text-input v-model="form.email" :errors="$page.errors.email" class="mt-6" label="Email" type="email" autofocus autocapitalize="off" />
          <text-input v-model="form.password" class="mt-6" label="Password" type="password" />
          <text-input v-model="form.passwordRepeat" class="mt-6" label="Repeat password" type="password" />
        </div>
        <div class="px-10 py-4 bg-gray-100 border-t border-gray-200 flex justify-between items-center">
          <inertia-link class="hover:underline" tabindex="-1" :href="this.route('login')">Login?</inertia-link>
          <loading-button :loading="sending" class="button-green btn-indigo" type="submit">Register</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'

export default {
  metaInfo: { title: 'Register' },
  components: {
    LoadingButton,
    TextInput,
  },
  props: {
    errors: Object,
  },
  data() {
    return {
      sending: false,
      form: {
        name: '',
        email: '',
        password: '',
        passwordRepeat: '',
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.post(this.route('register.attempt'), {
        name: this.form.name,
        email: this.form.email,
        password: this.form.password,
        passwordRepeat: this.form.passwordRepeat,
      }).then(() => this.sending = false)
    },
  },
}
</script>
