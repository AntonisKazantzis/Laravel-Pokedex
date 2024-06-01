<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { IconHeartFilled } from "@tabler/icons-vue";

let props = defineProps({
    pokemons: Object,
    pivot: Object,
});

const damageColor = (baseStat) => {
    if (baseStat <= 50) {
        return "low-damage";
    } else if (baseStat <= 105) {
        return "medium-damage";
    } else if (baseStat <= 150) {
        return "high-damage";
    } else {
        return "very-high-damage";
    }
};

const like = (pokemon) => router.post(route("pokemons.like", { pokemon: pokemon }), {
    preserveScroll: true,
    }
);

const isLiked = (pokemonId) => props.pivot.some((item) => item.pokemon_id === pokemonId);
</script>

<template>
    <AppLayout title="Liked Pokemons">
        <template #header>
            <div class="flex justify-between text-white dark:text-black p-[10px]">
                <h2 class="font-semibold text-xl flex-grow flex items-center justify-start">Liked Pokemons</h2>
            </div>
        </template>

        <div v-if="pokemons.data && pokemons.data.length" class="pt-16 pb-8 border-t sm:hidden">
            <div v-for="pokemon in pokemons.data" :key="pokemon.pokemon_id"
                class="shadow-md border-2 m-auto rounded overflow-hidden mb-8">
                <div class="flex flex-col items-center p-8">
                    <div>
                        <div class="flex">
                            <div class="flex-1 mb-[2vw] relative">
                                <h6 class="bg-[#343541] dark:bg-white dark:text-black text-white mr-[2vw] rounded-full z-10 w-[7vw] h-[7vw] flex items-center justify-center text-[3vw] font-bold absolute left-0 top-0">
                                    {{ (pokemon.height / 10).toFixed(1) }}m
                                </h6>
                            </div>

                            <div class="flex-1 relative">
                                <h6 class="bg-[#343541] dark:bg-white dark:text-black text-white rounded-full -mr-[2vw] z-10 w-[7vw] h-[7vw] flex items-center justify-center text-[3vw] font-bold absolute right-0 top-0">
                                    {{ (pokemon.weight / 10).toFixed(1) }}kg
                                </h6>
                            </div>
                        </div>

                        <Link :href="route('pokemons.show', { pokemon: pokemon.name })" :active="route().current('pokemons.show')" class="flex flex-col items-center">
                            <div class="relative w-[17vw] h-[17vw]">
                                <img :src="pokemon.sprite_1_path" class="relative inset-0 m-auto drop-shadow-xl z-10 w-full h-full object-contain" />
                            </div>

                            <div class="relative text-[#e2e2e1] dark:text-[#e2e2e1] text-[18vw] font-[600] z-0 w-full mx-auto mt-[-40%]">
                                #{{ pokemon.pokemon_id.toString().padStart(4, "0") }}
                            </div>
                        </Link>
                    </div>

                    <div class="text-center mt-[-32px]">
                        <div class="mt-8 flex justify-center">
                            <form @click.prevent="like(pokemon)">
                                <IconHeartFilled :class="{ 'text-red-500 hover:text-gray-400': isLiked(pokemon.id), 'text-gray-400 hover:text-red-500': !isLiked(pokemon.id) }" :size="['8vw']" />
                            </form>
                        </div>

                        <h6 class="capitalize text-[8vw] dark:text-black text-white font-bold">
                            {{ pokemon.name }}
                        </h6>

                        <div class="text-[4vw] mb-2 dark:text-black text-white">
                            {{ pokemon.genera }}
                        </div>

                        <div class="uppercase font-bold text-[4vw] mb-2">
                            <span v-for="(type, index) in pokemon.types" :key="index" :class="type" class="pr-1">
                                {{ type }}
                            </span>
                        </div>
                        <div class="text-[4vw] capitalize font-[400px]">
                            <span v-for="(ability, index) in pokemon.abilities" :key="index" :class="ability"
                                class="capitalize pr-1 dark:text-black text-white">
                                {{ ability }}
                            </span>
                        </div>

                        <div class="mt-8 flex flex-col items-center justify-center">
                            <table class="flex flex-col items-center justify-center capitalize" cellspacing="0">
                                <tbody v-for="(stat, index) in pokemon.stats" :key="index" class="flex items-center justify-center">
                                    <tr class="w-[35vw] text-[4vw] font-bold pr-[4vw] flex justify-end dark:text-black text-white">
                                        {{ stat.name }}
                                    </tr>

                                    <td class="w-[18vw] font-[500px] text-[4vw] flex justify-end pr-[4vw] dark:text-black text-white">
                                        {{ stat.base_stat }}
                                    </td>

                                    <td class="w-[30vw] h-[3vw] mt-[3px] bg-[#e2e2e1] dark:bg-[#e2e2e1] mb-[2px] rounded-[3px] overflow-hidden">
                                        <div :class="damageColor(stat.base_stat)" class="h-full transition-width duration-500 rounded-[3px]" :style="{ width: `${(stat.base_stat / 200) * 100}%`, }"></div>
                                    </td>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <Pagination v-if="pokemons.total > 10" :elements="pokemons" />
        </div>

        <div v-else class="sm:hidden">
            <p class="flex mx-auto items-center justify-center min-h-screen">
                No pokemons found :/
            </p>
        </div>

        <div v-if="pokemons.data && pokemons.data.length" class="pt-16 pb-8 border-t hidden sm:block">
            <div v-for="pokemon in pokemons.data" :key="pokemon.pokemon_id" class="shadow-md border-2 m-auto rounded md:w-[750px] md:h-[290px] w-[98vw] h-[38vw] flex p-8 mb-8">
                <div class="flex-1 mx-auto">
                    <div class="flex mb-4">
                        <div class="flex-1 relative">
                            <h6 class="bg-[#343541] dark:bg-white dark:text-black text-white rounded-full z-10 w-[5vw] h-[5vw] md:w-[40px] md:h-[40px] flex items-center justify-center md:text-[13px] text-[1.7vw] font-bold absolute float-left top-0 left-0">
                                {{ (pokemon.height / 10).toFixed(1) }}m
                            </h6>
                        </div>

                        <div class="flex-1 relative">
                            <h6 class="bg-[#343541] dark:bg-white dark:text-black text-white rounded-full z-10 w-[5vw] h-[5vw] md:w-[40px] md:h-[40px] flex items-center justify-center md:text-[13px] text-[1.7vw] font-bold absolute top-0 right-0">
                                {{ (pokemon.weight / 10).toFixed(1) }}kg
                            </h6>
                        </div>
                    </div>

                    <Link :href="route('pokemons.show', { pokemon: pokemon.name })" :active="route().current('pokemons.show')" class="flex flex-col items-center">
                        <div class="relative md:w-[100px] md:h-[100px] w-[13vw] h-[13vw]">
                            <img :src="pokemon.sprite_1_path" class="relative drop-shadow-xl z-10 mx-auto" />
                        </div>

                        <div class="relative text-[#e2e2e1] dark:text-[#e2e2e1] md:text-[100px] text-[13.2vw] font-[600] z-0 mx-auto md:mt-[-125px] mt-[-16.5vw]">
                            #{{ pokemon.pokemon_id.toString().padStart(4, "0") }}
                        </div>
                    </Link>

                    <div class="text-center mt-[-32px]">
                        <h6 class="capitalize md:text-[25px] text-[3.3vw] dark:text-black text-white font-bold">
                            {{ pokemon.name }}
                        </h6>

                        <div class="md:text-[14px] text-[1.8vw] mb-2 dark:text-black text-white">
                            {{ pokemon.genera }}
                        </div>

                        <div class="uppercase font-bold md:text-[14px] text-[1.8vw] mb-2">
                            <span v-for="(type, index) in pokemon.types" :key="index" :class="type" class="pr-1">
                                {{ type }}
                            </span>
                        </div>
                        <div class="md:text-[14px] text-[1.8vw] capitalize font-[400px]">
                            <span v-for="(ability, index) in pokemon.abilities" :key="index" :class="ability" class="capitalize pr-1 dark:text-black text-white">
                                {{ ability }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 flex flex-col items-end">
                    <div class="mb-4">
                        <form @click.prevent="like(pokemon)">
                            <IconHeartFilled :class="{ 'text-red-500 hover:text-gray-400': isLiked(pokemon.id), 'text-gray-400 hover:text-red-500': !isLiked(pokemon.id) }" :size="['8vw']" />
                        </form>
                    </div>

                    <table class="capitalize md:ml-[-20px] ml-[-4vw]" cellspacing="0">
                        <tbody v-for="(stat, index) in pokemon.stats" :key="index" class="flex">
                            <tr class="md:w-[145px] w-[20vw] md:text-[13px] text-[1.7vw] font-bold md:pr-[30px] pr-[4vw] flex justify-end dark:text-black text-white">
                                {{ stat.name }}
                            </tr>

                            <td class="md:w-[60px] w-[8vw] font-[500px] md:text-[13px] text-[1.7vw] flex justify-end md:pr-[20px] pr-[3vw] dark:text-black text-white">
                                {{ stat.base_stat }}
                            </td>

                            <td class="md:w-[190px] md:h-[12px] w-[25vw] h-[1.6vw] mt-[3px] bg-[#e2e2e1] dark:bg-[#e2e2e1] mb-[10px] rounded-[3px] overflow-hidden">
                                <div :class="damageColor(stat.base_stat)" class="h-full transition-width duration-500 rounded-[3px]" :style="{ width: `${(stat.base_stat / 200) * 100}%` }"></div>
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>

            <Pagination v-if="pokemons.total > 10" :elements="pokemons" />
        </div>

        <div v-else class="hidden sm:block">
            <p class="flex mx-auto items-center justify-center min-h-screen">
                No pokemons found :/
            </p>
        </div>
    </AppLayout>
</template>

<style scoped>
.low-damage {
    background-color: red;
}

.medium-damage {
    background-color: orange;
}

.high-damage {
    background-color: yellow;
}

.very-high-damage {
    background-color: green;
}

.rock {
    color: #b8a038;
}

.ghost {
    color: #705898;
}

.electric {
    color: #c09643;
}

.bug {
    color: #9cb820;
}

.poison {
    color: #a040a0;
}

.normal {
    color: #a8a8a8;
}

.flying {
    color: #9096f0;
}

.fairy {
    color: #f09ad9;
}

.fire {
    color: #f08030;
}

.grass {
    color: #22c02a;
}

.fighting {
    color: #c03028;
}

.water {
    color: #6890f0;
}

.ground {
    color: #e0b668;
}

.steel {
    color: #6d8f9c;
}

.psychic {
    color: #eb2d77;
}

.ice {
    color: #98d8d8;
}

.dragon {
    color: #7038f8;
}

.dark {
    color: #504843;
}

.unknown {
    color: #66625c;
}

.shadow {
    color: #66625c;
}
</style>
