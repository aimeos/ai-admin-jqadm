import $ from 'jquery'
import jQuery from 'jquery'

import param from 'jquery-param'

import { createApp } from 'vue'
import draggable from 'vuedraggable'
import CKEditor from '@ckeditor/ckeditor5-vue'

import '@vueform/multiselect/themes/default.css'
import Multiselect from '@vueform/multiselect'

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

globalThis.$ = $
globalThis.jQuery = jQuery
globalThis.serialize = param
globalThis.sprintf = require('sprintf-js').sprintf
globalThis.createApp = createApp
globalThis.Draggable = draggable
globalThis.Multiselect = Multiselect
globalThis.Flatpickr = flatpickr
globalThis.FlatpickrL10n = l10n
globalThis.confirmDatePlugin = confirmDatePlugin
globalThis.CKEditor = CKEditor
globalThis.LMap = LMap
globalThis.LTileLayer = LTileLayer
globalThis.LMarker = LMarker
globalThis.AwesomeGraphQLClient = AwesomeGraphQLClient
globalThis.moment = moment
globalThis.Chart = Chart
globalThis.topojson = topojson
