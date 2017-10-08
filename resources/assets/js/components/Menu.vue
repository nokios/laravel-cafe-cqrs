<template>
    <div>
        <h3>{{ itemType | capitalize }}
            <button type="button" class="btn btn-info btn-xs">
                <span class="glyphicon glyphicon-plus-sign"></span>
                Add Item
            </button>
        </h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th width="10%">#</th>
                <th>Description</th>
                <th><span class="glyphicon glyphicon-glass" aria-hidden="true"></span></th>
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
        props: {
            itemType: {
                type: String,
                default: 'Menu'
            }
        },
        data: () => ({
            items: [],
            selected_items: []
        }),
        created() {
        },
        filters: {
            capitalize: function (value) {
                if (!value) return '';
                value = value.toString();
                return value.charAt(0).toUpperCase() + value.slice(1);
            }
        },
        mounted() {
            let isDrink = (this.itemType === 'drink');
            axios.get('/api/menu/item', {params: {is_drink: isDrink}})
                .then(response => {
                    console.log("Items loaded");
                    this.items = response.data.map(function (item) {
                        item.selected = false;
                        return item;
                    });
                })
        },
        methods: {
            selectItem: function (item) {
                item.selected = !item.selected;
            }
        }
    }
</script>

<style scoped>
    tr.selected {
        background-color: #bce8f1;
    }
</style>