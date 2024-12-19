/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */



Aimeos.Nav = {

	init() {
		this.hover();
		this.menu();
		this.navitems();
		this.shortcuts();
		this.submenu();
		this.tabs();
	},


	hover() {
		let active = document.querySelector(".aimeos .main-sidebar .sidebar-menu > li.active");

		document.querySelectorAll(".aimeos .main-sidebar .sidebar-menu > li:not(.none)").forEach(function(item) {
			item.addEventListener("mouseenter", function(ev) {
				if(item !== active && ev.target.previousElementSibling) {
					ev.target.previousElementSibling.classList.add("before");
				}
				if(item !== active && ev.target.nextElementSibling) {
					ev.target.nextElementSibling.classList.add("after");
				}
			});
			item.addEventListener("mouseleave", function(ev) {
				if(item !== active && ev.target.previousElementSibling) {
					ev.target.previousElementSibling.classList.remove("before");
				}
				if(item !== active && ev.target.nextElementSibling) {
					ev.target.nextElementSibling.classList.remove("after");
				}
			});
		});
	},


	menu() {
		document.querySelector(".app-menu .menu")?.addEventListener("click", function(ev) {
			const sidebar = document.querySelector(".main-sidebar");
			const menu = document.querySelector(".app-menu");

			if(menu.classList.contains('open')) {
				sidebar.classList.remove("open");
				menu.classList.remove("open");
			} else {
				sidebar.classList.add("open");
				menu.classList.add("open");
			}
		});
	},


	navitems() {
		if(window.sessionStorage && window.sessionStorage.getItem('aimeos/jqadm/item/navbar') == 1) {
			$(".aimeos .item-navbar .navbar-content .more").removeClass("more").addClass("less");
			$(".aimeos .item-navbar .navbar-content").addClass("show");
		}

		$(".aimeos .item-navbar .navbar-content").on("click", ".more", function(ev) {
			if(window.sessionStorage) {
				window.sessionStorage.setItem('aimeos/jqadm/item/navbar', 1);
			}
			$(ev.currentTarget).removeClass("more").addClass("less");
			$(ev.delegateTarget).addClass("show");
		});

		$(".aimeos .item-navbar .navbar-content").on("click", ".less", function(ev) {
			if(window.sessionStorage) {
				window.sessionStorage.setItem('aimeos/jqadm/item/navbar', 0);
			}
			$(ev.currentTarget).removeClass("less").addClass("more");
			$(ev.delegateTarget).removeClass("show");
		});
	},


	shortcuts() {
		document.addEventListener('keydown', function(ev) {
			if((ev.ctrlKey || ev.metaKey) && ev.altKey && ev.key.match(/[a-z]/)) {
				const link = document.querySelector(".aimeos .sidebar-menu a[data-ctrlkey=" + ev.key + "]")

				if(link) {
					window.location = link.getAttribute("href");
				}
				return false
			}

			if((ev.ctrlKey || ev.metaKey) && ev.key === 'i') {
				for(btn of (document.activeElement?.closest('.card')?.querySelectorAll(".actions .act-add") || [])) {
					if(btn.clientWidth && btn.clientHeight) {
						btn.click()
						return false
					}
				}
				for(btn of document.querySelectorAll(".aimeos .card-tools-more .act-add")) {
					if(btn.clientWidth && btn.clientHeight) {
						btn.click()
						return false
					}
				}
				return false
			}

			if((ev.ctrlKey || ev.metaKey) && ev.keyCode === 13) {
				document.querySelector(".aimeos form.item")?.submit()
				return
			}

			if(ev.keyCode === 13) {
				document.querySelector(".btn:focus")?.click()
			}
		});
	},


	submenu() {
		document.querySelectorAll(".aimeos .main-sidebar .sidebar-menu>li:not(.none)").forEach(function(item) {
			item.addEventListener("click", function(ev) {
				ev.target.closest("li").classList.add("show");
			});
		});

		document.querySelectorAll(".aimeos .main-sidebar .sidebar-menu .menu-header").forEach(function(item) {
			item.addEventListener("click", function(ev) {
				ev.target.closest("li.treeview").classList.remove("show");
				ev.stopPropagation();
			});
		});
	},


	tabs() {
		if(document.location.hash) {
			const item = document.querySelector('.nav-tabs a.nav-link[href="' + document.location.hash + '"]')
			new bootstrap.Tab(item).show()
		}

		document.querySelectorAll('.nav-tabs .nav-link').forEach(function(item) {
			item.addEventListener('click', function(ev) {
				document.querySelectorAll("form").forEach(function(form) {
					form.setAttribute('action', form.getAttribute('action')?.split('#')[0] + ev.target.hash)
				})

				if(history.pushState) {
					history.pushState(null, null, ev.target.hash)
				}
			})
		})
	}
}



document.addEventListener("DOMContentLoaded", function() {
	Aimeos.Nav.init();
})