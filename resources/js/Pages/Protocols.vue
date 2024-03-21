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

const form = useForm('post', route('protocol.store'), {
    id: props.protocols.id,
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

const dateValidation = (date, maxDaysPast) => {
    const selectedDate = new Date(date);
    const currentDate = new Date();
    const maxDateAllowed = new Date().setDate(currentDate.getDate() - maxDaysPast);

    return [
        () => selectedDate <= currentDate && selectedDate >= maxDateAllowed || `A data deve estar dentro do intervalo de ${maxDaysPast} dias no passado e até o dia atual.`,
    ];
};

// Filtros e paginação
const search = ref('');
const page = ref(1);
const itemsPerPage = 10;

const filteredProtocols = computed(() => {
    const searchTerm = search.value.toLowerCase().trim();
    return props.protocols.filter(protocol => {
        return (
            protocol.people.name.toLowerCase().includes(searchTerm) ||
            protocol.date.toLowerCase().includes(searchTerm) ||
            protocol.id.toString().toLowerCase().includes(searchTerm)
        );
    });
});

const displayedProtocols = computed(() => {
    const start = (page.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredProtocols.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredProtocols.value.length / itemsPerPage);
});

const updatePage = (newPage) => {
    page.value = newPage;
};


// Modal deletar protocolo
const showDelete = ref(false);
const formDelete = ref();

const openDeleteModal = (id) => {
    console.log(id);
    showDelete.value = true;
    formDelete.value = useForm('delete', `/deletar-protocolo/${id}`, {
        id: id
    });
}

const closeDeleteModal = () => {
    showDelete.value = false;
}

const deleteProtocols = () => {
    formDelete.value.submit({
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            toast.success("Protocolo deletado com Sucesso!", {
                position: 'top-right',
            });
        }
    });
}


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
                                    <th class="text-left">Data de Abertura</th>
                                    <th class="text-left">Prazo</th>
                                    <th class="text-left">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="protocol in displayedProtocols" :key="protocol.name">
                                    <td>{{ protocol.id }}</td>
                                    <td>{{ protocol.people.name }}</td>
                                    <td>{{ protocol.date }}</td>
                                    <td>{{ protocol.term }}</td>
                                    <td>
                                        <div class="flex gap-4">
                                            <a :href="route('protocol.show', protocol.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-green-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>

                                            <button size="small" @click="openDeleteModal(protocol.id)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 hover:scale-125 ease-in-out hover:stroke-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
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
    <Modal :show="showDelete" @close="closeDeleteModal">
        <div class="p-4">
            <form @submit.prevent="deleteProtocols()">
                <h2 class="flex items-center justify-center border-b-4 text-xl p-4 m-4 font-bold">Tem certeza que deseja
                    excluir este Protocolo??</h2>
                <div class="flex justify-between">
                    <v-btn type="button" @click="closeDeleteModal">
                        Cancelar
                    </v-btn>
                    <v-btn type="submit">
                        Excluir
                    </v-btn>
                </div>
            </form>
        </div>
    </Modal>

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

                    <div>
                        <v-text-field v-model="form.date" label="Data" type="date" variant="outlined"
                            :rules="[() => dateValidation(form.date, 30, 0)]"
                            @change="form.validate('date')"></v-text-field>
                        <span v-if="form.invalid('date')" class="text-base text-red-500">{{ form.errors.date }}</span>
                    </div>
                    <div>
                        <div class="border-2 border-black rounded-md">
                            <select v-model="form.people_id" class="w-full" placeholder="Clique para Selecionar">
                                <option v-for="people in peoples" :value="people.id">{{ people.name }}</option>
                            </select>
                        </div>
                        <span v-if="form.invalid('people_id')" class="text-base text-red-500">
                            {{ form.errors.people_id }}
                        </span>
                    </div>
                    <div>
                        <v-text-field v-model="form.term" label="Prazo" type="date" variant="outlined"
                            :rules="[() => dateValidation(form.term, 5, 30)]"
                            @change="form.validate('term')"></v-text-field>
                        <span v-if="form.invalid('term')" class="text-base text-red-500">{{ form.errors.term }}</span>
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