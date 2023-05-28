<template>
    <div class="container pt-5">
        <div class="row mb-5">
            <div class="col-6">
                <h2>Выберите начало периода</h2>
                <datetime type="datetime" v-model="startDate"></datetime>
            </div>
            <div class="col-6">
                <h2>Выберите конец периода</h2>
                <datetime type="datetime" v-model="endDate"></datetime>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-6">
                <select v-model="dataType" class="form-select" aria-label="Default select example">
                    <option value="" selected disabled>Выберите данные для получения</option>
                    <option value="longMethods">Методы с долгим временем выполнения</option>
                    <option value="longMethodsWithLists">IP адреса с макс. нагрузкой (которые есть в списках)</option>
                    <option value="longMethodsWithoutLists">IP адреса с макс. нагрузкой (которых нет в списках)</option>
                </select>
            </div>
            <div class="col-6">
                <button :disabled="(dataType=='' || startDate=='' || endDate=='') ? true : false" @click="getData"
                        class="btn btn-primary">Получить данные
                </button>
            </div>
        </div>
        <table v-if="longMethods && longMethods.length" class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Имя контроллера</th>
                <th scope="col">Имя метода</th>
                <th scope="col">Время вып. скрипта (сек.)</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in longMethods">
                <td>{{ item.controller_name }}</td>
                <td>{{ item.method_name }}</td>
                <td>{{ item.execution_time }}</td>
            </tr>
            </tbody>
        </table>
        <p v-else-if="longMethods && !longMethods.length" class="text-danger">По выбранным параметрам отстутствуют
            методы</p>

        <table v-if="longIps && longIps.length" class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">IP адрес</th>
                <th scope="col">Время вып. скрипта (сек.)</th>
                <th scope="col">Наличие в списке</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in longIps">
                <td>{{ item.ip_address }}</td>
                <td>{{ item.execution_time }}</td>
                <td>
                    <span v-if="item.list == 'black'">Белый</span>
                    <span v-else-if="item.list == 'white'">Черный</span>
                    <span v-else>Нет</span>
                </td>
            </tr>
            </tbody>
        </table>
        <p v-else-if="longIps && !longIps.length" class="text-danger">По выбранным параметрам отстутствуют IP
            адреса</p>
    </div>
</template>

<script>

    import 'vue-datetime';
    import 'vue-datetime/dist/vue-datetime.css';
    import {DateTime} from 'luxon';

    const withQuery = require('with-query').default;

    export default {
        methods: {
            getData: function () {
                let url;
                let queryParam = {
                    startDate: DateTime.fromISO(this.startDate).toFormat('yyyy-MM-dd HH:mm:ss'),
                    endDate: DateTime.fromISO(this.endDate).toFormat('yyyy-MM-dd HH:mm:ss')
                }
                switch (this.dataType) {
                    case 'longMethods':
                        url = '/api/getLongMethods';
                        break;

                    case 'longMethodsWithLists':
                        url = '/api/getLongIps';
                        queryParam.isInTheList = 1;
                        break;

                    case 'longMethodsWithoutLists':
                        url = '/api/getLongIps';
                        queryParam.isInTheList = 0;
                        break;
                }

                fetch(withQuery(url, queryParam))
                    .then(response => response.json())
                    .then(result => {
                        if (this.dataType == 'longMethods') {
                            this.longIps = false;
                            this.longMethods = result.data;
                        } else {
                            this.longMethods = false;
                            this.longIps = result.data;
                        }
                    })
            }
        },
        data: function () {
            return {
                startDate: '',
                endDate: '',
                dataType: '',
                longIps: false,
                longMethods: false
            }
        }
    }
</script>
