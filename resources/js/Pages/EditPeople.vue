<script setup>
import { ref, computed, defineProps, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const toast = useToast();

const props = defineProps({
    people: Object,
});

const form = useForm('put', route('people.update', props.people.id), {
    name: props.people.name,
    birth: new Date (props.people.birth),
    cpf: props.people.cpf,
    sex: props.people.sex,
    city: props.people.city,
    neighborhood: props.people.neighborhood,
    street: props.people.street,
    number: props.people.number,
    complement: props.people.complement,
});
 const submit = () => {
    form.put(route('people.update', props.people.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.open({
                message: 'Pessoa atualizada com sucesso!',
                type: 'success',
                position: 'top-right',
            });
        },
        onError: () => {
            toast.open({
                message: 'Erro ao atualizar pessoa!',
                type: 'error',
            });
        },
    });
};

// configuração de datas
const isMenuOpen = ref(false);
const selectedDate = ref()
const formattedDate = computed(() => {
    if (!form.birth) return '';
    const dateObj = new Date(form.birth);
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
                Visualizar Pessoa
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <form @submit.prevent="submit">
                        <h2>Dados Pessoais:</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="mb-7">
                                <v-text-field label="Nome:*" v-model="form.name" variant="outlined"
                                    @change="form.validate('name')"></v-text-field>
                                <span v-if="form.invalid('name')" class="text-base text-red-500">
                                    {{ form.errors.name }}
                                </span>
                            </div>
                            <div>
                                <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                        <v-text-field label="Selecione a data" :model-value="formattedDate"
                                            v-bind="props" variant="outlined"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="form.birth" @change="form.validate('birth')"></v-date-picker>
                                </v-menu>
                                <span v-if="form.invalid('birth')" class="text-base text-red-500">
                                    {{ form.errors.birth }}
                                </span>
                            </div>
                            <div>
                                <v-text-field label="cpf:*" v-model="form.cpf" variant="outlined"
                                    v-mask="'###.###.###-##'" @change="form.validate('cpf')" disabled></v-text-field>
                                <span v-if="form.invalid('cpf')" class="text-base text-red-500">
                                    {{ form.errors.cpf }}
                                </span>
                            </div>
                            <div>
                                <div class="flex">
                                    <h2>sexo:*</h2>
                                    <v-radio-group v-model="form.sex" inline @change="form.validate('sex')">
                                        <v-radio label="Masculino" value="Masculino"></v-radio>
                                        <v-radio label="Feminino" value="Feminino"></v-radio>
                                        <v-radio label="Outro" value="Outro"></v-radio>
                                    </v-radio-group>
                                </div>
                                <span v-if="form.invalid('sex')" class="text-base text-red-500">
                                    {{ form.errors.sex }}
                                </span>
                            </div>
                        </div>
                        <h2>Endereço:</h2>
                        <div class="grid grid-cols-2 gap-4 mb-7">
                            <div>
                                <v-text-field label="Cidade" v-model="form.city" variant="outlined"></v-text-field>
                            </div>
                            <div>
                                <v-text-field label="Bairro" v-model="form.neighborhood"
                                    variant="outlined"></v-text-field>
                            </div>
                            <div>
                                <v-text-field label="Rua" v-model="form.street" variant="outlined"></v-text-field>
                            </div>
                            <div>
                                <v-text-field label="Número" v-model="form.number" variant="outlined"></v-text-field>
                            </div>
                            <div>
                                <v-textarea label="Complemento" v-model="form.complement"
                                    variant="outlined"></v-textarea>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <v-btn type="submit">
                                Salvar
                            </v-btn>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>