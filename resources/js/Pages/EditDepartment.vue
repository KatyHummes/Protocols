<script setup>
import { Link } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import AppLayout from '@/Layouts/AppLayout.vue';

const toast = useToast();
const props = defineProps({
    department: Object,
});

const form = useForm('put', route('departaments.update', props.department.id), {
    name: props.department.name,
});
const submit = () => {
    form.put(route('departaments.update', props.department.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.open({
                message: 'Departamento atualizado com sucesso!',
                type: 'success',
                position: 'top-right',
            });
        },
        onError: () => {
            toast.open({
                message: 'Erro ao atualizar Departamentos!',
                type: 'error',
            });
        },
    });
};
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Visualizar Pessoa
            </h2>
        </template>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <form @submit.prevent="submit">
                    <v-card title="Criar Departamento">
                        <v-container>
                            <v-text-field label="Nome:*" v-model="form.name" variant="outlined"
                                @change="form.validate('name')"></v-text-field>
                            <span v-if="form.invalid('name')" class="text-base text-red-500">
                                {{ form.errors.name }}
                            </span>
                        </v-container>

                        <v-divider></v-divider>

                        <div class="flex justify-between items-center">
                            <Link :href="route('departaments.index')" class="text-base  font-semibold border-2 border-gray-600 rounded-3xl mx-4 px-4 py-1 hover:bg-purple-800 hover:text-white">Voltar</Link>

                            <v-btn class="m-4" type="submit" color="primary">Salvar</v-btn>
                        </div>
                    </v-card>
                </form>
            </div>
        </div>
    </AppLayout>
</template>