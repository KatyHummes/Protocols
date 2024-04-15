<script setup>
import { defineProps } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    audit: Object,
});

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Visualizar Auditoria
            </h2>
        </template>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
                <div class="font-bold text-lg mb-4 text-center">Auditoria: {{ audit.id }}</div>
                <div class="grid grid-cols-4 mb-4">
                    <div>
                        <span>Usuário: </span>
                        <span>{{ audit.user.name }}</span>
                    </div>
                    <div>
                        <span>Evento: </span>
                        <span>{{ audit.event }}</span>
                    </div>
                    <div>
                        <span>Tipo de Auditoria: </span>
                        <span>{{ audit.auditable_type.split('\\').pop() }}</span>
                    </div>
                    <div>
                        <span>ID Auditado: </span>
                        <span>{{ audit.auditable_id }}</span>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div v-if="audit.old_values">
                        <h2 class="font-bold">Valores Antigos:</h2>
                        <div v-for="(value, key) in audit.old_values" :key="key">
                            <div>
                                <span>{{ capitalize(key) + ': ' }}</span>
                                <span>{{ value }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="audit.new_values && Object.keys(audit.new_values).length">
                        <h2 class="font-bold">Novos Valores:</h2>
                        <div v-for="(value, key) in audit.new_values" :key="key">
                            <div>
                                <span>{{ capitalize(key) + ': ' }}</span>
                                <span>{{ value }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>