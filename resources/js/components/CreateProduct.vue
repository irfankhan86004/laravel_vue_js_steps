<template>
    <div>
        <h3 class="text-center">Create Product</h3>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addProduct">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="product.name">
						{{name}}
                    </div>
                    <div class="form-group">
                        <label>Detail</label>
                        <input type="text" class="form-control" v-model="product.detail">
						{{detail}}
                    </div>
					
					<br>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</template>
 
<script>
    export default {
        data() {
            return {
                product: {},
				name: '',
				detail: ''
            }
        },
        methods: {
            addProduct() {
				let currentObj = this;
                this.axios
                    .post('http://localhost:8000/api/products', this.product)
                    .then(response => (
                        this.$router.push({ name: 'home' })
                    ))
                    .catch(function (error) {
						//currentObj.name = error.response.data.errors.name;
						//currentObj.detail = error.response.data.errors.detail;
						
						$.each(error.response.data.errors, function(key, value) {
						 
							if (key == 'name') {
								currentObj.name = value;
							}
							
							if (key == 'detail') {
								currentObj.detail = value;
							}
							
							
					   });
					})
                    .finally(() => this.loading = false)
            }
        }
    }
</script>