<script setup>
import { Link } from '@inertiajs/vue3';

const getImageUrl = (imagePath) => {
    return import.meta.env.BASE_URL + imagePath;
}
</script>

<template>
    <Link :href="'/'">
        <img class="w-16 h-16" :src="getImageUrl('image/Pokemon-Ball.png')" alt="Logo">
    </Link>
</template>
