/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2024
 */


Aimeos.Dashboard = {

	/**
	 * Returns a jQuery promise for the constructed request
	 *
	 * @param string resource Resource name like "product", "order" or "order/address"
	 * @param string key Aggregation key to group results by, e.g. "order.cdate", "order.address.countryid"
	 * @param object criteria Polish notation object with conditions for limiting the results, e.g. {">": {"order.cdate": "2000-01-01"}}
	 * @param string|null sort Optional sorting criteria like "order.cdate" (ascending) or "-order.cdate" (descending), also more then one separated by comma
	 * @param integer|null limit Optional limit for the number of records that are selected before aggregation (default: 25)
	 * @param string|null value Aggregate values from that column, e.g "order.price" (in combination with type)
	 * @param string|null type Type of aggregation like "sum" or "avg" (default: null for count)
	 * @return jQuery promise object
	 */
	getData(resource, key, criteria, sort, limit, value, type) {

		const method = 'aggregate' + resource.charAt(0).toUpperCase() + resource.slice(1) + 's';
		let str = 'key: ' + JSON.stringify(key)

		if(criteria) {
			str += ', filter: ' + JSON.stringify(JSON.stringify(criteria))
		}

		if(sort) {
			sort = Array.isArray(sort) ? sort : [sort]
			str += ', sort: ' + JSON.stringify(sort);
		}

		if(limit) {
			str += ', limit: ' + limit;
		}

		if(value) {
			str += ', value: ' + JSON.stringify(value);
		}

		if(type) {
			str += ', type: ' + JSON.stringify(type);
		}

		return Aimeos.query(`query {
			` + method + `(` + str + `) {
				aggregates
			}
		}`).then(result => {
			return JSON.parse(result[method]?.aggregates || '{}')
		})
	}
};
