import { createApp } from 'vue'
import draggable from 'vuedraggable'
import { Ckeditor } from '@ckeditor/ckeditor5-vue';

import {
	ClassicEditor,
	Alignment,
	Autoformat,
	AutoImage,
	AutoLink,
	BlockQuote,
	Bold,
	Essentials,
	FindAndReplace,
	GeneralHtmlSupport,
	Heading,
	HorizontalLine,
	Image,
	ImageCaption,
	ImageInsert,
	ImageStyle,
	ImageToolbar,
	ImageUpload,
	Indent,
	IndentBlock,
	Italic,
	Link,
	LinkImage,
	List,
	MediaEmbed,
	PasteFromOffice,
	RemoveFormat,
	SourceEditing,
	SpecialCharacters,
	SpecialCharactersText,
	Strikethrough,
	Style,
	Table,
	TableToolbar,
	TextTransformation,
	Underline,
	WordCount
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

import '@vueform/multiselect/themes/default.css'
import Multiselect from '@vueform/multiselect'

import { BaseTree, Draggable as DragTree, dragContext } from '@he-tree/vue'
import '@he-tree/vue/style/default.css'

import 'flatpickr/dist/flatpickr.css'
import flatpickr from 'vue-flatpickr-component'
import l10n from 'flatpickr/dist/l10n/index.js'
import confirmDatePlugin from 'flatpickr/dist/plugins/confirmDate/confirmDate.js'

import "leaflet/dist/leaflet.css"
import { LMap, LTileLayer, LMarker } from '@vue-leaflet/vue-leaflet'

import { AwesomeGraphQLClient } from 'awesome-graphql-client'

import moment from 'moment'
import 'chartjs-adapter-moment';

import Chart from 'chart.js/auto'
import { MatrixController, MatrixElement } from 'chartjs-chart-matrix'
import { ChoroplethChart, ChoroplethController, ColorScale, GeoFeature, ProjectionScale, topojson } from 'chartjs-chart-geo'

Chart.register(MatrixController, MatrixElement, ChoroplethChart, ChoroplethController, ColorScale, GeoFeature, ProjectionScale)

globalThis.sprintf = require('sprintf-js').sprintf
globalThis.createApp = createApp
globalThis.Draggable = draggable
globalThis.BaseTree = BaseTree
globalThis.DragTree = DragTree
globalThis.dragContext = dragContext
globalThis.Multiselect = Multiselect
globalThis.Flatpickr = flatpickr
globalThis.FlatpickrL10n = l10n
globalThis.confirmDatePlugin = confirmDatePlugin
globalThis.CKEditor = Ckeditor
globalThis.ClassicEditor = ClassicEditor
globalThis.CKEditorPlugins = [
	Alignment,
	Autoformat,
	AutoImage,
	AutoLink,
	BlockQuote,
	Bold,
	Essentials,
	FindAndReplace,
	GeneralHtmlSupport,
	Heading,
	HorizontalLine,
	Image,
	ImageCaption,
	ImageInsert,
	ImageStyle,
	ImageToolbar,
	ImageUpload,
	Indent,
	IndentBlock,
	Italic,
	Link,
	LinkImage,
	List,
	MediaEmbed,
	PasteFromOffice,
	RemoveFormat,
	SourceEditing,
	SpecialCharacters,
	SpecialCharactersText,
	Strikethrough,
	Style,
	Table,
	TableToolbar,
	TextTransformation,
	Underline,
	WordCount
]
globalThis.LMap = LMap
globalThis.LTileLayer = LTileLayer
globalThis.LMarker = LMarker
globalThis.AwesomeGraphQLClient = AwesomeGraphQLClient
globalThis.moment = moment
globalThis.Chart = Chart
globalThis.topojson = topojson
