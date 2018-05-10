<template>
	<div class="row">
		<template v-for="(module, keym, indexm) in modules">
			<div :class="{'row': (indexm%2 == 1)}">
				<div class="col-md-5" :class="(indexm%2 == 1) ? 'col-md-offset-1' : ''">
					<div class="box box-primary">
						<div class="box-header"><b>{{ module }}.</b></div>
						<div class="box-body" style="font-size: 13px;">
							<table>
								<tbody>
									<tr class="form-inline"
										v-for="(p, key, index) in permissions"
										v-if="p.module == keym">
										<td :for="'perm' + p.id" style="font-size:13px;">
											<label :for="'perm' + p.id" class="control-label" style="color:black;font-size:13px;">
												{{ p.name }}:
											</label>
										</td>
										<td>
											<input :id="'perm' + p.id" style="margin: 9px 0 0 5px" type="checkbox" :value="p.id" v-model="permissionsRol" @change="update" :rs="p.action">
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</template>
	</div>
</template>

<script>
export default {
	name: 'checkbox-permissions',
	props: ['user'],
	data () {
		return {
			permissionsRol: this.user,
			permissions: [],
			modules: {
				'rol': 'Roles',
				'permission': 'Permisos',
				'user': 'Usuarios',
				'module': 'Modulos',
			},
		};
	},
	mounted: function () {
		axios.post('get-data-roles')
		.then(response => {
			this.permissions = response.data.permissions;
		})
		.then( () => {
			setTimeout(function () {
				let index = $('input[rs="index"]')
				for (var i = 0; i < index.length; i++) {
					if (!index[i].checked)
						$(index[i]).parent().parent().find('input[type="checkbox"]').attr('disabled', 'disabled');
				}
				index.removeAttr('disabled');
			}, 50);
			$('input[rs="index"]').change(function (e) {
				let all = $(this).parent().parent().find('input[type="checkbox"]');
				if (this.checked) {
					all.removeAttr('disabled', 'disabled');
				} else {
					all.attr('disabled', 'disabled');
				}
				$(this).removeAttr('disabled');
			});
		});
	},
	methods: {
		update: function () {
			this.$emit('check', this.permissionsRol);
		}
	}
}
</script>
