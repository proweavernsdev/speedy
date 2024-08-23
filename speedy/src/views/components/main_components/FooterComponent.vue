<template>
    <ion-footer>
        <ion-toolbar>
            <ion-grid>
                <ion-row>
                    <ion-col size="3">
                        <ion-icon :name="button1Icon" @click="navigateTo('button1')"></ion-icon>
                    </ion-col>
                    <ion-col size=" 3">
                        <ion-icon :name="button2Icon" @click="navigateTo('button2')"></ion-icon>
                    </ion-col>
                    <ion-col size="3">
                        <ion-icon :name="button3Icon" @click="navigateTo('button3')"></ion-icon>
                    </ion-col>
                    <ion-col size="3">
                        <ion-icon :name="button4Icon" @click="navigateTo('button4')"></ion-icon>
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

function navigateTo(path: string) {
    if (path === "button1") {
        console.log(props.button1Link);
        router.push("/" + props.button1Link);
    } else if (path === "button2") {
        console.log(props.button2Link);
        router.push("/" + props.button2Link);
    } else if (path === "button3") {
        console.log(props.button3Link);
        router.push("/" + props.button3Link);
    } else {
        console.log(props.button4Link);
        router.push("/" + props.button4Link);
    }

}

</script>

<style scoped>
ion-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
}

ion-toolbar {
    --background: #250902;
}

ion-row {
    text-align: center;
}

ion-col {
    padding: 0;
}

ion-icon {
    font-size: 32px;
    color: #ffffff;
    cursor: pointer;
}
</style>
