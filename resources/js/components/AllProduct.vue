<template>
    <div>
        <h2 class="text-center">Products List</h2>
		<input type="text" @keyup="searchUnit" placeholder="Search" v-model="search" class="form-control form-control-sm">
		<select name="searchType" @change="onChange($event)" v-model="searchType">
			<option value='name'>Name</option>
			<option value='detail'>Detail</option>
		</select>
		<button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New <i class="fas fa-user-plus fa-fw"></i></button>
		<table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Detail</th>
                <th></th>
                <!-- <th>Actions</th> -->
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in products.data" :key="product.id">
                <td>{{ product.id }}</td>
                <td>{{ product.name }}</td>
                <td>{{ product.detail }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <router-link :to="{name: 'edit', params: { id: product.id }}" class="btn btn-success">Edit</router-link>
                        <button class="btn btn-danger" @click="deleteProduct(product.id)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
			<pagination :data="products" @pagination-change-page="getResults"></pagination>
        </table>
		
		<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
					<div class="modal-header">

						<h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New User</h5>
						<h5 v-show="editMode" class="modal-title" id="addNewLabel">Update User</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<form >
						<div class="modal-body">
							 
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
							<button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
							<button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
						</div>

					</form>

				</div>
			</div>
        </div>
    </div>
	
		
	</div>
	
	
	
</template>
 
<script>
	
	import _ from "lodash";
	
    export default {
        data() {
            return {
				editMode: false,

                products: {},
				search: '',
				searchType: ''
            }
        },
        created() {
            this.axios
                .get('http://localhost:8000/api/products/')
                .then(response => {
                    this.products = response.data;
                });
        },
        methods: {
			openModalWindow(){
			   $('#addNew').modal('show');
			},
			fetchingAllUnit() {
			  axios.get("api/products").then( data => (this.products = data.data));
			},
			getResults(page = 1) {
				  axios.get('api/products?page=' + page+'&q='+this.search)
					.then(response => {
					  this.products = response.data;
				  });
			},
            deleteProduct(id) { 
                /*this.axios
                    .delete(`http://localhost:8000/api/products/${id}`)
                    .then(response => {
                        let i = this.products.map(data => data.id).indexOf(id);
                        this.products.splice(i, 1)
                    });*/
				Swal.fire({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  icon: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!'
				})
            },
			searchUnit:_.debounce(function(){
				axios.get('/api/products?q='+this.search)
					.then((response)=>{
					
					this.products = response.data.unit
				})
			}),
			onChange(event) {
				//console.log(event.target.value)
				axios.get('/api/products?type='+event.target.value+'&q='+this.search)
					.then((response)=>{
					
					this.products = response.data.unit
				})
			}
        }
    }
</script>