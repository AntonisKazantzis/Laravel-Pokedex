<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { defineProps } from "vue";
import { Link, router } from "@inertiajs/vue3";
import FormatNumber from "@/Components/FormatNumber.vue";
import DiffForHumans from "@/Components/DiffForHumans.vue";
import {
    IconHeart,
    IconClock,
    IconEye,
    IconHeartFilled,
} from "@tabler/icons-vue";

const props = defineProps({
    pokemon: {
        type: Object,
        default: () => ({}),
    },
    likes: {
        type: Number,
        default: () => ({}),
    },
    pivot: {
        type: Object,
        default: () => ({}),
    },
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

const like = (pokemon) => router.post(route("pokemons.like", { pokemon: pokemon }));
const isLiked = (pokemonId) => props.pivot.some((item) => item.pokemon_id === pokemonId);
</script>

<template>
    <AppLayout title="Pokemons">
        <template #header>
            <div class="flex justify-between text-white dark:text-black p-[10px]">
                <h2 class="font-semibold text-xl flex-grow flex items-center justify-start">Pokemons</h2>
            </div>
        </template>

        <div class="border-t pt-16 pb-16">
            <div
                class="flex flex-col shadow-md border-2 justify-center mx-auto p-4 space-y-4 rounded overflow-hidden min-h-[795px] max-w-[1800px]">
                <!-- Header Section -->
                <div
                    class="header bg-cover flex flex-col sm:flex-row items-center mx-auto w-full p-4 rounded overflow-hidden justify-between">
                    <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <div class="text-[#e2e2e1] dark:text-black text-lg font-semibold leading-tight">
                            #{{ pokemon.pokemon_id.toString().padStart(4, "0") }}
                        </div>

                        <div class="text-lg font-semibold leading-tight">
                            <span class="capitalize dark:text-black text-white">{{ pokemon.name }}</span>
                        </div>

                        <div class="flex dark:text-black text-white text-lg font-semibold leading-tight">
                            Evolves &nbsp;To&nbsp;&nbsp;
                            <div v-for="(evolution, index) in pokemon.evolution_chain" :key="index" :class="evolution">
                                <Link :href="route('pokemons.show', {
                                    pokemon: evolution,
                                })
                                    " :active="route().current('pokemons.show')"
                                    class="border-b-2 border-transparent text-lg font-semibold leading-tight text-white dark:text-black hover:border-indigo-500 focus:outline-none focus:text-indigo-500 dark:focus:text-indigo-500 transition duration-150 ease-in-out capitalize">
                                {{ evolution }}
                                </Link>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-0">
                        <IconHeartFilled :class="{
                            'text-red-500 hover:text-gray-400': isLiked(pokemon.pokemon_id),
                            'text-gray-400 hover:text-red-500': !isLiked(pokemon.pokemon_id),
                        }" :size="28" @click="like(pokemon)" />
                    </div>
                </div>


                <!-- Divider -->
                <hr class="w-full border-t border-white dark:border-gray-900 my-2" />

                <!-- Body Section -->
                <div class="flex-grow mb-4">
                    <span class="relative inline-block">
                        <img class="h-32 w-32 rounded" :src="pokemon.sprite_2_path" />
                    </span>

                    <div class="mb-2 dark:text-black text-white">
                        Height: {{ (pokemon.height / 10).toFixed(1) }}m
                    </div>

                    <div class="mb-2 dark:text-black text-white">
                        Weight: {{ (pokemon.weight / 10).toFixed(1) }}kg
                    </div>

                    <div class="mb-2 dark:text-black text-white">
                        Category: {{ pokemon.genera }}
                    </div>

                    <div class="mb-2 dark:text-black text-white">
                        Type:
                        <span v-for="(type, index) in pokemon.types" :key="index" :class="type"
                            class="pr-1 text-base uppercase font-bold">
                            {{ type }}
                        </span>
                    </div>

                    <div class="capitalize font-[400px] dark:text-black text-white">
                        Abilities:
                        <span v-for="(ability, index) in pokemon.abilities" :key="index" :class="ability"
                            class="capitalize pr-1 text-base dark:text-black text-white">
                            {{ ability }}
                        </span>
                    </div>

                    <div class="flex-1 flex flex-col items-end">
                        <table class="text-[16px] capitalize ml-[-20px] sm:-mt-[145px] mt-[45px]" cellspacing="0">
                            <tbody v-for="(stat, index) in pokemon.stats" :key="index" class="flex">
                                <tr
                                    class="w-[145px] text-[13px] -mt-[2px] font-bold sm:pr-[30px] -pr-[15px] flex justify-end dark:text-black text-white">
                                    {{
                                        stat.name
                                    }}
                                </tr>

                                <td
                                    class="sm:w-[60px] w-[12vw] font-[500px] -mt-[2px] text-[13px] flex justify-end sm:pr-[20px] pr-[3vw] dark:text-black text-white">
                                    {{ stat.base_stat }}
                                </td>

                                <td
                                    class="sm:w-[190px] max-w-[190px] w-[40vw] h-[12px] mt-[3px] bg-[#e2e2e1] dark:border dark:border-black dark:bg-[#e2e2e1] mb-[10px] rounded-[3px] overflow-hidden">
                                    <div :class="damageColor(stat.base_stat)"
                                        class="h-full transition-width duration-500 rounded-[3px]" :style="{
                                            width: `${(stat.base_stat / 200) * 100}%`,
                                        }"></div>
                                </td>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Divider -->
                <hr class="w-full border-t border-white dark:border-gray-900 my-2" />

                <!-- Footer Section -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-between mx-auto w-full dark:text-black text-white">
                    <div class="flex items-center space-x-4 mb-4 sm:mb-0">
                        <span class="font-semibold flex items-center space-x-1">
                            <IconHeart class="mr-1" :size="18" />
                            <FormatNumber :number="likes" />
                        </span>
                        <span class="font-semibold flex items-center space-x-1">
                            <IconEye class="mr-1" :size="18" />
                            <FormatNumber :number="pokemon.views" />
                        </span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <span class="font-semibold flex items-center space-x-1">
                            <IconClock class="mr-1" :size="18" />
                            created
                            <DiffForHumans :date="pokemon.created_at" />
                        </span>
                        <span class="font-semibold flex items-center space-x-1">
                            <IconClock class="mr-1" :size="18" />
                            last updated
                            <DiffForHumans :date="pokemon.updated_at" />
                        </span>
                    </div>
                </div>

            </div>
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

