/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2025
 */


Aimeos.components['imagegen'] = {
	template: '#imagegen',
	emits: ['close', 'confirm'],
	props: {
		'show': {type: Boolean, default: false}
	},
	data() {
		return {
			prompt: '',
			loading: false,
			missing: false,
			size: '1792x1024',
			style: 'natural',
			selected: 0,
			images: [],
		}
	},
	methods: {
		async generate() {
			if(!this.prompt.trim().length) {
				this.missing = true;
				return;
			} else {
				this.missing = false;
			}

			this.loading = true;

			await Aimeos.graphql(`mutation($prompt: String!, $size: String, $style: String) {
				imagine(prompt: $prompt, size: $size, style: $style)
			}`, {
				prompt: this.prompt,
				size: this.size,
				style: this.style
			}).then(result => {
				const item = typeof result.imagine === 'string'
					? JSON.parse(result.imagine)
					: result.imagine;

				if(item?.base64) {
					const mime = item.mimeType || 'image/png';
					const ext = mime.split('/')[1]?.replace('jpeg', 'jpg') || 'png';
					const file = new File([Uint8Array.from(atob(item.base64), (m) => m.codePointAt(0))], `${Date.now()}.${ext}`, {
						lastModified: Date.now(),
						type: mime
					});
					this.images.push({
						file: file,
						url: URL.createObjectURL(file),
						prompt: this.prompt,
						usedprompt: item.description || this.prompt
					});
				}

				if(this.images.length) {
					this.selected = this.images.length - 1;
					this.prompt = this.images[this.selected].usedprompt;
				}
			}).finally(() => {
				this.loading = false;
			}).catch((error) => {
				alert(error);
			});
		}
	}
};
