<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref, computed, defineProps } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const toast = useToast();

const props = defineProps({
    peoples: Array,
    protocols: Array,
});

// modal incluir protocolo
const openingModal = ref(false);

const openModal = () => {
    openingModal.value = true;
};

const closeModal = () => {
    openingModal.value = false;
};

const form = useForm('post', route('protocols.store'), {
    id: props.protocols.length + 1,
    description: '',
    term: '',
    date: '',
    people_id: null
});

const submit = () => form.submit({
    preserveScroll: true,
    onSuccess: () => {
        closeModal();
        form.reset();
        toast.success("Protocolo criado com Sucesso!", {
            position: 'top-right',
        });
    }
});


// filtros
const search = ref('');
const page = ref(1);
const itemsPerPage = 10;

const filteredProtocol = computed(() => {
    const searchTerm = search.value.toLowerCase().trim();
    return props.protocols.filter(protocol => {
        return (
            protocol.description.toLowerCase().includes(searchTerm) ||
            protocol.date.toLowerCase().includes(searchTerm) ||
            protocol.people_id.toLowerCase().includes(searchTerm) ||
            protocol.id.toLowerCase().includes(searchTerm)
        );
    });
});

const pageCount = computed(() => {
    return Math.ceil(filteredProtocol.value.length / itemsPerPage);
});

const updatePage = (newPage) => {
    page.value = newPage;
};

// Modal deletar protocolo

</script>

<template>
    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <v-col cols="auto" class="flex justify-center mb-5">
                            <v-btn size="large" @click="openModal">Criar Protocolos</v-btn>
                        </v-col>

                        <v-card title="Protocolos" flat>
                            <template v-slot:text>
                                <v-text-field v-model="search" label="Search" prepend-inner-icon="mdi-magnify"
                                    variant="outlined" hide-details single-line></v-text-field>
                            </template>
                        </v-card>
                        <v-table>
                            <thead>
                                <tr>
                                    <th class="text-left">Número</th>
                                    <th class="text-left">Contribuinte</th>
                                    <th class="text-left">Data</th>
                                    <th class="text-left">Prezo</th>
                                    <th class="text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="protocol in filteredProtocol" :key="protocol.name">
                                    <td>{{ protocol.id }}</td>
                                    <td>{{ protocol.people.name }}</td>
                                    <td>{{ protocol.date }}</td>
                                    <td>{{ protocol.term }}</td>
                                    <td>
                                        <div class="flex gap-4">
                                           
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                        <div class="text-center pt-2">
                            <v-pagination v-model="page" :length="pageCount" @input="updatePage"></v-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

    <!-- Exclusão de Protocolo -->
    <Modal></Modal>

    <!-- Inclusão de Protocolo -->
    <Modal :show="openingModal" @close="closeModal" :max-width="'5xl'">
        <div class="flex justify-between p-6">
            <h3 class="font-semibold text-xl text-gray-800 leading-tight">
                Criar Protocolo
            </h3>
            <v-btn @click="closeModal" type="button"
                class="rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </v-btn>
        </div>
        <div class="p-6">
            <form @submit.prevent="submit">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-7">
                        <v-text-field v-model="form.id" label="Número" variant="outlined" @change="form.validate('id')"
                            disabled></v-text-field>
                        <span v-if="form.invalid('id')" class="text-base text-red-500">
                            {{ form.errors.id }}
                        </span>
                    </div>
                    <div>
                        <v-text-field v-model="form.date" label="Data" type="date" variant="outlined"
                            @change="form.validate('date')"></v-text-field>
                        <span v-if="form.invalid('date')" class="text-base text-red-500">
                            {{ form.errors.date }}
                        </span>
                    </div>
                    <div>
                        <div class="border-2 border-black rounded-md">
                            <select v-model="form.people_id">
                                <option v-for="people in peoples" :value="people.id">{{ people.name }}</option>
                            </select>
                        </div>
                        <span v-if="form.invalid('people_id')" class="text-base text-red-500">
                            {{ form.errors.people_id }}
                        </span>
                    </div>
                    <div>
                        <v-text-field v-model="form.term" label="Prazo" type="date" variant="outlined"
                            @change="form.validate('term')"></v-text-field>
                        <span v-if="form.invalid('term')" class="text-base text-red-500">
                            {{ form.errors.term }}
                        </span>
                    </div>
                    <div>
                        <v-textarea v-model="form.description" label="Descrição" variant="outlined"
                            @change="form.validate('description')"></v-textarea>
                        <span v-if="form.invalid('description')" class="text-base text-red-500">
                            {{ form.errors.description }}
                        </span>
                    </div>
                </div>
                <v-row>
                    <v-col cols="12" class="text-right">
                        <v-btn type="submit" color="primary">Salvar</v-btn>
                    </v-col>
                </v-row>
            </form>
        </div>
    </Modal>
</template>