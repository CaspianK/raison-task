<template>
    <div class="content">
        <div v-if="currentStore" @click="updateStore(null)">все магазины</div>
        <table>
            <thead>
                <tr>
                    <th>Магазин</th>
                    <th>Дата</th>
                    <th>Сумма</th>
                    <th>Документ</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="purchase in purchases" :key="purchase.id">
                    <td class="link" @click="updateStore(purchase.store.id)">{{ purchase.store.name }}</td>
                    <td>{{ formatDate(purchase.bought_at) }}</td>
                    <td>{{ calculateAmount(purchase.amount, purchase.currency.name) }}</td>
                    <td><a :href="purchase.document.full_path" target="_blank">{{ purchase.document.file_name }}</a>
                    </td>
                    <td>
                        <div class="icons">
                            <EditIcon @click.self="editPurchase(purchase.id)" />
                            <DeleteIcon @click="deletePurchase(purchase.id)" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import requestService from '../services/requestService';
import EditIcon from './icons/EditIcon.vue';
import DeleteIcon from './icons/DeleteIcon.vue';

const emit = defineEmits(['edit', 'update']);

const props = defineProps({
    purchases: Array,
    currencies: Object,
    currentStore: String,
    currentCurrency: String
});

const currentCurrency = ref(props.currentCurrency);
const currencies = ref(props.currencies);
const router = useRouter();
const route = useRoute();

const updateStore = (storeId) => {
    if (storeId) {
        router.push({
            name: 'purchasesWithStoreId',
            params: {
                store_id: storeId
            }
        });
    } else {
        router.push('/purchases');
    }
}

const deletePurchase = async (purchaseId) => {
    const response = await requestService.delete(`/purchases/${purchaseId}`);

    if (response.data) {
        emit('update');
    }
};

const editPurchase = (purchaseId) => {
    emit('edit', purchaseId);
};

const calculateAmount = (amount, currencyCode) => {
    const currency = currentCurrency.value;
    const currenciesList = currencies.value;

    if (!currency) {
        return `${amount} ${currencyCode}`;
    }
    console.log(currenciesList)

    const exchangeRate = currenciesList && currenciesList[currencyCode]?.value || 1;

    console.log('currency', currency)
    console.log('originalCurrency', currencyCode)
    console.log('exchangeRate', exchangeRate)
    console.log('amount', amount)
    return `${Math.round(amount / exchangeRate * 100) / 100} ${currency}`;
}

const formatDate = (value) => {
    return new Date(value).toLocaleDateString('ru-KZ', { year: 'numeric', month: 'long', day: 'numeric' });
};

watch(() => props.currentCurrency, (newCurrency) => {
    currentCurrency.value = newCurrency;
});

watch(() => props.currencies, (newCurrencies) => {
    currencies.value = newCurrencies;
});
</script>
