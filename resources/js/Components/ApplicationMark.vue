<template>
    <img class="w-12 h-12" :src="getImageUrl('image/pokeball.png')" alt="Logo">
</template>

<script setup>
const getImageUrl = (imagePath) => {
    return import.meta.env.BASE_URL + imagePath;
}
</script>
