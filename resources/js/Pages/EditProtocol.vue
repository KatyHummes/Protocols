<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref, computed, defineProps } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const toast = useToast();

const props = defineProps({
    protocol: Object,
    peoples: Array,
});

const form = useForm('put', route('protocol.update', props.protocol.id), {
    id: props.protocol.id,
    date: props.protocol.date,
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

const dateValidation = (date, maxDaysPast) => {
    const selectedDate = new Date(date);
    const currentDate = new Date();
    const maxDateAllowed = new Date().setDate(currentDate.getDate() - maxDaysPast);

    return [
        () => selectedDate <= currentDate && selectedDate >= maxDateAllowed || `A data deve estar dentro do intervalo de ${maxDaysPast} dias no passado e até o dia atual.`,
    ];
};

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
                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <v-text-field v-model="form.date" label="Data" type="date" variant="outlined"
                                    :rules="[() => dateValidation(form.date, 30, 0)]"
                                    @change="form.validate('date')"></v-text-field>
                                <span v-if="form.invalid('date')" class="text-base text-red-500">{{ form.errors.date
                                    }}</span>
                            </div>
                            <div>
                                <div class="border-2 border-black rounded-md">
                                    <select v-model="form.people_id" class="w-full"
                                        placeholder="Clique para Selecionar">
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
                                <span v-if="form.invalid('term')" class="text-base text-red-500">{{ form.errors.term
                                    }}</span>
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
            </div>
        </div>
    </AppLayout>
</template>