<script setup>
import { reactive } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import NavLink from '@/Components/NavLink.vue';

const rData = reactive({
    notes: {},
})

function loadNotes() {
    axios.get("http://localhost:8000/api/notes").then(response => {
        rData.notes = response.data
    })
}

loadNotes();
</script>

<template>
    <Head title="Note Browser" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Note Browser</h2>
        </template>

        <div class="py-12">
            <table class="table-auto border-separate border border-slate-400">
                <caption>Notes for the current user</caption>
                <thead>
                    <tr>
                        <th class="border border-slate-300">Category</th>
                        <th class="border border-slate-300">Note</th>
                        <th class="border border-slate-300">Preview</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="note in rData.notes">
                        <td class="border border-slate-300">{{ note.category.name }}</td>
                        <td class="border border-slate-300">{{ note.name }}</td>
                        <td class="border border-slate-300">{{ note.text }}</td>
                        <td class="border border-slate-300">
                            <NavLink :href="route('editor', [note.id])" :active="route().current('editor')">
                                Edit
                            </NavLink>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
