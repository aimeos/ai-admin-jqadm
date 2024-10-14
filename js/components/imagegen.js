/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2024
 */


Aimeos.components['imagegen'] = {
	template: '#imagegen',
	emits: ['close', 'confirm'],
	props: {
		'config': {type: Object, required: true},
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
			if(!this.config['key']) {
				alert('Add the OpenAI API key in the Setting > API panel first');
				return;
			}

			if(!this.prompt.trim().length) {
				this.missing = true;
				return;
			} else {
				this.missing = false;
			}

			this.loading = true;

			await fetch(this.config['image-url'] || 'https://api.openai.com/v1/images/generations', {
				body: JSON.stringify({
					model: this.config['image-model'] || 'dall-e-3',
					response_format: 'b64_json',
					prompt: this.prompt,
					style: this.style,
					size: this.size,
				}),
				headers: {
					'Content-Type': 'application/json',
					'Authorization': 'Bearer ' + this.config['key']
				},
				method: 'POST'
			}).then(response => {
				if(!response.ok) {
					throw new Error(`${response.status}: ${response.statusText}`)
				}
				return response.json();
			}).then(result => {
				for(const item of result.data) {
					if(item.b64_json) {
						const file = new File([Uint8Array.from(atob(item.b64_json), (m) => m.codePointAt(0))], new Date().getTime() + '.png', {
							lastModified: new Date().getTime(),
							type: 'image/png'
						});
						this.images.push({
							file: file,
							url: URL.createObjectURL(file),
							prompt: this.prompt,
							usedprompt: item.revised_prompt
						});
					}
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
