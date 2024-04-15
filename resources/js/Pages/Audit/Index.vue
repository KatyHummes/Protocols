<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    audits: Array,
    user: Object,
});

const translateUser = (type) => {
    switch (type) {
        case 'T':
            return 'Administrador da TI'
        case 'S':
            return 'Administrador do Sistema';
        case 'A':
            return 'Atendente';
        default:
            return 'Desconhecido';
    }
};

const translateEvent = (event) => {
    switch(event) {
        case 'created':
            return 'criado'
        case 'updated':
            return 'atualizado';
        case 'deleted':
            return 'excluído';
        default:
            return 'Desconhecido'; 
    }
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Auditorias
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <!-- <div class="font-bold text-center">{{ audits }}</div> -->

                    <v-table>
                        <thead>
                            <tr>
                                <th class="text-left">Id</th>
                                <th class="text-left">Tipo de usuário</th>
                                <th class="text-left">Nome do úsuario</th>
                                <th class="text-left">Evento</th>
                                <th class="text-left">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="audit in audits" :key="audit.id">
                                <td>{{ audit.id }}</td>
                                <td>{{ translateUser(audit.user.type) }}</td>
                                <td>{{ audit.user.name }}</td>
                                <td>{{ translateEvent(audit.event) }}</td>
                                <td>
                                    <Link :href="route('audit.show', audit.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>