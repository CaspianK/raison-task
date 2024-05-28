<template>
    <div class="modal">
        <div class="modal__content">
            <div class="modal__close link" @click.self="closeModal">Закрыть</div>
            <div class="error" v-if="error">{{ error }}</div>
            <form @submit.prevent="handleSubmit">
                <div>
                    <label for="store">Магазин:</label>
                    <select name="store" v-model="store">
                        <option v-for="store in stores" :key="store.id" :value="store.id">{{ store.name }}</option>
                    </select>
                </div>
                <div>
                    <label for="product">Дата:</label>
                    <input type="date" name="date" v-model="date" />
                </div>
                <div>
                    <label for="price">Сумма:</label>
                    <input type="number" name="price" v-model="price" />
                </div>
                <div>
                    <label for="currency">Валюта:</label>
                    <select name="currency" v-model="currency">
                        <option v-for="currency in currencies" :key="currency.id" :value="currency.id">{{ currency.name
                            }}</option>
                    </select>
                </div>
                <div>
                    <label for="document">Документ:</label>
                    <input type="file" name="document" @change="handleFileChange" accept=".pdf, .jpg, .jpeg" />
                </div>
                <button class="button" type="submit">{{ submitText }}</button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import requestService from '../services/requestService';

const store = ref(null);
const date = ref(null);
const price = ref(null);
const currency = ref(null);
const document = ref(null);
const stores = ref(null);
const currencies = ref(null);
const error = ref(null);
const emit = defineEmits(['close', 'update']);

const props = defineProps({
    action: String,
    purchaseId: Number,
});

const submitText = computed(() => props.action == 'add' ? 'Добавить' : 'Изменить');

const closeModal = () => {
    emit('close');
};

const fetchStores = async () => {
    try {
        const response = await requestService.get('/stores');
        if (response.data) {
            stores.value = response.data;
        }
    } catch (err) {
        if (err.request.response) {
            error.value = JSON.parse(err.request.response).message;
        } else {
            error.value = 'Failed to submit form';
        }
    }
}

const fetchCurrencies = async () => {
    try {
        const response = await requestService.get('/currencies');
        if (response.data) {
            currencies.value = response.data;
        }
    } catch (err) {
        if (err.request.response) {
            error.value = JSON.parse(err.request.response).message;
        } else {
            error.value = 'Failed to submit form';
        }
    }
}

const handleFileChange = (event) => {
    document.value = event.target.files[0];
};


const handleSubmit = async () => {
    const formData = new FormData();
    if (store.value !== null) formData.set('store_id', store.value);
    if (date.value !== null) formData.set('bought_at', date.value);
    if (price.value !== null) formData.set('amount', price.value);
    if (currency.value !== null) formData.set('currency_id', currency.value);
    if (document.value !== null) formData.set('document', document.value);

    try {
        const options = {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        };

        if (props.action == 'add') {
            const response = await requestService.post('/purchases', formData, options);
        } else {
            formData.append('_method', 'PATCH');
            const response = await requestService.post(`/purchases/${props.purchaseId}`, formData, options);
        }

        if (response.data) {
            emit('update');
            emit('close');
        }
    } catch (err) {
        if (err.request.response) {
            error.value = JSON.parse(err.request.response).message;
        } else {
            error.value = 'Failed to submit form';
        }
    }
};

onMounted(() => {
    fetchCurrencies();
    fetchStores();
});
</script>