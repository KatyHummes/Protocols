<script setup>
import { ref, computed, defineProps, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import AppLayout from '@/Layouts/AppLayout.vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import axios from 'axios';

const toast = useToast();

const tab = ref('one');

const props = defineProps({
    protocol: Object,
    peoples: Array,
    departments: Array,
    reports: Array,
});

// Editar Protocolos
const form = useForm('put', route('protocol.update', props.protocol.id), {
    id: props.protocol.id,
    date: new Date(props.protocol.date),
    people_id: props.protocol.people_id,
    department_id: props.protocol.department_id,
    term: props.protocol.term,
    description: props.protocol.description,
});

const submit = () => form.submit({
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

// configuração de data do input
const isMenuOpen = ref(false);
const selectedDate = ref()
const formattedDate = computed(() => {
    if (!form.date) return '';
    const dateObj = new Date(form.date);
    return dateObj.toLocaleDateString('pt-BR');
});

watch(selectedDate, (newValue, oldValue) => {
    isMenuOpen.value = false
});

// script Acompanhamentos:
const formReport = useForm('post', route('store.Report'), {
    protocol_id: props.protocol.id,
    description: '',
    status: '',
});

const reportSubmit = () => formReport.submit({
    preserveScroll: true,
    onSuccess: () => {
        formReport.reset();
        toast.success("Pessoa criada com Sucesso!", {
            position: 'top-right',
        });
    },
    onError: () => {
        toast.error("Erro ao atualizar Protocolo!", {
            position: 'top-right',
        });
    }
});

const formatDate = (dateString) => {
    const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const translateStatus = (status) => {
    switch (status) {
        case 'A':
            return 'Aberto';
        case 'E':
            return 'Em atendimento';
        case 'S':
            return 'Solucionado';
    }
};

// configurações das files 

const isImage = (filename) => {
  return /\.(jpg|jpeg|png)$/i.test(filename);
};

const deleteFile = async (attach) => {
    await axios.delete(`/api/docattachs/${attach.id}`);
    props.protocol.docattachs = props.protocol.docattachs.filter(a => a.id !== attach.id);
};


// Função para gerar o PDF
const generatePDF = () => {
    const doc = new jsPDF();

    doc.setFont('helvetica', 'bold');
    doc.setFontSize(16);
    doc.text('Protocolo: ' + props.protocol.id, 10, 10);
    doc.setFontSize(12);
    doc.text(`Data: ${formatDate(form.date)}`, 10, 20);
    doc.text(`Departamento: ${props.departments.find(d => d.id === form.department_id)?.name}`, 10, 30);
    doc.text(`Contribuinte: ${props.peoples.find(p => p.id === form.people_id)?.name}`, 10, 40);

    doc.setFont('helvetica', 'normal');
    let currentY = 50;
    const splitDescription = doc.splitTextToSize(form.description, 180);
    doc.text(splitDescription, 10, currentY);
    currentY += splitDescription.length * 10 + 10;

    props.reports.forEach((report, index) => {
        doc.text(`Relatório ${index + 1}:`, 10, currentY);
        const splitReportDescription = doc.splitTextToSize(report.description, 180);
        doc.text(splitReportDescription, 10, currentY + 10);
        currentY += splitReportDescription.length * 10 + 10;

        doc.text(`Situação: ${translateStatus(report.status)}`, 10, currentY);
        doc.text(`Data: ${formatDate(report.created_at)}`, 10, currentY + 10);
        currentY += 20;

        if (currentY > 280) { // Verifica se precisa de uma nova página
            doc.addPage();
            currentY = 10;
        }
    });

    doc.save(`protocolo-${props.protocol.id}.pdf`);
};

</script>

<template>
    <AppLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <v-card>
                <v-tabs v-model="tab" class="bg-violet-100">
                    <v-tab value="one">Visualizar Protocolo</v-tab>
                    <v-tab value="two">Acompanhamentos</v-tab>
                </v-tabs>
                <v-card-text>
                    <v-window v-model="tab">
                        <v-window-item value="one">
                            <form @submit.prevent="submit">
                                <v-card>
                                    <v-container>
                                        <v-select label="Departamento" :items="departments" item-title="name"
                                            item-value="id" variant="outlined" v-model="form.department_id"
                                            @change="form.validate('department_id')"></v-select>
                                        <span v-if="form.invalid('department_id')" class="text-base text-red-500">
                                            {{ form.errors.department_id }}
                                        </span>
                                    </v-container>

                                    <v-container>
                                        <v-select label="Contribuinte" :items="peoples" item-title="name"
                                            item-value="id" variant="outlined" v-model="form.people_id"
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
                                            <v-date-picker v-model="form.date"
                                                @change="form.validate('date')"></v-date-picker>
                                        </v-menu>
                                        <span v-if="form.invalid('date')" class="text-base text-red-500">
                                            {{ form.errors.date }}
                                        </span>
                                    </v-container>

                                    <v-container>
                                        <v-text-field v-model="form.term" label="Prazo em dias" type="number"
                                            variant="outlined" min="5" max="30"
                                            @change="form.validate('term')"></v-text-field>
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
                                    <div class="flex justify-between items-center">
                                        <Link :href="route('protocols.index')"
                                            class="text-base  font-semibold border-2 border-gray-600 rounded-3xl mx-4 px-4 py-1 hover:bg-purple-800 hover:text-white">
                                        Voltar</Link>

                                        <v-btn class="m-4" type="submit" color="primary">Salvar</v-btn>
                                    </div>
                                </v-card>
                            </form>

                            <h1 class="m-4">Pré-visualização dos Arquivos:</h1>
                                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 m-4">
                                        <div v-for="attach in protocol.docattachs" :key="attach.id"
                                            class="mb-4 rounded-2xl border-slate-100 border-4 bg-white p-4">
                                            <div v-if="isImage(attach.file)">
                                                <img :src="`/storage/${attach.file}`" alt="Image Preview"
                                                    class="max-w-full h-auto rounded">
                                            </div>
                                            <div v-else>
                                                <a :href="`/storage/${attach.file}`" target="_blank">Abrir PDF</a>
                                            </div>
                                            <div class="flex justify-end mt-2">
                                                <!-- <button @click="downloadFile(attach.file)"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                                    Baixar
                                                </button> -->
                                                <button @click="deleteFile(attach)"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                    Excluir
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                        </v-window-item>

                        <v-window-item value="two">
                            <v-dialog max-width="500">
                                <template v-slot:activator="{ props: activatorProps }">
                                    <div class="flex justify-between">
                                        <v-btn v-bind="activatorProps" color="surface-variant" variant="flat"
                                            class="flex justify-end items-end P-8 bg-black rounded-full border-2 border-neutral-500"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4.5v15m7.5-7.5h-15" />
                                            </svg>Acompanhamento
                                        </v-btn>
                                        <v-btn @click="generatePDF" color="surface-variant" variant="flat"
                                            class="flex justify-end items-end p-8 bg-black rounded-full border-2 border-neutral-500">
                                            PDF do Protocolo
                                        </v-btn>
                                    </div>
                                </template>

                                <template v-slot:default="{ isActive }">
                                    <v-card title="Incluir Acompanhamento">
                                        <form @submit.prevent="reportSubmit">
                                            <v-card-text>
                                                <v-container>

                                                    <v-radio-group v-model="formReport.status" inline label="Situação"
                                                        @change="formReport.validate('status')">
                                                        <v-radio label="Aberto" value="A"></v-radio>
                                                        <v-radio label="Em atendimento" value="E"></v-radio>
                                                        <v-radio label="Solucionado" value="S"></v-radio>
                                                    </v-radio-group>
                                                    <span v-if="formReport.errors.status"
                                                        class="text-base text-red-500">
                                                        {{ formReport.errors.status }}
                                                    </span>

                                                    <v-textarea v-model="formReport.description" label="Descrição"
                                                        variant="outlined"
                                                        @change="formReport.validate('description')"></v-textarea>
                                                    <span v-if="formReport.invalid('description')"
                                                        class="text-base text-red-500">
                                                        {{ formReport.errors.description }}
                                                    </span>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn text="Cancelar" @click="isActive.value = false"></v-btn>
                                                <v-btn class="m-4" type="submit" color="primary">Salvar</v-btn>
                                            </v-card-actions>
                                        </form>
                                    </v-card>
                                </template>
                            </v-dialog>
                            <v-card-text>
                                <h1>Acompanhamento Realizados:</h1>

                                <div v-for="report in reports" :key="report.id" cols="12" md="6" ref="updateKey">
                                    <v-card class="m-4">
                                        <v-container>
                                            <div class="flex justify-between m-4">
                                                <div>Situação: {{ translateStatus(report.status) }}</div>
                                                <div>Data: {{ formatDate(report.created_at) }}</div>
                                            </div>
                                            <div>{{ report.description }}</div>
                                        </v-container>
                                    </v-card>
                                </div>
                            </v-card-text>

                        </v-window-item>
                    </v-window>
                </v-card-text>
            </v-card>
        </div>
    </AppLayout>
</template>

<style>
.fixed-size-img {
    width: 200px;
    height: 200px;
    object-fit: cover;
}
</style>