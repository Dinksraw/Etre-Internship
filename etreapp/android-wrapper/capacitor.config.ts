import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.etrefeedback.app',
  appName: 'Etre Feedback',
  webDir: 'dist',
  server: {
    url: 'https://etre-feedback.byethost7.com',   // <-- your hosted web app
    cleartext: false                  // true only if http (not recommended)
  }
};

export default config;
