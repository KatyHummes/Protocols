<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import LinkButton from '@/Components/LinkButton.vue';

const toast = useToast();
const props = defineProps({
    user: Object,
});

const form = useForm('put', route('users.update', props.user.id), {
    name: props.user.name,
    email: props.user.email,
    type: props.user.type,
    cpf: props.user.cpf,
    active: props.user.active,
});

const submit = () => {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.open({
                message: 'Usuário atualizado com sucesso!',
                type: 'success',
                position: 'top-right',
            });
        },
        onError: () => {
            toast.open({
                message: 'Erro ao atualizar usuário!',
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
                Visualizar Usuário
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                    <v-container>
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" value="Nome" class="text-gray-900" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                                    autofocus autocomplete="name" />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Email" class="text-gray-900" />
                                <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full"
                                    disabled autocomplete="username" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="type" value="Perfil" class="text-gray-900" />
                                <SelectInput id="type" v-model="form.type" type="select"
                                    class="mt-1 block w-full bg-slate-50" required autofocus />
                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="cpf" value="CPF" class="text-gray-900" />
                                <TextInput id="cpf" v-model="form.cpf" type="text" class="mt-1 block w-full" disabled
                                    v-mask="'###.###.###-##'" autocomplete="cpf" @change="form.validate('cpf')" />
                                <span v-if="form.invalid('description')" class="text-base text-red-500">
                                    {{ form.errors.description }}
                                </span>
                                <InputError class="mt-2" :message="form.errors.cpf" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="active" value="active" class="text-gray-900" />
                                <select id="active" v-model="form.active"
                                    class="mt-1 block w-full bg-slate-50 rounded-md shadow-sm" autofocus>
                                    <option value="S">Ativo</option>
                                    <option value="N">Desativado</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-between mt-4 ">
                                <LinkButton :href="route('users.index')" class="m-4 text-cyan-600">Voltar</LinkButton>
                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Salvar
                                </PrimaryButton>
                            </div>

                        </form>
                    </v-container>
                </div>
            </div>
        </div>
    </AppLayout>
</template>