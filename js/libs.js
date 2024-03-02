import $ from 'jquery'
import jQuery from 'jquery'

import param from 'jquery-param'

import { createApp } from 'vue'
import draggable from 'vuedraggable'

import '@vueform/multiselect/themes/default.css'
import Multiselect from '@vueform/multiselect'

import 'flatpickr/dist/flatpickr.css'
import flatpickr from 'vue-flatpickr-component'
//import default as flatPickrLocales from 'flatpickr/dist/l10n/index.js'
import confirmDatePlugin from 'flatpickr/dist/plugins/confirmDate/confirmDate.js'

import "leaflet/dist/leaflet.css"
import { LMap, LTileLayer, LMarker } from '@vue-leaflet/vue-leaflet'


globalThis.$ = $
globalThis.jQuery = jQuery
globalThis.param = param
globalThis.sprintf = require('sprintf-js').sprintf
globalThis.createApp = createApp
globalThis.Draggable = draggable
globalThis.Multiselect = Multiselect
globalThis.Flatpickr = flatpickr
globalThis.confirmDatePlugin = confirmDatePlugin
globalThis.LMap = LMap
globalThis.LTileLayer = LTileLayer
globalThis.LMarker = LMarker
