/**
 * Utility functions
 *
 * @author K3
 * @since 2011/05/04
 */

// return indent
function _indent(count) {
	var output = '';
	for (var i = 0; i < count; i ++ )
	{
		output += '    ';
	}
	return output;
}

// make object's contents (recursive)
function _showElements(obj, level) {
	if (typeof(obj) != 'object' || level > 4) {
		return obj;
	}
	var output = '';
	for (var field in obj) {
		output += "\n";
		if (obj[field] !== null && typeof(obj[field]) == 'object') {
			output += _indent(level) + field + ' : ' + _showElements(obj[field], level+1);
		} else {
			output += _indent(level) + field + ' : ' + (obj[field] === null ? "null" : obj[field]);
		}
	}
	return output;
}

function alertObject(obj) {
	alert(_showElements(obj, 0));
}

/**
 * Trim string
 * 
 * @param string value
 * @return string
 */
function trim(value) {
	return value.replace(/^\s+|\s+$/g,"");
}

/**
 * Format number with comma
 * 
 * @param {String} x
 * @returns number
 */
function formatNumberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}