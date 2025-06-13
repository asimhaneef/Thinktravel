<template>
  <div>
    <h3>Two Factor Authentication</h3>
    
    <div v-if="!enabled">
      <button @click="enableTwoFactorAuth">Enable Two-Factor Auth</button>
    </div>
    
    <div v-if="enabled">
      <p>Two-factor authentication is enabled.</p>
      <button @click="disableTwoFactorAuth">Disable</button>
      
      <div v-if="showingQrCode">
        <p>Scan this QR code with your authenticator app:</p>
        <div v-html="qrCodeSvg"></div>
      </div>
      
      <div v-if="showingRecoveryCodes">
        <p>Store these recovery codes securely:</p>
        <ul>
          <li v-for="code in recoveryCodes" :key="code">{{ code }}</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      enabled: false,
      showingQrCode: false,
      qrCodeSvg: '',
      showingRecoveryCodes: false,
      recoveryCodes: []
    }
  },
  
  mounted() {
    this.checkTwoFactorStatus();
  },
  
  methods: {
    async checkTwoFactorStatus() {
      const response = await axios.get('/user');
      this.enabled = response.data.two_factor_enabled;
    },
    
    async enableTwoFactorAuth() {
      const response = await axios.post('/user/two-factor-authentication');
      this.qrCodeSvg = response.data.svg;
      this.recoveryCodes = response.data.recovery_codes;
      this.enabled = true;
      this.showingQrCode = true;
      this.showingRecoveryCodes = true;
    },
    
    async disableTwoFactorAuth() {
      await axios.delete('/user/two-factor-authentication');
      this.enabled = false;
      this.showingQrCode = false;
      this.showingRecoveryCodes = false;
    }
  }
}
</script>