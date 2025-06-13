<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="mb-2 row">
          <div class="col-sm-6">
            <h1 class="m-0">Two Factor Authentication</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="p-0 card-body">
                <!-- Single form that handles both auth code and recovery code -->
                <form @submit.prevent="handleSubmit()" class="p-4">
                  <div v-if="!recoveryMode">
                    <div class="form-group">
                      <label for="code">Authentication Code</label>
                      <input id="code" 
                             v-model="form.code" 
                             type="text" 
                             class="form-control" 
                             :required="!recoveryMode"
                             autofocus>
                    </div>
                  </div>

                  <div v-else>
                    <div class="form-group">
                      <label for="recovery_code">Recovery Code</label>
                      <input id="recovery_code" 
                             v-model="form.recovery_code" 
                             type="text" 
                             class="form-control"
                             :required="recoveryMode">
                    </div>
                  </div>

                  <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                      {{ recoveryMode ? 'Verify Recovery Code' : 'Verify Authentication Code' }}
                    </button>
                    
                    <button type="button" 
                            @click="toggleRecoveryMode"
                            class="btn btn-link ml-2">
                      {{ recoveryMode ? 'Use Authentication Code' : 'Use Recovery Code' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform';
import axios from 'axios';
export default {
  data() {
    return {
      recoveryMode: false,
      form: new Form({
        code: '',
        recovery_code: ''
      }),
      isLoading: false,
      error: null
    }
  },
  methods: {
    toggleRecoveryMode() {
      this.recoveryMode = !this.recoveryMode
      this.error = null
      // Clear the opposite field when toggling
      if (this.recoveryMode) {
        this.form.code = ''
      } else {
        this.form.recovery_code = ''
      }
    },
    async handleSubmit() {
      this.isLoading = true
      this.error = null
      
      try {
        const response = await axios.post('/two-factor-challenge', this.form)
        console.log(response.data)
        // Handle successful verification
        if (response.data.redirect) {
          window.location.href = response.data.redirect
        } else {
          window.location.href = '/dashboard'
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Verification failed'
      } finally {
        this.isLoading = false
      }
    }
  }
}
</script>

<style scoped>
.btn-link {
  text-decoration: none;
}
</style>