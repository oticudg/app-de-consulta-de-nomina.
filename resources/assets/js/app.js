/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// window.addEventListener('load', () => {
// 	let loader = document.getElementById('loader');
// 	loader.classList.add('fadeOut');
// });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.mixin({
	methods: {
		restoreMsg: function (msg) {
			for(i in msg) {
				$('.modal small#'+i+'Help').text(msg[i]);
			}
			console.log('msg retore')
		}
	}
});

const app = new Vue({
	el: '#app',
	data: {
		cedula: '',
		trabajador: {
			apellidos:"",
			cedula_a: '',
			confirmed: '',
			den_cargo: "",
			den_ubi: "",
			des_age: "",
			entregado: [],
			fec_ing: "",
			id: '',
			nombres: "",
		},
	},
	methods: {
		enviar: function () {
			axios.post('/', {cedula:this.cedula})
			.then(response => {
				let trabajador = response.data.trabajador;
				if (trabajador == null) {
					toastr.error('Trabajador no encontrado.');
					return;
				}
				for (let i in trabajador.entregado) {
					if (trabajador.entregado[i].event_id == response.data.event_current) {
						toastr.error('Ya se le fue entregado.');
						return;
					}
				}
				this.restoreMsg({cedula: 'Ingrese el número de cédula.'})
				this.trabajador = response.data.trabajador;
				$('#myModal').modal('toggle');
			})
			.catch(response => {
			});
		},
		entregar: function () {
			axios.post('/entregar/' + this.trabajador.id)
			.then(response => {
				this.trabajador = {
					apellidos:"",
					cedula_a: '',
					confirmed: '',
					den_cargo: "",
					den_ubi: "",
					des_age: "",
					entregado: [],
					fec_ing: "",
					id: '',
					nombres: "",
				};
				this.cedula = '';
				$('#myModal').modal('toggle');
				toastr.success('Bolsa entregada.');
			});
		}
	}
});
