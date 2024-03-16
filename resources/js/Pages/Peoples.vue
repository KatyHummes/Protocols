<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref } from 'vue';

const props = defineProps({
    peoples: Array,
});

const openingCreatePeopleModal = ref(false);

const openCreatePeopleModal = () => {
    openingCreatePeopleModal.value = true;
};

const closeCreatePeopleModal = () => {
    openingCreatePeopleModal.value = false;
};

const form = useForm('post', route('people.store'), {
    name: '',
    birth: '',
    cpf: '',
    sex: '',
    city: '',
    neighborhood: '',
    street: '',
    number: '',
    complement: '',
});


const submit = () => form.submit({
    preserveScroll: true,
    onSuccess: () => {
        closeCreatePeopleModal();
        form.reset();
        // toast.success("Pessoa criada com Sucesso!", {
        //     position: 'top-right',
        // });
    }
});
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Peoples
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <v-col cols="auto" class="flex justify-center mb-5">
                            <v-btn size="large" @click="openCreatePeopleModal">Criar Pessoas</v-btn>
                        </v-col>
                        <v-table>
                            <thead>
                                <tr>
                                    <th class="text-left">
                                        Name
                                    </th>
                                    <th class="text-left">
                                        Data de Nascimento
                                    </th>
                                    <th class="text-left">
                                        CPF
                                    </th>
                                    <th class="text-left">
                                        Sexo
                                    </th>
                                    <th class="text-left">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="people in peoples" :key="people.name">
                                    <td>{{ people.name }}</td>
                                    <td>{{ people.birth }}</td>
                                    <td>{{ people.cpf }}</td>
                                    <td>{{ people.sex }}</td>
                                    <td>
                                        <v-btn size="small">Editar</v-btn>
                                        <v-btn size="small">Excluir</v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <Modal :show="openingCreatePeopleModal" @close="closeCreatePeopleModal" :max-width="'3xl'">
        <div class="flex justify-between m-4">
            <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar Pessoas
            </h3>
            <v-btn @click="closeCreatePeopleModal" type="button"
                class="rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </v-btn>
        </div>
        <div>
            <form @submit.prevent="submit">

                <div class="grid grid-cols-5 gap-4 m-4">
                    <div>
                        <label>Nome:*</label>
                        <input type="text" v-model="form.name" label="Nome" class="border border-l-black rounded-2xl" />
                    </div>

                    <div>
                        <label>Data de Nascimento:*</label>
                        <input type="date" v-model="form.birth" label="Data de Nascimento"
                            class="border border-l-black rounded-2xl" />
                    </div>

                    <div>
                        <label>Cpf:*</label>
                        <input type="number" v-model="form.cpf" label="CPF" class="border border-l-black rounded-2xl" />
                    </div>

                    <div>
                        <label>Sexo:*</label>
                        <select v-model="form.sex" >
                            <option value="">--Selecione uma opição--</option>
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>

                </div>

                <div class="m-4 flex justify-around">
                    <v-btn type="submit" class="">
                        Cancelar
                    </v-btn>
                    <v-btn type="submit">
                        Salvar
                    </v-btn>
                </div>

            </form>
        </div>
    </Modal>
</template>