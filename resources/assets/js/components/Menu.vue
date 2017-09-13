<template>
    <div>
        <h3>{{ menu_name }}</h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="10%">#</th>
                <th>Description</th>
                <th width="15%" class="text-right">$</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in items" v-on:click="selectItem(item)" v-bind:class="{ selected: item.selected }">
                <td>{{item.id}}</td>
                <td>{{item.description}}</td>
                <td class="text-right">{{item.price}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data: () => ({
            menu_name: "Food",
            items: [],
            selected_items: []
        }),
        created() {

        },
        mounted() {
            console.log('Menu mounted.');

            axios.get('/api/menu-item')
                .then(response => {
                    console.log("Items loaded");
                    this.items = response.data.map(function (item) {
                        item.selected = false;
                        return item;
                    });
                })
        },
        methods: {
            selectItem: function(item) {
                item.selected = ! item.selected;
            }
        }
    }
</script>

<style scoped>
    tr.selected {
        background-color: #bce8f1;
    }
</style>