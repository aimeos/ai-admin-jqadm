/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */



Aimeos.Form = {

	init() {
		this.fields()
		this.help()
		this.next()
		this.submit()
	},


	check(form) {
		let result = true

		form?.querySelectorAll('.is-invalid').forEach(function(el) {
			el.classList.remove('is-invalid');
		});

		form?.querySelectorAll("input,select,textarea").forEach(function(node) {
			if(!node.checkValidity() && !node.closest(".prototype")?.length) {
				if(!node.classList.contains('.form-control') && !node.classList.contains('form-select')) {
					node = node.closest('.form-control')
				}

				const id = node.closest(".tab-pane")?.getAttribute('id')
				document.querySelector(".item-navbar .nav-item." + id + " .nav-link")?.classList.add("is-invalid");

				node.closest(".card")?.querySelector(".card-header")?.classList?.add("is-invalid");
				node.classList.add("is-invalid")
				result = false
			}
		});

		if(!result) {
			form?.querySelectorAll('.main-navbar .btn-primary').forEach(function(el) {
				el.classList.add('is-invalid');
			});
		}

		return result
	},


	fields() {
		document.querySelectorAll(".aimeos form").forEach(function(el) {
			el.addEventListener("blur", function(ev) {
				const node = ev.target

				if(node.tagName === 'INPUT' || node.tagName === 'SELECT') {
					if(node.checkValidity() && !node.classList.contains('novalidate')) {
						node.classList.remove('is-invalid')
						node.classList.add('is-valid')
					} else {
						node.classList.remove('is-valid')
						node.classList.add('is-invalid')
					}
				}
			}, true)
		})
	},


	help() {
		document.querySelector(".aimeos")?.addEventListener("click", function(ev) {
			if(ev.target.classList.contains("help")) {
				ev.target?.parentNode?.querySelector(".help-text")?.classList?.toggle('show')
			}

			if(ev.target.classList.contains("act-help")) {
				document.querySelectorAll(".help-text").forEach(function(el) {
					el.classList.toggle('show')
				})
			}
		})
	},


	next() {
		document.querySelectorAll(".aimeos form.item").forEach(function(el) {
			el.addEventListener("click", function(ev) {
				if(ev.target.classList.contains("next-action")) {
					document.querySelector("#item-next").value = ev.target?.dataset?.next
					el.submit()
					return false
				}
			})
		})
	},


	submit() {
		document.querySelectorAll(".aimeos form").forEach(function(el) {
			el.noValidate = true;
		});

		document.querySelector(".aimeos form.item")?.addEventListener("submit", function(ev) {
			if(!Aimeos.Form.check(ev.target)) {
				document.querySelector('body').scrollTop = 0
				ev.preventDefault()
				return false;
			}

			const maxInput = document.querySelector("#problem .max_input_vars")
			const value = maxInput?.dataset?.value

			if(value && value < document.querySelectorAll("input,select").length) {
				(new bootstrap.Modal('#problem')).show();
				maxInput.classList.toggle('hidden');

				ev.preventDefault()
				return false;
			}

			return true
		});
	}
}



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Form.init();
})
