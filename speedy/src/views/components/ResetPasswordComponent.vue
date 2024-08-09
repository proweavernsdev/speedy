<template>

    <ion-page>
        <ion-content :fullscreen="true">
            <ion-header>
                <ion-toolbar>
                    <ion-title>Reset Password</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-content class="ion-padding">
                <div id="container">
                    <strong>Reset Password</strong>
                    <p>
                        A simple app to help you get started with Ionic and Vue.
                    </p>
                    <ion-item>
                        <ion-label position="floating">Email</ion-label>
                        <ion-input type="email"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">New Password</ion-label>
                        <ion-input type="password"></ion-input>
                    </ion-item>
                    <ion-item>
                        <ion-label position="floating">Confirm Password</ion-label>
                        <ion-input type="password"></ion-input>
                    </ion-item>
                    <ion-button expand="full" shape="round">Reset Password</ion-button>
                </div>
            </ion-content>
        </ion-content>
    </ion-page>

</template>

<script setup lang="ts">
</script>

<style scoped></style>