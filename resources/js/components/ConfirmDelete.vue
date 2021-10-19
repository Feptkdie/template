<template>
	<div class="confirm-delete__container d-inline">
		<!-- Button trigger modal -->
		<button type="button" class="btn btn-danger" data-toggle="modal" :data-target="'#deleteModal' + this.dataId">
			Delete
		</button>

		<!-- Modal -->
		<div class="modal fade" :id="'deleteModal' + this.dataId" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteModalLabel">Are you this delete ?</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning" role="alert">
							"{{ dataTitle }}" - Please delete all related information!
						</div>
						<input type="text" v-model="data.message" class="form-control" placeholder="delete">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-danger" :disabled="data.disabled" @click="remove">
							<span v-if="data.loading">Loading ...</span>
							<span v-else>Delete</span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ["dataId", "dataTitle", "dataUrl", "dataRedirectUrl"],
		data() {
			return {
				data: {
					loading: false,
					message: "",
					disabled: true
				}
			}
		},
		mounted() {
			let vm = this;
			$('#deleteModal').on('show.bs.modal', function (event) {
				vm.data.loading = false;
				vm.data.disabled = true;
				vm.data.message = "";
			});
		},
		methods: {
			remove() {
				this.data.disabled = true;
				this.data.loading = true;
				axios.delete(this.dataUrl).then(res => {
					this.data.loading = false;
					this.data.disabled = false;
					if (res.data.success) {
						window.location.href = this.dataRedirectUrl;
					} else {
						alert(res.data);
					}
					console.log(res);
				})
				.catch(err => {
					this.data.loading = false;
					this.data.disabled = false;
					
					alert(err);
				});
			}
		},
		watch: {
			"data.message": function(msg) {
				if (msg == "delete") {
					this.data.disabled = false;
				} else {
					this.data.disabled = true;
				}
			}
		}
	}
</script>