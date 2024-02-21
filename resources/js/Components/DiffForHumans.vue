<template>
    <div>
        {{ formattedDate }}
    </div>
</template>

<script setup>
import { ref, defineProps } from "vue";
import { formatDistanceToNow } from "date-fns";

const props = defineProps(["date"]);
const formattedDate = ref(diffForHumans(props.date));

function diffForHumans(date) {
    return formatDistanceToNow(date, { addSuffix: true });
}
</script>
