<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    users: Array,
});

// Filtros e paginação
// const search = ref('');
// const page = ref(1);
// const itemsPerPage = 10;

// const filteredPeoples = computed(() => {
//     const searchTerm = search.value.toLowerCase().trim();
//     return props.peoples.filter(people => {
//         return (
//             people.name.toLowerCase().includes(searchTerm) ||
//             people.birth.toLowerCase().includes(searchTerm) ||
//             people.cpf.toLowerCase().includes(searchTerm) ||
//             people.sex.toLowerCase().includes(searchTerm)
//         );
//     });
// });

// const displayedPeoples = computed(() => {
//     const start = (page.value - 1) * itemsPerPage;
//     const end = start + itemsPerPage;
//     return filteredPeoples.value.slice(start, end);
// });

// const pageCount = computed(() => {
//     return Math.ceil(filteredPeoples.value.length / itemsPerPage);
// });

// const updatePage = (newPage) => {
//     page.value = newPage;
// };


</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                gerenciar Usuários  <Link :href="route('users.create')" class="text-blue-500">Novo Usuário</Link>
            </h2>
        </template>
        <v-container>
            <v-table>
                <thead>
                    <tr>
                        <th class="text-left">Id</th>
                        <th class="text-left">Nome</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Perfil</th>
                        <th class="text-left">Ativo</th>
                        <th class="text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.type }}</td>
                        <td>{{ user.ativo }}</td>
                        <td>
                            <div class="flex gap-4">
                                <a :href="route('users.show', user.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>

                                <!-- <button size="small" @click="openDeleteModal(people.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button> -->
                            </div>
                        </td>
                    </tr>
                </tbody>
            </v-table>

        </v-container>
    </AppLayout>
</template>