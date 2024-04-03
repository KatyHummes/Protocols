<script setup>
import { ref, computed, defineProps } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    users: Array,
});

// Filtros e paginação
const search = ref('');
const page = ref(1);
const itemsPerPage = 10;

const filteredusers = computed(() => {
    const searchTerm = search.value.toLowerCase().trim();
    return props.users.filter(user => {
        return (
            user.active.toLowerCase().includes(searchTerm) ||
            user.name.toLowerCase().includes(searchTerm) ||
            user.cpf.toLowerCase().includes(searchTerm) ||
            user.type.toLowerCase().includes(searchTerm)
        );
    });
});

const displayedusers = computed(() => {
    const start = (page.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredusers.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredusers.value.length / itemsPerPage);
});

const updatePage = (newPage) => {
    page.value = newPage;
};

const translateType = (type) => {
    switch (type) {
        case 'T':
            return 'Administrador da TI';
        case 'S':
            return 'Administrador do sistema';
        case 'A':
            return 'Atendente';
        default:
            return 'Desconhecido';
    }
};

</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuários
                <Link v-if="$page.props.auth.user.type === 'T' || $page.props.auth.user.type === 'S'" :href="route('create')">Novo Usuário</Link>
            </h2>
        </template>
        <v-container>
            <v-card title="Pessoas" flat>
                <template v-slot:text>
                    <v-text-field v-model="search" label="Search" prepend-inner-icon="mdi-magnify" variant="outlined"
                        hide-details single-line></v-text-field>
                </template>
            </v-card>
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
                        <td>{{ translateType(user.type) }}</td>
                        <td>{{ user.active }}</td>
                        <td>
                            <div class="flex gap-4">
                                <Link :href="route('users.show', user.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </v-table>
            <div class="text-center pt-2">
                <v-pagination v-model="page" :length="pageCount" @input="updatePage"></v-pagination>
            </div>
        </v-container>
    </AppLayout>
</template>