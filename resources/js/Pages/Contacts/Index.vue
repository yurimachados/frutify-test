<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Success/Error Messages -->
        <div v-if="$page.props.flash.message" class="bg-green-500 text-white p-4 mb-4 flex justify-between items-center">
            <span>{{ $page.props.flash.message }}</span>
            <button @click="clearMessage" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div v-if="$page.props.flash.error" class="bg-red-500 text-white p-4 mb-4 flex justify-between items-center">
            <span>{{ $page.props.flash.error }}</span>
            <button @click="clearError" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900">Contatos</h1>
            </div>
        </div>

        <!-- Main content -->
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <!-- Add contact button -->
                <div class="mb-6">
                    <button
                        @click="showCreateForm = true"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Adicionar Contato
                    </button>
                </div>

                <!-- Contacts list -->
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul v-if="contacts.length > 0" role="list" class="divide-y divide-gray-200">
                        <li v-for="contact in contacts" :key="contact.id">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ contact.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ contact.name }}</div>
                                            <div class="text-sm text-gray-500">{{ contact.email }}</div>
                                            <div v-if="contact.phone" class="text-sm text-gray-500">{{ contact.phone }}</div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            @click="editContact(contact)"
                                            class="text-blue-600 hover:text-blue-900 text-sm font-medium"
                                        >
                                            Editar
                                        </button>
                                        <button
                                            @click="deleteContact(contact.id)"
                                            class="text-red-600 hover:text-red-900 text-sm font-medium"
                                        >
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div v-else class="text-center py-6">
                        <p class="text-gray-500">Nenhum contato encontrado.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Contact Modal -->
        <div v-if="showCreateForm" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" @click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    {{ editingContact ? 'Editar Contato' : 'Novo Contato' }}
                </h3>
                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Telefone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        >
                            {{ editingContact ? 'Atualizar' : 'Criar' }}
                        </button>
                        <button
                            type="button"
                            @click="closeModal"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3'

export default {
    props: {
        contacts: {
            type: Array,
            default: () => []
        }
    },
    setup(props) {
        const showCreateForm = ref(false)
        const editingContact = ref(null)
        const form = reactive({
            name: '',
            email: '',
            phone: '',
            processing: false
        })

        const resetForm = () => {
            form.name = ''
            form.email = ''
            form.phone = ''
            form.processing = false
            editingContact.value = null
        }

        const closeModal = () => {
            showCreateForm.value = false
            resetForm()
        }

        const editContact = (contact) => {
            editingContact.value = contact
            form.name = contact.name
            form.email = contact.email
            form.phone = contact.phone || ''
            showCreateForm.value = true
        }

        const submitForm = () => {
            form.processing = true

            if (editingContact.value) {
                // Update existing contact
                router.put(`/contacts/${editingContact.value.id}`, {
                    name: form.name,
                    email: form.email,
                    phone: form.phone
                }, {
                    onSuccess: () => {
                        closeModal()
                    },
                    onFinish: () => {
                        form.processing = false
                    }
                })
            } else {
                // Create new contact
                router.post('/contacts', {
                    name: form.name,
                    email: form.email,
                    phone: form.phone
                }, {
                    onSuccess: () => {
                        closeModal()
                    },
                    onFinish: () => {
                        form.processing = false
                    }
                })
            }
        }

        const deleteContact = (id) => {
            if (confirm('Tem certeza que deseja excluir este contato?')) {
                router.delete(`/contacts/${id}`)
            }
        }

        const clearMessage = () => {
            // Remove a mensagem flash
            router.reload({ only: ['flash'] })
        }

        const clearError = () => {
            // Remove a mensagem de erro
            router.reload({ only: ['flash'] })
        }

        return {
            showCreateForm,
            editingContact,
            form,
            closeModal,
            editContact,
            submitForm,
            deleteContact,
            clearMessage,
            clearError
        }
    }
}
</script>
