<template>
    <ion-footer>
        <ion-toolbar>
            <ion-grid>
                <ion-row>
                    <ion-col size="3">
                        <ion-icon :name="button1Icon" @click="navigateTo(button1Link)"></ion-icon>
                    </ion-col>
                    <ion-col size=" 3">
                        <ion-icon :name="button2Icon" @click="navigateTo(button2Link)"></ion-icon>
                    </ion-col>
                    <ion-col size="3">
                        <ion-icon :name="button3Icon" @click="navigateTo(button3Link)"></ion-icon>
                    </ion-col>
                    <ion-col size="3">
                        <ion-icon :name="button4Icon" @click="navigateTo(button4Link)"></ion-icon>
                    </ion-col>
                </ion-row>
            </ion-grid>
        </ion-toolbar>
    </ion-footer>
</template>

<script setup lang="ts">
import { IonFooter, IonToolbar, IonGrid, IonRow, IonCol, IonIcon } from '@ionic/vue';
import { defineProps } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
    button1Text: String,
    button1Link: String,
    button1Icon: String,
    button2Text: String,
    button2Link: String,
    button2Icon: String,
    button3Text: String,
    button3Link: String,
    button3Icon: String,
    button4Text: String,
    button4Link: String,
    button4Icon: String
});
const router = useRouter();

const navigateTo = (path: string) => {
    router.push(path.startsWith('/') ? path : `/${path}`);
};
</script>

<style scoped>
ion-footer {
    margin-top: auto;
}

ion-row {
    text-align: center;
}

ion-col {
    padding: 0;
}

ion-icon {
    font-size: 32px;
    color: #ad2831;
    cursor: pointer;
}
</style>
