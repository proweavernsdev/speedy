<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title>Account Status</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <div class="ion-text-center">
                <div v-if="status === 'approved'">
                    <img src="/src/assets/loading.gif" alt="Loading" />
                </div>

                <div v-else-if="status === 'denied'" class="status-box ion-padding">
                    <h1 class="ion-text-bold">Account Denied</h1>
                    <p class="ion-text-bold">
                        🚫 We regret to inform you that your account application has been denied.
                    </p>
                    <p>
                        Our team has carefully reviewed your submission, and unfortunately, we are unable to approve
                        your account at this time. We apologize for any inconvenience this may cause.
                    </p>
                    <p>
                        If you believe this decision was made in error or have any questions, please contact our support
                        team.
                    </p>
                    <p>Thank you for considering us, and we wish you the best in your endeavors.</p>

                    <ion-router-link :to="{ path: '/login' }">
                        <ion-button expand="block" fill="outline">
                            Already have an account? Login
                        </ion-button>
                    </ion-router-link>
                </div>

                <div v-else-if="status === 'pending'" class="status-box ion-padding">
                    <h1 class="ion-text-bold">Account Approval Status</h1>
                    <p class="ion-text-bold">🔍 Your Account is Pending Approval</p>
                    <p>
                        Thank you for registering with us! We appreciate your interest in joining our community.
                        However, your account is currently under review.
                    </p>
                    <p>We apologize for any inconvenience and appreciate your understanding.</p>
                    <p class="ion-text-bold">🌟 Thank you for choosing us!</p>

                    <ion-router-link :to="{ path: '/login' }">
                        <ion-button expand="block" fill="outline">
                            Already have an account? Login
                        </ion-button>
                    </ion-router-link>
                </div>

                <div v-else-if="status === 'blocked'" class="status-box ion-padding">
                    <h1 class="ion-text-bold">🚫 Account Blocked</h1>
                    <p>Your account has been temporarily blocked due to a violation of our terms of use. Our team has
                        detected suspicious activity.</p>
                    <p class="ion-text-bold">Reasons for Account Blocking:</p>
                    <ul>
                        <li><strong>Malware:</strong> Sending harmful software.</li>
                        <li><strong>Phishing:</strong> Attempting to steal private information.</li>
                        <li><strong>Interfering with systems:</strong> Harming or spoofing our networks.</li>
                    </ul>

                    <p class="ion-text-bold">Next Steps:</p>
                    <ol>
                        <li>Contact support if you believe this is an error.</li>
                        <li>Unlock your account by requesting a security code.</li>
                    </ol>

                    <p>Thank you for choosing us. We hope to resolve this issue promptly.</p>

                    <ion-router-link :to="{ path: '/login' }">
                        <ion-button expand="block" fill="outline">
                            Already have an account? Login
                        </ion-button>
                    </ion-router-link>
                </div>
            </div>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { retrieveData } from '../../../services/api/api.js';
import { compRetrieveData, userRetrieveData, driverRetrieveData } from '../../../services/api/api.js';

const router = useRouter();
const accessLevelNumber = ref('');
const information = ref('');
const status = ref('');
const userType = ref('');

onMounted(() => {
    try {
        retrieveData().then(data => {
            const level = JSON.stringify(data.data.UserAccess);
            accessLevelNumber.value = level;

            if (level === 'null') {
                router.push({
                    path: '/access-level',
                    query: {
                        marker: btoa(data.data.UserID),
                        email: btoa(data.data.Email),
                        level: btoa(data.data.UserAccess),
                        company: btoa(data.data.Company),
                    },
                });
            } else {
                switch (level) {
                    case '"0"':
                        userType.value = 'Super Admin';
                        router.push(`/admin/dashboard`);
                        break;
                    case '"1"':
                        userType.value = 'Admin';
                        router.push(`/admin/dashboard`);
                        break;
                    case '"2"':
                        compRetrieveData().then(dat => {
                            information.value = dat.result;
                            status.value = information.value.status;
                        });
                        break;
                    case '"3"':
                        userRetrieveData().then(dat => {
                            information.value = dat.data;
                            userType.value = 'Customer';
                            router.push(`/customer/dashboard`);
                        });
                        break;
                    case '"4"':
                        driverRetrieveData().then(dat => {
                            information.value = dat.result;
                            status.value = information.value.status;
                        });
                        break;
                    case '"5"':
                        userType.value = 'Company Employee';
                        router.push(`/company/user/dashboard`);
                        break;
                    default:
                        router.push('/error');
                }
            }
        }).catch(error => {
            console.error('Error fetching data:', error);
        });
    } catch (error) {
        console.error('Error:', error);
    }
});
</script>

<style scoped>
.status-box {
    background-color: white;
    color: #630617;
    border-radius: 12px;
    padding: 20px;
    max-width: 1080px;
    margin: auto;
}
ion-content {
    --background: #250902;
    --height: 100vh;
}

ion-input {
    --background: #ffffff;
}
</style>