'use strict';






window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

(function(global) {
	var MONTHS = [
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	];


	var HTML_COLORS = [
		'#F0F8FF',
		'#FAEBD7',
		'#00FFFF',
		'#7FFFD4',
		'#F0FFFF',
		'#F5F5DC',
		'#FFE4C4',
		'#000000',
		'#FFEBCD',
		'#0000FF',
		'#8A2BE2',
		'#A52A2A',
		'#DEB887',
		'#5F9EA0',
		'#7FFF00',
		'#D2691E',
		'#FF7F50',
		'#6495ED',
		'#FFF8DC',
		'#DC143C',
		'#00FFFF',
		'#00008B',
		'#008B8B',
		'#B8860B',
		'#A9A9A9',
		'#A9A9A9',
		'#006400',
		'#BDB76B',
		'#8B008B',
		'#556B2F',
		'#FF8C00',
		'#9932CC',
		'#8B0000',
		'#E9967A',
		'#8FBC8F',
		'#483D8B',
		'#2F4F4F',
		'#2F4F4F',
		'#00CED1',
		'#9400D3',
		'#FF1493',
		'#00BFFF',
		'#696969',
		'#696969',
		'#1E90FF',
		'#B22222',
		'#FFFAF0',
		'#228B22',
		'#FF00FF',
		'#DCDCDC',
		'#F8F8FF',
		'#FFD700',
		'#DAA520',
		'#808080',
		'#808080',
		'#008000',
		'#ADFF2F',
		'#F0FFF0',
		'#FF69B4',
		'#CD5C5C',
		'#4B0082',
		'#FFFFF0',
		'#F0E68C',
		'#E6E6FA',
		'#FFF0F5',
		'#7CFC00',
		'#FFFACD',
		'#ADD8E6',
		'#F08080',
		'#E0FFFF',
		'#FAFAD2',
		'#D3D3D3',
		'#D3D3D3',
		'#90EE90',
		'#FFB6C1',
		'#FFA07A',
		'#20B2AA',
		'#87CEFA',
		'#778899',
		'#778899',
		'#B0C4DE',
		'#FFFFE0',
		'#00FF00',
		'#32CD32',
		'#FAF0E6',
		'#FF00FF',
		'#800000',
		'#66CDAA',
		'#0000CD',
		'#BA55D3',
		'#9370DB',
		'#3CB371',
		'#7B68EE',
		'#00FA9A',
		'#48D1CC',
		'#C71585',
		'#191970',
		'#F5FFFA',
		'#FFE4E1',
		'#FFE4B5',
		'#FFDEAD',
		'#000080',
		'#FDF5E6',
		'#808000',
		'#6B8E23',
		'#FFA500',
		'#FF4500',
		'#DA70D6',
		'#EEE8AA',
		'#98FB98',
		'#AFEEEE',
		'#DB7093',
		'#FFEFD5',
		'#FFDAB9',
		'#CD853F',
		'#FFC0CB',
		'#DDA0DD',
		'#B0E0E6',
		'#800080',
		'#663399',
		'#FF0000',
		'#BC8F8F',
		'#4169E1',
		'#8B4513',
		'#FA8072',
		'#F4A460',
		'#2E8B57',
		'#FFF5EE',
		'#A0522D',
		'#C0C0C0',
		'#87CEEB',
		'#6A5ACD',
		'#708090',
		'#708090',
		'#FFFAFA',
		'#00FF7F',
		'#4682B4',
		'#D2B48C',
		'#008080',
		'#D8BFD8',
		'#FF6347',
		'#40E0D0',
		'#EE82EE',
		'#F5DEB3',
		'#FFFFFF',
		'#F5F5F5',
		'#FFFF00',
		'#9ACD32'
	];



	var COLORS = [
		'#4dc9f6',
		'#f67019',
		'#f53794',
		'#537bc4',
		'#acc236',
		'#166a8f',
		'#00a950',
		'#58595b',
		'#8549ba'
	];

	var Samples = global.Samples || (global.Samples = {});
	var Color = global.Color;

	Samples.utils = {
		// Adapted from http://indiegamr.com/generate-repeatable-random-numbers-in-js/
		srand: function(seed) {
			this._seed = seed;
		},

		rand: function(min, max) {
			var seed = this._seed;
			min = min === undefined ? 0 : min;
			max = max === undefined ? 1 : max;
			this._seed = (seed * 9301 + 49297) % 233280;
			return min + (this._seed / 233280) * (max - min);
		},

		numbers: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 1;
			var from = cfg.from || [];
			var count = cfg.count || 8;
			var decimals = cfg.decimals || 8;
			var continuity = cfg.continuity || 1;
			var dfactor = Math.pow(10, decimals) || 0;
			var data = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = (from[i] || 0) + this.rand(min, max);
				if (this.rand() <= continuity) {
					data.push(Math.round(dfactor * value) / dfactor);
				} else {
					data.push(null);
				}
			}

			return data;
		},

		labels: function(config) {
			var cfg = config || {};
			var min = cfg.min || 0;
			var max = cfg.max || 100;
			var count = cfg.count || 8;
			var step = (max - min) / count;
			var decimals = cfg.decimals || 8;
			var dfactor = Math.pow(10, decimals) || 0;
			var prefix = cfg.prefix || '';
			var values = [];
			var i;

			for (i = min; i < max; i += step) {
				values.push(prefix + Math.round(dfactor * i) / dfactor);
			}

			return values;
		},

		months: function(config) {
			var cfg = config || {};
			var count = cfg.count || 12;
			var section = cfg.section;
			var values = [];
			var i, value;

			for (i = 0; i < count; ++i) {
				value = MONTHS[Math.ceil(i) % 12];
				values.push(value.substring(0, section));
			}

			return values;
		},

		color: function(index) {
			return COLORS[index % COLORS.length];
		},

		transparentize: function(color, opacity) {
			var alpha = opacity === undefined ? 0.5 : 1 - opacity;
			return Color(color).alpha(alpha).rgbString();
		}
	};

	// DEPRECATED
	window.randomScalingFactor = function() {
		return Math.round(Samples.utils.rand(-100, 100));
	};

	// INITIALIZATION

	Samples.utils.srand(Date.now());

}(this));