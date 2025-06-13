<template>
  <div>
    <div v-if="!enabled">
      <button @click="enable2FA" class="btn btn-primary">Enable Two-Factor Authentication</button>
    </div>

    <div v-if="showQrCode">
      <h3>Scan this QR code</h3>
      <div v-html="qrCode"></div>
      
      <h3>Or enter this code manually</h3>
      <p>{{ recoveryCodes[0] }}</p> <!-- Show just one code as example -->
      
      <h3>Enter verification code</h3>
      <input v-model="verificationCode" type="text" placeholder="Enter code">
      <button @click="verify2FA" class="btn btn-success">Verify</button>
    </div>

    <div v-if="enabled">
      <h3>Two-Factor Authentication is enabled</h3>
      <button @click="disable2FA" class="btn btn-danger">Disable Two-Factor Authentication</button>
      
      <div v-if="showRecoveryCodes">
        <h4>Recovery Codes</h4>
        <ul>
          <li v-for="code in recoveryCodes" :key="code">{{ code }}</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      enabled: false,
      showQrCode: false,
      qrCode: '',
      recoveryCodes: [],
      verificationCode: '',
      showRecoveryCodes: false
    };
  },
  mounted() {
    this.check2FAStatus();
  },
  methods: {
    check2FAStatus() {
      axios.get('/api/user').then(response => {
        this.enabled = response.data.two_factor_enabled;
      });
    },
    enable2FA() {
      axios.post('/api/user/two-factor-authentication')
        .then(response => {
          this.qrCode = response.data.qr_code;
          this.recoveryCodes = response.data.recovery_codes;
          this.showQrCode = true;
        });
    },
    verify2FA() {
      axios.post('/api/user/two-factor-verification', {
        code: this.verificationCode
      }).then(() => {
        this.enabled = true;
        this.showQrCode = false;
        this.showRecoveryCodes = true;
      }).catch(error => {
        alert(error.response.data.error || 'Verification failed');
      });
    },
    disable2FA() {
      axios.delete('/api/user/two-factor-authentication')
        .then(() => {
          this.enabled = false;
          this.showRecoveryCodes = false;
        });
    }
  }
};
</script>