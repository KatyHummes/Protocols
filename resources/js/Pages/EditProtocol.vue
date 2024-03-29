<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref, computed, defineProps, watch } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const toast = useToast();

const props = defineProps({
    protocol: Object,
    peoples: Array,
});

const form = useForm('put', route('protocol.update', props.protocol.id), {
    id: props.protocol.id,
    date: new Date(props.protocol.date),
    people_id: props.protocol.people_id,
    term: props.protocol.term,
    description: props.protocol.description,
});

const submit = () => {
    form.put(route('protocol.update', props.protocol.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.open({
                message: 'Protocolo atualizado com sucesso!',
                type: 'success',
                position: 'top-right',
            });
        },
        onError: () => {
            toast.open({
                message: 'Erro ao atualizar protocolo!',
                type: 'error',
                position: 'top-right',
            });
        },
    });
};

// configuração de datas
const isMenuOpen = ref(false);
const selectedDate = ref()
const formattedDate = computed(() => {
    if (!form.date) return '';
    const dateObj = new Date(form.date);
    return dateObj.toLocaleDateString('pt-BR');
});

watch(selectedDate, (newValue, oldValue) => {
    isMenuOpen.value = false
})
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Visualizar Protocolo
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <form @submit.prevent="submit">
                        <v-card>
                            <v-container>
                                <v-select label="Contribuinte" :items="peoples" item-title="name" item-value="id"
                                    variant="outlined" v-model="form.people_id"
                                    @change="form.validate('people_id')"></v-select>
                                <span v-if="form.invalid('people_id')" class="text-base text-red-500">
                                    {{ form.errors.people_id }}
                                </span>
                            </v-container>

                            <v-container>
                                <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                        <v-text-field label="Selecione a data" :model-value="formattedDate"
                                            v-bind="props" variant="outlined"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="form.date" @change="form.validate('date')"></v-date-picker>
                                </v-menu>
                                <span v-if="form.invalid('date')" class="text-base text-red-500">
                                    {{ form.errors.date }}
                                </span>
                            </v-container>

                            <v-container>
                                <v-text-field v-model="form.term" label="Prazo em dias" type="number" variant="outlined"
                                    min="5" max="30" @change="form.validate('term')"></v-text-field>
                                <span v-if="form.invalid('term')" class="text-base text-red-500">
                                    {{ form.errors.term }}</span>
                            </v-container>

                            <v-container>
                                <v-textarea v-model="form.description" label="Descrição" variant="outlined"
                                    @change="form.validate('description')"></v-textarea>
                                <span v-if="form.invalid('description')" class="text-base text-red-500">
                                    {{ form.errors.description }}
                                </span>
                            </v-container>

                            <v-divider></v-divider>
                            <div class="flex m-4">
                                <v-row>
                                    <v-col cols="12" class="text-right">
                                        <v-btn type="submit" color="primary">Salvar</v-btn>
                                    </v-col>
                                </v-row>
                            </div>
                        </v-card>

                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>