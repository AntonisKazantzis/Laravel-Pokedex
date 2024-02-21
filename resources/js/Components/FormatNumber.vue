<template>
    <div>
        {{ formattedNumber }}
    </div>
</template>

<script setup>
import { ref, defineProps } from "vue";

const props = defineProps(["number"]);
const formattedNumber = ref(formatNumber(props.number));

function formatNumber(number) {
    const suffixes = ["", "K", "M", "B", "T"];
    let index = 0;

    while (number >= 1000) {
        number /= 1000;
        index++;
    }

    const formattedNumber = Number.isInteger(number)
        ? number.toFixed(0)
        : number.toFixed(1);

    return `${formattedNumber}${suffixes[index]}`;
}
</script>
